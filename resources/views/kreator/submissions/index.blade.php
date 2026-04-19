@extends('layouts.kreator')

@section('title', 'Riwayat Submissions')

@section('content')
<div class="max-w-6xl mx-auto pb-12 space-y-7 pt-2">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5 mb-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-violet-500/10 text-violet-400 text-[10px] font-bold mb-3" style="box-shadow: inset 0 0 0 1px rgba(139,92,246,0.2)">
                <i data-lucide="history" class="w-3 h-3"></i> Log Aktivitas
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-white leading-tight mb-2">Riwayat Submissions</h1>
            <p class="text-xs text-slate-500 max-w-sm">Pantau status pengajuan klaim views dan estimasi komisi kamu di sini.</p>
        </div>
        
        <a href="{{ route('kreator.submissions.create') }}" class="self-start sm:self-auto bg-gradient-to-br from-violet-600 to-fuchsia-500 text-white px-6 py-3 rounded-2xl text-[0.8rem] font-extrabold tracking-wide inline-flex items-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(139,92,246,0.3)] hover:shadow-[0_8px_25px_rgba(139,92,246,0.45)] hover:-translate-y-[1px]">
            <i data-lucide="plus" class="w-4 h-4"></i> Buat Submit Baru
        </a>
    </div>

    {{-- FILTER TABS --}}
    <div class="flex gap-2 overflow-x-auto pb-2 [&::-webkit-scrollbar]:hidden">
        <a href="#" class="px-4 py-2.5 rounded-xl text-[0.75rem] font-bold whitespace-nowrap transition-all duration-200 bg-violet-500/10 text-violet-300 shadow-[inset_0_0_0_1px_rgba(139,92,246,0.25)]">Semua Riwayat</a>
        <a href="#" class="px-4 py-2.5 rounded-xl text-[0.75rem] font-bold whitespace-nowrap bg-transparent text-slate-400 transition-all duration-200 shadow-[inset_0_0_0_1px_transparent] hover:text-slate-50 hover:bg-white/[0.03] hover:shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)]">
            ⏳ Pending <span class="ml-1 px-1.5 py-0.5 rounded-md bg-white/10 text-[9px]">2</span>
        </a>
        <a href="#" class="px-4 py-2.5 rounded-xl text-[0.75rem] font-bold whitespace-nowrap bg-transparent transition-all duration-200 shadow-[inset_0_0_0_1px_transparent] text-emerald-400/80 hover:text-emerald-300 hover:bg-emerald-500/10 hover:shadow-[inset_0_0_0_1px_rgba(16,185,129,0.2)]">💰 Disetujui (Dibayar)</a>
        <a href="#" class="px-4 py-2.5 rounded-xl text-[0.75rem] font-bold whitespace-nowrap bg-transparent transition-all duration-200 shadow-[inset_0_0_0_1px_transparent] text-red-400/80 hover:text-red-300 hover:bg-red-500/10 hover:shadow-[inset_0_0_0_1px_rgba(239,68,68,0.2)]">🛑 Ditolak / Dispute</a>
    </div>

    {{-- LIST WRAPPER --}}
    <div class="bg-[#0a0a0a] rounded-3xl overflow-hidden shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]">
        
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" style="box-shadow: 0 1px 0 rgba(255,255,255,0.04); background: rgba(255,255,255,0.01)">
            <div class="col-span-5">Campaign & Platform</div>
            <div class="col-span-2 text-right">Views Klaim</div>
            <div class="col-span-2 text-right">Estimasi / Hasil</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-1 text-right">Aksi</div>
        </div>

        @php
            $history = [
                ['id' => 1, 'campaign' => 'Tokopedia 12.12 Mega Sale', 'platform' => 'TikTok', 'status' => 'Pending Review', 'color' => 'bg-amber-500/10 text-amber-400 shadow-[inset_0_0_0_1px_rgba(245,158,11,0.25)]', 'views' => '120.000', 'potensi' => 'Rp 2.400.000', 'date' => 'Hari ini, 14:30'],
                ['id' => 2, 'campaign' => 'Skincare Routine Challenge - Wardah', 'platform' => 'IG Reels', 'status' => 'Pending Review', 'color' => 'bg-amber-500/10 text-amber-400 shadow-[inset_0_0_0_1px_rgba(245,158,11,0.25)]', 'views' => '45.000', 'potensi' => 'Rp 1.125.000', 'date' => 'Hari ini, 09:15'],
                ['id' => 3, 'campaign' => 'Review Kopi Kenangan Edisi Gold', 'platform' => 'TikTok', 'status' => 'Dibayar', 'color' => 'bg-emerald-500/10 text-emerald-400 shadow-[inset_0_0_0_1px_rgba(16,185,129,0.25)]', 'views' => '2.100.000', 'potensi' => 'Rp 42.000.000', 'date' => 'Kemarin, 11:20'],
                ['id' => 4, 'campaign' => 'Unboxing Raket Badminton Pro', 'platform' => 'YouTube Shorts', 'status' => 'Ditolak', 'color' => 'bg-red-500/10 text-red-300 shadow-[inset_0_0_0_1px_rgba(239,68,68,0.25)]', 'views' => '8.000', 'potensi' => 'Rp 160.000', 'date' => '24 Mar 2026', 'alasan' => 'Bukti screenshot terdeteksi buram/resolusi sangat rendah. Harap unggah screenshot asli dari aplikasi Creator Studio tanpa dikompresi.'],
            ];
        @endphp

        <div class="flex flex-col">
            @foreach($history as $h)
            <div class="p-5 transition-colors duration-200 flex flex-col md:flex-row md:grid md:grid-cols-12 md:gap-4 md:items-center border-b border-white/5 last:border-0 hover:bg-white/[0.02] relative group">
                
                {{-- Column 1: Campaign & Icon (Col: 5) --}}
                <div class="md:col-span-5 flex items-center gap-4 min-w-0">
                    <div class="w-11 h-11 rounded-2xl shrink-0 flex items-center justify-center {{ $h['platform'] == 'TikTok' ? 'bg-black text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)]' : ($h['platform'] == 'IG Reels' ? 'bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white' : 'bg-[#ff0000] text-white') }}">
                        @if($h['platform'] == 'TikTok')
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                        @elseif($h['platform'] == 'IG Reels')
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        @else
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33zM9.75 15.02V8.48l6.5 3.27-6.5 3.27z"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 pr-2">
                        <h3 class="text-[13px] font-bold text-white mb-0.5 truncate group-hover:text-violet-300 transition-colors">{{ $h['campaign'] }}</h3>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest">{{ $h['platform'] }} <span class="mx-1">•</span> {{ $h['date'] }}</p>
                    </div>
                </div>

                {{-- Mobile divider --}}
                <div class="md:hidden w-full h-px bg-white/5 my-2"></div>

                {{-- Mobile specific label grid --}}
                <div class="md:hidden grid grid-cols-2 gap-4 w-full">
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Views</p>
                        <p class="text-xs font-black text-white">{{ $h['views'] }} <i data-lucide="eye" class="inline w-3 h-3 text-slate-600"></i></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Estimasi</p>
                        <p class="text-xs font-black {{ $h['status'] === 'Ditolak' ? 'text-slate-500 line-through opacity-50' : ($h['status'] === 'Dibayar' ? 'text-emerald-400' : 'text-amber-400') }}">{{ $h['potensi'] }}</p>
                    </div>
                    <div class="col-span-2 flex items-center justify-between mt-2">
                        <span class="px-3 py-1.5 rounded-full text-[0.65rem] font-black tracking-widest uppercase inline-flex items-center justify-center whitespace-nowrap {{ $h['color'] }}">{{ $h['status'] }}</span>
                        <a href="#" class="text-[10px] font-bold text-violet-400 flex items-center gap-1 bg-violet-500/10 px-3 py-1.5 rounded-lg">Detail <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
                    </div>
                </div>

                {{-- Desktop Columns --}}
                {{-- Column 2: Views (Col: 2) --}}
                <div class="hidden md:block md:col-span-2 text-right">
                    <p class="text-sm font-black text-white">{{ $h['views'] }}</p>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5 uppercase tracking-widest"><i data-lucide="eye" class="inline w-3 h-3 text-slate-600 mr-0.5"></i> Views</p>
                </div>

                {{-- Column 3: Estimasi (Col: 2) --}}
                <div class="hidden md:block md:col-span-2 text-right">
                    <p class="text-[13px] font-black {{ $h['status'] === 'Ditolak' ? 'text-slate-500 line-through opacity-50' : ($h['status'] === 'Dibayar' ? 'text-emerald-400' : 'text-amber-400') }}">{{ $h['potensi'] }}</p>
                </div>

                {{-- Column 4: Status (Col: 2) --}}
                <div class="hidden md:flex md:col-span-2 justify-center">
                    <span class="px-3 py-1.5 rounded-full text-[0.65rem] font-black tracking-widest uppercase inline-flex items-center justify-center whitespace-nowrap {{ $h['color'] }}">{{ $h['status'] }}</span>
                </div>

                {{-- Column 5: Action (Col: 1) --}}
                <div class="hidden md:flex md:col-span-1 justify-end">
                    <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Lihat Detail">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>

                {{-- Warning Dropdown Area if Rejected --}}
                @if(isset($h['alasan']))
                    <div class="md:col-span-12 mt-2 w-full">
                        <div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 flex gap-3 items-start">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-0.5">Alasan Penolakan</p>
                                <p class="text-xs text-red-200/80 leading-relaxed">{{ $h['alasan'] }}</p>
                                <button class="mt-2 text-[10px] font-bold text-white bg-red-500 hover:bg-red-400 px-3 py-1.5 rounded-lg transition-colors shadow-lg shadow-red-500/20">Ajukan Ulang / Dispute</button>
                            </div>
                        </div>
                    </div>
                @endif
                
            </div>
            @endforeach
        </div>
        
    </div>

</div>
@endsection
