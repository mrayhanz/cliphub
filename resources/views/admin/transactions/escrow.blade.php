@extends('layouts.admin')
@section('title', 'Status Escrow')
@section('page_title', 'Status Escrow Campaign')
@section('page_subtitle', 'Monitor dana escrow yang sedang ditahan per campaign aktif')

@section('content')
<div class="space-y-5">

    {{-- ── STAT CARDS ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 items-center justify-center">
                <i data-lucide="lock" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="text-xl font-bold text-white">Rp {{ number_format($totalEscrowAmount) }}</p>
            <p class="text-xs text-slate-500">Total Escrow Aktif</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 items-center justify-center">
                <i data-lucide="megaphone" class="w-4 h-4 text-brand"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalEscrowActive) }}</p>
            <p class="text-xs text-slate-500">Campaign Menahan Escrow</p>
        </div>
        <div class="stat-card col-span-2 lg:col-span-1">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 items-center justify-center">
                <i data-lucide="arrow-left-right" class="w-4 h-4 text-violet-400"></i>
            </div>
            <p class="text-sm font-semibold text-white">
                <a href="{{ route('admin.transactions.index') }}"
                   class="text-brand hover:text-brand-light flex items-center gap-1">
                    <i data-lucide="list" class="w-4 h-4"></i> Lihat Riwayat Transaksi
                </a>
            </p>
            <p class="text-xs text-slate-500 mt-1">Semua catatan debit/kredit</p>
        </div>
    </div>

    {{-- ── TABLE ─────────────────────────────────────────────── --}}
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 border-b border-neutral-800/60 gap-3">
            <h3 class="text-sm font-semibold text-white">Escrow per Campaign Aktif</h3>
            <form method="GET" action="{{ route('admin.transactions.escrow') }}"
                  class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-1.5">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari campaign / brand..."
                    class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-40">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Campaign', 'Brand', 'Budget', 'Terpakai', 'Escrow Ditahan', 'Deadline', 'Status'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($campaigns as $campaign)
                    @php $held = $campaign->escrow_held; @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        {{-- Campaign --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($campaign->thumbnail)
                                    <img src="{{ $campaign->thumbnail_url }}"
                                         class="w-8 h-8 rounded-lg object-cover flex-shrink-0" alt="">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-brand/20 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="megaphone" class="w-3.5 h-3.5 text-brand"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $campaign->title }}</p>
                                    <p class="text-[10px] text-slate-500">{{ ucfirst($campaign->type ?? '-') }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Brand --}}
                        <td class="px-5 py-3.5">
                            <p class="text-xs font-medium text-white">{{ $campaign->user->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-500">{{ $campaign->user->email ?? '' }}</p>
                        </td>
                        {{-- Budget --}}
                        <td class="px-5 py-3.5 text-xs text-slate-300">
                            Rp {{ number_format($campaign->budget ?? 0) }}
                        </td>
                        {{-- Terpakai --}}
                        <td class="px-5 py-3.5 text-xs text-slate-300">
                            Rp {{ number_format($campaign->budget_spent ?? 0) }}
                        </td>
                        {{-- Escrow Ditahan --}}
                        <td class="px-5 py-3.5">
                            @if($held > 0)
                                <span class="text-sm font-bold text-amber-400">
                                    Rp {{ number_format($held) }}
                                </span>
                            @else
                                <span class="text-xs text-slate-600">—</span>
                            @endif
                        </td>
                        {{-- Deadline --}}
                        <td class="px-5 py-3.5 text-xs text-slate-400">
                            {{ $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('d M Y') : '-' }}
                        </td>
                        {{-- Status --}}
                        <td class="px-5 py-3.5">
                            @php $es = $campaign->effective_status; @endphp
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full
                                {{ $es === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-400 border border-slate-500/20' }}">
                                <span class="w-1.5 h-1.5 rounded-full inline-block
                                    {{ $es === 'active' ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                                {{ $campaign->effective_status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <i data-lucide="inbox" class="w-8 h-8 text-slate-700 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-500">Tidak ada campaign aktif dengan escrow ditahan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campaigns->hasPages())
        <div class="px-5 py-4 border-t border-neutral-800/60">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
