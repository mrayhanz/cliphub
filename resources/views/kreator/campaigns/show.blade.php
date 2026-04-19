@extends('layouts.kreator')

@section('title', $campaign['title'] . ' | ClipHub')

@section('content')
<div class="max-w-7xl mx-auto pb-12 relative" x-data="{ applied: false }">

    {{-- TOP NAVIGATION HEADER --}}
    <div class="flex items-center justify-between mb-6 relative z-20">
        <a href="{{ route('kreator.campaigns') }}" class="flex items-center gap-3 w-fit px-4 h-12 rounded-xl bg-neutral-900/80 backdrop-blur-md border border-neutral-800 text-slate-300 hover:text-white hover:bg-neutral-800 transition shadow-lg">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span class="font-bold text-sm">Kembali</span>
        </a>
        <button class="w-12 h-12 rounded-xl bg-neutral-900/80 backdrop-blur-md border border-neutral-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-neutral-800 transition shadow-lg">
            <i data-lucide="share-2" class="w-5 h-5"></i>
        </button>
    </div>

    {{-- COVER BANNER --}}
    <div class="w-full h-[280px] md:h-[350px] bg-[#0a0a0a] bg-center bg-cover relative rounded-[1.5rem] overflow-hidden -mb-[5rem] border border-white/5 shadow-2xl" style="background-image: url('{{ asset($campaign['cover']) }}');">
        <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-[#121212]/30 to-transparent"></div>
    </div>

    {{-- CONTENT WRAPPER --}}
    <div class="relative z-10 px-0 md:px-8">
        
        {{-- HEAD INFO --}}
        <div class="bg-[#121212] border border-white/10 rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.5)] p-6 md:p-8 mb-8 backdrop-blur-xl relative top-0 mx-4 md:mx-0">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black text-white shadow-xl shrink-0" style="background: {{ $campaign['dotColor'] }}">
                        {{ $campaign['initial'] }}
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-white leading-tight mb-2">{{ $campaign['title'] }}</h2>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-sm font-bold text-slate-300 flex items-center gap-1.5">
                                <i data-lucide="building-2" class="w-4 h-4 text-slate-500"></i>
                                {{ $campaign['brand'] }}
                            </span>
                            <span class="w-1 h-1 rounded-full bg-slate-600 hidden md:block"></span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[0.7rem] font-extrabold uppercase tracking-wider bg-violet-500/15 text-violet-300 border border-violet-500/30">
                                <i data-lucide="{{ $campaign['type'] == 'clip' ? 'scissors' : 'video' }}" class="w-3.5 h-3.5"></i>
                                {{ strtoupper($campaign['type']) }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-[0.7rem] font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> TERSEDIA
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="text-left lg:text-right bg-neutral-900/50 p-4 rounded-xl border border-white/5 shrink-0 w-full lg:w-auto">
                    <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Rate Komisi</p>
                    <p class="text-2xl font-black text-violet-400 flex items-baseline gap-1 lg:justify-end">
                        {{ $campaign['rate'] }} <span class="text-sm text-slate-500 font-bold">/ 1K Views</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start px-4 md:px-0">
            
            {{-- LEFT COLUMN: CONTENT --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Tugas & Cara Kerja --}}
                <div class="bg-[#121212] border border-white/5 rounded-[1.5rem] p-6 md:p-8 shadow-lg">
                    <h3 class="text-lg font-black text-white flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/20 flex items-center justify-center text-violet-400">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        Tugas & Cara Kerja
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">{{ $campaign['desc'] }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-neutral-900/50 p-4 rounded-xl border border-white/5 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center shrink-0">
                                <i data-lucide="youtube" class="w-5 h-5 text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-wide mb-0.5">Upload Ke</p>
                                <p class="font-bold text-sm text-white">Tiktok & Web</p>
                            </div>
                        </div>
                        <div class="bg-neutral-900/50 p-4 rounded-xl border border-white/5 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5 text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-wide mb-0.5">Panjang Video</p>
                                <p class="font-bold text-sm text-white">20-60 detik</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Full Brief --}}
                <div class="bg-[#121212] border border-white/5 rounded-[1.5rem] p-6 md:p-8 shadow-lg">
                    <h3 class="text-lg font-black text-white flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/20 flex items-center justify-center text-violet-400">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        Isi Konten Harus Begini
                    </h3>
                    <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-neutral-900/40 p-5 rounded-xl border border-white/5">{{ $campaign['full_brief'] }}</div>

                    <div class="mt-6 p-5 rounded-xl bg-violet-900/10 border border-violet-500/20 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center shrink-0 hidden sm:flex">
                            <i data-lucide="folder-open" class="w-6 h-6 text-violet-400"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-white font-bold text-sm">Ambil Bahan Di Sini</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Google Drive Folder (Video & Script)</p>
                        </div>
                        <a href="#" class="shrink-0 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold px-5 py-3 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-violet-500/20">
                            Buka Link <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

                {{-- Don'ts --}}
                <div class="bg-red-950/20 border border-red-500/20 rounded-[1.5rem] p-6 md:p-8 shadow-lg">
                    <h3 class="text-lg font-black text-white flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center text-red-500">
                            <i data-lucide="alert-octagon" class="w-4 h-4"></i>
                        </div>
                        JANGAN Lakukan Ini!
                    </h3>
                    <p class="text-xs text-red-400/80 font-medium mb-6 bg-red-500/10 inline-block px-3 py-1.5 rounded-md border border-red-500/10">Awas! Kalau melanggar, konten kamu bisa otomatis ditolak berujung penalti.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 w-5 h-5 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center shrink-0"><i data-lucide="x" class="w-3 h-3"></i></div>
                            <span class="text-slate-300 text-sm leading-snug">Klaim berlebihan atau terlalu hiperbola</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 w-5 h-5 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center shrink-0"><i data-lucide="x" class="w-3 h-3"></i></div>
                            <span class="text-slate-300 text-sm leading-snug">Menyebut pesaing / brand kompetitor lain</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 w-5 h-5 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center shrink-0"><i data-lucide="x" class="w-3 h-3"></i></div>
                            <span class="text-slate-300 text-sm leading-snug">Menyebut nominal harga palsu</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 w-5 h-5 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center shrink-0"><i data-lucide="x" class="w-3 h-3"></i></div>
                            <span class="text-slate-300 text-sm leading-snug">SARA, politik kebencian, & info palsu</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: SIDEBAR (STICKY) --}}
            <div class="lg:col-span-1 border border-white/5 bg-[#121212] p-6 lg:p-8 rounded-[1.5rem] lg:sticky lg:top-8 space-y-8 shadow-lg">
                
                {{-- Info Detail --}}
                <div>
                    <h3 class="text-md font-black text-white flex items-center gap-2 mb-6">
                        <i data-lucide="info" class="w-4 h-4 text-violet-500"></i> Info & Status
                    </h3>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-1.5">
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest">Sistem Pembayaran</p>
                            <p class="text-sm font-semibold text-slate-200">Views otomatis berhenti jika batas waktu tiba.</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest">Batas Waktu</p>
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-200 bg-neutral-900/50 w-fit px-3 py-1.5 rounded-lg border border-white/5">
                                <i data-lucide="calendar" class="w-4 h-4 text-emerald-400"></i>
                                {{ $campaign['deadline'] }}
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest">Kapasitas Kreator</p>
                            <div class="flex items-center gap-2 text-sm font-bold text-amber-400">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                Banyak Peminat (Sisa 14 Slot)
                            </div>
                            <div class="w-full h-1.5 bg-neutral-800 rounded-full mt-1.5 overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full w-[80%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-neutral-800">

                {{-- ACTION PERTAMA (Ambil Campaign) --}}
                <div class="space-y-4">
                    <div class="text-center md:text-left lg:text-center">
                        <p class="text-sm text-slate-200 font-bold mb-1">Siap untuk mulai?</p>
                        <p class="text-[0.7rem] text-slate-500">Pastikan kamu sudah membaca seluruh syarat.</p>
                    </div>
                    
                    <template x-if="!applied">
                        <button @click="applied = true" class="w-full bg-violet-600 hover:bg-violet-500 text-white font-black py-4 px-6 rounded-[1rem] transition-all shadow-[0_0_20px_rgba(139,92,246,0.3)] hover:shadow-[0_0_30px_rgba(139,92,246,0.5)] hover:-translate-y-0.5 flex justify-center items-center gap-2 group">
                            Gabung Campaign 
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </template>
                    <template x-if="applied">
                        <button disabled class="w-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-black py-4 px-6 rounded-[1rem] flex justify-center items-center gap-2 cursor-not-allowed">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Sudah Bergabung
                        </button>
                    </template>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:initialized', () => {
        Alpine.effect(() => {
            setTimeout(() => {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }, 10);
        });
    });
</script>
@endsection
