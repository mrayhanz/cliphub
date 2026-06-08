<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalProcessed;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = trim((string) $request->query('q', ''));

        $validStatuses = ['all', 'pending', 'completed', 'rejected'];
        if (!in_array($status, $validStatuses, true)) {
            $status = 'all';
        }

        $query = Withdrawal::with('user')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($search !== '', fn($q) => $q->whereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')))
            ->latest();

        $withdrawals = $query->paginate(20)->appends($request->query());

        // Statistik
        $pendingCount      = Withdrawal::where('status', 'pending')->count();
        $totalDisbursed    = Withdrawal::where('status', 'completed')->sum('amount');
        $avgWithdrawal     = Withdrawal::where('status', 'completed')->avg('amount') ?? 0;

        return view('admin.withdrawals.index', compact(
            'withdrawals',
            'status',
            'search',
            'pendingCount',
            'totalDisbursed',
            'avgWithdrawal'
        ));
    }

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $kreator = $withdrawal->user;

        // Saldo sudah dikurangi saat kreator mengajukan, jadi cukup update status
        $withdrawal->update(['status' => 'completed']);

        // Catat ke tabel transactions sebagai pengeluaran (amount negatif)
        Transaction::create([
            'user_id'        => $kreator->id,
            'type'           => 'withdrawal',
            'amount'         => -abs($withdrawal->amount), // negatif = keluar
            'description'    => "Penarikan ke {$withdrawal->bank_name} ({$withdrawal->bank_account}) a.n {$withdrawal->account_name}",
            'reference_type' => 'withdrawal',
            'reference_id'   => $withdrawal->id,
            'balance_after'  => $kreator->balance,
        ]);

        // Kirim notifikasi ke kreator
        $kreator->notify(new WithdrawalProcessed($withdrawal));

        return redirect()->back()->with('success', "Penarikan dana a.n {$withdrawal->account_name} sebesar Rp " . number_format($withdrawal->amount, 0, ',', '.') . " berhasil dicairkan.");
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $kreator = $withdrawal->user;

        // Kembalikan saldo ke kreator karena ditolak
        $kreator->increment('balance', $withdrawal->amount);
        $kreator->refresh();

        $withdrawal->update(['status' => 'rejected']);

        // Kirim notifikasi ke kreator
        $kreator->notify(new WithdrawalProcessed($withdrawal));

        return redirect()->back()->with('success', "Penarikan dana a.n {$withdrawal->account_name} ditolak dan saldo dikembalikan.");
    }
}
