@extends('layouts.kreator')

@section('title', 'Dashboard')
@section('page_title', 'Creator Dashboard')

@section('content')
<div class="space-y-5 pb-8">
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Creator Active
                </div>
                <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight leading-snug">
                    Halo, {{ auth()->user()->name }}
                </h1>
                <p class="text-emerald-200/70 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Cari campaign, buat clip dengan AI Auto-Clipper, lalu klaim views saat konten sudah tayang.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-2.5 lg:min-w-[210px]">
                <a href="{{ route('kreator.campaigns') }}" class="btn-primary">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> Cari Campaign
                </a>
                <a href="{{ route('kreator.ai_clipper') }}" class="btn-ghost">
                    <i data-lucide="sparkles" class="w-4 h-4"></i> AI Clipper
                </a>
                <a href="{{ route('kreator.submissions.create') }}" class="btn-ghost">
                    <i data-lucide="upload" class="w-4 h-4"></i> Klaim Views
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        <a href="{{ route('kreator.finance') }}" class="stat-card group animate-fade-in-up block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Saldo Tersedia</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                Rp {{ number_format($stats['saldo_tersedia'], 0, ',', '.') }}
            </p>
        </a>

        <a href="{{ route('kreator.campaigns') }}" class="stat-card group animate-fade-in-up delay-100 block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="megaphone" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Campaign Tersedia</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $stats['active_campaigns'] }}</p>
        </a>

        <a href="{{ route('kreator.submissions') }}" class="stat-card group animate-fade-in-up delay-200 block hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-amber">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Submission Review</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">0</p>
        </a>

        <a href="{{ route('kreator.ai_clipper') }}" class="stat-card group animate-fade-in-up delay-300 block hover:border-emerald-500/20 transition-colors">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="sparkles" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-700 group-hover:text-white transition-colors"></i>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">AI Auto-Clipper</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">Aktif</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">
        <div class="glass-card overflow-hidden lg:col-span-1 flex flex-col animate-fade-in-up">
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Langkah Kerja</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Alur kreator yang paling penting</p>
                </div>
            </div>

            <div class="p-4 space-y-3">
                <a href="{{ route('kreator.campaigns') }}" class="block rounded-xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-brand"></i>
                        <span class="text-xs font-bold text-white">Pilih campaign yang cocok</span>
                    </div>
                </a>
                <a href="{{ route('kreator.ai_clipper') }}" class="block rounded-xl p-4 border border-emerald-500/20 bg-emerald-500/[0.04] hover:bg-emerald-500/[0.08] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="sparkles" class="w-4 h-4 text-emerald-400"></i>
                        <span class="text-xs font-bold text-white">Buat bahan klip dengan AI</span>
                    </div>
                </a>
                <a href="{{ route('kreator.submissions.create') }}" class="block rounded-xl p-4 border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="upload-cloud" class="w-4 h-4 text-amber-400"></i>
                        <span class="text-xs font-bold text-white">Klaim views setelah publish</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="glass-card overflow-hidden lg:col-span-2 flex flex-col animate-fade-in-up delay-100">
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Campaign Rekomendasi</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Campaign aktif yang bisa langsung kamu kerjakan</p>
                </div>
                <a href="{{ route('kreator.campaigns') }}" class="text-[10px] font-semibold text-brand hover:text-brand-light transition-colors flex items-center gap-1">
                    Marketplace <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="px-4 py-3 space-y-2.5 flex-1">
                @forelse($recs as $r)
                <a href="{{ route('kreator.campaigns') }}" class="flex items-center gap-3.5 p-4 rounded-xl transition-all duration-200 group relative overflow-hidden"
                   style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="absolute inset-0 pointer-events-none rounded-[inherit]" style="background: {{ $r['bgAlpha'] }};"></div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 relative z-10" style="background: {{ $r['iconBg'] }};">
                        <span class="w-3 h-3 rounded-full block" style="background: {{ $r['dotColor'] }}; box-shadow: 0 0 8px {{ $r['dotColor'] }}80;"></span>
                    </div>
                    <div class="flex-1 min-w-0 relative z-10">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest truncate">{{ $r['brand'] }}</p>
                            <span class="badge-green text-[9px] flex-shrink-0">{{ $r['tag'] }}</span>
                        </div>
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-emerald-200 transition-colors">{{ $r['title'] }}</h4>
                        <p class="text-xs font-semibold text-brand mt-1">{{ $r['rate'] }}</p>
                    </div>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 relative z-10 transition-all duration-200 group-hover:scale-110"
                         style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-brand"></i>
                    </div>
                </a>
                @empty
                <div class="py-10 text-center">
                    <p class="text-xs text-slate-600 font-medium">Belum ada campaign aktif.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
