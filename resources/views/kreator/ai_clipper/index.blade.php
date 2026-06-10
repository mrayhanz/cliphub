@extends('layouts.kreator')

@section('title', 'Pemotong Video AI')

@push('styles')
<style>
/* Orb Float Animation */
@keyframes orbFloat {
    0%, 100% { transform: translate(0,0) scale(1); }
    50% { transform: translate(20px,15px) scale(1.05); }
}
.animate-orb { animation: orbFloat 8s ease-in-out infinite; }
.animate-orb-delay { animation: orbFloat 8s ease-in-out infinite 3s; }
.clip-option {
    flex: 1;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    min-height: 2.75rem;
    padding: 0.75rem 0.5rem;
    border-radius: 0.75rem;
    color: #71717a;
    font-size: 0.75rem;
    font-weight: 700;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.06);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
    transition: all 0.2s ease;
}
.clip-option:hover {
    color: #f8fafc;
    background: rgba(255,255,255,0.04);
    border-color: rgba(255,255,255,0.1);
}
.clip-option.active {
    color: #ffffff;
    background: linear-gradient(135deg, rgba(16,185,129,0.14), rgba(16,185,129,0.04));
    border-color: rgba(16,185,129,0.28);
    box-shadow: inset 3px 0 0 0 #10b981, 0 0 18px rgba(16,185,129,0.10), inset 0 0 0 1px rgba(16,185,129,0.12);
    transform: translateY(-1px);
}
.clip-option.active::after {
    content: '';
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(135deg, rgba(52,211,153,0.12), transparent 55%);
}
.clip-option.active i,
.clip-option.active svg {
    color: #10b981;
    filter: drop-shadow(0 0 6px rgba(16,185,129,0.6));
}
.clip-count-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.clip-count-option {
    flex: 0 0 2.75rem;
    width: 2.75rem;
    min-height: 2.5rem;
    padding: 0;
}
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
            <p class="text-[10px] font-semibold text-slate-600">Tempel Tautan</p>
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
            Tautan Video YouTube
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

            <template x-if="videoId()">
                <div class="rounded-[0.875rem] overflow-hidden bg-black shadow-[0_0_0_1px_rgba(255,255,255,0.06)] max-w-2xl">
                    <div class="flex items-stretch">
                        <div class="relative w-[160px] md:w-[200px] shrink-0 bg-[#080808] overflow-hidden">
                            <img :src="thumbnailUrl()" alt="YouTube thumbnail" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent"></div>
                            <div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-black/75 text-[8px] font-black text-white uppercase tracking-wider">Pratinjau</div>
                        </div>
                        <div class="p-3.5 flex flex-col justify-center gap-2 flex-1 min-w-0">
                            <div class="flex items-center gap-2 text-xs font-bold text-white">
                                <i data-lucide="youtube" class="w-3.5 h-3.5 text-red-500 shrink-0"></i>
                                <span>Gambar sampul video terdeteksi</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Hasil klip akan dibuat dari tautan YouTube ini, memakai jumlah klip, rasio, resolusi, dan durasi yang kamu pilih di bawah.
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <div class="bg-amber-500/[0.04] shadow-[0_0_0_1px_rgba(245,158,11,0.12)] border-none rounded-[0.875rem] py-3.5 px-4 flex items-start gap-3">
                <i data-lucide="timer" class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5"></i>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    <span class="font-bold text-slate-400">Estimasi proses:</span>
                    transkripsi audio, analisis AI, dan pemrosesan FFmpeg biasanya membutuhkan 2-10 menit tergantung durasi video, resolusi keluaran, dan antrean server.
                </p>
            </div>

            <!-- AI Settings Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 pt-1">
                <!-- Clip Count -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="layers-3" class="w-3.5 h-3.5"></i> Jumlah Klip</label>
                    <div class="clip-count-group">
                        <template x-for="count in [1, 2, 3, 4, 5]" :key="count">
                            <button type="button" @click="settings.clip_count = count" class="clip-option clip-count-option" :class="settings.clip_count === count ? 'active' : ''">
                                <span x-text="count"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Ratio -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="smartphone" class="w-3.5 h-3.5"></i> Perbandingan Video</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.ratio = '9:16'" class="clip-option" :class="settings.ratio === '9:16' ? 'active' : ''">
                            <i data-lucide="smartphone" class="w-4 h-4" x-show="settings.ratio === '9:16'"></i>
                            9:16 (Vertikal)
                        </button>
                        <button type="button" @click="settings.ratio = '16:9'" class="clip-option" :class="settings.ratio === '16:9' ? 'active' : ''">
                            <i data-lucide="monitor-play" class="w-4 h-4" x-show="settings.ratio === '16:9'"></i>
                            16:9 (Lanskap)
                        </button>
                    </div>
                </div>

                <!-- Resolution -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="monitor-up" class="w-3.5 h-3.5"></i> Resolusi Keluaran</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.resolution = '480'" class="clip-option" :class="settings.resolution === '480' ? 'active' : ''">480</button>
                        <button type="button" @click="settings.resolution = '720'" class="clip-option" :class="settings.resolution === '720' ? 'active' : ''">720</button>
                        <button type="button" @click="settings.resolution = '1080'" class="clip-option" :class="settings.resolution === '1080' ? 'active' : ''">1080</button>
                    </div>
                </div>

                <!-- Duration -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Durasi Tiap Klip</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.duration = 'auto'" class="clip-option" :class="settings.duration === 'auto' ? 'active' : ''">Otomatis</button>
                        <button type="button" @click="settings.duration = '15s'" class="clip-option" :class="settings.duration === '15s' ? 'active' : ''">15s</button>
                        <button type="button" @click="settings.duration = '30s'" class="clip-option" :class="settings.duration === '30s' ? 'active' : ''">30s</button>
                        <button type="button" @click="settings.duration = '60s'" class="clip-option" :class="settings.duration === '60s' ? 'active' : ''">60s</button>
                    </div>
                </div>

                <!-- Captions -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="type" class="w-3.5 h-3.5"></i> Takarir Otomatis AI</label>
                    <div class="flex gap-2">
                        <button type="button" @click="settings.captions = false" class="clip-option" :class="!settings.captions ? 'active' : ''">
                            <i data-lucide="captions-off" class="w-4 h-4" x-show="!settings.captions"></i>
                            Takarir MATI
                        </button>
                        <button type="button" @click="settings.captions = true" class="clip-option" :class="settings.captions ? 'active' : ''">
                            <i data-lucide="captions" class="w-4 h-4" x-show="settings.captions"></i>
                            Takarir NYALA
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-600 leading-relaxed" x-text="settings.captions ? 'AI akan menambahkan takarir ke video.' : 'AI tidak akan menambahkan takarir baru.'"></p>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-[0_-1px_0_rgba(255,255,255,0.05)] mt-4">
                <div class="text-[11px] text-slate-500 font-medium flex-1 pt-3">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mb-0.5 text-green-400"></i>
                    AI akan menganalisis keseluruhan konten video untuk mencari momen yang paling berpotensi viral sesuai jumlah klip yang kamu pilih.
                </div>
                <button type="submit" id="btn-generate" class="w-full md:w-auto shrink-0 px-6 py-3.5 rounded-[0.875rem] text-[0.8rem] font-black tracking-widest uppercase flex items-center justify-center gap-2 cursor-pointer transition-all duration-200 whitespace-nowrap border-none mt-3 md:mt-0"
                        :class="isLoading ? 'bg-[#1a1a1a] text-zinc-700 cursor-not-allowed' : 'bg-gradient-to-br from-emerald-600 to-green-500 text-white shadow-[0_0_24px_rgba(16,185,129,0.35)] hover:shadow-[0_0_32px_rgba(16,185,129,0.5)] hover:-translate-y-[1px]'"
                        :disabled="isLoading">
                    <span x-show="!isLoading" class="flex items-center gap-2">
                        <i data-lucide="wand-2" class="w-4 h-4"></i> Buat Klip
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
                Gunakan video 5–30 menit untuk hasil terbaik. AI otomatis memilih momen paling menarik untuk TikTok / Reels / Shorts.
            </p>
        </div>
    </div>

    {{-- PROCESSING QUEUE --}}
    <template x-if="pendingClips.length > 0">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Antrean Pemrosesan</h2>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-amber-400 bg-amber-500/10 shadow-[0_0_0_1px_rgba(245,158,11,0.2)]"
                      x-text="pendingClips.length + ' klip'"></span>
            </div>
            <template x-for="c in pendingClips" :key="c.id">
                <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-2xl p-4 flex items-center gap-4">
                    <div class="relative w-14 h-10 rounded-lg overflow-hidden shrink-0 bg-[#080808] shadow-[0_0_0_1px_rgba(16,185,129,0.16)]">
                        <template x-if="clipThumbnail(c)">
                            <img :src="clipThumbnail(c)" alt="" class="w-full h-full object-cover opacity-80">
                        </template>
                        <template x-if="!clipThumbnail(c)">
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="film" class="w-4 h-4 text-emerald-500/70"></i>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div x-show="c.status === 'processing'" class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="w-5 h-5 rounded-full border-2 border-emerald-500/20 border-t-emerald-400 animate-[spin_0.9s_linear_infinite]"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate mb-0.5" x-text="c.title"></p>
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-600 flex-wrap">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span x-text="c.timestamp_range || '-'"></span>
                            <span>-</span>
                            <span x-text="c.duration"></span>
                            <span>-</span>
                            <span x-text="c.ratio || '9:16'"></span>
                            <span>-</span>
                            <span x-text="c.resolution || '1080'"></span>
                            <span>-</span>
                            <span x-text="c.has_captions ? 'Takarir NYALA' : 'Takarir MATI'"></span>
                            <span>-</span>
                            <span x-text="statusHelp(c)"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider whitespace-nowrap"
                              :class="statusBadgeClass(c)"
                              x-text="statusLabel(c)"></span>
                        <button type="button" @click="cancelClip(c)"
                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500/18 text-red-400 transition-all shadow-[0_0_0_1px_rgba(239,68,68,0.18)] hover:shadow-[0_0_14px_rgba(239,68,68,0.18)] disabled:opacity-45 disabled:cursor-not-allowed"
                                :disabled="Boolean(c.isCancelling)"
                                title="Batal buat">
                            <i data-lucide="x" class="w-3.5 h-3.5" x-show="!c.isCancelling"></i>
                            <svg x-show="c.isCancelling" class="animate-spin h-3.5 w-3.5" style="display:none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
            <div class="rounded-xl px-4 py-3 text-[11px] leading-relaxed text-slate-500 bg-amber-500/[0.04] shadow-[0_0_0_1px_rgba(245,158,11,0.12)]">
                <span class="font-bold text-amber-300">Proses otomatis aktif:</span>
                Dasbor akan menyalakan proses di latar belakang. Anda bisa tetap di halaman ini, status akan berubah dari menunggu ke pemrosesan lalu klip siap diunduh.
            </div>
        </div>
    </template>

    {{-- FAILED CLIPS --}}
    <template x-if="failedClips.length > 0">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Perlu Buat Ulang</h2>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-red-400 bg-red-500/10 shadow-[0_0_0_1px_rgba(239,68,68,0.2)]"
                      x-text="failedClips.length + ' klip'"></span>
            </div>
            <template x-for="c in failedClips" :key="c.id">
                <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(239,68,68,0.12)] border-none rounded-2xl p-4 flex items-center gap-4">
                    <div class="relative w-14 h-10 rounded-lg overflow-hidden shrink-0 bg-[#080808]">
                        <template x-if="clipThumbnail(c)">
                            <img :src="clipThumbnail(c)" alt="" class="w-full h-full object-cover opacity-60">
                        </template>
                        <div class="absolute inset-0 bg-red-950/30 flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-400"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate mb-0.5" x-text="c.title"></p>
                        <p class="text-[11px] text-slate-500">Pemrosesan klip gagal. Coba buat ulang dari tautan yang sama.</p>
                    </div>
                    <span class="text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider whitespace-nowrap text-red-400 bg-red-500/10 shadow-[0_0_0_1px_rgba(239,68,68,0.2)]">GAGAL</span>
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
                        <div class="border-t-0 shadow-[0_-1px_0_rgba(255,255,255,0.04)] pt-3 mt-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600 flex-wrap">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span class="text-slate-400" x-text="c.timestamp_range || '-'"></span>
                                <span>-</span>
                                <span class="text-slate-400" x-text="c.duration"></span>
                                <span>- <span x-text="c.ratio || '9:16'"></span></span>
                                <span>- <span x-text="c.resolution || '1080'"></span></span>
                                <span>- <span x-text="c.has_captions ? 'Takarir NYALA' : 'Takarir MATI'"></span></span>
                                <span x-show="c.file_size_human">· <span x-text="c.file_size_human"></span></span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" @click="deleteClip(c)"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500/18 text-red-400 transition-all shadow-[0_0_0_1px_rgba(239,68,68,0.18)] hover:shadow-[0_0_14px_rgba(239,68,68,0.18)]"
                                        title="Hapus klip">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                                <a :href="c.file_url" :download="c.title + '.mp4'"
                                   class="flex items-center gap-2 px-3.5 py-2 bg-gradient-to-br from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white text-xs font-black rounded-xl transition-all shadow-[0_0_16px_rgba(16,185,129,0.32)] hover:-translate-y-[1px] whitespace-nowrap">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- EMPTY STATE --}}
    <div x-show="doneClips.length === 0 && pendingClips.length === 0 && failedClips.length === 0 && !isLoading" class="flex flex-col items-center text-center py-16 px-8">
        <div class="w-[72px] h-[72px] rounded-[1.25rem] bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] flex items-center justify-center mb-5">
            <i data-lucide="film" class="w-8 h-8 text-slate-700"></i>
        </div>
        <h3 class="text-sm font-black text-slate-500 mb-1">Belum ada klip yang dibuat</h3>
        <p class="text-xs text-slate-700 mb-5">Tempel tautan YouTube di atas dan klik Buat Klip</p>
        <div class="flex flex-wrap justify-center gap-2 text-xs">
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Siniar 10–30 menit</span>
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Panduan produk</span>
            <span class="px-2.5 py-1 rounded-lg text-slate-500 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.05)]">Buku Harian Video / Ulasan</span>
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
            ratio: '',
            resolution: '',
            duration: '',
            clip_count: '',
            captions: false
        },
        pendingClips: [], doneClips: @json($doneClips), failedClips: @json($failedClips), pollingTimers: {},

        init() {
            const pending = @json($pendingClips);
            pending.forEach(c => { this.pendingClips.push(c); this.pollClipStatus(c); });
        },

        async generate() {
            if (!this.url) return;
            if (!this.videoId()) {
                const message = 'Masukkan tautan YouTube yang valid sebelum membuat.';
                this.error = message;
                alert(message);
                return;
            }

            if (!this.settings.clip_count || !this.settings.ratio || !this.settings.resolution || !this.settings.duration) {
                const message = 'Pilih jumlah klip, rasio video, resolusi output, dan durasi tiap klip terlebih dahulu.';
                this.error = message;
                alert(message);
                return;
            }

            this.isLoading = true; this.error = ''; this.info = '';
            try {
                const res = await fetch('{{ route("kreator.ai_clipper.process") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        url: this.url,
                        ratio: this.settings.ratio,
                        resolution: this.settings.resolution,
                        duration: this.settings.duration,
                        clip_count: this.settings.clip_count,
                        captions: Boolean(this.settings.captions)
                    })
                });
                const result = await res.json();
                if (res.ok && result.success) {
                    this.info = result.message;
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
                        delete this.pollingTimers[clip.id];
                        this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                        this.doneClips.unshift({ ...clip, status: 'done', timestamp_range: data.timestamp_range || clip.timestamp_range, ratio: data.ratio || clip.ratio, resolution: data.resolution || clip.resolution, has_captions: data.has_captions ?? clip.has_captions, file_url: data.file_url, file_size_human: data.file_size_human });
                        setTimeout(() => lucide.createIcons(), 100);
                    } else if (data.status === 'failed') {
                        clearInterval(timer);
                        delete this.pollingTimers[clip.id];
                        this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                        this.failedClips.unshift({ ...clip, status: 'failed' });
                        this.error = `Klip "${clip.title}" gagal diproses. Coba buat ulang.`;
                        setTimeout(() => lucide.createIcons(), 100);
                    } else if (data.status === 'cancelled') {
                        clearInterval(timer);
                        delete this.pollingTimers[clip.id];
                        this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                        this.info = `Pembuatan "${clip.title}" dibatalkan.`;
                        setTimeout(() => lucide.createIcons(), 100);
                    } else {
                        const found = this.pendingClips.find(c => c.id === clip.id);
                        if (found) found.status = data.status;
                    }
                } catch (e) {}
            }, 5000);
            this.pollingTimers[clip.id] = timer;
        },

        async cancelClip(clip) {
            const ok = confirm(`Batalkan pembuatan "${clip.title}"? Video yang sedang dibuat akan dihapus otomatis.`);
            if (!ok) return;

            this.error = '';
            this.info = '';
            clip.isCancelling = true;

            try {
                const res = await fetch(`/kreator/ai-tools/clip/${clip.id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await res.json();

                if (res.ok && result.success) {
                    if (this.pollingTimers[clip.id]) {
                        clearInterval(this.pollingTimers[clip.id]);
                        delete this.pollingTimers[clip.id];
                    }
                    this.pendingClips = this.pendingClips.filter(c => c.id !== clip.id);
                    this.info = result.message || 'Pembuatan dibatalkan.';
                    setTimeout(() => lucide.createIcons(), 100);
                    return;
                }

                clip.isCancelling = false;
                this.error = result.message || result.error || 'Pembuatan belum berhasil dibatalkan.';
            } catch (err) {
                clip.isCancelling = false;
                this.error = 'Koneksi gagal: ' + err.message;
            }
        },

        async deleteClip(clip) {
            const ok = confirm(`Hapus klip "${clip.title}"? File video yang sudah jadi juga akan dihapus.`);
            if (!ok) return;

            this.error = '';
            this.info = '';

            try {
                const res = await fetch(`/kreator/ai-tools/clip/${clip.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const result = await res.json();

                if (res.ok && result.success) {
                    this.doneClips = this.doneClips.filter(c => c.id !== clip.id);
                    this.info = result.message || 'Klip berhasil dihapus.';
                    setTimeout(() => lucide.createIcons(), 100);
                    return;
                }

                this.error = result.message || result.error || 'Klip belum berhasil dihapus.';
            } catch (err) {
                this.error = 'Koneksi gagal: ' + err.message;
            }
        },

        videoId() {
            const match = this.url.match(/(?:youtube\.com\/(?:.*[?&]v=|embed\/|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/i);
            return match ? match[1] : '';
        },

        thumbnailUrl() {
            return this.videoId() ? `https://img.youtube.com/vi/${this.videoId()}/hqdefault.jpg` : '';
        },

        clipThumbnail(clip) {
            return clip.video_id ? `https://img.youtube.com/vi/${clip.video_id}/mqdefault.jpg` : '';
        },

        statusLabel(clip) {
            if (clip.status === 'processing') return 'RENDER';
            if (clip.status === 'queued') return 'MENUNGGU';
            return (clip.status || '').toUpperCase();
        },

        statusHelp(clip) {
            if (clip.status === 'processing') return 'FFmpeg sedang merender klip';
            if (clip.status === 'queued') return 'Menunggu antrean render';
            return 'Memeriksa status klip';
        },

        statusBadgeClass(clip) {
            if (clip.status === 'processing') return 'text-emerald-400 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.2)]';
            if (clip.status === 'queued') return 'text-amber-400 bg-amber-500/10 shadow-[0_0_0_1px_rgba(245,158,11,0.2)]';
            return 'text-slate-400 bg-white/5 shadow-[0_0_0_1px_rgba(255,255,255,0.08)]';
        }
    }
}
</script>
@endpush
