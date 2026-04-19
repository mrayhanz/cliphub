@extends('layouts.brand')

@section('title', 'Review UGC Submissions')

@section('content')
<div class="w-full pb-12 pt-2">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Review UGC</h1>
        <p class="text-xs text-slate-400 max-w-lg leading-relaxed">Validasi performa dan riwayat kerja kreator di sini. Setujui UGC agar pembayaran langsung diteruskan dari escrow ke akun mereka.</p>
    </div>

    {{-- MAIN GRID: Left (Summary) | Right (Submissions List) --}}
    <div class="grid grid-cols-1 xl:grid-cols-[340px_1fr] gap-5 lg:gap-6 items-start">

        {{-- ========================
             LEFT COLUMN: SUMMARY METRICS
             ======================== --}}
        <div class="flex flex-col gap-4 lg:gap-5">

            {{-- MAIN ACTION CARD (Menunggu Review) --}}
            <div class="bg-[#111111] border border-violet-500/30 rounded-[1.5rem] relative overflow-hidden shadow-[inset_0_0_20px_rgba(139,92,246,0.05),0_10px_40px_rgba(91,33,182,0.15)] p-6 lg:p-7">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-violet-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-fuchsia-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <span class="text-[10px] lg:text-xs font-bold text-violet-300 uppercase tracking-widest bg-violet-500/10 border border-violet-500/20 px-2.5 py-1 rounded shadow-sm">Butuh Review</span>
                        <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                            <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(139,92,246,0.5)]">⏳</span>
                        </div>
                    </div>

                    <h2 class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2">
                        45 <span class="text-[10px] lg:text-xs text-slate-400 font-bold ml-1 uppercase tracking-widest">UGC</span>
                    </h2>
                    <p class="text-[10px] lg:text-xs text-slate-400/90 font-medium leading-relaxed mb-1">
                        Video menunggu validasi dan persetujuan (ACC).
                    </p>
                </div>
            </div>

            {{-- SECONDARY METRIC CARDS --}}
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] p-5">
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg drop-shadow-[0_2px_4px_rgba(16,185,129,0.3)]">✅</span>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-emerald-500/90 uppercase tracking-widest mb-1">Telah Disetujui</h4>
                        <p class="text-lg lg:text-xl font-black text-white mb-1">1.204</p>
                        <p class="text-[10px] lg:text-[11px] text-slate-500 leading-relaxed font-medium">UGC selesai divalidasi dan telah terbayar.</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] p-5">
                <div class="flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg drop-shadow-[0_2px_4px_rgba(244,63,94,0.3)]">❌</span>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-rose-500/90 uppercase tracking-widest mb-1">Ditolak / Dispute</h4>
                        <p class="text-lg lg:text-xl font-black text-white mb-1">12</p>
                        <p class="text-[10px] lg:text-[11px] text-slate-500 leading-relaxed font-medium">UGC yang ditolak atau sedang dalam sengketa.</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================
             RIGHT COLUMN: SUBMISSIONS LIST
             ======================== --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.5rem] p-4 lg:p-6 flex flex-col h-full">

            {{-- Tab Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-5 border-b border-white/5 gap-4">
                <h3 class="text-sm lg:text-base font-black text-white">Daftar Submissions</h3>
                
                {{-- Quick Tabs --}}
                <div class="flex gap-1.5 p-1 bg-white/[0.02] border border-white/5 rounded-xl self-start sm:self-auto overflow-x-auto w-full sm:w-auto">
                    <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold transition-all bg-violet-500/10 text-violet-400 border border-violet-500/25 whitespace-nowrap">Semua</button>
                    <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all whitespace-nowrap border border-transparent">Menunggu Review</button>
                    <button class="px-3.5 py-1.5 rounded-lg text-[10px] lg:text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all whitespace-nowrap border border-transparent">Selesai</button>
                </div>
            </div>

            {{-- Custom Search (Inline with List) --}}
            <div class="relative w-full mb-5">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <input type="text" placeholder="Cari nama kreator atau campaign..." class="bg-white/[0.01] border border-white/[0.05] text-[11px] lg:text-xs font-semibold text-white rounded-xl py-3 pr-4 pl-10 outline-none w-full focus:border-violet-500/50 focus:bg-white/[0.03] transition-colors">
            </div>

            {{-- List Wrapper --}}
            <div class="flex flex-col gap-3 flex-1 overflow-y-auto pr-1">
                @php
                    $submissions = [
                        ['creator' => '@sarah.tiktok', 'platform' => 'TikTok', 'status' => 'pending', 'campaign' => 'Review Serum Skincare X', 'views' => 45000, 'cost' => 900000, 'time' => '10 mnt'],
                        ['creator' => '@budi_gaming', 'platform' => 'IG Reels', 'status' => 'pending', 'campaign' => 'Tokopedia 12.12 Mega Sale', 'views' => 120500, 'cost' => 2410000, 'time' => '1 jam'],
                        ['creator' => '@indomie_lover', 'platform' => 'YT Shorts', 'status' => 'pending', 'campaign' => 'Makan Indomie Hack', 'views' => 2100000, 'cost' => 42000000, 'time' => '3 jam'],
                        ['creator' => '@dina_style', 'platform' => 'TikTok', 'status' => 'approved', 'campaign' => 'OOTD Keren 2025', 'views' => 5000, 'cost' => 100000, 'time' => '1 hari'],
                        ['creator' => '@ryan.review', 'platform' => 'IG Reels', 'status' => 'rejected', 'campaign' => 'Spill Gadget 2025', 'views' => 3200, 'cost' => 64000, 'time' => '2 hari'],
                    ];
                @endphp

                @foreach($submissions as $s)
                <div class="bg-white/[0.02] hover:bg-white/[0.04] border border-white/[0.05] rounded-2xl p-4 lg:p-5 transition-colors flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5 justify-between">
                    
                    {{-- Section 1: Avatar & Creator Details --}}
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                        {{-- Icon Indicator (Status) --}}
                        <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl bg-white/[0.03] border border-white/[0.05] flex items-center justify-center flex-shrink-0 relative shadow-inner">
                            @if($s['status'] === 'pending')
                                <i data-lucide="clock" class="w-4 h-4 lg:w-5 lg:h-5 text-amber-500"></i>
                            @elseif($s['status'] === 'approved')
                                <i data-lucide="check-circle" class="w-4 h-4 lg:w-5 lg:h-5 text-emerald-500"></i>
                            @else
                                <i data-lucide="x-circle" class="w-4 h-4 lg:w-5 lg:h-5 text-rose-500"></i>
                            @endif

                            {{-- Mini platform badge icon --}}
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-[#111111] border border-[#1f1f1f] flex items-center justify-center">
                                @if($s['platform'] == 'TikTok')
                                    <i data-lucide="music-2" class="w-2.5 h-2.5 text-slate-300"></i>
                                @elseif($s['platform'] == 'IG Reels')
                                    <i data-lucide="instagram" class="w-2.5 h-2.5 text-pink-500"></i>
                                @else
                                    <i data-lucide="youtube" class="w-2.5 h-2.5 text-red-500"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Main Text Details --}}
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5 lg:mb-1">
                                <p class="text-[13px] lg:text-sm font-black text-white truncate">
                                    {{ $s['creator'] }}
                                </p>
                            </div>
                            <p class="text-[10px] lg:text-xs font-semibold text-slate-400 truncate w-[200px] lg:w-[240px]">
                                {{ $s['campaign'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Section 2: Metrics --}}
                    <div class="flex items-center justify-between sm:justify-start gap-4 lg:gap-6 px-1 sm:px-0">
                        {{-- Views --}}
                        <div class="text-left">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1 flex items-center gap-1.5 focus:outline-none">
                                <i data-lucide="eye" class="w-3 h-3 text-slate-600"></i> Views
                            </p>
                            <p class="text-xs lg:text-sm font-black text-white bg-white/[0.03] px-2 py-0.5 rounded border border-white/5 w-fit">
                                {{ number_format($s['views'], 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Fee/Cost --}}
                        <div class="text-right sm:text-left">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Fee Creator</p>
                            <p class="text-xs lg:text-sm font-black text-emerald-400">Rp {{ number_format($s['cost'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    {{-- Section 3: Action Buttons (Only visible if pending) --}}
                    <div class="pt-3 pb-1 border-t border-white/5 sm:border-0 sm:pt-0 sm:pb-0 mt-3 sm:mt-0 flex items-center gap-2 lg:gap-2.5 justify-end sm:ml-4 flex-shrink-0">
                        @if($s['status'] === 'pending')
                            <button class="flex-1 sm:flex-none h-8 sm:h-9 px-4 sm:px-0 sm:w-9 flex items-center justify-center rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-rose-500 hover:border-rose-500 hover:text-white text-slate-400 transition-colors focus:ring-2 ring-rose-500/20" title="Tolak / Dispute">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                <span class="sm:hidden text-xs font-bold ml-1.5">Tolak</span>
                            </button>
                            <button class="h-8 sm:h-9 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-colors" title="Lihat Screenshot Bukti">
                                <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                <span class="hidden lg:inline text-[10px] font-bold uppercase">Bukti</span>
                            </button>
                            <button class="flex-1 sm:flex-none h-8 sm:h-9 px-4 lg:px-5 rounded-xl bg-violet-600 text-white font-bold text-xs border border-violet-500 hover:bg-violet-500 shadow-[0_0_15px_rgba(124,58,237,0.3)] transition-all flex items-center justify-center gap-1.5" title="Setujui Pembayaran">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> ACC
                            </button>
                        @elseif($s['status'] === 'approved')
                            <div class="px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Selesai
                            </div>
                        @else
                            <div class="px-3 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-bold uppercase flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Dispute
                            </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Pagination/Load More --}}
            <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-center">
                <button class="text-[10px] lg:text-xs font-bold text-slate-500 hover:text-white transition-colors flex items-center gap-1.5 py-2 px-4 rounded-xl hover:bg-white/5">
                    Tampilkan Lebih Banyak <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </button>
            </div>

        </div>

    </div>

</div>
@endsection
