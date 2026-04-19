@extends('layouts.brand')

@section('title', 'Brand Dashboard')
@section('page_title', 'Brand Dashboard')

@section('content')

<div class="space-y-5 pb-8">

    {{-- ===== HERO / GREETING CARD ===== --}}
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up">
        <!-- Decorative elements -->
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-16 -left-10 w-48 h-48 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(5,150,105,0.1) 0%, transparent 70%);"></div>
        <!-- Grid texture overlay -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03] rounded-2xl"
             style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">

            {{-- Left: Greeting --}}
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Brand Active
                </div>
                <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight leading-snug">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-emerald-200/70 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Pantau kinerja <span class="font-bold text-white">campaign aktif</span> Anda dan review UGC dari kreator hari ini.
                </p>
            </div>

            {{-- Right: Action Buttons --}}
            <div class="flex flex-col gap-2.5 sm:w-auto shrink-0 w-full">
                <a href="{{ route('brand.campaigns.create') ?? '#' }}" class="btn-primary sm:w-48">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat Campaign
                </a>
                <a href="{{ route('brand.finance') ?? '#' }}" class="btn-ghost sm:w-48">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Top-up Saldo
                </a>
            </div>

        </div>
    </div>

    {{-- ===== STAT CARDS: 2-col mobile → 4-col desktop ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        
        <div class="stat-card group animate-fade-in-up">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="dollar-sign" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <p class="text-[10px] lg:text-xs text-slate-500 font-semibold">Saldo Deposit</p>
            </div>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                <span class="text-xs lg:text-sm text-slate-500 font-bold mr-0.5">Rp</span>{{ number_format($balance, 0, ',', '.') }}
            </h2>
        </div>

        <div class="stat-card group animate-fade-in-up delay-100">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="trending-up" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <p class="text-[10px] lg:text-xs text-slate-500 font-semibold">Total Views</p>
            </div>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                {{ number_format($totalViews, 0, ',', '.') }}
            </h2>
        </div>

        <div class="stat-card group animate-fade-in-up delay-200">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-slate">
                    <i data-lucide="video" class="w-4 h-4 text-slate-300"></i>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Video UGC Dibuat</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $totalUgc }}</h2>
        </div>

        <div class="stat-card group animate-fade-in-up delay-300" style="{{ $pendingReview > 0 ? 'border-color: rgba(245,158,11,0.2);' : '' }}">
            <div class="absolute inset-0 rounded-2xl pointer-events-none" style="background: radial-gradient(ellipse at bottom right, rgba(245,158,11,0.05) 0%, transparent 70%);"></div>
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-amber">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                </div>
                @if($pendingReview > 0)
                <span class="badge-amber animate-pulse text-[9px]">Action Needed</span>
                @endif
            </div>
            <p class="text-[10px] lg:text-xs text-amber-500/80 font-bold mb-1 relative z-10">Menunggu Review</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $pendingReview }}</h2>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS: Mobile only ===== --}}
    <div class="lg:hidden animate-fade-in-up delay-200">
        <p class="text-[9px] font-bold tracking-widest uppercase text-slate-600 mb-2.5 px-0.5">Aksi Cepat</p>
        <div class="grid grid-cols-3 gap-2.5">
            <a href="{{ route('brand.submissions') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15);">
                    <i data-lucide="check-square" class="w-5 h-5 text-brand"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">Review<br>Konten</span>
            </a>
            <a href="{{ route('brand.campaigns') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.12);">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">Laporan<br>Performa</span>
            </a>
            <a href="{{ route('brand.finance') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.12);">
                    <i data-lucide="receipt" class="w-5 h-5 text-teal-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">Riwayat<br>Invoice</span>
            </a>
        </div>
    </div>

    {{-- ===== MAIN LAYOUT (2 Columns) ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">

        {{-- LEFT COLUMN: Active Campaigns --}}
        <div class="glass-card overflow-hidden lg:col-span-2 flex flex-col animate-fade-in-up">
            <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Campaign Aktif</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Serapan budget & views real-time.</p>
                </div>
                <a href="{{ route('brand.campaigns') }}" class="text-[10px] font-semibold text-brand hover:text-brand-light transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="p-4 space-y-3">
                @forelse($campaigns as $c)
                <div class="rounded-xl p-4 transition-all duration-200 group cursor-pointer"
                     style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 hover:border-emerald-500/20">
                        <div class="min-w-0 pr-4">
                            <h4 class="text-xs lg:text-sm font-bold text-white mb-1 truncate group-hover:text-emerald-200 transition-colors">{{ $c->title }}</h4>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest flex items-center gap-1.5">
                                <i data-lucide="smartphone" class="w-3 h-3"></i> {{ $c->platform ?? 'Mixed' }}
                            </p>
                        </div>
                        <span class="w-fit px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest"
                              style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #34d399;">
                            {{ strtoupper($c->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">Budget Total</p>
                            <p class="text-xs lg:text-sm font-black text-white">Rp {{ number_format($c->budget, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">UGC</p>
                            <p class="text-xs lg:text-sm font-black text-white flex items-center gap-1.5">0 <i data-lucide="video" class="w-3.5 h-3.5 text-slate-500"></i></p>
                        </div>
                    </div>

                    <div class="w-full h-1 rounded-full overflow-hidden relative" style="background: rgba(255,255,255,0.04);">
                        <div class="absolute left-0 top-0 h-full rounded-full progress-bar"
                             style="width: 0%; background: linear-gradient(90deg, #059669, #10b981);"></div>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <i data-lucide="megaphone" class="w-5 h-5 text-slate-600"></i>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">Belum ada campaign aktif.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: Action Needed (Review) --}}
        <div class="glass-card overflow-hidden lg:col-span-1 flex flex-col animate-fade-in-up delay-100">
            <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        Butuh Review
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #10b981;"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2" style="background: #10b981;"></span>
                        </span>
                    </h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">UGC menunggu ACC.</p>
                </div>
            </div>

            <div class="p-3 space-y-2 flex-1 overflow-y-auto">
                {{-- Dynamic submissions will be added here once the feature is ready --}}
                <div class="py-10 text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <i data-lucide="inbox" class="w-4 h-4 text-slate-600"></i>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">Belum ada UGC yang butuh review.</p>
                </div>
            </div>

            <div class="p-3 pt-0">
                <a href="{{ route('brand.submissions') }}" class="w-full py-2.5 rounded-xl text-[10px] font-bold text-slate-400 hover:text-white transition-colors flex items-center justify-center gap-1.5"
                   style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    Lihat Semua ({{ $pendingReview }}) <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>
            
        </div>
    </div>

</div>
@endsection
