@extends('layouts.brand')

@section('title', 'Detail Campaign - ' . $campaign->title)

@section('content')
<div class="pb-8 pt-2 space-y-6" x-data="{ 
    activeTab: 'brief',
    showRejectModal: false, 
    showProofModal: false,
    proofImageUrl: '',
    rejectionActionUrl: '',
    rejectionCreatorName: '',
    rejectionCampaignTitle: ''
}">

    {{-- BREADCRUMB & BACK ACTION --}}
    <div class="flex items-center justify-between flex-wrap gap-4 mb-2 animate-fade-in-up">
        <a href="{{ route('brand.campaigns') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-xs font-extrabold text-slate-300 hover:text-white hover:bg-white/10 hover:border-emerald-500/30 transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 text-emerald-400"></i> Kembali ke Daftar
        </a>
        
        <div class="flex items-center gap-2">
            @php
                $status = strtolower($campaign->status ?? 'draft');
                if ($status === 'active') {
                    $statusColor = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30';
                    $statusBullet = 'bg-emerald-400 animate-pulse';
                } elseif ($status === 'completed') {
                    $statusColor = 'bg-blue-500/10 text-blue-400 border-blue-500/30';
                    $statusBullet = 'bg-blue-400';
                } elseif ($status === 'cancelled') {
                    $statusColor = 'bg-rose-500/10 text-rose-400 border-rose-500/30';
                    $statusBullet = 'bg-rose-400';
                } else {
                    $statusColor = 'bg-amber-500/10 text-amber-400 border-amber-500/30';
                    $statusBullet = 'bg-amber-400';
                }
            @endphp
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border {{ $statusColor }} text-xs font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusBullet }}"></span>
                {{ $campaign->status }}
            </div>
            
            <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-black uppercase tracking-wider text-slate-300">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-400"></i>
                Jenis: {{ $campaign->type === 'video' ? 'UGC Video' : 'Clipper Reupload' }}
            </div>
        </div>
    </div>

    <div class="my-8 animate-fade-in-up">
        <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-none mb-2">Detail Campaign</h1>
        <p class="text-xs lg:text-sm text-slate-400">Pantau performa, tinjau submission, dan kelola campaign Anda di sini.</p>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-600/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-600/10 border border-rose-500/30 text-rose-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ========================================================
         MAIN DETAILED GRID
         ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: THUMBNAIL, SPECS, CONTROL CENTER --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Campaign Cover Card --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl overflow-hidden relative group">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent z-10 pointer-events-none"></div>
                <div class="aspect-[16/10] w-full bg-neutral-950 overflow-hidden relative">
                    <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
                </div>
                
                <div class="absolute bottom-4 left-4 right-4 z-20">
                    <span class="text-[9px] font-extrabold text-emerald-400 uppercase tracking-widest block mb-1">Nama Campaign</span>
                    <h1 class="text-lg font-black text-white leading-tight tracking-tight">{{ $campaign->title }}</h1>
                </div>
            </div>

            {{-- Specifications Attribute Card --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Atribut Campaign</h3>
                
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-400">Platform Target</span>
                        <span class="font-black text-white uppercase px-2.5 py-1 rounded bg-black/60 border border-white/5">{{ $campaign->platform }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-400">Durasi Video</span>
                        <span class="font-black text-white">{{ $campaign->video_length }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-400">Batas Waktu</span>
                        <span class="font-black text-rose-400 flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            {{ $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="pt-2 border-t border-white/5">
                        <span class="block text-xs font-extrabold text-slate-400 uppercase tracking-wide mb-1.5">Link Konversi</span>
                        <a href="{{ $campaign->link }}" target="_blank" class="w-full bg-black/40 hover:bg-emerald-500/10 border border-white/5 hover:border-emerald-500/30 text-xs font-semibold text-slate-300 hover:text-emerald-400 px-3.5 py-2.5 rounded-xl transition-all duration-200 flex items-center justify-between gap-2 overflow-hidden">
                            <span class="truncate pr-2">{{ $campaign->link }}</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Control Panel --}}
            @if (in_array($status, ['draft', 'active']))
            <div class="bg-[#111] border border-white/5 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Pusat Kendali</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Atur status ketersediaan campaign Anda langsung dari panel kontrol ini.</p>
                
                <div class="space-y-3.5 pt-2">
                    @if ($status === 'draft')
                        <button type="button" 
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Luncurkan Campaign', 
                                    message: 'Apakah Anda yakin ingin meluncurkan campaign ini? Saldo Anda sebesar Rp {{ number_format($campaign->budget, 0, ',', '.') }} akan ditahan secara aman sebagai jaminan escrow.', 
                                    action: '{{ route('brand.campaigns.activate', $campaign->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Luncurkan', 
                                    buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black'
                                })"
                                class="w-full bg-gradient-to-br from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-extrabold text-sm py-3.5 px-4 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-98">
                            <i data-lucide="rocket" class="w-4 h-4"></i> Luncurkan Campaign Sekarang
                        </button>
                    @endif

                    @if ($status === 'active')
                        <button type="button"
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Selesaikan Campaign', 
                                    message: 'Apakah Anda yakin ingin menyelesaikan campaign ini? Status campaign akan ditandai selesai dan tidak dapat menerima submission baru.', 
                                    action: '{{ route('brand.campaigns.complete', $campaign->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Selesaikan', 
                                    buttonClass: 'bg-blue-600 hover:bg-blue-500 text-white'
                                })"
                                class="w-full bg-gradient-to-br from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-extrabold text-sm py-3.5 px-4 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-98">
                            <i data-lucide="check" class="w-4 h-4"></i> Selesaikan Campaign
                        </button>
                        
                        <button type="button"
                                @click.prevent="$dispatch('open-confirm-modal', {
                                    title: 'Batalkan Campaign', 
                                    message: 'Apakah Anda yakin ingin membatalkan campaign ini? Seluruh anggaran dana escrow sebesar Rp {{ number_format($campaign->budget, 0, ',', '.') }} akan dikembalikan secara instan ke saldo Anda.', 
                                    action: '{{ route('brand.campaigns.cancel', $campaign->id) }}', 
                                    method: 'PUT', 
                                    buttonText: 'Ya, Batalkan', 
                                    buttonClass: 'bg-rose-600 hover:bg-rose-500 text-white'
                                })"
                                class="w-full bg-black/40 hover:bg-rose-500/10 border border-white/5 hover:border-rose-500/30 text-rose-400 font-extrabold text-sm py-3.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 active:scale-98">
                            <i data-lucide="x" class="w-4 h-4"></i> Batalkan Campaign (Refund Saldo)
                        </button>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT COLUMN: TAB NAVIGATION & CONTENT PANELS --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Escrow Info Panel --}}
            <div class="bg-gradient-to-br from-[#0c0d0c] to-[#080808] border border-white/5 rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="flex items-center gap-3.5 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/25 text-emerald-400">
                        <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-white uppercase tracking-widest">Alokasi & Jaminan Escrow</h2>
                        <p class="text-xs text-slate-500">Anggaran yang ditahan dengan aman oleh sistem pintar ClipHub.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-black/40 border border-white/5 rounded-xl p-4">
                        <span class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1">Total Budget</span>
                        <span class="text-lg font-black text-white">Rp {{ number_format($campaign->budget, 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-black/40 border border-white/5 rounded-xl p-4">
                        <span class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1">Rate Bidding (1K Views)</span>
                        <span class="text-lg font-black text-emerald-400">Rp {{ number_format($campaign->price_per_1k, 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-black/40 border border-white/5 rounded-xl p-4">
                        <span class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-1">Batas Kuota Slots</span>
                        <span class="text-lg font-black text-white">{{ $campaign->slots }} Slots</span>
                    </div>
                </div>
            </div>

            {{-- ALPINE TABS COORDINATOR --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl overflow-hidden">
                
                {{-- TAB BAR PANEL --}}
                <div class="flex border-b border-white/5 bg-white/[0.01]">
                    <button @click="activeTab = 'brief'" 
                        :class="activeTab === 'brief' ? 'border-emerald-500 text-emerald-400 bg-white/[0.01]' : 'border-transparent text-slate-400 hover:text-white'" 
                        class="py-4 px-6 border-b-2 font-black text-sm uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                        <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                        Brief & Ketentuan
                    </button>
                    <button @click="activeTab = 'submissions'" 
                        :class="activeTab === 'submissions' ? 'border-emerald-500 text-emerald-400 bg-white/[0.01]' : 'border-transparent text-slate-400 hover:text-white'" 
                        class="py-4 px-6 border-b-2 font-black text-sm uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                        <i data-lucide="video" class="w-3.5 h-3.5"></i>
                        Submissions Kreator
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/5 border border-white/10 font-bold" :class="activeTab === 'submissions' ? 'text-emerald-400 border-emerald-500/25 bg-emerald-500/5' : 'text-slate-500'">
                            {{ $campaign->submissions->count() }}
                        </span>
                    </button>
                </div>

                {{-- TAB CONTENT CANVAS --}}
                <div class="p-6 lg:p-8">
                    
                    {{-- TAB 1: BRIEF CONTENT --}}
                    <div x-show="activeTab === 'brief'" class="space-y-6 animate-fade-in-up">
                        {{-- Brief desc --}}
                        <div class="space-y-2">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest border-b border-white/5 pb-2">Tujuan Singkat & Cara Kerja</h4>
                            <p class="text-sm font-medium text-slate-300 leading-relaxed">{{ $campaign->desc }}</p>
                        </div>

                        {{-- Full brief instructions --}}
                        <div class="space-y-3.5 pt-2">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest border-b border-white/5 pb-2">Isi Konten Harus Begini (Full Brief)</h4>
                            <div class="text-sm font-medium text-slate-300 leading-relaxed whitespace-pre-line bg-black/35 border border-white/5 rounded-xl p-4">{{ $campaign->full_brief }}</div>
                        </div>

                        {{-- Asset link --}}
                        @if ($campaign->assets_url)
                        <div class="pt-4 border-t border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h5 class="text-xs font-black text-white uppercase tracking-wider">Aset & Bahan Pendukung</h5>
                                <p class="text-[10px] text-slate-500">Materi mentah foto/video yang wajib digunakan kreator.</p>
                            </div>
                            <a href="{{ $campaign->assets_url }}" target="_blank" class="bg-emerald-500 hover:bg-emerald-400 text-black px-5 py-3 rounded-xl text-xs font-extrabold transition-all duration-200 inline-flex items-center gap-2 active:scale-95">
                                <i data-lucide="download-cloud" class="w-4 h-4"></i> Download Aset Bahan
                            </a>
                        </div>
                        @endif

                        {{-- Guardrails / Don'ts Panel --}}
                        <div class="bg-rose-950/10 border border-rose-900/20 rounded-xl p-5 mt-4">
                            <div class="flex items-center gap-3 mb-3.5">
                                <i data-lucide="shield-alert" class="w-5 h-5 text-rose-400"></i>
                                <h4 class="text-xs font-black text-rose-400 uppercase tracking-widest">JANGAN Lakukan Ini! (Don'ts)</h4>
                            </div>
                            <div class="text-sm font-medium text-slate-300 leading-relaxed whitespace-pre-line bg-black/20 rounded-xl p-4 border border-rose-950/20">
                                {{ $campaign->donts }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: SUBMISSIONS CONTENT --}}
                    <div x-show="activeTab === 'submissions'" class="space-y-6 animate-fade-in-up" style="display: none;">
                        @if(empty($campaign->submissions) || $campaign->submissions->count() === 0)
                            {{-- Clean empty state --}}
                            <div class="text-center py-16 flex flex-col items-center justify-center border border-dashed border-neutral-800 rounded-2xl bg-black/10">
                                <div class="w-14 h-14 bg-neutral-900 border border-neutral-800 rounded-full flex items-center justify-center mb-4 relative z-10">
                                    <i data-lucide="video-off" class="w-6 h-6 text-slate-600"></i>
                                </div>
                                <h4 class="text-sm font-bold text-white mb-1">Belum Ada Submission</h4>
                                <p class="text-xs text-slate-500 max-w-xs leading-relaxed">Kreator belum membagikan video untuk campaign ini. Submissions yang dibuat oleh kreator akan terdaftar di sini secara otomatis.</p>
                            </div>
                        @else
                            {{-- Submissions Responsive List --}}
                            <div class="overflow-x-auto -mx-6 lg:-mx-8">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-white/5 text-[10px] font-extrabold uppercase tracking-widest text-slate-500">
                                            <th class="text-left px-6 py-3">Kreator</th>
                                            <th class="text-left px-6 py-3">Platform</th>
                                            <th class="text-right px-6 py-3">Views Diklaim</th>
                                            <th class="text-right px-6 py-3">Estimasi Reward</th>
                                            <th class="text-center px-6 py-3">Status</th>
                                            <th class="text-right px-6 py-3">Verifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/[0.05]">
                                        @foreach($campaign->submissions as $sub)
                                        @php
                                            $platform = strtolower($sub->platform ?? 'tiktok');
                                            $platColor = match($platform) {
                                                'tiktok' => 'bg-black text-white border-white/10',
                                                'instagram' => 'bg-gradient-to-r from-orange-500/10 to-pink-500/10 text-pink-400 border-pink-500/20',
                                                default => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            };
                                            $statusVal = strtolower($sub->status ?? 'pending');
                                            $statusStyle = match($statusVal) {
                                                'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            };
                                            $statusText = match($statusVal) {
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                                default => 'Menunggu',
                                            };
                                        @endphp
                                        <tr class="hover:bg-white/[0.02] transition-colors text-xs">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center font-black text-emerald-400">
                                                        {{ strtoupper(substr($sub->user->name ?? 'K', 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-white">{{ $sub->user->name ?? 'Kreator' }}</p>
                                                        <p class="text-[10px] text-slate-500">{{ $sub->user->email ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 rounded text-[10px] font-black border uppercase tracking-wider {{ $platColor }}">{{ $sub->platform }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-black text-white">
                                                {{ number_format($sub->views_claimed, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-black text-emerald-400">
                                                Rp {{ number_format($sub->estimated_reward, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $statusStyle }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    {{-- Video URL Link --}}
                                                    <a href="{{ $sub->video_url }}" target="_blank" class="h-8 px-3 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.08] text-slate-300 hover:text-white transition-colors flex items-center justify-center gap-1.5" title="Buka Konten Video">
                                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                        <span class="text-[10px] font-bold">Video</span>
                                                    </a>

                                                    @php
                                                        $estimatedReward = ($sub->views_claimed * $campaign->price_per_1k) / 1000;
                                                    @endphp

                                                    @if ($statusVal === 'pending')
                                                        {{-- Detail Link --}}
                                                        <a href="{{ route('brand.submissions.show', $sub->id) }}" class="h-8 px-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-center gap-1.5" title="Detail Workspace Submisi">
                                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                            <span class="text-[10px] font-bold">Detail</span>
                                                        </a>

                                                        {{-- Proof Trigger Button --}}
                                                        <button class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-white/[0.04] border border-white/[0.08] hover:bg-white/[0.08] text-slate-300 transition-colors"
                                                                @click="proofImageUrl = '{{ asset('storage/' . $sub->analytics_proof_path) }}'; showProofModal = true"
                                                                title="Lihat bukti analytics">
                                                            <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-400"></i>
                                                            <span class="text-[10px] font-bold">Bukti</span>
                                                        </button>
                                                        
                                                        {{-- Reject Action Button --}}
                                                        <button class="h-8 px-3 flex items-center justify-center gap-1.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-colors"
                                                                @click="rejectionActionUrl = '{{ route('brand.submissions.reject', $sub->id) }}'; rejectionCreatorName = '{{ addslashes($sub->user->name ?? '') }}'; rejectionCampaignTitle = '{{ addslashes($campaign->title) }}'; showRejectModal = true">
                                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                            <span class="text-[10px] font-bold">Reject</span>
                                                        </button>
                                                        
                                                        {{-- Approve Action Button --}}
                                                         <button class="h-8 px-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-colors flex items-center justify-center gap-1.5"
                                                                 @click.prevent="$dispatch('open-confirm-modal', { 
                                                                     title: 'Setujui Submission', 
                                                                     message: 'Apakah Anda yakin ingin menyetujui submission dari &lt;strong class=&quot;text-white&quot;&gt;{{ addslashes($sub->user->name ?? '') }}&lt;/strong&gt;?&lt;br&gt;&lt;br&gt;Dana escrow sebesar &lt;span class=&quot;text-emerald-400 font-extrabold&quot;&gt;Rp {{ number_format($estimatedReward, 0, ',', '.') }}&lt;/span&gt; akan langsung ditransfer ke dompet kreator.&lt;br&gt;&lt;br&gt;&lt;div class=&quot;p-3.5 rounded-xl bg-white/[0.02] border border-white/5 space-y-1.5 mt-2&quot;&gt;&lt;div class=&quot;text-[9px] font-black text-emerald-400 uppercase tracking-widest&quot;&gt;Kalkulasi Payout&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Formula:&lt;/span&gt;&lt;span class=&quot;text-white font-semibold&quot;&gt;(Views/1K) &times; Rate/1K&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Rincian:&lt;/span&gt;&lt;span class=&quot;text-slate-300 font-bold&quot;&gt;({{ number_format($sub->views_claimed, 0, ',', '.') }} / 1.000) &times; Rp {{ number_format($campaign->price_per_1k, 0, ',', '.') }}&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;', 
                                                                     action: '{{ route('brand.submissions.approve', $sub->id) }}', 
                                                                     method: 'POST', 
                                                                     buttonText: 'Ya, Setujui', 
                                                                     buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black' 
                                                                 })">
                                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                            <span class="text-[10px] font-bold">Approve</span>
                                                        </button>
                                                    @else
                                                        {{-- Detail Link --}}
                                                        <a href="{{ route('brand.submissions.show', $sub->id) }}" class="h-8 px-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-center gap-1.5" title="Detail Workspace Submisi">
                                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                            <span class="text-[10px] font-bold">Detail</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL: PROOF PREVIEW (Alpine inline modal) --}}
    <template x-teleport="body">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 overflow-hidden" 
             x-show="showProofModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="showProofModal = false"></div>
            <div class="relative w-full max-w-2xl bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-4 animate-fade-in-up">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white">Bukti Analytics Konten</h3>
                    <button class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors" @click="showProofModal = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex justify-center max-h-[70vh] overflow-y-auto">
                    <img :src="proofImageUrl" class="rounded-xl w-full object-contain" alt="Bukti Analytics">
                </div>
            </div>
        </div>
    </template>

    {{-- MODAL: REJECT SUBMISSION --}}
    <template x-teleport="body">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 overflow-hidden" 
             x-show="showRejectModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="showRejectModal = false"></div>
            <div class="relative w-full max-w-lg bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-6 animate-fade-in-up">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white">Tolak Submission</h3>
                    <button class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors" @click="showRejectModal = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="mb-4 space-y-1">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Kreator</p>
                    <p class="text-sm font-black text-white" x-text="rejectionCreatorName"></p>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-2">Campaign</p>
                    <p class="text-xs font-semibold text-slate-300" x-text="rejectionCampaignTitle"></p>
                </div>

                <form :action="rejectionActionUrl" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest block">Alasan Penolakan</label>
                        <textarea name="rejection_reason" required placeholder="Berikan alasan yang jelas mengapa submission ini ditolak (misalnya: tidak sesuai brief, video terpotong, dll)." 
                                  class="w-full h-32 rounded-xl bg-white/5 border border-white/10 p-4 text-sm text-white focus:outline-none focus:border-rose-500 transition-colors"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" class="px-5 py-3 rounded-xl text-xs lg:text-sm font-bold text-slate-400 hover:text-white transition-colors" @click="showRejectModal = false">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-rose-500 hover:bg-rose-400 text-white text-xs lg:text-sm font-black transition-all">
                            Tolak Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>



</div>
@endsection
