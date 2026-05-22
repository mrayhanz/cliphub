@extends('layouts.brand')

@section('title', 'Campaign Saya')

@section('content')
<div class="max-w-7xl mx-auto pb-12 pt-2">

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-600/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-600/10 border border-red-500/30 text-red-400 font-bold text-sm flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER & SEARCH BAR --}}
    <div class="bg-[#0f0f0f] rounded-2xl p-3 border border-neutral-800 flex justify-between items-center flex-wrap gap-4 mb-8">
        <div class="flex gap-2 overflow-x-auto [&::-webkit-scrollbar]:hidden flex-1">
            @php
                $filterLinkClass = fn($name) => 'px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap ' . ($filter === $name ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25' : 'bg-transparent text-zinc-500 hover:text-zinc-100 hover:bg-white/5');
                $queryFor = fn($status) => array_filter(['status' => $status, 'q' => $search ?: null], fn($value) => $value !== null && $value !== '');
            @endphp
            <a href="{{ route('brand.campaigns', $queryFor('all')) }}" class="{{ $filterLinkClass('all') }}">Semua <span class="text-[10px] opacity-70">({{ $counts['all'] ?? 0 }})</span></a>
            <a href="{{ route('brand.campaigns', $queryFor('active')) }}" class="{{ $filterLinkClass('active') }}">Aktif <span class="text-[10px] opacity-70">({{ $counts['active'] ?? 0 }})</span></a>
            <a href="{{ route('brand.campaigns', $queryFor('completed')) }}" class="{{ $filterLinkClass('completed') }}">Selesai <span class="text-[10px] opacity-70">({{ $counts['completed'] ?? 0 }})</span></a>
            <a href="{{ route('brand.campaigns', $queryFor('draft')) }}" class="{{ $filterLinkClass('draft') }}">Draft <span class="text-[10px] opacity-70">({{ $counts['draft'] ?? 0 }})</span></a>
        </div>
        <form method="GET" action="{{ route('brand.campaigns') }}" class="relative w-full sm:w-auto">
            <input type="hidden" name="status" value="{{ $filter }}">
            <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari campaign..." class="bg-black border border-zinc-800 text-sm font-semibold text-white rounded-2xl py-3 pr-4 pl-10 outline-none transition-colors duration-200 w-full sm:w-[250px] focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/15">
        </form>
    </div>
    @if(empty($campaigns) || count($campaigns) === 0)
        <div class="w-full flex flex-col items-center justify-center py-24 px-6 border border-dashed border-neutral-800 rounded-3xl bg-[#111111]/30">
            <div class="w-20 h-20 bg-neutral-900 border border-neutral-800 rounded-full flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(16,185,129,0.15)] relative">
                <div class="absolute inset-0 rounded-full bg-emerald-500/20 blur-xl pointer-events-none"></div>
                <i data-lucide="megaphone" class="w-8 h-8 text-emerald-400 relative z-10"></i>
            </div>
            <h3 class="text-xl font-black text-white mb-2">Belum Ada Campaign</h3>
            <p class="text-sm text-slate-500 mb-8 max-w-sm text-center leading-relaxed">Anda belum memiliki campaign aktif. Mulai buat campaign pertama Anda untuk menjangkau kreator terbaik.</p>
            <a href="{{ route('brand.campaigns.create') }}" class="bg-gradient-to-br from-emerald-600 to-green-600 text-white px-6 py-3 rounded-2xl text-sm font-extrabold inline-flex items-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(5,150,105,0.25)] active:scale-95">
                <i data-lucide="plus" class="w-4 h-4"></i> Buat Campaign Sekarang
            </a>
        </div>
    @else
    {{-- CAMPAIGN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6">
        @foreach($campaigns as $c)
        @php
            $budgetValue     = (float) ($c->budget ?? 0);
            $spentValue      = (float) ($c->budget_spent ?? 0);
            $progressPercent = $budgetValue > 0 ? ($spentValue / $budgetValue) * 100 : 0;
            $progressWidth   = min(100, max(0, $progressPercent));
            
            // Theme mapping based on effective status
            $status = $c->effective_status;
            if ($status === 'active') {
                $statusColor = 'text-green-400';
                $progressClass = 'bg-gradient-to-r from-emerald-600 to-green-600 shadow-[0_0_10px_rgba(5,150,105,0.5)]';
                $glowEffect = '<div class="absolute -right-10 top-20 w-32 h-32 bg-emerald-600/10 rounded-full blur-2xl pointer-events-none group-hover:bg-green-600/20 transition-colors duration-500"></div>';
            } elseif ($status === 'completed') {
                $statusColor = 'text-slate-300';
                $progressClass = 'bg-gradient-to-r from-zinc-700 to-zinc-500';
                $glowEffect = '';
            } else { // draft
                $statusColor = 'text-amber-400';
                $progressClass = 'bg-gradient-to-r from-amber-700 to-amber-500';
                $glowEffect = '';
            }

            $thumbUrl = $c->thumbnail_url;
        @endphp
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl relative overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:border-emerald-900 hover:shadow-[0_10px_40px_rgba(5,150,105,0.15)] group flex flex-col {{ $status === 'draft' ? 'opacity-70 grayscale-[50%]' : '' }}">
            {!! $glowEffect !!}

            {{-- THUMBNAIL IMAGE --}}
            <div class="relative w-full aspect-[21/9] bg-neutral-900 overflow-hidden flex-shrink-0">
                <img src="{{ $thumbUrl }}" alt="{{ $c->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#111111] via-[#111111]/40 to-transparent pointer-events-none"></div>
                
                {{-- Status Badge --}}
                <div class="absolute top-3 left-3 z-10">
                    <div class="bg-black/40 backdrop-blur-md border border-white/10 text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $status === 'active' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.8)]' : ($status === 'draft' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $statusColor }}">{{ $c->effective_status_label }}</span>
                    </div>
                </div>
                
                {{-- Menu Icon --}}
                <div class="absolute top-3 right-3 z-20" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click.prevent="open = !open" class="w-8 h-8 flex items-center justify-center rounded-lg bg-black/40 backdrop-blur-md border border-white/10 text-white hover:bg-white/10 transition-colors focus:outline-none">
                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                    </button>
                    <div x-show="open" x-transition style="display:none" class="absolute right-0 mt-2 w-36 rounded-xl bg-[#050505] border border-zinc-700 shadow-[0_16px_36px_rgba(0,0,0,0.75)] overflow-hidden">
                        @if($status !== 'completed')
                            <a href="{{ route('brand.campaigns.edit', $c->id) }}" class="flex items-center gap-2 px-3 py-2.5 text-xs font-bold text-white bg-[#050505] hover:bg-zinc-800">
                                <i data-lucide="pencil" class="w-3.5 h-3.5 text-emerald-400"></i>
                                Edit
                            </a>
                        @else
                            <div class="flex items-center gap-2 px-3 py-2.5 text-xs font-bold text-zinc-500 bg-[#050505] cursor-not-allowed">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                Edit
                            </div>
                        @endif

                        <form action="{{ route('brand.campaigns.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus campaign ini? Aksi ini tidak bisa dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 text-xs font-bold text-red-300 bg-[#050505] hover:bg-red-950">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="px-5 pb-5 pt-2 flex-1 relative z-10 flex flex-col">
                {{-- Title --}}
                <h3 class="text-lg font-black text-white mb-3.5 leading-tight group-hover:text-emerald-400 transition-colors">{{ $c->title }}</h3>
                
                {{-- Quick Metrics Row --}}
                <div class="flex items-center gap-2 text-xs text-slate-400 font-bold mb-5">
                    <span class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5">
                        <i data-lucide="video" class="w-4 h-4 text-emerald-400"></i> 0 Klip
                    </span>
                    <span class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white/[0.03] border border-white/5">
                        <i data-lucide="eye" class="w-4 h-4 text-green-400"></i> 0
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-2 mt-auto">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Anggaran Terpakai</p>
                            <p class="text-xs font-black text-white">Rp {{ number_format($spentValue, 0, ',', '.') }} <span class="text-[10px] text-slate-500 font-semibold">/ Rp {{ number_format($budgetValue, 0, ',', '.') }}</span></p>
                        </div>
                        <span class="text-[10px] font-black text-emerald-400">{{ number_format($progressPercent, 0) }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden relative mt-3">
                        <div class="absolute left-0 top-0 h-full rounded-full {{ $progressClass }}" style="width: {{ $progressWidth }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-5 py-4 border-t border-white/5 bg-white/[0.01] flex justify-between items-center relative z-10">
                <a href="{{ route('brand.campaigns.show', $c->id) }}" class="text-[11px] font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail
                </a>
                <a href="{{ route('brand.submissions.index') }}" class="text-[11px] font-black {{ $status === 'active' ? 'text-emerald-400 hover:text-green-400' : 'text-slate-400 hover:text-white' }} transition-colors flex items-center gap-1.5 px-4 py-2 rounded-lg {{ $status === 'active' ? 'bg-emerald-500/10 hover:bg-emerald-500/20' : 'bg-white/5 hover:bg-white/10' }}">
                    Review Submission <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
