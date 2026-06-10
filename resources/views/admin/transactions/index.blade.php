@extends('layouts.admin')
@section('title', 'Transaksi & Escrow')
@section('page_title', 'Transaksi & Escrow')
@section('page_subtitle', 'Riwayat semua transaksi keuangan dan status escrow campaign brand')

@section('content')
<div class="space-y-5">

    {{-- ── STAT CARDS ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 items-center justify-center">
                <i data-lucide="wallet-cards" class="w-4 h-4 text-brand"></i>
            </div>
            <p class="text-xl font-bold text-white">Rp {{ number_format($totalEscrowHeld) }}</p>
            <p class="text-xs text-slate-500">Total Escrow Ditahan</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 items-center justify-center">
                <i data-lucide="lock" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalEscrowHeldCount) }}</p>
            <p class="text-xs text-slate-500">Escrow Hold Tercatat</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 items-center justify-center">
                <i data-lucide="refresh-ccw" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <p class="text-xl font-bold text-white">Rp {{ number_format(abs($totalEscrowRefunded)) }}</p>
            <p class="text-xs text-slate-500">Total Refund Escrow</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 items-center justify-center">
                <i data-lucide="list" class="w-4 h-4 text-violet-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalTransactions) }}</p>
            <p class="text-xs text-slate-500">Total Transaksi</p>
        </div>
    </div>

    {{-- ── FILTER + SEARCH ─────────────────────────────────────── --}}
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 border-b border-neutral-800/60 gap-3">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-white">Riwayat Transaksi</h3>
                <a href="{{ route('admin.transactions.escrow') }}"
                   class="text-xs text-brand hover:text-brand-light font-semibold flex items-center gap-1 ml-4">
                    Lihat Status Escrow <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center gap-2">
                <select name="type" onchange="this.form.submit()"
                    class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5 text-xs text-slate-300 outline-none">
                    <option value="all"            {{ $type === 'all'            ? 'selected' : '' }}>Semua Tipe</option>
                    <option value="escrow_hold"    {{ $type === 'escrow_hold'    ? 'selected' : '' }}>Escrow Hold</option>
                    <option value="escrow_release" {{ $type === 'escrow_release' ? 'selected' : '' }}>Escrow Release</option>
                    <option value="escrow_refund"  {{ $type === 'escrow_refund'  ? 'selected' : '' }}>Escrow Refund</option>
                    <option value="deposit"        {{ $type === 'deposit'        ? 'selected' : '' }}>Deposit</option>
                    <option value="payout"         {{ $type === 'payout'         ? 'selected' : '' }}>Payout</option>
                </select>
                <div class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari user..."
                        class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-32">
                </div>
                <button type="submit" class="hidden"></button>
            </form>
        </div>

        {{-- ── TABLE ─────────────────────────────────────────────── --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['#', 'Waktu', 'Brand / User', 'Tipe', 'Jumlah', 'Keterangan', 'Saldo Setelah'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $trx->id }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 whitespace-nowrap">
                            {{ $trx->created_at->format('d M Y') }}<br>
                            <span class="text-slate-600">{{ $trx->created_at->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-500/20 flex items-center justify-center text-xs font-bold text-emerald-400 flex-shrink-0">
                                    {{ strtoupper(substr($trx->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-white">{{ $trx->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $trx->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $typeMap = [
                                    'escrow_hold'    => ['label' => 'Escrow Hold',    'color' => 'amber'],
                                    'escrow_release' => ['label' => 'Escrow Release', 'color' => 'emerald'],
                                    'escrow_refund'  => ['label' => 'Escrow Refund',  'color' => 'blue'],
                                    'deposit'        => ['label' => 'Deposit',         'color' => 'violet'],
                                    'payout'         => ['label' => 'Payout',          'color' => 'rose'],
                                ];
                                $t = $typeMap[$trx->type] ?? ['label' => $trx->type, 'color' => 'slate'];
                            @endphp
                            <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-full
                                bg-{{ $t['color'] }}-500/10 text-{{ $t['color'] }}-400 border border-{{ $t['color'] }}-500/20">
                                {{ $t['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($trx->amount < 0)
                                <span class="text-sm font-bold text-red-400">- Rp {{ number_format(abs($trx->amount)) }}</span>
                            @else
                                <span class="text-sm font-bold text-emerald-400">+ Rp {{ number_format($trx->amount) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 max-w-xs truncate">
                            {{ $trx->description ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-300 font-medium">
                            Rp {{ number_format($trx->balance_after) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <i data-lucide="inbox" class="w-8 h-8 text-slate-700 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-500">Belum ada transaksi tercatat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── PAGINATION ────────────────────────────────────────── --}}
        @if($transactions->hasPages())
        <div class="px-5 py-4 border-t border-neutral-800/60">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
