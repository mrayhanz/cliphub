@extends('layouts.brand')

@section('title', 'Review Workspace - ClipHub')

@section('content')
<div class="pb-8 pt-2 space-y-6" x-data="{ 
    showProofLightbox: false,
    showRejectForm: false
}">

    {{-- BREADCRUMBS & CONTEXT ACTIONS --}}
    <div class="flex items-center justify-between flex-wrap gap-4 mb-2 animate-fade-in-up">
        <div class="flex items-center gap-2">
            <a href="{{ route('brand.campaigns.show', $submission->campaign->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-xs font-extrabold text-slate-300 hover:text-white hover:bg-white/10 hover:border-emerald-500/30 transition-all duration-200">
                <i data-lucide="arrow-left" class="w-4 h-4 text-emerald-400"></i> Kembali ke Campaign
            </a>
            <a href="{{ route('brand.submissions') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-xs font-extrabold text-slate-300 hover:text-white hover:bg-white/10 transition-all duration-200">
                Semua Submission
            </a>
        </div>

        <div class="flex items-center gap-2">
            @php
                $statusVal = strtolower($submission->status ?? 'pending');
                $statusStyle = match($statusVal) {
                    'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                    'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                };
                $statusText = match($statusVal) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu Review',
                };
                $platform = strtolower($submission->platform ?? 'tiktok');
                $platColor = match($platform) {
                    'tiktok' => 'bg-black text-white border-white/10',
                    'instagram' => 'bg-gradient-to-r from-orange-500/10 to-pink-500/10 text-pink-400 border-pink-500/20',
                    default => 'bg-red-500/10 text-red-400 border-red-500/20',
                };
            @endphp
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border {{ $statusStyle }} text-xs font-black uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusVal === 'pending' ? 'bg-amber-400 animate-pulse' : ($statusVal === 'approved' ? 'bg-emerald-400' : 'bg-rose-400') }}"></span>
                {{ $statusText }}
            </div>
            <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border {{ $platColor }} text-xs font-black uppercase tracking-wider">
                <i data-lucide="video" class="w-3.5 h-3.5 text-emerald-400"></i>
                {{ $submission->platform }}
            </div>
            <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/5 bg-white/5 text-xs font-black uppercase tracking-wider text-slate-300">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-400"></i>
                Campaign: {{ $submission->campaign->title }}
            </div>
        </div>
    </div>

    {{-- HERO SECTION --}}
    <div class="my-8 animate-fade-in-up">
        <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-none mb-2">Review Workspace</h1>
        <p class="text-xs lg:text-sm text-slate-400">Periksa kualitas video, verifikasi data tayangan, dan bayar hadiah kreator di panel terintegrasi.</p>
    </div>

    {{-- NOTIFICATIONS --}}
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-600/10 border border-emerald-500/30 text-emerald-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="p-4 rounded-xl bg-rose-600/10 border border-rose-500/30 text-rose-400 font-bold text-sm flex items-center gap-3 animate-fade-in-up">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('warning') }}
        </div>
    @endif

    {{-- ========================================================
         SPLIT PANE DASHBOARD LAYOUT
         ======================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: CONTENT & ANALYTICS SCREENSHOT WORKSPACE (60% width) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Creator Profile Profile Card --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Profil Kreator</h3>
                
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center font-black text-emerald-400 text-sm">
                        {{ strtoupper(substr($submission->user->name ?? 'K', 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-black text-white text-sm leading-none mb-1.5">{{ $submission->user->name ?? 'Kreator' }}</p>
                        <p class="text-[10px] text-slate-500 leading-none">{{ $submission->user->email ?? '' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-white/5 grid grid-cols-2 gap-4 text-xs font-semibold">
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-500 uppercase tracking-wide mb-0.5">Tanggal Mengirim</span>
                        <span class="text-slate-300">{{ $submission->created_at ? $submission->created_at->format('d M Y - H:i') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-500 uppercase tracking-wide mb-0.5">Target Platform</span>
                        <span class="text-slate-300 capitalize">{{ $submission->platform }}</span>
                    </div>
                </div>
            </div>

            {{-- Calculated Reward Widget --}}
            <div class="bg-gradient-to-br from-[#0c0d0c] to-[#080808] border border-white/5 rounded-2xl p-5 space-y-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-500/5 rounded-full blur-[60px] pointer-events-none"></div>
                
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Perhitungan Reward</h3>
                
                @php
                    $estimatedReward = ($submission->views_claimed * $submission->campaign->price_per_1k) / 1000;
                    $finalReward = $submission->estimated_reward ?: $estimatedReward;
                @endphp

                <div class="space-y-3.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-400">Total Views Terklaim</span>
                        <span class="font-black text-white">{{ number_format($submission->views_claimed, 0, ',', '.') }} Views</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-400">Rate Bidding (1K Views)</span>
                        <span class="font-black text-slate-300">Rp {{ number_format($submission->campaign->price_per_1k, 0, ',', '.') }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white/[0.02] border border-white/5 space-y-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-black text-emerald-400 uppercase tracking-widest">
                            <i data-lucide="calculator" class="w-3.5 h-3.5"></i> Rumus Kalkulasi ClipHub
                        </div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-400">
                            <span>Formula:</span>
                            <span class="text-white">(Views / 1.000) &times; Rate</span>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-500">
                            <span>Bidding:</span>
                            <span>({{ number_format($submission->views_claimed, 0, ',', '.') }} / 1.000) &times; Rp {{ number_format($submission->campaign->price_per_1k, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest block">Estimasi Hadiah</span>
                            <span class="text-lg font-black text-emerald-400 leading-none">Rp {{ number_format($finalReward, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <i data-lucide="coins" class="w-4.5 h-4.5"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Campaign Guidelines Reference --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-5 space-y-5">
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Ketentuan Campaign</h3>
                
                <div class="space-y-4">
                    {{-- Goal/Desc section --}}
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tujuan Campaign</h4>
                        <div class="text-xs font-medium text-slate-300 leading-relaxed whitespace-pre-line bg-white/[0.01] border border-white/5 rounded-xl p-3.5">
                            {{ $submission->campaign->desc }}
                        </div>
                    </div>

                    {{-- Full Brief section --}}
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Instruksi Konten</h4>
                        <div class="text-xs font-medium text-slate-300 leading-relaxed whitespace-pre-line bg-white/[0.01] border border-white/5 rounded-xl p-3.5 max-h-48 overflow-y-auto">
                            {{ $submission->campaign->full_brief }}
                        </div>
                    </div>

                    {{-- Don'ts section --}}
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black text-rose-400 uppercase tracking-widest">JANGAN Lakukan (Don'ts)</h4>
                        <div class="text-xs font-medium text-rose-300/90 leading-relaxed whitespace-pre-line bg-rose-500/[0.02] border border-rose-500/10 rounded-xl p-3.5">
                            {{ $submission->campaign->donts }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Audit Decision Action Card --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-5 space-y-4">
                <h3 class="text-sm font-black text-white uppercase tracking-widest border-b border-white/5 pb-3">Tindakan Audit</h3>
                
                @if ($statusVal === 'pending')
                    <div class="space-y-3.5" x-show="!showRejectForm">
                        <p class="text-xs text-slate-500 leading-relaxed">Pilih tindakan audit di bawah ini berdasarkan kesesuaian brief dan keakuratan screenshot proof.</p>
                        
                        {{-- Approve form --}}
                        <button type="button" 
                                @click.prevent="$dispatch('open-confirm-modal', { 
                                    title: 'Setujui Submission', 
                                    message: 'Apakah Anda yakin ingin menyetujui submission dari &lt;strong class=&quot;text-white&quot;&gt;{{ addslashes($submission->user->name ?? '') }}&lt;/strong&gt;?&lt;br&gt;&lt;br&gt;Dana escrow sebesar &lt;span class=&quot;text-emerald-400 font-extrabold&quot;&gt;Rp {{ number_format($finalReward, 0, ',', '.') }}&lt;/span&gt; akan langsung ditransfer ke dompet kreator.&lt;br&gt;&lt;br&gt;&lt;div class=&quot;p-3.5 rounded-xl bg-white/[0.02] border border-white/5 space-y-1.5 mt-2&quot;&gt;&lt;div class=&quot;text-[9px] font-black text-emerald-400 uppercase tracking-widest&quot;&gt;Kalkulasi Payout&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Formula:&lt;/span&gt;&lt;span class=&quot;text-white font-semibold&quot;&gt;(Views/1K) &times; Rate/1K&lt;/span&gt;&lt;/div&gt;&lt;div class=&quot;flex justify-between&quot;&gt;&lt;span class=&quot;text-slate-400&quot;&gt;Rincian:&lt;/span&gt;&lt;span class=&quot;text-slate-300 font-bold&quot;&gt;({{ number_format($submission->views_claimed, 0, ',', '.') }} / 1.000) &times; Rp {{ number_format($submission->campaign->price_per_1k, 0, ',', '.') }}&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;', 
                                    action: '{{ route('brand.submissions.approve', $submission->id) }}', 
                                    method: 'POST', 
                                    buttonText: 'Ya, Setujui', 
                                    buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black' 
                                })"
                                class="w-full bg-gradient-to-br from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-black text-sm py-3.5 px-4 rounded-xl shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-98">
                            <i data-lucide="check" class="w-4.5 h-4.5"></i> Setujui & Cairkan Dana
                        </button>

                        {{-- Reveal reject form --}}
                        <button type="button" @click="showRejectForm = true" class="w-full bg-black/40 hover:bg-rose-500/10 border border-white/5 hover:border-rose-500/30 text-rose-400 font-black text-sm py-3.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 active:scale-98">
                            <i data-lucide="x" class="w-4.5 h-4.5"></i> Tolak Submission Konten
                        </button>
                    </div>

                    {{-- Reject Form Block --}}
                    <div x-show="showRejectForm" class="space-y-4 animate-fade-in-up" style="display: none;">
                        <span class="block text-xs font-black text-rose-400 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="x-circle" class="w-4 h-4"></i> Tolak Submission</span>
                        
                        <form action="{{ route('brand.submissions.reject', $submission->id) }}" method="POST" class="space-y-3.5">
                            @csrf
                            <textarea name="rejection_reason" required placeholder="Berikan alasan yang jelas mengapa submission ini ditolak (misalnya: tidak sesuai brief, video terpotong, dll)." 
                                      class="w-full h-28 rounded-xl bg-white/5 border border-white/10 p-3.5 text-xs text-white focus:outline-none focus:border-rose-500 transition-colors"></textarea>
                            
                            <div class="flex items-center justify-end gap-2.5">
                                <button type="button" @click="showRejectForm = false" class="px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                                    Batal
                                </button>
                                <button type="submit" class="px-4.5 py-2.5 bg-rose-500 hover:bg-rose-400 text-white text-xs font-black rounded-xl transition-all">
                                    Tolak Konten
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif ($statusVal === 'approved')
                    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 space-y-1.5">
                        <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider">
                            <i data-lucide="check-circle-2" class="w-4.5 h-4.5 text-emerald-400"></i> Disetujui
                        </div>
                        <p class="text-[11px] font-medium text-slate-300 leading-relaxed">Dana escrow sebesar <strong class="text-white">Rp {{ number_format($finalReward, 0, ',', '.') }}</strong> telah sukses dilepaskan ke dompet kreator.</p>
                        <span class="block text-[9px] text-emerald-400/70 font-semibold italic">Diaudit pada: {{ $submission->updated_at ? $submission->updated_at->format('d M Y - H:i') : '' }}</span>
                    </div>
                @elseif ($statusVal === 'rejected')
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 space-y-2">
                        <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider">
                            <i data-lucide="x-circle" class="w-4.5 h-4.5 text-rose-400"></i> Ditolak
                        </div>
                        <div>
                            <span class="block text-[9px] font-black uppercase text-rose-300 tracking-widest mb-0.5">Alasan Penolakan:</span>
                            <p class="text-[11px] font-medium text-slate-300 leading-relaxed">{{ $submission->rejection_reason }}</p>
                        </div>
                        <span class="block text-[9px] text-rose-400/70 font-semibold italic border-t border-rose-950/20 pt-2">Diaudit pada: {{ $submission->updated_at ? $submission->updated_at->format('d M Y - H:i') : '' }}</span>
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN: AUDIT ACCORDIONS & CONTROLS (40% width) --}}
        <div class="lg:col-span-1 space-y-6 lg:h-fit lg:sticky lg:top-10">
            {{-- Content Player Card --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Tautan Video Kreator</h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Wajib diputar dan dievaluasi untuk memastikan kualitas konten.</p>
                    </div>
                    <a href="{{ $submission->video_url }}" target="_blank" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-extrabold rounded-xl transition-all duration-200 flex items-center gap-2 active:scale-95">
                        <i data-lucide="play" class="w-4 h-4 fill-black"></i> Putar Video <i data-lucide="external-link" class="w-3.5 h-3.5 shrink-0"></i>
                    </a>
                </div>

                <div class="p-5 rounded-2xl bg-black/40 border border-white/5 hover:border-white/10 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 shrink-0">
                            <i data-lucide="link" class="w-4.5 h-4.5 text-emerald-400"></i>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-[9px] font-extrabold text-slate-500 uppercase tracking-widest block mb-0.5">Alamat URL Video</span>
                            <a href="{{ $submission->video_url }}" target="_blank" class="text-xs font-semibold text-slate-300 hover:text-emerald-400 transition-colors break-all pr-2 block">
                                {{ $submission->video_url }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Analytics Proof Viewport --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 space-y-4 relative overflow-hidden">
                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Bukti Tayangan & Analytics</h3>
                        <p class="text-[10px] text-slate-500 mt-0.5">Tangkapan layar otentik dari dasbor performa konten milik kreator.</p>
                    </div>
                    @if ($submission->analytics_proof_path)
                        <button type="button" @click="showProofLightbox = true" class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-300 hover:text-white transition-all flex items-center gap-1.5">
                            <i data-lucide="zoom-in" class="w-3.5 h-3.5"></i> Perbesar Gambar
                        </button>
                    @endif
                </div>

                @if ($submission->analytics_proof_path)
                    <div class="relative group cursor-zoom-in border border-white/5 rounded-2xl overflow-hidden bg-neutral-950 p-4 flex items-center justify-center max-h-[500px]" @click="showProofLightbox = true">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 z-10">
                            <span class="px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-xs font-extrabold text-white flex items-center gap-2">
                                <i data-lucide="zoom-in" class="w-4 h-4"></i> Klik Untuk Memperbesar
                            </span>
                        </div>
                        <img src="{{ asset('storage/' . $submission->analytics_proof_path) }}" class="rounded-xl max-h-[460px] w-full object-contain" alt="Bukti Analytics">
                    </div>
                @else
                    <div class="text-center py-16 flex flex-col items-center justify-center border border-dashed border-neutral-800 rounded-2xl bg-black/10">
                        <div class="w-14 h-14 bg-neutral-900 border border-neutral-800 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="image-off" class="w-6 h-6 text-slate-600"></i>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1">Bukti Tangkapan Layar Kosong</h4>
                        <p class="text-xs text-slate-500 max-w-xs leading-relaxed">Kreator ini tidak menyertakan berkas gambar bukti analytics ketika mengirim submisi.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL: LIGHTBOX IMAGE ZOOM --}}
    <template x-teleport="body">
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 overflow-hidden" 
             x-show="showProofLightbox" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            <div class="fixed inset-0 bg-black/90 backdrop-blur-md" @click="showProofLightbox = false"></div>
            <div class="relative w-full max-w-4xl bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-4 animate-fade-in-up">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/5">
                    <h3 class="text-sm lg:text-base font-black text-white">Pratinjau Bukti Analytics</h3>
                    <button class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors" @click="showProofLightbox = false">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex justify-center max-h-[80vh] overflow-y-auto">
                    <img src="{{ asset('storage/' . $submission->analytics_proof_path) }}" class="rounded-xl w-full object-contain" alt="Bukti Analytics">
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
