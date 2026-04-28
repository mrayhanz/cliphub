@extends('layouts.brand')

@section('title', 'Brand Dashboard')
@section('page_title', 'Brand Dashboard')

@section('content')
@php
$activeCampaigns = $campaigns->where('status', 'active')->count();
$draftCampaigns = $campaigns->where('status', 'draft')->count();
$totalCampaignBudget = $campaigns->sum('budget');
@endphp

<div class="space-y-5 pb-8">
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Brand Active
                </div>
                <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight leading-snug">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p class="text-emerald-200/70 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Kelola campaign, pastikan saldo cukup, dan review submission kreator dari satu tempat.
                </p>
            </div>

            <div class="flex flex-col gap-2.5 sm:w-auto shrink-0 w-full">
                <a href="{{ route('brand.campaigns.create') }}" class="btn-primary sm:w-48">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat Campaign
                </a>
                <a href="{{ route('brand.finance') }}" class="btn-ghost sm:w-48">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Top-up Saldo
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        <a href="{{ route('brand.finance') }}" class="stat-card group animate-fade-in-up block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Saldo Tersedia</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                <span class="text-xs lg:text-sm text-slate-500 font-bold mr-0.5">Rp</span>{{ number_format($balance, 0, ',', '.') }}
            </h2>
        </a>

        <a href="{{ route('brand.campaigns') }}" class="stat-card group animate-fade-in-up delay-100 block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="megaphone" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Campaign Aktif</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $activeCampaigns }}</h2>
        </a>

        <a href="{{ route('brand.submissions') }}" class="stat-card group animate-fade-in-up delay-200 block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-amber">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-amber-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-amber-500/80 font-bold mb-1 relative z-10">Menunggu Review</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $pendingReview }}</h2>
        </a>

        <a href="{{ route('brand.campaigns') }}" class="stat-card group animate-fade-in-up delay-300 block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-slate">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-300"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Budget Campaign</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                <span class="text-xs lg:text-sm text-slate-500 font-bold mr-0.5">Rp</span>{{ number_format($totalCampaignBudget, 0, ',', '.') }}
            </h2>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">
        <div class="glass-card overflow-hidden lg:col-span-2 flex flex-col animate-fade-in-up">
            <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Campaign Terbaru</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Pantau status, budget, dan deadline campaign Anda.</p>
                </div>
                <a href="{{ route('brand.campaigns') }}" class="text-[10px] font-semibold text-brand hover:text-brand-light transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="p-4 space-y-3">
                @forelse($campaigns as $c)
                <div class="rounded-xl p-4 transition-all duration-200 group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
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

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">Budget</p>
                            <p class="text-xs lg:text-sm font-black text-white">Rp {{ number_format($c->budget, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">Rate / 1K</p>
                            <p class="text-xs lg:text-sm font-black text-white">Rp {{ number_format($c->price_per_1k, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">Slot</p>
                            <p class="text-xs lg:text-sm font-black text-white">{{ $c->slots }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-1">Draft</p>
                            <p class="text-xs lg:text-sm font-black text-white">{{ $draftCampaigns }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <i data-lucide="megaphone" class="w-5 h-5 text-slate-600"></i>
                    </div>
                    <p class="text-xs text-slate-600 font-medium mb-4">Belum ada campaign.</p>
                    <a href="{{ route('brand.campaigns.create') }}" class="btn-primary inline-flex w-auto px-4">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat Campaign
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="glass-card overflow-hidden lg:col-span-1 flex flex-col animate-fade-in-up delay-100">
            <div class="px-5 py-4 border-b" style="border-color: rgba(255,255,255,0.05);">
                <h3 class="text-sm font-bold text-white">Langkah Berikutnya</h3>
                <p class="text-[10px] text-slate-500 mt-0.5">Prioritas yang paling sering dibutuhkan brand.</p>
            </div>

            <div class="p-4 space-y-3">
                <a href="{{ route('brand.campaigns.create') }}" class="block rounded-xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-brand"></i>
                        <span class="text-xs font-bold text-white">Buat campaign baru</span>
                    </div>
                </a>
                <a href="{{ route('brand.submissions') }}" class="block rounded-xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-check-2" class="w-4 h-4 text-amber-400"></i>
                        <span class="text-xs font-bold text-white">Review submission kreator</span>
                    </div>
                </a>
                <a href="{{ route('brand.finance') }}" class="block rounded-xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                        <span class="text-xs font-bold text-white">Top-up saldo campaign</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
