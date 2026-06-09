@extends('layouts.kreator')

@section('title', 'Riwayat Kiriman')

@section('content')
<div class="max-w-6xl mx-auto pb-12 space-y-7 pt-2">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 animate-fade-in">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 animate-fade-in">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 mb-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold mb-3" style="box-shadow: inset 0 0 0 1px rgba(16,185,129,0.2)">
                <i data-lucide="history" class="w-3 h-3"></i> Klaim Tayangan
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-white leading-tight mb-2">Riwayat Kiriman</h1>
            <p class="text-xs text-slate-500 max-w-sm">Pantau status klaim tayangan dan estimasi imbalan dari kampanye yang kamu kerjakan.</p>
        </div>

        <a href="{{ route('kreator.submissions.create') }}" class="self-start sm:self-auto btn-primary px-6">
            <i data-lucide="plus" class="w-4 h-4"></i> Klaim Tayangan Baru
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 items-center justify-center mb-3">
                <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-500">Menunggu Ulasan</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-500">Disetujui</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-red/10 border border-red/20 items-center justify-center mb-3">
                <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i>
            </div>
            <p class="text-xl font-black text-white">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-slate-500">Ditolak</p>
        </div>
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 items-center justify-center mb-3">
                <i data-lucide="wallet" class="w-4 h-4 text-brand"></i>
            </div>
            <p class="text-xl font-black text-white">Rp {{ number_format($stats['total_reward'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500">Estimasi Imbalan</p>
        </div>
    </div>

    <div class="bg-[#0a0a0a] rounded-3xl overflow-hidden shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]">
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" style="box-shadow: 0 1px 0 rgba(255,255,255,0.04); background: rgba(255,255,255,0.01)">
            <div class="col-span-5">Kampanye & Platform</div>
            <div class="col-span-2 text-right">Tayangan</div>
            <div class="col-span-2 text-right">Imbalan</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-1 text-right">Detail</div>
        </div>

        <div class="flex flex-col">
            @forelse($submissions as $submission)
            @php
            $statusConfig = match($submission->status) {
                'pending_brand' => ['label' => 'Menunggu Pemilik Merek', 'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'],
                'approved_by_brand' => ['label' => 'Menunggu Admin', 'color' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                'approved_by_admin' => ['label' => 'Disetujui', 'color' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                'rejected_by_brand' => ['label' => 'Ditolak Pemilik Merek', 'color' => 'bg-red-500/10 text-red-300 border-red-500/20'],
                'rejected_by_admin' => ['label' => 'Ditolak Admin', 'color' => 'bg-red-500/10 text-red-300 border-red-500/20'],
                default => ['label' => 'Unknown', 'color' => 'bg-slate-500/10 text-slate-400 border-slate-500/20'],
            };
            @endphp
            <div class="p-5 transition-colors duration-200 flex flex-col md:grid md:grid-cols-12 md:gap-4 md:items-center border-b border-white/5 last:border-0 hover:bg-white/[0.02] relative group">
                <div class="md:col-span-5 flex items-center gap-4 min-w-0">
                    <div class="w-11 h-11 rounded-2xl shrink-0 flex items-center justify-center bg-white/[0.04] border border-white/[0.06]">
                        <i data-lucide="video" class="w-5 h-5 text-brand"></i>
                    </div>
                    <div class="min-w-0 pr-2">
                        <h3 class="text-[13px] font-bold text-white mb-0.5 truncate group-hover:text-emerald-300 transition-colors">{{ $submission->campaign->title }}</h3>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest">{{ $submission->platform }} <span class="mx-1">-</span> {{ $submission->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="md:hidden w-full h-px bg-white/5 my-3"></div>

                <div class="md:col-span-2 md:text-right">
                    <p class="text-sm font-black text-white">{{ number_format($submission->views_claimed, 0, ',', '.') }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5 uppercase tracking-widest">Tayangan</p>
                </div>

                <div class="mt-3 md:mt-0 md:col-span-2 md:text-right">
                    <p class="text-[13px] font-black {{ in_array($submission->status, ['rejected_by_brand', 'rejected_by_admin']) ? 'text-slate-500 line-through opacity-50' : ($submission->status === 'approved_by_admin' ? 'text-emerald-400' : 'text-amber-400') }}">
                        Rp {{ number_format($submission->estimated_reward, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5 uppercase tracking-widest">Imbalan</p>
                </div>

                <div class="mt-3 md:mt-0 md:col-span-2 md:flex md:justify-center">
                    <span class="px-3 py-1.5 rounded-full text-[0.65rem] font-black tracking-widest uppercase inline-flex items-center justify-center whitespace-nowrap border {{ $statusConfig['color'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                <div class="hidden md:flex md:col-span-1 justify-end">
                    <a href="{{ $submission->video_url }}" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Lihat Video">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="inbox" class="w-8 h-8 text-slate-600"></i>
                </div>
                <p class="text-sm font-semibold text-slate-400 mb-1">Belum ada kiriman</p>
                <p class="text-xs text-slate-600 mb-4">Mulai klaim tayangan dari kampanye yang kamu kerjakan</p>
                <a href="{{ route('kreator.submissions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand/10 hover:bg-brand/20 border border-brand/20 rounded-xl text-sm font-medium text-brand transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Klaim Tayangan Pertama
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
