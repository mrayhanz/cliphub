@extends('layouts.brand')

@section('title', 'Campaign Saya')

@section('content')
<div class="pb-12 pt-2" x-data="{}">

    {{-- PAGE HEADER & ACTION --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 pb-4 border-b border-white/5 mb-8 animate-fade-in-up">
        <div class="space-y-4">
            <div>
                <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-none">Campaign Saya</h1>
                <p class="text-xs lg:text-sm text-slate-400 mt-2">Kelola, luncurkan, dan pantau kampanye pemasaran Anda dengan aman.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('brand.campaigns.create') }}" class="px-8 py-3.5 rounded-xl text-xs lg:text-sm font-black text-white bg-gradient-to-br from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 shadow-lg shadow-emerald-500/10 transition-all duration-200 active:scale-97 flex items-center justify-center gap-2">
                <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i> Buat Campaign Baru
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-600/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-600/10 border border-rose-500/30 text-rose-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER & SEARCH BAR FORM --}}
    @include('brand.partials.filter-search', [
        'action' => route('brand.campaigns'),
        'filters' => $filters,
        'currentStatus' => $status ?? '',
        'search' => $search ?? '',
        'searchPlaceholder' => 'Cari campaign...',
        'sortOptions' => $sortOptions ?? [],
        'currentSort' => $currentSort ?? 'newest'
    ])

    @if(empty($campaigns) || count($campaigns) === 0)
        <div class="w-full flex flex-col items-center justify-center py-32 px-6 border border-dashed border-neutral-800 rounded-3xl bg-[#111111]/30">
            <div class="w-24 h-24 bg-neutral-900 border border-neutral-800 rounded-full flex items-center justify-center mb-8 shadow-[0_0_40px_rgba(16,185,129,0.15)] relative">
                <div class="absolute inset-0 rounded-full bg-emerald-500/20 blur-xl pointer-events-none"></div>
                <i data-lucide="megaphone" class="w-10 h-10 text-emerald-400 relative z-10"></i>
            </div>
            <h3 class="text-2xl font-black text-white mb-3">Tidak Ada Campaign Ditemukan</h3>
            <p class="text-sm lg:text-base text-slate-500 mb-10 max-w-md text-center leading-relaxed">Coba ubah filter atau kata kunci pencarian Anda, atau buat campaign baru sekarang.</p>
            <a href="{{ route('brand.campaigns.create') }}" class="bg-gradient-to-br from-emerald-600 to-green-600 text-white px-8 py-3.5 rounded-2xl text-sm font-extrabold inline-flex items-center gap-2.5 transition-all duration-200 shadow-[0_8px_20px_rgba(5,150,105,0.25)] active:scale-95">
                <i data-lucide="plus" class="w-5 h-5"></i> Buat Campaign Baru
            </a>
        </div>
    @else
    {{-- CAMPAIGN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in-up">
        @foreach($campaigns as $c)
        @php
            $budgetValue     = (float) ($c->budget ?? 0);
            $spentValue      = 0; 
            $progressPercent = $budgetValue > 0 ? ($spentValue / $budgetValue) * 100 : 0;
            
            // Theme mapping based on status
            $status = strtolower($c->status ?? 'draft');
            if ($status === 'active') {
                $statusColor = 'text-emerald-400';
                $statusBg = 'bg-emerald-500/10 border-emerald-500/20';
                $statusBullet = 'bg-emerald-400 animate-pulse';
                $progressClass = 'bg-gradient-to-r from-emerald-600 to-green-600 shadow-[0_0_10px_rgba(16,185,129,0.4)]';
                $glowEffect = '<div class="absolute -right-10 top-20 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none group-hover:bg-emerald-500/10 transition-colors duration-500"></div>';
            } elseif ($status === 'completed') {
                $statusColor = 'text-blue-400';
                $statusBg = 'bg-blue-500/10 border-blue-500/20';
                $statusBullet = 'bg-blue-400';
                $progressClass = 'bg-gradient-to-r from-blue-700 to-blue-500';
                $glowEffect = '';
            } elseif ($status === 'cancelled') {
                $statusColor = 'text-rose-400';
                $statusBg = 'bg-rose-500/10 border-rose-500/20';
                $statusBullet = 'bg-rose-400';
                $progressClass = 'bg-gradient-to-r from-zinc-700 to-zinc-500';
                $glowEffect = '';
            } else { // draft
                $statusColor = 'text-amber-400';
                $statusBg = 'bg-amber-500/10 border-amber-500/20';
                $statusBullet = 'bg-amber-400';
                $progressClass = 'bg-gradient-to-r from-amber-700 to-amber-500';
                $glowEffect = '';
            }

            $thumbUrl = $c->thumbnail_url;
        @endphp
        
        <div class="bg-[#111] border border-white/5 rounded-2xl relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/20 hover:shadow-[0_12px_40px_rgba(16,185,129,0.06)] group flex flex-col {{ $status === 'draft' ? 'opacity-90' : ($status === 'cancelled' ? 'opacity-60 grayscale-[40%]' : '') }}">
            {!! $glowEffect !!}

            {{-- THUMBNAIL IMAGE --}}
            <div class="relative w-full aspect-[16/9] overflow-hidden flex-shrink-0 border-b border-white/5">
                <img src="{{ $thumbUrl }}" alt="{{ $c->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-transparent pointer-events-none"></div>
                
                {{-- Status Badge --}}
                <div class="absolute top-4 left-4 z-10">
                    <div class="backdrop-blur-md text-white px-3.5 py-1.5 rounded-lg flex items-center gap-2 border {{ $statusBg }} bg-black/40">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusBullet }}"></span>
                        <span class="text-xs font-black uppercase tracking-widest {{ $statusColor }}">{{ $c->status }}</span>
                    </div>
                </div>
            </div>

            <div class="px-6 pb-6 pt-5 flex-1 relative z-10 flex flex-col">
                
                {{-- Platform & Date --}}
                <div class="flex justify-between items-center mb-3">
                    <span class="px-2 py-1 rounded border border-white/10 bg-white/5 text-xs font-extrabold text-slate-300 tracking-wider">
                        {{ strtoupper($c->platform ?? 'Multi Platform') }}
                    </span>
                    <span class="text-xs font-bold text-slate-500">
                        <i data-lucide="calendar" class="w-3 h-3 inline-block mr-0.5 -mt-0.5"></i> 
                        {{ $c->deadline ? \Carbon\Carbon::parse($c->deadline)->format('d M Y') : 'Tanpa Batas' }}
                    </span>
                </div>

                {{-- Title --}}
                <h3 class="text-lg lg:text-xl font-black text-white leading-tight group-hover:text-emerald-400 transition-colors duration-200 line-clamp-2 mb-1">{{ $c->title }}</h3>
                <p class="text-xs font-bold text-slate-500 mb-6 truncate">{{ $c->type === 'clip' ? 'Clip Video' : 'UGC Video Biasa' }}</p>
                
                {{-- Quick Metrics Row --}}
                <div class="flex items-center gap-6 mb-6">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Submissions</span>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="video" class="w-4 h-4 text-emerald-400"></i>
                            <span class="text-sm font-black text-white">{{ $c->submissions ? $c->submissions->count() : 0 }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Total Views</span>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i>
                            <span class="text-sm font-black text-white">{{ $c->submissions ? number_format($c->submissions->sum('views_claimed'), 0, ',', '.') : 0 }}</span>
                        </div>
                    </div>
                </div>

                {{-- Minimalist Progress Bar --}}
                <div class="mt-auto pt-4 border-t border-white/5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-slate-400">Rp 0 <span class="text-slate-600">/ Rp {{ number_format($budgetValue, 0, ',', '.') }}</span></span>
                        <span class="text-xs font-black text-emerald-400">{{ number_format($progressPercent, 0) }}%</span>
                    </div>
                    <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $progressClass }}" style="width: {{ $progressPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-4 flex justify-between items-center relative z-10 flex-wrap gap-4">
                <a href="{{ route('brand.campaigns.show', $c->id) }}" class="text-xs font-extrabold text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i> Detail Campaign
                </a>
                
                <div class="flex items-center gap-2">
                    @if ($status === 'draft')
                        <button type="button" 
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Luncurkan Campaign', 
                                    message: 'Apakah Anda yakin ingin meluncurkan campaign ini? Saldo Anda sebesar Rp {{ number_format($budgetValue, 0, ',', '.') }} akan ditahan secara aman sebagai jaminan escrow.', 
                                    action: '{{ route('brand.campaigns.activate', $c->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Luncurkan', 
                                    buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black'
                                })" 
                                class="text-xs font-black text-white bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-lg transition-all active:scale-95 flex items-center gap-1.5">
                            Luncurkan
                        </button>
                    @elseif ($status === 'active')
                        <button type="button" 
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Selesaikan Campaign', 
                                    message: 'Apakah Anda yakin ingin menyelesaikan campaign ini? Status campaign akan ditandai selesai dan tidak dapat menerima submission baru.', 
                                    action: '{{ route('brand.campaigns.complete', $c->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Selesaikan', 
                                    buttonClass: 'bg-blue-600 hover:bg-blue-500 text-white'
                                })" 
                                class="text-xs font-black text-white bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-lg transition-all active:scale-95 flex items-center gap-1.5">
                            Selesai
                        </button>
                        <button type="button" 
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Batalkan Campaign', 
                                    message: 'Apakah Anda yakin ingin membatalkan campaign ini? Seluruh anggaran dana escrow sebesar Rp {{ number_format($budgetValue, 0, ',', '.') }} akan dikembalikan secara instan ke saldo Anda.', 
                                    action: '{{ route('brand.campaigns.cancel', $c->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Batalkan', 
                                    buttonClass: 'bg-rose-600 hover:bg-rose-500 text-white'
                                })" 
                                class="text-xs font-black text-white bg-white/5 hover:bg-rose-500/20 border border-white/10 hover:border-rose-500/50 px-4 py-2 rounded-lg transition-all active:scale-95 flex items-center gap-1.5">
                            Batal
                        </button>
                    @endif
                    
                    @if ($status === 'active')
                        <a href="{{ route('brand.submissions') }}" class="text-xs font-black text-black bg-emerald-500 hover:bg-emerald-400 transition-colors flex items-center gap-1.5 px-4 py-2 rounded-lg">
                            Review <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($campaigns->hasPages())
        <div class="mt-12">
            {{ $campaigns->appends(request()->query())->links('brand.partials.pagination') }}
        </div>
    @endif
    @endif

</div>
@endsection
