@extends('layouts.brand')

@section('title', 'Campaign Saya')

@section('content')
<div class="max-w-7xl mx-auto pb-12 pt-2">

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-violet-600/10 border border-violet-500/30 text-violet-400 font-bold text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER & SEARCH BAR --}}
    <div class="bg-[#0f0f0f] rounded-2xl p-3 border border-neutral-800 flex justify-between items-center flex-wrap gap-4 mb-8">
        <div class="flex gap-2 overflow-x-auto [&::-webkit-scrollbar]:hidden flex-1">
            <a href="#" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap bg-violet-500/10 text-violet-400 border border-violet-500/25">Semua</a>
            <a href="#" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap bg-transparent text-zinc-500 hover:text-zinc-100 hover:bg-white/5">🚀 Aktif</a>
            <a href="#" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap bg-transparent text-zinc-500 hover:text-zinc-100 hover:bg-white/5">✅ Selesai</a>
            <a href="#" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap bg-transparent text-zinc-500 hover:text-zinc-100 hover:bg-white/5">📝 Draft</a>
        </div>
        <div class="relative w-full sm:w-auto">
            <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input type="text" placeholder="Cari campaign..." class="bg-black border border-zinc-800 text-sm font-semibold text-white rounded-2xl py-3 pr-4 pl-10 outline-none transition-colors duration-200 w-full sm:w-[250px] focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15">
        </div>
    </div>

    @if(empty($campaigns) || count($campaigns) === 0)
        <div class="w-full flex flex-col items-center justify-center py-24 px-6 border border-dashed border-neutral-800 rounded-3xl bg-[#111111]/30">
            <div class="w-20 h-20 bg-neutral-900 border border-neutral-800 rounded-full flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(139,92,246,0.15)] relative">
                <div class="absolute inset-0 rounded-full bg-violet-500/20 blur-xl pointer-events-none"></div>
                <i data-lucide="megaphone" class="w-8 h-8 text-violet-400 relative z-10"></i>
            </div>
            <h3 class="text-xl font-black text-white mb-2">Belum Ada Campaign</h3>
            <p class="text-sm text-slate-500 mb-8 max-w-sm text-center leading-relaxed">Anda belum memiliki campaign aktif. Mulai buat campaign pertama Anda untuk menjangkau kreator terbaik.</p>
            <a href="{{ route('brand.campaigns.create') }}" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-2xl text-sm font-extrabold inline-flex items-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Buat Campaign Sekarang
            </a>
        </div>
    @else
    {{-- CAMPAIGN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6">
        @foreach($campaigns as $c)
        @php
            $budgetValue     = (float) ($c->budget ?? 0);
            $spentValue      = 0; // Temporarily 0 until we have actual spending logic
            $progressPercent = $budgetValue > 0 ? ($spentValue / $budgetValue) * 100 : 0;
            
            // Theme mapping based on status
            $status = strtolower($c->status ?? 'draft');
            if ($status === 'active') {
                $statusColor = 'text-green-400';
                $progressClass = 'bg-gradient-to-r from-violet-600 to-fuchsia-600 shadow-[0_0_10px_rgba(192,38,211,0.5)]';
                $glowEffect = '<div class="absolute -right-10 top-20 w-32 h-32 bg-violet-600/10 rounded-full blur-2xl pointer-events-none group-hover:bg-fuchsia-600/20 transition-colors duration-500"></div>';
            } elseif ($status === 'completed') {
                $statusColor = 'text-slate-300';
                $progressClass = 'bg-gradient-to-r from-zinc-700 to-zinc-500';
                $glowEffect = '';
            } else { // draft
                $statusColor = 'text-amber-400';
                $progressClass = 'bg-gradient-to-r from-amber-700 to-amber-500';
                $glowEffect = '';
            }

            // Thumbnail fallback
            $thumbUrl = $c->thumbnail ? asset('storage/' . $c->thumbnail) : asset('images/brand/campaign-placeholder.png');
        @endphp
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl relative overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:border-violet-900 hover:shadow-[0_10px_40px_rgba(91,33,182,0.15)] group flex flex-col {{ $status === 'draft' ? 'opacity-70 grayscale-[50%]' : '' }}">
            {!! $glowEffect !!}

            {{-- THUMBNAIL IMAGE --}}
            <div class="relative w-full aspect-[21/9] bg-neutral-900 overflow-hidden flex-shrink-0">
                <img src="{{ $thumbUrl }}" alt="{{ $c->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#111111] via-[#111111]/40 to-transparent pointer-events-none"></div>
                
                {{-- Status Badge --}}
                <div class="absolute top-3 left-3 z-10">
                    <div class="bg-black/40 backdrop-blur-md border border-white/10 text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $status === 'active' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.8)]' : ($status === 'draft' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">{{ $c->status }}</span>
                    </div>
                </div>
                
                {{-- Menu Icon --}}
                <div class="absolute top-3 right-3 z-10">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-white/10 transition-colors focus:outline-none">
                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="px-5 pb-5 pt-2 flex-1 relative z-10 flex flex-col">
                {{-- Title --}}
                <h3 class="text-lg font-black text-white mb-3.5 leading-tight group-hover:text-violet-400 transition-colors">{{ $c->title }}</h3>
                
                {{-- Quick Metrics Row --}}
                <div class="flex items-center gap-2 text-xs text-slate-400 font-bold mb-5">
                    <span class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5">
                        <i data-lucide="video" class="w-4 h-4 text-violet-400"></i> 0 Klip
                    </span>
                    <span class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5">
                        <i data-lucide="eye" class="w-4 h-4 text-fuchsia-400"></i> 0
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-2 mt-auto">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Anggaran Terpakai</p>
                            <p class="text-xs font-black text-white">Rp 0 <span class="text-[10px] text-slate-500 font-semibold">/ Rp {{ number_format($budgetValue, 0, ',', '.') }}</span></p>
                        </div>
                        <span class="text-[10px] font-black text-violet-400">{{ number_format($progressPercent, 0) }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden relative mt-3">
                        <div class="absolute left-0 top-0 h-full rounded-full {{ $progressClass }}" style="width: {{ $progressPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-5 py-4 border-t border-white/5 bg-white/[0.01] flex justify-between items-center relative z-10">
                <button class="text-[11px] font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
                    <i data-lucide="settings" class="w-3.5 h-3.5"></i> Setelan
                </button>
                <a href="{{ route('brand.submissions') }}" class="text-[11px] font-black {{ $status === 'active' ? 'text-violet-400 hover:text-fuchsia-400' : 'text-slate-400 hover:text-white' }} transition-colors flex items-center gap-1.5 px-4 py-2 rounded-lg {{ $status === 'active' ? 'bg-violet-500/10 hover:bg-violet-500/20' : 'bg-white/5 hover:bg-white/10' }}">
                    Review UGC <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
