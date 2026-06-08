<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type   = $request->query('type', 'all');
        $search = trim((string) $request->query('q', ''));

        $validTypes = ['all', 'escrow_hold', 'escrow_release', 'escrow_refund', 'deposit', 'payout'];
        if (!in_array($type, $validTypes, true)) {
            $type = 'all';
        }

        $query = Transaction::with(['user'])
            ->when($type !== 'all', fn($q) => $q->where('type', $type))
            ->when($search !== '', fn($q) => $q->whereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')))
            ->latest();

        $transactions = $query->paginate(20)->appends($request->query());

        // ── Statistik ringkas ──────────────────────────────────────────────
        $totalEscrowHeld = Campaign::whereIn('status', ['active', 'draft'])
            ->get()
            ->sum(fn($c) => $c->escrow_held);

        $totalEscrowHeldCount = Transaction::where('type', 'escrow_hold')->count();
        $totalEscrowRefunded  = Transaction::where('type', 'escrow_refund')->sum('amount');
        $totalTransactions    = Transaction::count();

        return view('admin.transactions.index', compact(
            'transactions',
            'type',
            'search',
            'totalEscrowHeld',
            'totalEscrowHeldCount',
            'totalEscrowRefunded',
            'totalTransactions'
        ));
    }

    /**
     * Tampilkan seluruh escrow yang sedang ditahan per campaign.
     */
    public function escrow(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $campaigns = Campaign::with('user')
            ->where('status', 'active')
            ->when($search !== '', fn($q) => $q->where('title', 'like', '%' . $search . '%')
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')))
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        // Hanya tampilkan campaign yang masih menahan escrow
        $totalEscrowActive = Campaign::where('status', 'active')
            ->get()
            ->filter(fn($c) => $c->escrow_held > 0)
            ->count();

        $totalEscrowAmount = Campaign::where('status', 'active')
            ->get()
            ->sum(fn($c) => $c->escrow_held);

        return view('admin.transactions.escrow', compact(
            'campaigns',
            'search',
            'totalEscrowActive',
            'totalEscrowAmount'
        ));
    }
}
