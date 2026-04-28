@extends('layouts.kreator')

@section('title', 'Klaim Views')

@section('content')
<div class="max-w-2xl mx-auto pb-12 space-y-5">
    <div class="flex items-center gap-4 pt-2">
        <a href="{{ route('kreator.submissions') }}"
           class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all flex-shrink-0"
           style="background:#111111; box-shadow: 0 0 0 1px rgba(255,255,255,0.06)">
            <i data-lucide="arrow-left" class="w-4.5 h-4.5"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-white leading-tight">Klaim Views</h1>
            <p class="text-xs text-slate-500 mt-0.5">Upload bukti analytics dan link postingan kamu.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-xl text-sm font-semibold">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('kreator.submissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-3xl overflow-hidden">
            <div class="flex items-center gap-3 py-5 px-6 bg-white/[0.02] shadow-[0_1px_0_rgba(255,255,255,0.04)]">
                <div class="w-[26px] h-[26px] rounded-full shrink-0 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center">1</div>
                <div>
                    <p class="text-sm font-black text-white">Pilih Campaign Terkait</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Campaign mana yang kamu kerjakan?</p>
                </div>
            </div>
            <div class="p-6">
                <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Campaign <span class="text-red-500 normal-case font-black">*</span></label>
                <select name="campaign_id" required
                    class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none cursor-pointer [&>option]:bg-[#111] [&>option]:text-slate-200">
                    <option value="" disabled {{ old('campaign_id') ? '' : 'selected' }}>Pilih campaign yang sedang kamu kerjakan...</option>
                    @forelse($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }} - Rp {{ number_format($campaign->price_per_1k, 0, ',', '.') }} / 1K views
                        </option>
                    @empty
                        <option value="" disabled>Belum ada campaign aktif</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-3xl overflow-hidden">
            <div class="flex items-center gap-3 py-5 px-6 bg-white/[0.02] shadow-[0_1px_0_rgba(255,255,255,0.04)]">
                <div class="w-[26px] h-[26px] rounded-full shrink-0 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center">2</div>
                <div>
                    <p class="text-sm font-black text-white">Detail Konten</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Platform, jumlah views, dan link postingan.</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Platform Video <span class="text-red-500 normal-case font-black">*</span></label>
                        <select name="platform" required
                            class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none cursor-pointer [&>option]:bg-[#111] [&>option]:text-slate-200">
                            <option value="TikTok" {{ old('platform') === 'TikTok' ? 'selected' : '' }}>TikTok</option>
                            <option value="Instagram" {{ old('platform') === 'Instagram' ? 'selected' : '' }}>Instagram Reels</option>
                            <option value="YouTube" {{ old('platform') === 'YouTube' ? 'selected' : '' }}>YouTube Shorts</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Total Views Diklaim <span class="text-red-500 normal-case font-black">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-600"></i>
                            </div>
                            <input type="number" name="views_claimed" value="{{ old('views_claimed') }}"
                                class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 placeholder-zinc-700 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none pl-9"
                                placeholder="Contoh: 154000" min="1" required>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Link URL Postingan Video <span class="text-red-500 normal-case font-black">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="link-2" class="w-3.5 h-3.5 text-slate-600"></i>
                        </div>
                        <input type="url" name="video_url" value="{{ old('video_url') }}"
                            class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 placeholder-zinc-700 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none pl-9"
                            placeholder="https://www.tiktok.com/@kamu/video/..." required>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-3xl overflow-hidden">
            <div class="flex items-center gap-3 py-5 px-6 bg-white/[0.02] shadow-[0_1px_0_rgba(255,255,255,0.04)]">
                <div class="w-[26px] h-[26px] rounded-full shrink-0 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center">3</div>
                <div>
                    <p class="text-sm font-black text-white">Upload Bukti Analytics</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Screenshot analytics yang menampilkan jumlah views.</p>
                </div>
            </div>
            <div class="p-6">
                <label for="sf-file-input" class="block w-full cursor-pointer">
                    <div class="group border-[1.5px] border-dashed border-white/10 rounded-2xl py-10 px-4 text-center bg-white/[0.01] transition-all duration-200 hover:border-emerald-500/40 hover:bg-emerald-500/[0.04]" id="sf-dropzone">
                        <div class="w-[52px] h-[52px] rounded-2xl mx-auto mb-4 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.06)] flex items-center justify-center transition-all duration-200 group-hover:bg-emerald-500/10 group-hover:shadow-[0_0_0_1px_rgba(16,185,129,0.2)]">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-500 transition-colors group-hover:text-emerald-400"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-300 mb-1">
                            <span class="text-emerald-400">Klik untuk upload</span> atau seret dan lepas di sini
                        </p>
                        <p class="text-xs text-slate-600">PNG, JPG, JPEG - Maks. 2 MB</p>

                        <div id="sf-preview" class="hidden mt-4 flex items-center justify-center gap-3 p-3 rounded-xl bg-white/[0.03] shadow-[0_0_0_1px_rgba(255,255,255,0.06)]">
                            <i data-lucide="image" class="w-5 h-5 text-emerald-400 flex-shrink-0"></i>
                            <span id="sf-filename" class="text-xs font-semibold text-white truncate"></span>
                            <span class="text-[10px] text-emerald-400 font-bold flex-shrink-0">Siap</span>
                        </div>
                    </div>
                </label>
                <input type="file" id="sf-file-input" name="analytics_proof" accept="image/*" class="hidden" required>
            </div>
        </div>

        <div class="flex gap-3.5 items-start p-4 rounded-2xl bg-amber-500/5 shadow-[0_0_0_1px_rgba(245,158,11,0.15)]">
            <i data-lucide="info" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-sm font-bold text-amber-300 mb-1">Pastikan Bukti Jelas</p>
                <p class="text-xs text-slate-400 leading-relaxed">Brand atau admin akan mencocokkan link postingan dengan screenshot analytics. Jika bukti kurang jelas, submission bisa ditolak dan kamu dapat mengajukan klaim ulang.</p>
            </div>
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2.5 py-4 rounded-2xl text-sm font-black text-black tracking-wide transition-all duration-200 bg-emerald-500 hover:bg-emerald-400 hover:-translate-y-[1px]">
            <i data-lucide="send" class="w-4 h-4"></i>
            Ajukan Klaim Pembayaran
        </button>
    </form>
</div>

@push('scripts')
<script>
const input = document.getElementById('sf-file-input');
const dropzone = document.getElementById('sf-dropzone');
const preview = document.getElementById('sf-preview');
const filename = document.getElementById('sf-filename');

input.addEventListener('change', () => {
    if (input.files[0]) {
        filename.textContent = input.files[0].name;
        preview.classList.remove('hidden');
        preview.style.display = 'flex';
    }
});

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.style.borderColor = 'rgba(16,185,129,0.5)';
});
dropzone.addEventListener('dragleave', () => {
    dropzone.style.borderColor = '';
});
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        input.files = e.dataTransfer.files;
        filename.textContent = file.name;
        preview.classList.remove('hidden');
        preview.style.display = 'flex';
    }
});
</script>
@endpush
@endsection
