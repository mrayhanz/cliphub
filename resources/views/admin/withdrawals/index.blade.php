@extends('layouts.admin')
@section('title', 'Penarikan Dana')
@section('page_title', 'Penarikan Dana')
@section('page_subtitle', 'Approve atau tolak permintaan penarikan saldo kreator')

@section('content')
<div class="space-y-5">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm">
        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Menunggu Approval',       'val' => $pendingCount,                                            'icon' => 'clock',      'color' => 'amber'],
            ['label' => 'Total Dicairkan',          'val' => 'Rp ' . number_format($totalDisbursed, 0, ',', '.'),     'icon' => 'banknote',   'color' => 'emerald'],
            ['label' => 'Rata-rata Penarikan',      'val' => 'Rp ' . number_format($avgWithdrawal,  0, ',', '.'),     'icon' => 'calculator', 'color' => 'brand'],
        ] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] === 'brand' ? 'brand' : $s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filter & Search --}}
    <div class="flex items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.withdrawals') }}" class="flex items-center gap-3">
            {{-- Filter Status --}}
            <div class="flex items-center gap-1.5 bg-neutral-900/60 border border-neutral-800/60 rounded-xl p-1">
                @foreach(['all' => 'Semua', 'pending' => 'Menunggu', 'completed' => 'Berhasil', 'rejected' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.withdrawals', array_merge(request()->query(), ['status' => $val])) }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                          {{ $status === $val ? 'bg-brand text-white' : 'text-slate-400 hover:text-white' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Search --}}
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500 pointer-events-none"></i>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama kreator..."
                       class="pl-8 pr-4 py-2 text-xs bg-neutral-900/60 border border-neutral-800/60 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-brand/60 w-56">
            </div>
            <button type="submit" class="px-3 py-2 text-xs font-medium bg-brand/10 text-brand border border-brand/20 rounded-xl hover:bg-brand/20 transition-colors">
                Cari
            </button>
        </form>
    </div>

    {{-- Tabel Penarikan --}}
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Permintaan Penarikan</h3>
            <span class="text-xs text-slate-500">{{ $withdrawals->total() }} total permintaan</span>
        </div>

        <div class="divide-y divide-neutral-800/40">
            @forelse($withdrawals as $w)
            @php
                $sc = match($w->status) {
                    'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'rejected'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                    default     => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                };
                $label = match($w->status) {
                    'completed' => 'Berhasil',
                    'rejected'  => 'Ditolak',
                    default     => 'Menunggu',
                };
            @endphp
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-sm font-bold text-emerald-400 flex-shrink-0">
                    {{ strtoupper(substr($w->account_name, 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">{{ $w->account_name }}</p>
                    <p class="text-xs text-slate-500 truncate">
                        {{ $w->bank_name }} · {{ $w->bank_account }} · {{ $w->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Jumlah --}}
                <p class="text-sm font-bold text-white whitespace-nowrap">
                    Rp {{ number_format($w->amount, 0, ',', '.') }}
                </p>

                {{-- Badge Status --}}
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap {{ $sc }}">
                    {{ $label }}
                </span>

                {{-- Tombol Aksi (hanya jika masih pending) --}}
                @if($w->status === 'pending')
                <div class="flex gap-1.5 flex-shrink-0">
                    {{-- Tombol Cairkan --}}
                    <form method="POST" action="{{ route('admin.withdrawals.approve', $w) }}"
                          onsubmit="return confirm('Cairkan Rp {{ number_format($w->amount, 0, ',', '.') }} untuk {{ $w->account_name }}?')">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                            <i data-lucide="check" class="w-3 h-3"></i> Cairkan
                        </button>
                    </form>

                    {{-- Tombol Tolak --}}
                    <form method="POST" action="{{ route('admin.withdrawals.reject', $w) }}"
                          onsubmit="return confirm('Tolak penarikan dan kembalikan saldo {{ $w->account_name }}?')">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                            <i data-lucide="x" class="w-3 h-3"></i> Tolak
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 rounded-2xl bg-neutral-800/60 flex items-center justify-center mb-4">
                    <i data-lucide="inbox" class="w-6 h-6 text-slate-500"></i>
                </div>
                <p class="text-sm font-medium text-slate-400">Tidak ada permintaan penarikan</p>
                <p class="text-xs text-slate-600 mt-1">
                    @if($search) Tidak ditemukan untuk kata kunci "{{ $search }}"
                    @elseif($status !== 'all') Tidak ada penarikan dengan status "{{ $status }}"
                    @else Belum ada kreator yang mengajukan penarikan
                    @endif
                </p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($withdrawals->hasPages())
        <div class="px-5 py-4 border-t border-neutral-800/40">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
