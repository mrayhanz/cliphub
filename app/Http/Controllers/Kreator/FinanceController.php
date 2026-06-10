<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalRequested;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $user   = auth()->user();
        $filter = $request->query('filter', 'all'); // all | in | out

        // ── 1. Data dari tabel `transactions` (payout/income dari kampanye) ──
        $txQuery = Transaction::where('user_id', $user->id);

        if ($filter === 'in') {
            $txQuery->where('amount', '>', 0);
        } elseif ($filter === 'out') {
            $txQuery->where('amount', '<', 0);
        }

        $dbTransactions = $txQuery->orderBy('created_at', 'desc')->get()
            ->map(function ($tx) {
                $isIncome = $tx->amount > 0;
                return [
                    'source'    => 'transaction',
                    'type'      => $isIncome ? 'Pendapatan Kampanye' : 'Penarikan Dana',
                    'desc'      => $tx->description ?? ($isIncome ? 'Pembayaran dari kampanye' : 'Transfer ke rekening'),
                    'amount'    => ($isIncome ? '+ ' : '- ') . 'Rp ' . number_format(abs($tx->amount), 0, ',', '.'),
                    'date'      => $tx->created_at->format('d M Y, H:i'),
                    'is_income' => $isIncome,
                    'raw_date'  => $tx->created_at,
                    'status'    => null,
                ];
            });

        // ── 2. Data dari tabel `withdrawals` ──
        // Hanya masukkan withdrawal yang TIDAK ada di transactions
        // (withdrawal pending / rejected belum ada di transactions)
        $withdrawalQuery = Withdrawal::where('user_id', $user->id);

        // Jika filter "in", jangan tampilkan withdrawal (withdrawal = keluar)
        if ($filter === 'in') {
            $withdrawalQuery->whereRaw('0=1'); // kosongkan
        }

        // Jika filter "out" atau "all", tampilkan withdrawal yang belum ada di transactions
        $withdrawals = $withdrawalQuery->orderBy('created_at', 'desc')->get()
            ->filter(function ($w) {
                // Hindari duplikasi: jika withdrawal sudah masuk ke transactions (completed),
                // maka sudah ditangani oleh $dbTransactions
                return $w->status !== 'completed';
            })
            ->map(function ($w) {
                $bankShort = current(explode('(', $w->bank_name));
                $statusLabel = match($w->status) {
                    'rejected' => 'Ditolak',
                    default    => 'Menunggu',
                };
                return [
                    'source'    => 'withdrawal',
                    'type'      => 'Penarikan Dana',
                    'desc'      => "Transfer ke {$bankShort} a.n {$w->account_name} • {$statusLabel}",
                    'amount'    => '- Rp ' . number_format($w->amount, 0, ',', '.'),
                    'date'      => $w->created_at->format('d M Y, H:i'),
                    'is_income' => false,
                    'raw_date'  => $w->created_at,
                    'status'    => $w->status,
                ];
            });

        // ── 3. Gabung & urutkan berdasarkan tanggal ──
        $transactions = $dbTransactions->concat($withdrawals)
            ->sortByDesc('raw_date')
            ->values();

        // Pending withdrawal sum (dana tertahan)
        $pending_withdrawal = Withdrawal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Statistik tambahan
        $total_income = Transaction::where('user_id', $user->id)
            ->where('amount', '>', 0)
            ->sum('amount');

        $total_withdrawn = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->sum('amount'); // negatif

        return view('kreator.finance.index', compact(
            'transactions',
            'pending_withdrawal',
            'total_income',
            'total_withdrawn',
            'filter'
        ));
    }

    public function updateBank(Request $request)
    {
        $request->validate([
            'bank_name'    => 'required|string',
            'bank_account' => 'required|string',
        ]);

        $user = auth()->user();
        $user->update([
            'bank_name'    => $request->bank_name,
            'bank_account' => $request->bank_account,
        ]);

        return redirect()->back()->with('success', 'Rekening berhasil diperbarui.');
    }

    public function withdraw(Request $request)
    {
        $user = auth()->user();

        if (!$user->bank_name || !$user->bank_account) {
            return redirect()->back()->with('error', 'Silakan atur rekening pencairan terlebih dahulu.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:50000|max:' . $user->balance,
        ]);

        // Buat catatan withdrawal
        $withdrawal = Withdrawal::create([
            'user_id'      => $user->id,
            'amount'       => $request->amount,
            'bank_name'    => $user->bank_name,
            'bank_account' => $user->bank_account,
            'account_name' => $user->name,
            'status'       => 'pending',
        ]);

        // Kurangi saldo (ditahan sementara)
        $user->decrement('balance', $request->amount);

        // Kirim notifikasi ke semua Admin
        $admins = User::where('role', 'admin')->get();
        $withdrawal->load('user');
        foreach ($admins as $admin) {
            $admin->notify(new WithdrawalRequested($withdrawal));
        }

        return redirect()->back()->with('success', 'Penarikan berhasil diajukan dan sedang diproses.');
    }
}
