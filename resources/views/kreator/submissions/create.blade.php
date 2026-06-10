@extends('layouts.kreator')

@section('title', 'Klaim Tayangan')

@section('content')
<div class="max-w-2xl mx-auto pb-12 space-y-5">
    <div class="flex items-center gap-4 pt-2">
        <a href="{{ route('kreator.submissions') }}"
           class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all flex-shrink-0"
           style="background:#111111; box-shadow: 0 0 0 1px rgba(255,255,255,0.06)">
            <i data-lucide="arrow-left" class="w-4.5 h-4.5"></i>
        </a>
        <div>
            <h1 class="text-xl font-black text-white leading-tight">Klaim Tayangan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Unggah bukti analitik dan tautan kiriman Anda.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-300 px-4 py-3 rounded-xl">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-400 mb-2">Mohon lengkapi data berikut:</p>
                    <ul class="text-xs space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('kreator.submissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-3xl overflow-hidden">
            <div class="flex items-center gap-3 py-5 px-6 bg-white/[0.02] shadow-[0_1px_0_rgba(255,255,255,0.04)]">
                <div class="w-[26px] h-[26px] rounded-full shrink-0 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center">1</div>
                <div>
                    <p class="text-sm font-black text-white">Pilih Kampanye Terkait</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Kampanye mana yang kamu kerjakan?</p>
                </div>
            </div>
            <div class="p-6">
                <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Kampanye <span class="text-red-500 normal-case font-black">*</span></label>
                <select name="campaign_id" id="campaign_select" required
                    class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none cursor-pointer [&>option]:bg-[#111] [&>option]:text-slate-200">
                    <option value="" disabled {{ old('campaign_id') ? '' : 'selected' }}>Pilih kampanye yang sedang kamu kerjakan...</option>
                    @forelse($campaigns as $campaign)
                        @php
                        $remainingBudget = $campaign->budget - ($campaign->budget_spent ?? 0);
                        $maxViews = $campaign->price_per_1k > 0 ? floor(($remainingBudget / $campaign->price_per_1k) * 1000) : 0;
                        @endphp
                        <option value="{{ $campaign->id }}" 
                                data-price="{{ $campaign->price_per_1k }}"
                                data-remaining="{{ $remainingBudget }}"
                                data-max-views="{{ $maxViews }}"
                                {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }} - Rp {{ number_format($campaign->price_per_1k, 0, ',', '.') }} / 1K tayangan
                        </option>
                    @empty
                        <option value="" disabled>Belum ada kampanye aktif</option>
                    @endforelse
                </select>
                
                <!-- Budget Info -->
                <div id="budget_info" class="hidden mt-3 p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                    <div class="flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0"></i>
                        <div class="text-xs">
                            <p class="text-blue-400 font-bold mb-1">Info Anggaran Kampanye:</p>
                            <p class="text-slate-300">Sisa anggaran: <span id="remaining_budget" class="font-bold text-white"></span></p>
                            <p class="text-slate-300">Maksimal tayangan yang bisa diklaim: <span id="max_views" class="font-bold text-emerald-400"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] border-none rounded-3xl overflow-hidden">
            <div class="flex items-center gap-3 py-5 px-6 bg-white/[0.02] shadow-[0_1px_0_rgba(255,255,255,0.04)]">
                <div class="w-[26px] h-[26px] rounded-full shrink-0 bg-emerald-500/10 shadow-[0_0_0_1px_rgba(16,185,129,0.25)] text-emerald-400 text-[0.7rem] font-black flex items-center justify-center">2</div>
                <div>
                    <p class="text-sm font-black text-white">Detail Konten</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Platform, jumlah tayangan, dan link postingan.</p>
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
                        <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Total Tayangan Diklaim <span class="text-slate-600 normal-case font-normal text-[10px]">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-600"></i>
                            </div>
                            <input type="number" name="views_claimed" id="views_input" value="{{ old('views_claimed') }}"
                                class="w-full bg-[#080808] border-none shadow-[0_0_0_1px_rgba(255,255,255,0.07)] rounded-xl py-3 px-4 text-[0.875rem] text-slate-200 placeholder-zinc-700 outline-none transition-shadow duration-200 focus:shadow-[0_0_0_1.5px_rgba(16,185,129,0.5),_0_0_0_4px_rgba(16,185,129,0.07)] appearance-none pl-9"
                                placeholder="Contoh: 154000 (kosongkan jika belum tahu)" min="1">
                        </div>
                        <p class="text-[10px] text-slate-600 mt-1.5">Pemilik merek akan verifikasi dari bukti analitik yang kamu unggah</p>
                        
                        <!-- Estimated Reward -->
                        <div id="reward_estimate" class="hidden mt-2 p-2 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                            <p class="text-xs text-emerald-400">
                                <span class="font-bold">Estimasi imbalan:</span> 
                                <span id="reward_amount" class="font-black"></span>
                            </p>
                        </div>
                        
                        <!-- Warning if exceeds budget -->
                        <div id="budget_warning" class="hidden mt-2 p-2 bg-red-500/10 border border-red-500/20 rounded-lg">
                            <p class="text-xs text-red-400 font-bold">
                                ⚠️ Tayangan melebihi sisa anggaran kampanye!
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-[0.7rem] font-bold text-slate-500 uppercase tracking-widest block mb-2">Tautan Kiriman Video <span class="text-red-500 normal-case font-black">*</span></label>
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
                    <p class="text-sm font-black text-white">Unggah Bukti Analitik</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Tangkapan layar analitik yang menampilkan jumlah tayangan.</p>
                </div>
            </div>
            <div class="p-6">
                <label for="sf-file-input" class="block w-full cursor-pointer">
                    <div class="group border-[1.5px] border-dashed border-white/10 rounded-2xl py-10 px-4 text-center bg-white/[0.01] transition-all duration-200 hover:border-emerald-500/40 hover:bg-emerald-500/[0.04]" id="sf-dropzone">
                        <div class="w-[52px] h-[52px] rounded-2xl mx-auto mb-4 bg-[#111] shadow-[0_0_0_1px_rgba(255,255,255,0.06)] flex items-center justify-center transition-all duration-200 group-hover:bg-emerald-500/10 group-hover:shadow-[0_0_0_1px_rgba(16,185,129,0.2)]">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-500 transition-colors group-hover:text-emerald-400"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-300 mb-1">
                            <span class="text-emerald-400">Klik untuk unggah</span> atau seret dan lepas di sini
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
                <p class="text-sm font-bold text-amber-300 mb-1">Cara Mengajukan Klaim</p>
                <ul class="text-xs text-slate-400 leading-relaxed space-y-1 list-disc list-inside">
                    <li>Unggah <strong class="text-slate-300">tautan video</strong> dan <strong class="text-slate-300">tangkapan layar analitik</strong> yang jelas</li>
                    <li>Jumlah tayangan <strong class="text-slate-300">bisa dikosongkan</strong>, pemilik merek akan verifikasi dari bukti</li>
                    <li>Jika bukti kurang jelas, kiriman bisa ditolak dan kamu dapat mengajukan ulang</li>
                </ul>
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

