@extends('layouts.kreator')

@section('title', 'Cari Campaign (Marketplace)')

@section('content')
<div class="space-y-6 pb-12 pt-2" x-data="{ currentType: 'all', searchQuery: '' }">

    {{-- HEADER --}}
    {{-- <div>
        <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight mb-1">Temukan Kampanye</h1>
        <p class="text-xs text-slate-500">Lakukan misi brand & dapatkan penghasilan dari views yang kamu hasilkan</p>
    </div> --}}

    {{-- CONTROLS --}}
    <div class="flex gap-3 items-center flex-wrap bg-[#0f0f0f] p-3 rounded-[1.25rem] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)] mb-8">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input type="text" x-model="searchQuery" id="ccSearch" placeholder="Cari campaign…" class="bg-black border-none text-[0.8rem] font-semibold shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] text-white rounded-2xl py-3 pr-4 pl-10 outline-none transition-shadow duration-200 w-full min-w-[200px] focus:shadow-[inset_0_0_0_1.5px_rgba(139,92,246,0.6),_0_0_0_4px_rgba(139,92,246,0.1)]">
        </div>

        {{-- Type toggle --}}
        <div class="inline-flex overflow-x-auto gap-1.5 cc-toggle [&::-webkit-scrollbar]:hidden [scrollbar-width:none] [-ms-overflow-style:none]">
            <button @click="currentType = 'all'" :class="currentType === 'all' ? 'active' : ''" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-[0.75rem] font-extrabold cursor-pointer transition-all duration-200 text-zinc-500 bg-transparent whitespace-nowrap hover:bg-white/[0.02] hover:text-white [&.active]:bg-violet-500/10 [&.active]:text-violet-400 [&.active]:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.3)]">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-500 shadow-[0_0_8px_rgba(139,92,246,0.8)]"></span> Semua
            </button>
            <button @click="currentType = 'clip'" :class="currentType === 'clip' ? 'active' : ''" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-[0.75rem] font-extrabold cursor-pointer transition-all duration-200 text-zinc-500 bg-transparent whitespace-nowrap hover:bg-white/[0.02] hover:text-white [&.active]:bg-violet-500/10 [&.active]:text-violet-400 [&.active]:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.3)]">
                <span class="w-1.5 h-1.5 rounded-full bg-pink-500 shadow-[0_0_8px_rgba(236,72,153,0.8)]"></span> Nge-clip
            </button>
            <button @click="currentType = 'ugc'" :class="currentType === 'ugc' ? 'active' : ''" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-[0.75rem] font-extrabold cursor-pointer transition-all duration-200 text-zinc-500 bg-transparent whitespace-nowrap hover:bg-white/[0.02] hover:text-white [&.active]:bg-violet-500/10 [&.active]:text-violet-400 [&.active]:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.3)]">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)]"></span> UGC
            </button>
        </div>

        <div class="flex-1 lg:flex-none"></div>

        {{-- Filter icon --}}
        <button class="flex items-center justify-center w-10 h-10 rounded-xl bg-black border-none text-slate-400 hover:text-white hover:bg-neutral-900 transition-colors shadow-[inset_0_0_0_1px_rgba(255,255,255,0.08)] ml-auto cursor-pointer focus:outline-none focus:shadow-[inset_0_0_0_1.5px_rgba(139,92,246,0.5)]">
            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
        </button>
    </div>

    {{-- CAMPAIGN GRID --}}
    <!-- CAMPAIGNS INJECTED FROM CONTROLLER -->

    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4" id="ccGrid">
        @foreach($campaigns as $idx => $c)

        <a href="{{ $c['full'] ? '#' : route('kreator.campaigns.show', $c['id']) }}" class="bg-[#0a0a0a] rounded-[1.25rem] overflow-hidden relative flex flex-col shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] transition-all duration-200 {{ $c['full'] ? 'opacity-50 grayscale cursor-default pointer-events-none' : 'group hover:-translate-y-[3px] hover:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.3),_0_10px_30px_rgba(0,0,0,0.6)] cursor-pointer' }}"
             x-show="(currentType === 'all' || currentType === '{{ $c['type'] }}') && (searchQuery === '' || '{{ strtolower(addslashes($c['title'] . ' ' . $c['brand'] . ' ' . $c['category'])) }}'.includes(searchQuery.toLowerCase()))"
             style="animation-delay: {{ $idx * 0.06 }}s">

            {{-- THUMBNAIL --}}
            <div class="relative w-full aspect-video overflow-hidden bg-[#111] flex-shrink-0">
                <img src="{{ asset($c['image']) }}" alt="{{ $c['title'] }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 ease-out {{ $c['full'] ? '' : 'group-hover:scale-105' }}">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/40 to-transparent pointer-events-none"></div>

                {{-- Content type badge — top left --}}
                <span class="absolute top-2.5 left-2.5 z-10 text-[0.6rem] font-black py-1 px-2.5 rounded-lg backdrop-blur-md bg-black/40 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.15)] text-slate-200">{{ strtoupper($c['type']) }}</span>

                {{-- Status badge — top right --}}
                @php
                    $statusColorMap = [
                        's-green' => 'bg-emerald-500/15 shadow-[inset_0_0_0_1px_rgba(16,185,129,0.3)] text-emerald-400',
                        's-amber' => 'bg-amber-500/15 shadow-[inset_0_0_0_1px_rgba(245,158,11,0.3)] text-amber-400',
                        's-red'   => 'bg-red-500/15 shadow-[inset_0_0_0_1px_rgba(239,68,68,0.3)] text-red-400',
                    ];
                    $mappedStatusCls = $statusColorMap[$c['statusCls']] ?? '';
                @endphp
                <span class="absolute top-2.5 right-2.5 z-10 text-[0.55rem] font-black py-1 px-2.5 rounded-lg backdrop-blur-md whitespace-nowrap {{ $mappedStatusCls }}">{{ $c['statusText'] }}</span>

                {{-- Brand chip — bottom left --}}
                <div class="absolute bottom-2 left-2.5 z-10 flex items-center gap-1.5 bg-black/50 backdrop-blur-md shadow-[inset_0_0_0_1px_rgba(255,255,255,0.08)] rounded-lg py-1 pr-2 pl-1 max-w-[calc(100%-20px)]">
                    <span class="w-3.5 h-3.5 rounded flex items-center justify-center text-[0.5rem] font-black text-white shrink-0 shadow-[0_0_5px_rgba(255,255,255,0.2)]" style="background: {{ $c['dotColor'] }}">{{ $c['initial'] }}</span>
                    <span class="text-[0.6rem] font-bold text-slate-200 whitespace-nowrap overflow-hidden text-ellipsis">{{ $c['brand'] }} · {{ $c['category'] }}</span>
                </div>
            </div>

            {{-- CARD BODY --}}
            <div class="p-4 flex-1 flex flex-col bg-[#0a0a0a]">

                {{-- Title --}}
                <h3 class="text-[0.95rem] font-black text-white leading-tight mb-2 transition-colors {{ $c['full'] ? '' : 'group-hover:text-violet-400' }}">{{ $c['title'] }}</h3>

                {{-- Desc --}}
                <p class="text-[0.7rem] text-zinc-400 leading-relaxed mb-4 line-clamp-2 flex-1">{{ $c['desc'] }}</p>

                {{-- Stats row --}}
                <div class="flex bg-[#111] rounded-xl shadow-[inset_0_0_0_1px_rgba(255,255,255,0.04)] p-2 items-center">
                    <div class="flex-1 flex flex-col items-center text-center border-r border-white/5">
                        <span class="text-[0.55rem] font-black text-zinc-500 uppercase tracking-wider mb-1">Deadline</span>
                        <span class="text-[0.7rem] font-extrabold text-slate-300">{{ $c['deadline'] }}</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center text-center border-r border-white/5">
                        <span class="text-[0.55rem] font-black text-zinc-500 uppercase tracking-wider mb-1">Creator</span>
                        <span class="text-[0.7rem] font-extrabold {{ $c['crColor'] }}">{{ $c['creator'] }}</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center text-center">
                        <span class="text-[0.55rem] font-black text-zinc-500 uppercase tracking-wider mb-1">Rate/1K</span>
                        <span class="text-[0.7rem] font-extrabold text-violet-400">{{ $c['rate'] }}</span>
                    </div>
                </div>

            </div>
        </a>

        @endforeach
    </div>

    </div>
</div>
@endsection
