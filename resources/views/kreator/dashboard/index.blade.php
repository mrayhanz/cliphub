@extends('layouts.kreator')

@section('title', 'Dashboard')
@section('page_title', 'Creator Dashboard')

@push('styles')
@endpush

@push('scripts')
<script>
    // Animated counter
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('[data-count]');
        counters.forEach(el => {
            const target = parseFloat(el.dataset.count);
            const isDecimal = el.dataset.count.includes('.');
            const duration = 1200;
            const start = performance.now();
            const update = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                const current = ease * target;
                el.textContent = isDecimal
                    ? current.toFixed(1)
                    : Math.round(current).toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
        });
    });
</script>
@endpush

@section('content')

<div class="space-y-5 pb-8">

    {{-- ===== HERO / GREETING CARD ===== --}}
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up">
        <!-- Decorative circles -->
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-16 -left-12 w-56 h-56 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(5,150,105,0.08) 0%, transparent 70%);"></div>
        <!-- Grid texture -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.025] rounded-2xl"
             style="background-image: linear-gradient(rgba(255,255,255,0.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 28px 28px;"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- Left: Greeting --}}
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Creator Active
                </div>
                <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight leading-snug">
                    Halo, {{ auth()->user()->name }}! 🔥
                </h1>
                <p class="text-emerald-200/70 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Ada <span class="font-bold text-white">{{ $stats['active_campaigns'] }} campaign baru</span> menantimu. Yuk mulai kerja dan kumpulkan cuan hari ini!
                </p>
            </div>

            {{-- Right: Earnings Box --}}
            <div class="rounded-xl p-4 lg:p-5 flex items-center justify-between lg:flex-col lg:items-start gap-3 lg:min-w-[200px]"
                 style="background: rgba(0,0,0,0.25); backdrop-filter: blur(12px); border: 1px solid rgba(16,185,129,0.15);">
                <div>
                    <p class="text-[10px] text-emerald-300/60 font-semibold uppercase tracking-widest">Total Pendapatan</p>
                    <p class="text-2xl lg:text-3xl font-black text-white mt-1 leading-none">
                        Rp&nbsp;<span data-count="{{ $stats['total_pendapatan'] }}">{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="flex flex-col items-end lg:items-start gap-1.5">
                    <div class="flex items-center gap-1.5 rounded-lg px-2.5 py-1" style="background: {{ $stats['revenue_growth'] >= 0 ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; border: 1px solid {{ $stats['revenue_growth'] >= 0 ? 'rgba(16,185,129,0.2)' : 'rgba(239,68,68,0.2)' }};">
                        <i data-lucide="{{ $stats['revenue_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
                        <span class="text-[10px] font-bold {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $stats['revenue_growth'] > 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% bulan ini</span>
                    </div>
                    <a href="{{ route('kreator.finance') }}"
                        class="flex items-center gap-1 text-[10px] font-bold text-white/60 hover:text-white transition-colors">
                        Lihat wallet <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== STAT CARDS: 2-col mobile → 4-col desktop ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">

        {{-- Saldo Tersedia --}}
        <div class="stat-card group animate-fade-in-up cursor-pointer">
            <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"
                 style="background: radial-gradient(ellipse at top right, rgba(16,185,129,0.06) 0%, transparent 70%);"></div>
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Saldo Tersedia</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                Rp <span data-count="{{ $stats['saldo_tersedia'] }}">{{ number_format($stats['saldo_tersedia'], 0, ',', '.') }}</span>
            </p>
            <a href="{{ route('kreator.finance') }}"
                class="mt-2 lg:mt-3 inline-flex items-center gap-1 text-[10px] lg:text-xs font-bold text-brand hover:text-brand-light transition-colors relative z-10">
                Tarik Dana <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>

        {{-- Saldo Tertahan --}}
        <div class="stat-card group animate-fade-in-up delay-100 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-amber">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Dalam Review</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                Rp <span data-count="{{ $stats['dalam_review'] }}">{{ number_format($stats['dalam_review'], 0, ',', '.') }}</span>
            </p>
            <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600 leading-tight relative z-10">Menunggu konfirmasi brand</p>
        </div>

        {{-- Total Views --}}
        <div class="stat-card group animate-fade-in-up delay-200 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-green">
                    <i data-lucide="flame" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <span class="badge-green text-[9px]">Top 10%</span>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Total Views</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">
                <span data-count="{{ $stats['total_views'] }}">{{ number_format($stats['total_views'], 0, ',', '.') }}</span>
            </p>
            <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600 relative z-10">dari {{ $stats['videos_approved'] }} video disetujui</p>
        </div>

        {{-- Success Rate --}}
        <div class="stat-card group animate-fade-in-up delay-300 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="icon-box-slate">
                    <i data-lucide="target" class="w-4 h-4 text-teal-400"></i>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1 relative z-10">Success Rate</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ number_format($stats['success_rate'], 1) }}%</p>
            <div class="mt-2 lg:mt-3 w-full h-1 rounded-full overflow-hidden relative z-10" style="background: rgba(255,255,255,0.05);">
                <div class="h-full rounded-full progress-bar" style="--fill: {{ $stats['success_rate'] }}%; width: {{ $stats['success_rate'] }}%; background: linear-gradient(90deg, #0d9488, #14b8a6);"></div>
            </div>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS: Mobile only ===== --}}
    <div class="lg:hidden animate-fade-in-up delay-200">
        <p class="text-[9px] font-bold tracking-[0.12em] uppercase text-slate-600 mb-2.5 px-0.5">Aksi Cepat</p>
        <div class="grid grid-cols-3 gap-2.5">
            <a href="{{ route('kreator.submissions.create') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.15);">
                    <i data-lucide="upload" class="w-5 h-5 text-brand"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">Submit<br>Views</span>
            </a>
            <a href="{{ route('kreator.campaigns') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.12);">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">Cari<br>Campaign</span>
            </a>
            <a href="{{ route('kreator.ai_tools') }}" class="glass-card-hover flex flex-col items-center justify-center gap-2 p-3.5 text-center transition-all duration-150 active:scale-95">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.12);">
                    <i data-lucide="sparkles" class="w-5 h-5 text-teal-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 leading-tight">AI<br>Clipper</span>
            </a>
        </div>
    </div>

    {{-- ===== BOTTOM GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5">

        {{-- ===== STATUS PEKERJAAN ===== --}}
        <div class="glass-card overflow-hidden flex flex-col animate-fade-in-up">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Status Pekerjaan</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Video kamu yang sedang diproses</p>
                </div>
                <a href="{{ route('kreator.submissions') }}"
                    class="text-[10px] font-semibold text-brand hover:text-brand-light transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            {{-- Job List --}}
            <div class="px-4 py-3 space-y-2 flex-1">
                @foreach($jobs as $j)
                @php 
                    $colorMap = [
                        'amber'   => ['bg' => 'rgba(245,158,11,0.1)',   'text' => '#f59e0b',   'border' => 'rgba(245,158,11,0.2)'],
                        'emerald' => ['bg' => 'rgba(16,185,129,0.1)', 'text' => '#10b981', 'border' => 'rgba(16,185,129,0.2)'],
                    ];
                    $c = $colorMap[$j['color']] ?? $colorMap['emerald']; 
                @endphp
                <div class="rounded-xl p-3.5 flex items-center gap-3 transition-all duration-200 group"
                     style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);"
                     onmouseenter="this.style.borderColor='rgba(16,185,129,0.15)'"
                     onmouseleave="this.style.borderColor='rgba(255,255,255,0.05)'">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: {{ $c['bg'] }}; border: 1px solid {{ $c['border'] }};">
                        <i data-lucide="{{ $j['icon'] }}" class="w-4 h-4" style="color: {{ $c['text'] }};"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs lg:text-sm font-bold text-white truncate">{{ $j['campaign'] }}</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">
                            <span class="text-slate-400 font-semibold">{{ $j['views'] }}</span> views diklaim
                        </p>
                    </div>
                    <span class="flex-shrink-0 text-[9px] font-black px-2 py-1 rounded-lg uppercase tracking-wide whitespace-nowrap"
                          style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border: 1px solid {{ $c['border'] }};">
                        {{ $j['status'] === 'Approved' ? '✓ OK' : ($j['status'] === 'Empty' ? 'KOSONG' : 'Review') }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="px-4 pb-4 pt-2">
                <a href="{{ route('kreator.submissions.create') }}"
                    class="btn-primary w-full py-3">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Submit Screenshot Views Baru
                </a>
            </div>
        </div>

        {{-- ===== CAMPAIGN REKOMENDASI ===== --}}
        <div class="glass-card overflow-hidden flex flex-col animate-fade-in-up delay-100">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b" style="border-color: rgba(255,255,255,0.05);">
                <div>
                    <h3 class="text-sm font-bold text-white">Campaign Rekomendasi</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Berdasarkan performa kamu</p>
                </div>
                <a href="{{ route('kreator.campaigns') }}"
                    class="text-[10px] font-semibold text-brand hover:text-brand-light transition-colors flex items-center gap-1">
                    Marketplace <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            {{-- Recommendations --}}
            <div class="px-4 py-3 space-y-2.5 flex-1">
                @foreach($recs as $r)
                <a href="{{ route('kreator.campaigns') }}" class="flex items-center gap-3.5 p-4 rounded-xl transition-all duration-200 group relative overflow-hidden"
                   style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);"
                   onmouseenter="this.style.borderColor='rgba(16,185,129,0.2)'; this.style.background='rgba(16,185,129,0.03)'"
                   onmouseleave="this.style.borderColor='rgba(255,255,255,0.05)'; this.style.background='rgba(255,255,255,0.02)'">
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 pointer-events-none rounded-[inherit]"
                         style="background: {{ $r['bgAlpha'] }};"></div>

                    {{-- Brand Icon --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 relative z-10"
                         style="background: {{ $r['iconBg'] }};">
                        <span class="w-3 h-3 rounded-full block"
                              style="background: {{ $r['dotColor'] }}; box-shadow: 0 0 8px {{ $r['dotColor'] }}80;"></span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0 relative z-10">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest truncate">{{ $r['brand'] }}</p>
                            <span class="badge-green text-[9px] flex-shrink-0">{{ $r['tag'] }}</span>
                        </div>
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-emerald-200 transition-colors">{{ $r['title'] }}</h4>
                        <p class="text-xs font-semibold text-brand mt-1">{{ $r['rate'] }}</p>
                    </div>

                    {{-- Arrow --}}
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 relative z-10 transition-all duration-200 group-hover:scale-110"
                         style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-brand"></i>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="px-4 pb-4 pt-2">
                <a href="{{ route('kreator.campaigns') }}"
                    class="btn-ghost w-full py-3">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-brand"></i>
                    Jelajahi Semua Campaign
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
