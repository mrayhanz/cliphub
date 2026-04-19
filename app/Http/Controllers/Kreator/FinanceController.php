<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Withdrawal;

class FinanceController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $transactions = $withdrawals->map(function ($w) {
            return [
                'type' => 'Penarikan Dana (Withdrawal)',
                'desc' => 'Transfer ke ' . current(explode('(', $w->bank_name)) . ' a.n ' . $w->account_name,
                'amount' => '- Rp ' . number_format($w->amount, 0, ',', '.'),
                'date' => $w->created_at->format('d M Y, H:i') . ' • ' . ucfirst($w->status),
                'is_income' => false,
            ];
        })->toArray();

        // Also pending sum for "Dana Tertahan" placeholder
        $pending_withdrawal = Withdrawal::where('user_id', auth()->id())->where('status', 'pending')->sum('amount');

        return view('kreator.finance.index', compact('transactions', 'pending_withdrawal'));
    }

    public function updateBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_account' => 'required|string',
        ]);

        $user = auth()->user();
        $user->update([
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
        ]);

        return redirect()->back();
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

        // Create withdrawal record
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'bank_name' => $user->bank_name,
            'bank_account' => $user->bank_account,
            'account_name' => $user->name,
            'status' => 'pending',
        ]);

        // Deduct balance
        $user->decrement('balance', $request->amount);

        return redirect()->back()->with('success', 'Penarikan berhasil diajukan dan sedang diproses.');
    }
}
