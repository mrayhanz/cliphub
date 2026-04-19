@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan performa platform ClipHub hari ini')

@section('title', 'Dashboard')

@push('styles')
<style>
/* KPI Cards */
.kpi-card { position:relative; overflow:hidden; border-radius:1.25rem; padding:1.5rem; transition:all 0.35s cubic-bezier(0.4,0,0.2,1); backdrop-filter:blur(16px); cursor:default; }
.kpi-card::before { content:''; position:absolute; inset:0; border-radius:inherit; opacity:0; transition:opacity 0.35s; pointer-events:none; }
.kpi-card:hover { transform:translateY(-4px); }
.kpi-card:hover::before { opacity:1; }
.kpi-card.kp-g { background:rgba(16,185,129,0.04); border:1px solid rgba(16,185,129,0.15); }
.kpi-card.kp-g:hover { border-color:rgba(16,185,129,0.32); box-shadow:0 20px 50px rgba(0,0,0,0.5),0 0 40px rgba(16,185,129,0.09); }
.kpi-card.kp-g::before { background:radial-gradient(ellipse at top right,rgba(16,185,129,0.09) 0%,transparent 65%); }
.kpi-card.kp-a { background:rgba(245,158,11,0.04); border:1px solid rgba(245,158,11,0.15); }
.kpi-card.kp-a:hover { border-color:rgba(245,158,11,0.32); box-shadow:0 20px 50px rgba(0,0,0,0.5),0 0 40px rgba(245,158,11,0.08); }
.kpi-card.kp-a::before { background:radial-gradient(ellipse at top right,rgba(245,158,11,0.08) 0%,transparent 65%); }
.kpi-card.kp-r { background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.2); box-shadow:0 0 30px rgba(239,68,68,0.05); }
.kpi-card.kp-r:hover { border-color:rgba(239,68,68,0.38); box-shadow:0 20px 50px rgba(0,0,0,0.5),0 0 40px rgba(239,68,68,0.12); }
.kpi-card.kp-r::before { background:radial-gradient(ellipse at bottom right,rgba(239,68,68,0.09) 0%,transparent 65%); }
/* Shimmer top line */
.kpi-shimmer { position:absolute; top:0; left:0; right:0; height:1px; }
.kp-g .kpi-shimmer { background:linear-gradient(90deg,transparent,rgba(16,185,129,0.4),transparent); }
.kp-a .kpi-shimmer { background:linear-gradient(90deg,transparent,rgba(245,158,11,0.4),transparent); }
.kp-r .kpi-shimmer { background:linear-gradient(90deg,transparent,rgba(239,68,68,0.45),transparent); }
/* Icon box */
.kpi-icon { width:2.75rem; height:2.75rem; border-radius:.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:transform 0.2s; }
.kpi-card:hover .kpi-icon { transform:scale(1.1) rotate(-4deg); }
.ki-g { background:linear-gradient(135deg,rgba(16,185,129,0.2),rgba(16,185,129,0.06)); border:1px solid rgba(16,185,129,0.28); box-shadow:0 0 16px rgba(16,185,129,0.12); }
.ki-a { background:linear-gradient(135deg,rgba(245,158,11,0.2),rgba(245,158,11,0.06)); border:1px solid rgba(245,158,11,0.28); }
.ki-r { background:linear-gradient(135deg,rgba(239,68,68,0.2),rgba(239,68,68,0.06)); border:1px solid rgba(239,68,68,0.28); box-shadow:0 0 14px rgba(239,68,68,0.12); }
/* KPI number */
.kpi-num { font-size:1.8rem; font-weight:900; color:#fff; line-height:1; letter-spacing:-0.03em; margin-bottom:.3rem; }
.kpi-lbl { font-size:.68rem; color:#64748b; font-weight:500; }
/* Badges */
.bd-up { display:inline-flex; align-items:center; gap:3px; font-size:.65rem; font-weight:700; padding:.2rem .5rem; border-radius:999px; color:#10b981; background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.25); }
.bd-new { font-size:.65rem; font-weight:700; padding:.2rem .5rem; border-radius:999px; color:#94a3b8; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09); }
.bd-urg { display:inline-flex; align-items:center; gap:4px; font-size:.65rem; font-weight:700; padding:.2rem .6rem; border-radius:999px; color:#f87171; background:rgba(239,68,68,0.13); border:1px solid rgba(239,68,68,0.28); animation:urgP 1.8s ease-in-out infinite; }
@keyframes urgP { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.28)} 50%{box-shadow:0 0 0 6px rgba(239,68,68,0)} }
.urg-dot { width:6px; height:6px; border-radius:50%; background:#ef4444; animation:dblink 1.2s ease-in-out infinite; }
@keyframes dblink { 0%,100%{opacity:1;box-shadow:0 0 6px rgba(239,68,68,.8)} 50%{opacity:.35;box-shadow:none} }
/* Task items */
.task-item { position:relative; border-radius:.875rem; padding:1rem 1rem 1rem 1.25rem; transition:all 0.25s ease; overflow:hidden; }
.task-item::after { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:0 2px 2px 0; }
.ti-r { background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.1); }
.ti-r::after { background:linear-gradient(to bottom,#ef4444,rgba(239,68,68,.2)); }
.ti-r:hover { background:rgba(239,68,68,0.08); border-color:rgba(239,68,68,0.25); transform:translateX(3px); }
.ti-a { background:rgba(245,158,11,0.04); border:1px solid rgba(245,158,11,0.1); }
.ti-a::after { background:linear-gradient(to bottom,#f59e0b,rgba(245,158,11,.2)); }
.ti-a:hover { background:rgba(245,158,11,0.08); border-color:rgba(245,158,11,0.25); transform:translateX(3px); }
.ti-g { background:rgba(16,185,129,0.04); border:1px solid rgba(16,185,129,0.1); }
.ti-g::after { background:linear-gradient(to bottom,#10b981,rgba(16,185,129,.2)); }
.ti-g:hover { background:rgba(16,185,129,0.08); border-color:rgba(16,185,129,0.25); transform:translateX(3px); }
.tc-r { font-size:.7rem; font-weight:900; padding:.15rem .55rem; border-radius:.45rem; color:#f87171; background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.25); }
.tc-a { font-size:.7rem; font-weight:900; padding:.15rem .55rem; border-radius:.45rem; color:#fbbf24; background:rgba(245,158,11,.15); border:1px solid rgba(245,158,11,.25); }
.tc-g { font-size:.7rem; font-weight:900; padding:.15rem .55rem; border-radius:.45rem; color:#34d399; background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.25); }
.tdot-r { width:7px; height:7px; border-radius:50%; background:#ef4444; box-shadow:0 0 8px rgba(239,68,68,.8); flex-shrink:0; }
.tdot-a { width:7px; height:7px; border-radius:50%; background:#f59e0b; box-shadow:0 0 8px rgba(245,158,11,.8); flex-shrink:0; }
.tdot-g { width:7px; height:7px; border-radius:50%; background:#10b981; box-shadow:0 0 8px rgba(16,185,129,.8); flex-shrink:0; }
.ta-r { font-size:.7rem; font-weight:600; color:#f87171; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
.ta-r:hover { color:#fca5a5; gap:8px; }
.ta-a { font-size:.7rem; font-weight:600; color:#fbbf24; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
.ta-a:hover { color:#fcd34d; gap:8px; }
.ta-g { font-size:.7rem; font-weight:600; color:#34d399; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
.ta-g:hover { color:#6ee7b7; gap:8px; }
/* Chart bars */
.bar-fee { width:100%; border-radius:4px 4px 0 0; background:linear-gradient(to top,#059669,#10b981,#34d399); transition:all .4s cubic-bezier(.4,0,.2,1); position:relative; z-index:2; }
.bar-fee:hover { filter:brightness(1.2); box-shadow:0 -6px 18px rgba(16,185,129,.55); }
.bar-esc { width:100%; border-radius:2px 2px 0 0; background:linear-gradient(to top,rgba(16,185,129,.08),rgba(16,185,129,.22)); position:relative; z-index:1; }
.chart-tip { position:absolute; bottom:calc(100% + 10px); left:50%; transform:translateX(-50%) scale(0); white-space:nowrap; padding:6px 10px; border-radius:10px; background:rgba(8,8,8,.96); border:1px solid rgba(255,255,255,.1); backdrop-filter:blur(16px); pointer-events:none; transition:transform .15s ease,opacity .15s ease; opacity:0; z-index:20; }
.bar-grp:hover .chart-tip { transform:translateX(-50%) scale(1); opacity:1; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ===== KPIS / STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Card 1: Revenue --}}
        <div class="kpi-card kp-g animate-fade-in-up">
            <div class="kpi-shimmer"></div>
            <div class="flex items-start justify-between mb-5 relative z-10">
                <div class="kpi-icon ki-g"><i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-400"></i></div>
                <span class="bd-up"><i data-lucide="trending-up" class="w-2.5 h-2.5"></i> +12%</span>
            </div>
            <div class="relative z-10"><p class="kpi-num">Rp 12.4M</p><p class="kpi-lbl">Pendapatan Platform (Bulan ini)</p></div>
        </div>

        {{-- Card 2: Escrow --}}
        <div class="kpi-card kp-a animate-fade-in-up delay-100">
            <div class="kpi-shimmer"></div>
            <div class="flex items-start justify-between mb-5 relative z-10">
                <div class="kpi-icon ki-a"><i data-lucide="lock" class="w-5 h-5 text-amber-400"></i></div>
                <span class="bd-up"><i data-lucide="trending-up" class="w-2.5 h-2.5"></i> +5%</span>
            </div>
            <div class="relative z-10"><p class="kpi-num">Rp 148.5M</p><p class="kpi-lbl">Total Escrow Ditahan</p></div>
        </div>

        {{-- Card 3: Campaigns --}}
        <div class="kpi-card kp-g animate-fade-in-up delay-200">
            <div class="kpi-shimmer"></div>
            <div class="flex items-start justify-between mb-5 relative z-10">
                <div class="kpi-icon ki-g"><i data-lucide="megaphone" class="w-5 h-5 text-emerald-400"></i></div>
                <span class="bd-new">12 Baru</span>
            </div>
            <div class="relative z-10"><p class="kpi-num">86</p><p class="kpi-lbl">Campaign Berjalan</p></div>
        </div>

        {{-- Card 4: Action Needed --}}
        <div class="kpi-card kp-r animate-fade-in-up delay-300">
            <div class="kpi-shimmer"></div>
            <div class="flex items-start justify-between mb-5 relative z-10">
                <div class="kpi-icon ki-r"><i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i></div>
                <span class="bd-urg"><span class="urg-dot"></span> Urgent</span>
            </div>
            <div class="relative z-10"><p class="kpi-num">24</p><p class="kpi-lbl">Tindakan Admin Menunggu</p></div>
        </div>

    </div>

    {{-- ===== MAIN GRID ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- 1. TUGAS MENUNGGU / ACTION QUEUE --}}
        <div class="glass-card p-5 flex flex-col xl:col-span-1 animate-fade-in-up delay-100">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-white">Antrean Tugas</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Butuh persetujuan manual</p>
                </div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.15);">
                    <span class="text-xs font-black text-red-400">24</span>
                </div>
            </div>

            <div class="space-y-2.5 flex-1">
                {{-- UGC Task --}}
                <div class="task-item ti-r">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2.5">
                            <span class="tdot-r"></span>
                            <span class="text-xs font-bold text-white">Moderasi Video UGC</span>
                        </div>
                        <span class="tc-r">12</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">Video di-submit & menunggu ditinjau sebelum masuk ke Brand.</p>
                    <a href="{{ route('admin.ugc') }}" class="ta-r">Tinjau Sekarang <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                </div>

                {{-- KYC Task --}}
                <div class="task-item ti-a">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2.5">
                            <span class="tdot-a"></span>
                            <span class="text-xs font-bold text-white">Verifikasi KYC</span>
                        </div>
                        <span class="tc-a">5</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">KTP dan Selfie kreator baru perlu divalidasi manual.</p>
                    <a href="{{ route('admin.kyc') }}" class="ta-a">Validasi Kreator <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                </div>

                {{-- Withdrawal Task --}}
                <div class="task-item ti-g">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2.5">
                            <span class="tdot-g"></span>
                            <span class="text-xs font-bold text-white">Penarikan Dana</span>
                        </div>
                        <span class="tc-g">7</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">Permintaan payout dari wallet kreator ke rekening Bank.</p>
                    <a href="{{ route('admin.withdrawals') }}" class="ta-g">Proses Transfer <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                </div>
            </div>
        </div>

        {{-- 2. CHART GRAFIK PENDAPATAN & ESCROW --}}
        <div class="glass-card p-5 xl:col-span-2 flex flex-col animate-fade-in-up delay-200">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-white">Kinerja Keuangan</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Pergerakan Escrow VS Platform Fee (30 hari)</p>
                </div>
                <select class="select-glass">
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                    <option>Tahun Ini</option>
                </select>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 rounded-sm" style="background: #10b981;"></div>
                    <span class="text-[10px] text-slate-400 font-medium">Platform Fee</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 rounded-sm" style="background: rgba(16,185,129,0.25);"></div>
                    <span class="text-[10px] text-slate-400 font-medium">Escrow</span>
                </div>
            </div>

            {{-- Bar chart --}}
            <div class="flex-1 flex items-end justify-between gap-1.5 h-44 px-1">
                @php
                    $escrow = [40, 65, 45, 80, 55, 90, 70, 85, 60, 95, 75, 88, 50, 72, 93];
                    $fee = [10, 20, 15, 25, 18, 28, 22, 26, 19, 30, 24, 27, 16, 23, 29];
                @endphp
                @foreach($escrow as $i => $h)
                <div class="bar-grp flex-1 flex flex-col items-center justify-end h-full relative" style="gap:1px;">
                    <div class="chart-tip">
                        <span class="text-emerald-400 text-[10px] block font-bold">Escrow: Rp{{ $h }}Jt</span>
                        <span class="text-[10px] block font-bold" style="color:#34d399;">Fee: Rp{{ $fee[$i] }}Jt</span>
                    </div>
                    <div class="bar-fee" style="height: {{ $fee[$i] }}%;"></div>
                    <div class="bar-esc" style="height: {{ $h }}%; margin-top: -{{ $h }}%;"></div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between text-[9px] text-slate-600 mt-3 px-1 font-medium">
                <span>1 Mar</span><span>5 Mar</span><span>10 Mar</span><span>15 Mar</span><span>20 Mar</span><span>25 Mar</span><span>30 Mar</span>
            </div>
        </div>
    </div>

    {{-- ===== BOTTOM GRID: Activity & Top Campaigns ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Log Aktivitas Terbaru --}}
        <div class="glass-card p-5 animate-fade-in-up">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-white">Log Aktivitas Terbaru</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Transaksi & Notifikasi sistem</p>
                </div>
                <a href="{{ route('admin.logs') }}" class="text-[11px] text-brand hover:text-brand-light font-semibold transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="space-y-4">
                @php
                    $logs = [
                        ['type' => 'finance', 'icon' => 'banknote', 'color' => 'emerald', 'title' => 'Pembayaran Escrow Selesai', 'desc' => 'Campaign Tokopedia 12.12 telah dirilis ke 15 kreator.', 'time' => '10 menit yang lalu'],
                        ['type' => 'user', 'icon' => 'user-plus', 'color' => 'brand', 'title' => 'Brand Baru Terdaftar', 'desc' => 'Wardah Beauty ID (wardah@brand.com) baru mendaftar.', 'time' => '1 jam yang lalu'],
                        ['type' => 'alert', 'icon' => 'alert-triangle', 'color' => 'red', 'title' => 'Dispute Dibuka', 'desc' => 'Kreator @dimasviral membuka komplain untuk campaign Indomie.', 'time' => '2 jam yang lalu'],
                        ['type' => 'system', 'icon' => 'cpu', 'color' => 'slate', 'title' => 'Backup Database Selesai', 'desc' => 'Backup harian otomatis berhasil pada 03:00 WIB.', 'time' => 'Hari ini, 03:00'],
                    ];
                @endphp
                @foreach($logs as $log)
                <div class="flex gap-3 group">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 transition-transform duration-200 group-hover:scale-110
                        {{ $log['color'] === 'brand' ? '' : '' }}"
                        style="background: rgba({{ $log['color'] === 'brand' ? '16,185,129' : ($log['color'] === 'emerald' ? '16,185,129' : ($log['color'] === 'red' ? '239,68,68' : '100,116,139')) }},0.1); border: 1px solid rgba({{ $log['color'] === 'brand' ? '16,185,129' : ($log['color'] === 'emerald' ? '16,185,129' : ($log['color'] === 'red' ? '239,68,68' : '100,116,139')) }},0.2);">
                        <i data-lucide="{{ $log['icon'] }}" class="w-3.5 h-3.5 text-{{ $log['color'] === 'brand' ? 'brand' : ($log['color'] === 'emerald' ? 'emerald-400' : ($log['color'] === 'red' ? 'red-400' : 'slate-400')) }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-200">{{ $log['title'] }}</p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">{{ $log['desc'] }}</p>
                        <p class="text-[10px] text-slate-600 mt-1.5 font-medium">{{ $log['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Top Campaign --}}
        <div class="glass-card p-5 animate-fade-in-up delay-100">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-bold text-white">Campaign Paling Aktif</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Berdasarkan volume submission UGC</p>
                </div>
                <a href="{{ route('admin.campaigns') }}" class="text-[11px] text-brand hover:text-brand-light font-semibold transition-colors flex items-center gap-1">
                    Kelola <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="space-y-2.5">
                @php
                    $campaigns = [
                        ['brand' => 'Wardah Beauty', 'title' => 'Skincare Routine Challenge', 'progress' => 85, 'kreators' => 48, 'color' => 'emerald'],
                        ['brand' => 'Tokopedia', 'title' => 'Promo Bebas Ongkir', 'progress' => 45, 'kreators' => 102, 'color' => 'teal'],
                        ['brand' => 'Indomie', 'title' => 'Kreasi Indomie Goreng', 'progress' => 92, 'kreators' => 23, 'color' => 'amber'],
                        ['brand' => 'Shopee', 'title' => 'Haul Shopee 12.12', 'progress' => 60, 'kreators' => 77, 'color' => 'brand'],
                    ];
                    $barColors = [
                        'emerald' => 'from-emerald-600 to-emerald-400',
                        'teal' => 'from-teal-600 to-teal-400',
                        'amber' => 'from-amber-600 to-amber-400',
                        'brand' => 'from-brand-dim to-brand',
                    ];
                    $borderColors = [
                        'emerald' => 'rgba(16,185,129,0.5)',
                        'teal' => 'rgba(20,184,166,0.5)',
                        'amber' => 'rgba(245,158,11,0.5)',
                        'brand' => 'rgba(16,185,129,0.5)',
                    ];
                @endphp
                @foreach($campaigns as $c)
                <div class="rounded-xl p-3.5 transition-all duration-200 cursor-pointer group"
                     style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-left: 2px solid {{ $borderColors[$c['color']] }};">
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <div class="min-w-0">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-600">{{ $c['brand'] }}</span>
                            <h4 class="text-xs font-semibold text-slate-200 mt-0.5 truncate group-hover:text-white transition-colors">{{ $c['title'] }}</h4>
                        </div>
                        <span class="flex items-center gap-1 text-[10px] text-slate-400 flex-shrink-0 px-2 py-1 rounded-lg" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                            <i data-lucide="users" class="w-2.5 h-2.5 text-brand"></i> {{ $c['kreators'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] mb-1.5">
                        <span class="text-slate-500">Penyelesaian</span>
                        <span class="font-bold text-slate-300">{{ $c['progress'] }}%</span>
                    </div>
                    <div class="h-1 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.05);">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $barColors[$c['color']] }} progress-bar" style="width: {{ $c['progress'] }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
