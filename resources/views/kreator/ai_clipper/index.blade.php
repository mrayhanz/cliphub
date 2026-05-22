@extends('layouts.kreator')

@section('title', 'AI Auto-Clipper')

@push('styles')
<style>
/* Orb Float Animation */
@keyframes orbFloat {
    0%, 100% { transform: translate(0,0) scale(1); }
    50% { transform: translate(20px,15px) scale(1.05); }
}
.animate-orb { animation: orbFloat 8s ease-in-out infinite; }
.animate-orb-delay { animation: orbFloat 8s ease-in-out infinite 3s; }
</style>
@endpush

@section('content')
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
    <div class="absolute rounded-full blur-[80px] opacity-10 animate-orb w-[400px] h-[400px] bg-emerald-600 -top-[100px] -left-[100px]"></div>
    <div class="absolute rounded-full blur-[80px] opacity-10 animate-orb-delay w-[300px] h-[300px] bg-green-500 -bottom-[100px] -right-[50px]"></div>
</div>

<div class="relative z-10 max-w-5xl mx-auto space-y-7 pb-12" x-data="aiClipper()">

    {{-- HERO --}}
    {{-- <div class="text-center pt-4">
        <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight mb-2 leading-tight">
            Clip<span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-400">Hub</span> AI
        </h1>
        <p class="text-base text-slate-500 font-medium mb-2">Auto-Clipper</p>
        <p class="text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
            Paste link video YouTube, biarkan AI kami memotong, menganalisis, dan menyiapkan klip viral secara otomatis.
        </p>
    </div> --}}

    {{-- STEPS --}}
    <div class="flex items-start gap-3 max-w-2xl mx-auto w-full px-4 lg:px-0">
        <div class="flex flex-col items-center text-center gap-2 flex-1">
            <div class="w-[28px] h-[28px] rounded-full bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center shrink-0">1</div>
            <i data-lucide="link" class="w-4 h-4 text-emerald-400"></i>
            <p class="text-[10px] font-semibold text-slate-600">Paste URL</p>
        </div>
        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent self-start mt-[14px]"></div>
        <div class="flex flex-col items-center text-center gap-2 flex-1">
            <div class="w-[28px] h-[28px] rounded-full bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center shrink-0">2</div>
            <i data-lucide="brain-circuit" class="w-4 h-4 text-green-400"></i>
            <p class="text-[10px] font-semibold text-slate-600">AI Analisis</p>
        </div>
        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent self-start mt-[14px]"></div>
        <div class="flex flex-col items-center text-center gap-2 flex-1">
            <div class="w-[28px] h-[28px] rounded-full bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center shrink-0">3</div>
            <i data-lucide="scissors" class="w-4 h-4 text-emerald-400"></i>
            <p class="text-[10px] font-semibold text-slate-600">Auto Potong</p>
        </div>
        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent self-start mt-[14px]"></div>
        <div class="flex flex-col items-center text-center gap-2 flex-1">
            <div class="w-[28px] h-[28px] rounded-full bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center shrink-0">4</div>
            <i data-lucide="download" class="w-4 h-4 text-emerald-400"></i>
            <p class="text-[10px] font-semibold text-slate-600">Unduh Klip</p>
        </div>
    </div>

    {{-- INPUT CARD --}}
    <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-[1.5rem] p-6 relative overflow-hidden before:content-[''] before:absolute before:inset-x-0 before:top-0 before:h-px before:bg-gradient-to-r before:from-transparent before:via-emerald-500/50 before:to-green-500/50">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
            <i data-lucide="video" class="w-4 h-4 text-red-500"></i>
            Link Video YouTube
        </p>

        <form @submit.prevent="generate" class="space-y-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="link-2" class="w-4 h-4 text-slate-600"></i>
                </div>
                <input x-model="url" type="url" id="video-url" class="w-full bg-[#080808] shadow-[0_0_0_1px_rgba(255,255,255,0.06)] border-none rounded-[0.875rem] py-3.5 pl-11 pr-4 text-[0.875rem] text-slate-200 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.55),_0_0_0_4px_rgba(16,185,129,0.08)] placeholder-zinc-700 disabled:opacity-50"
                       placeholder="https://www.youtube.com/watch?v=..."
                       required :disabled="isLoading">
            </div>

            <!-- AI Settings Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-1">
                <!-- Ratio -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="smartphone" class="w-3.5 h-3.5"></i> Rasio Video</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.ratio = '9:16'" class="flex-1 py-3 px-2 bg-white/[0.02] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] rounded-xl text-[0.75rem] font-semibold text-zinc-500 cursor-pointer transition-all duration-200 flex items-center justify-center gap-1.5 hover:bg-white/[0.04] hover:text-zinc-300 [&.active]:bg-emerald-500/10 [&.active]:text-green-500 [&.active]:shadow-[inset_0_0_0_1.5px_rgba(52,211,153,0.4)]" :class="settings.ratio === '9:16' ? 'active' : ''">
                            <i data-lucide="smartphone" class="w-4 h-4" x-show="settings.ratio === '9:16'"></i>
                            9:16 (Vertikal)
                        </button>
                        <button type="button" @click="settings.ratio = '16:9'" class="flex-1 py-3 px-2 bg-white/[0.02] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] rounded-xl text-[0.75rem] font-semibold text-zinc-500 cursor-pointer transition-all duration-200 flex items-center justify-center gap-1.5 hover:bg-white/[0.04] hover:text-zinc-300 [&.active]:bg-emerald-500/10 [&.active]:text-green-500 [&.active]:shadow-[inset_0_0_0_1.5px_rgba(52,211,153,0.4)]" :class="settings.ratio === '16:9' ? 'active' : ''">
                            <i data-lucide="monitor-play" class="w-4 h-4" x-show="settings.ratio === '16:9'"></i>
                            16:9 (Lanskap)
                        </button>
                    </div>
                </div>

                <!-- Duration -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Durasi Tiap Klip</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.duration = 'auto'" class="flex-1 py-3 px-2 bg-white/[0.02] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] rounded-xl text-[0.75rem] font-semibold text-zinc-500 cursor-pointer transition-all duration-200 flex items-center justify-center gap-1.5 hover:bg-white/[0.04] hover:text-zinc-300 [&.active]:bg-emerald-500/10 [&.active]:text-green-500 [&.active]:shadow-[inset_0_0_0_1.5px_rgba(52,211,153,0.4)]" :class="settings.duration === 'auto' ? 'active' : ''">Auto</button>
                        <button type="button" @click="settings.duration = '30s'" class="flex-1 py-3 px-2 bg-white/[0.02] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] rounded-xl text-[0.75rem] font-semibold text-zinc-500 cursor-pointer transition-all duration-200 flex items-center justify-center gap-1.5 hover:bg-white/[0.04] hover:text-zinc-300 [&.active]:bg-emerald-500/10 [&.active]:text-green-500 [&.active]:shadow-[inset_0_0_0_1.5px_rgba(52,211,153,0.4)]" :class="settings.duration === '30s' ? 'active' : ''">< 30s</button>
                        <button type="button" @click="settings.duration = '60s'" class="flex-1 py-3 px-2 bg-white/[0.02] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)] rounded-xl text-[0.75rem] font-semibold text-zinc-500 cursor-pointer transition-all duration-200 flex items-center justify-center gap-1.5 hover:bg-white/[0.04] hover:text-zinc-300 [&.active]:bg-emerald-500/10 [&.active]:text-green-500 [&.active]:shadow-[inset_0_0_0_1.5px_rgba(52,211,153,0.4)]" :class="settings.duration === '60s' ? 'active' : ''">30s - 60s</button>
                    </div>
                </div>

                <!-- Captions -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="type" class="w-3.5 h-3.5"></i> AI Auto-Captions</label>
                    <div class="flex items-center justify-between p-2.5 px-3.5 rounded-xl cursor-pointer" 
                         style="background: rgba(255,255,255,0.02); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06);"
                         @click="settings.captions = !settings.captions">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold" :class="settings.captions ? 'text-white' : 'text-slate-400'">Teks Percakapan</span>
                            <span class="text-[10px] text-slate-500 font-medium">Auto-generate teks animasi</span>
                        </div>
                        <div class="w-9 h-5 bg-zinc-800 rounded-full relative transition-colors duration-300 group" :class="settings.captions ? 'bg-green-500 active' : ''">
                            <div class="w-3.5 h-3.5 bg-white rounded-full absolute top-[3px] left-[3px] transition-transform duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] group-[.active]:translate-x-[16px]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-[0_-1px_0_rgba(255,255,255,0.05)] mt-4">
                <div class="text-[11px] text-slate-500 font-medium flex-1 pt-3">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mb-0.5 text-green-400"></i>
                    AI akan menganalisis keseluruhan konten video untuk mencari momen yang paling berpotensi viral berdasarkan algoritma FYP terbaru.
                </div>
                <button type="submit" id="btn-generate" class="w-full md:w-auto shrink-0 px-6 py-3.5 rounded-[0.875rem] text-[0.8rem] font-black tracking-widest uppercase flex items-center justify-center gap-2 cursor-pointer transition-all duration-200 whitespace-nowrap border-none mt-3 md:mt-0"
                        :class="isLoading ? 'bg-[#1a1a1a] text-zinc-700 cursor-not-allowed' : 'bg-gradient-to-br from-emerald-600 to-green-500 text-white shadow-[0_0_24px_rgba(16,185,129,0.35)] hover:shadow-[0_0_32px_rgba(16,185,129,0.5)] hover:-translate-y-[1px]'"
                        :disabled="isLoading">
                    <span x-show="!isLoading" class="flex items-center gap-2">
                        <i data-lucide="wand-2" class="w-4 h-4"></i> Generate Clips
                    </span>
                    <span x-show="isLoading" class="flex items-center gap-2" style="display:none">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        AI Sedang Menganalisis...
                    </span>
                </button>
            </div>

            <div x-show="error" x-transition class="p-3 rounded-xl text-xs font-medium flex items-start gap-2 text-red-400 bg-red-500/5 shadow-[0_0_0_1px_rgba(239,68,68,0.18)]" style="display:none">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                <span x-text="error"></span>
            </div>
            <div x-show="info" x-transition class="p-3 rounded-xl text-xs font-medium flex items-center gap-2 text-emerald-300 bg-emerald-500/5 shadow-[0_0_0_1px_rgba(16,185,129,0.18)]" style="display:none">
                <i data-lucide="check-circle-2" class="w-4 h-4 flex-shrink-0 text-emerald-400"></i>
                <span x-text="info"></span>
            </div>
        </form>

        <div class="bg-amber-500/[0.04] shadow-[0_0_0_1px_rgba(245,158,11,0.12)] border-none rounded-[0.875rem] py-3.5 px-4.5 flex items-start gap-3 mt-4">
            <i data-lucide="lightbulb" class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5"></i>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                <span class="font-bold text-slate-400">Tips:</span>
                Gunakan video 5–30 menit untuk hasil terbaik. AI otomatis memilih momen paling engaging untuk TikTok / Reels / Shorts.
            </p>
        </div>
    </div>

    {{-- PROCESSING QUEUE --}}
    <template x-if="pendingClips.length > 0">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Sedang Diproses</h2>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-amber-400 bg-amber-500/10 shadow-[0_0_0_1px_rgba(245,158,11,0.2)]"
                      x-text="pendingClips.length + ' klip'"></span>
            </div>
            <template x-for="c in pendingClips" :key="c.id">
                <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full shrink-0 border-2 border-emerald-500/15 border-t-emerald-500 animate-[spin_0.9s_linear_infinite]"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate mb-0.5" x-text="c.title"></p>
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-600">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span x-text="c.duration"></span>
                            <span>•</span>
                            <span>FFmpeg sedang memotong klip…</span>
                        </div>
                    </div>
                    <span class="text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider whitespace-nowrap text-amber-400 bg-amber-500/10 shadow-[0_0_0_1px_rgba(245,158,11,0.2)]"
                          x-text="c.status"></span>
                </div>
            </template>
        </div>
    </template>

    {{-- DONE CLIPS --}}
    <div class="space-y-4">
        <template x-if="doneClips.length > 0">
            <div class="flex items-center gap-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Klip Siap</h2>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-emerald-400 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.2)]"
                      x-text="doneClips.length + ' klip'"></span>
            </div>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="c in doneClips" :key="c.id">
                <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-[1.25rem] overflow-hidden flex flex-col transition-all duration-200 hover:shadow-[0_0_0_1px_rgba(16,185,129,0.35),_0_8px_30px_rgba(0,0,0,0.4)] hover:-translate-y-[2px]">
                    <div class="relative bg-black aspect-video w-full">
                        <video :id="'video-' + c.id" class="w-full h-full object-cover" controls playsinline preload="metadata">
                            <source :src="c.file_url" type="video/mp4">
                        </video>
                    </div>
                    <div class="p-4 flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-sm font-bold text-white leading-snug flex-1 line-clamp-2" x-text="c.title"></h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.65rem] font-black bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 shrink-0">
                                <i data-lucide="flame" class="w-3 h-3"></i>
                                <span x-text="c.score + '/100'"></span>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 italic line-clamp-2">"<span x-text="c.hook"></span>"</p>
                        <div class="border-t-0 shadow-[0_-1px_0_rgba(255,255,255,0.04)] pt-3 mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span class="text-slate-400" x-text="c.duration"></span>
                                <span x-show="c.file_size_human">· <span x-text="c.file_size_human"></span></span>
                            </div>
                            <a :href="c.file_url" :download="c.title + '.mp4'"
                               class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition-all shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- EMPTY STATE --}}
    <div x-show="doneClips.length === 0 && pendingClips.length === 0 && !isLoading" class="flex flex-col items-center text-center py-16 px-8">
        <div class="w-[72px] h-[72px] rounded-[1.25rem] bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] flex items-center justify-center mb-5">
            <i data-lucide="film" class="w-8 h-8 text-slate-700"></i>
        </div>
        <h3 class="text-sm font-black text-slate-500 mb-1">Belum ada klip yang dibuat</h3>
        <p class="text-xs text-slate-700 mb-5">Paste link YouTube di atas dan klik Generate Clips</p>
        <div class="flex flex-wrap justify-center gap-2 text-xs">
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Podcast 10–30 menit</span>
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Tutorial produk</span>
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Vlog / Review</span>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function aiClipper() {
    return {
        url: '', isLoading: false, error: '', info: '',
        settings: {
            ratio: '9:16',
            duration: 'auto',
            captions: true
        },
        pendingClips: [], doneClips: @json($doneClips), pollingTimers: {},

        init() {
            const pending = @json($pendingClips);
            pending.forEach(c => { this.pendingClips.push(c); this.pollClipStatus(c); });
        },

        async generate() {
            if (!this.url) return;
            this.isLoading = true; this.error = ''; this.info = '';
            try {
                const res = await fetch('{{ route("kreator.ai_clipper.process") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        url: this.url,
                        ratio: this.settings.ratio,
                        duration: this.settings.duration,
                        captions: this.settings.captions
                    })
                });
                const result = await res.json();
                if (res.ok && result.success) {
                    this.info = result.message; this.url = '';
                    result.clips.forEach(c => { this.pendingClips.unshift(c); this.pollClipStatus(c); });
                    setTimeout(() => lucide.createIcons(), 100);
                } else { this.error = result.error || 'Terjadi kesalahan tidak diketahui.'; }
            } catch (err) { this.error = 'Koneksi gagal: ' + err.message; }
            finally { this.isLoading = false; }
        },

        pollClipStatus(clip) {
            const timer = setInterval(async () => {
                try {
                    const res = await fetch(`/kreator/ai-tools/clip/${clip.id}/status`);
                    const data = await res.json();
                    if (data.status === 'done') {
                        clearInterval(timer);
                        this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                        this.doneClips.unshift({ ...clip, status: 'done', file_url: data.file_url, file_size_human: data.file_size_human });
                        setTimeout(() => lucide.createIcons(), 100);
                    } else if (data.status === 'failed') {
                        clearInterval(timer);
                        this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                        this.error = `Klip "${clip.title}" gagal diproses. Coba generate ulang.`;
                    } else {
                        const found = this.pendingClips.find(c => c.id === clip.id);
                        if (found) found.status = data.status;
                    }
                } catch (e) {}
            }, 5000);
            this.pollingTimers[clip.id] = timer;
        }
    }
}
</script>
@endpush