// Campaign selection handler
const campaignSelect = document.getElementById('campaign_select');
const budgetInfo = document.getElementById('budget_info');
const remainingBudgetEl = document.getElementById('remaining_budget');
const maxViewsEl = document.getElementById('max_views');

// Views input handler
const viewsInput = document.getElementById('views_input');
const rewardEstimate = document.getElementById('reward_estimate');
const rewardAmount = document.getElementById('reward_amount');
const budgetWarning = document.getElementById('budget_warning');

let currentCampaign = null;

campaignSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        currentCampaign = {
            price: parseFloat(selectedOption.dataset.price),
            remaining: parseFloat(selectedOption.dataset.remaining),
            maxViews: parseInt(selectedOption.dataset.maxViews)
        };
        
        // Show budget info
        budgetInfo.classList.remove('hidden');
        remainingBudgetEl.textContent = 'Rp ' + currentCampaign.remaining.toLocaleString('id-ID');
        maxViewsEl.textContent = currentCampaign.maxViews.toLocaleString('id-ID') + ' tayangan';
        
        // Recalculate if views already entered
        if (viewsInput.value) {
            calculateReward();
        }
    } else {
        budgetInfo.classList.add('hidden');
        currentCampaign = null;
    }
});

viewsInput.addEventListener('input', calculateReward);

function calculateReward() {
    if (!currentCampaign || !viewsInput.value) {
        rewardEstimate.classList.add('hidden');
        budgetWarning.classList.add('hidden');
        return;
    }
    
    const views = parseInt(viewsInput.value);
    const reward = (views / 1000) * currentCampaign.price;
    
    // Show reward estimate
    rewardEstimate.classList.remove('hidden');
    rewardAmount.textContent = 'Rp ' + Math.floor(reward).toLocaleString('id-ID');
    
    // Check if exceeds budget
    if (reward > currentCampaign.remaining) {
        budgetWarning.classList.remove('hidden');
        rewardEstimate.classList.add('hidden');
    } else {
        budgetWarning.classList.add('hidden');
    }
}

// File upload handler
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

// Trigger campaign change if already selected (for old input)
if (campaignSelect.value) {
    campaignSelect.dispatchEvent(new Event('change'));
}
</script>
@endpush
@endsection
