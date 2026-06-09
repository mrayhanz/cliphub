@extends('layouts.brand')

@section('title', 'Edit Campaign')

@section('content')
@php
    $lockedFinancial = $campaign->status !== 'draft' || $campaign->submissions_count > 0;
@endphp

<div class="w-full max-w-5xl mx-auto space-y-6 pb-20 pt-2">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-white">Edit Campaign</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui informasi campaign tanpa mengganggu submission yang sudah berjalan.</p>
        </div>
        <a href="{{ route('brand.campaigns') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-400 bg-white/5 hover:bg-white/10 hover:text-white transition">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($lockedFinancial)
        <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs leading-relaxed">
            Campaign ini sudah aktif atau sudah memiliki submission, jadi field finansial seperti tipe, slot, budget, dan rate tidak diedit agar reward kreator tetap konsisten.
        </div>
    @endif

    <form action="{{ route('brand.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="bg-[#111111] border border-[#1f1f1f] rounded-[1.5rem] overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 lg:p-8 border-b border-white/5 space-y-6">
            <h2 class="text-sm font-black text-white">Informasi Dasar</h2>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Nama Campaign</label>
                <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Jenis Campaign</label>
                    <select name="type" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400 {{ $lockedFinancial ? 'opacity-50' : '' }}" {{ $lockedFinancial ? 'disabled' : '' }}>
                        <option value="video" {{ old('type', $campaign->type) === 'video' ? 'selected' : '' }}>UGC Video Biasa</option>
                        <option value="clip" {{ old('type', $campaign->type) === 'clip' ? 'selected' : '' }}>Clip Video</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Slot Kreator</label>
                    <input type="number" name="slots" value="{{ old('slots', $campaign->slots) }}" min="1" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400 {{ $lockedFinancial ? 'opacity-50' : '' }}" {{ $lockedFinancial ? 'disabled' : '' }}>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Thumbnail Baru</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-slate-300 outline-none focus:border-emerald-400">
                <p class="text-[10px] text-slate-500 mt-2">Kosongkan jika tidak ingin mengganti thumbnail.</p>
            </div>
        </div>

        <div class="p-6 lg:p-8 border-b border-white/5 space-y-6 bg-black/40">
            <h2 class="text-sm font-black text-white">Brief & Instruksi</h2>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Tujuan Singkat</label>
                <textarea name="desc" rows="3" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>{{ old('desc', $campaign->desc) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Full Brief</label>
                <textarea name="full_brief" rows="6" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>{{ old('full_brief', $campaign->full_brief) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Don'ts</label>
                <textarea name="donts" rows="3" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>{{ old('donts', $campaign->donts) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Link Aset</label>
                    <input type="url" name="assets_url" value="{{ old('assets_url', $campaign->assets_url) }}" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline', $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->format('Y-m-d') : '') }}" min="{{ \Carbon\Carbon::today('Asia/Jakarta')->toDateString() }}" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Durasi Video</label>
                    <input type="text" name="video_length" value="{{ old('video_length', $campaign->video_length) }}" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Link Target</label>
                    <input type="url" name="link" value="{{ old('link', $campaign->link) }}" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Platform</label>
                <select name="platform" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400">
                    <option value="all" {{ old('platform', $campaign->platform) === 'all' ? 'selected' : '' }}>Semua Platform</option>
                    <option value="tiktok" {{ old('platform', $campaign->platform) === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    <option value="ig_reels" {{ old('platform', $campaign->platform) === 'ig_reels' ? 'selected' : '' }}>Instagram Reels</option>
                    <option value="yt_shorts" {{ old('platform', $campaign->platform) === 'yt_shorts' ? 'selected' : '' }}>YouTube Shorts</option>
                </select>
            </div>
        </div>

        <div class="p-6 lg:p-8 space-y-6">
            <h2 class="text-sm font-black text-white">Budget</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Budget</label>
                    <input type="number" name="budget" value="{{ old('budget', $campaign->budget) }}" min="0" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400 {{ $lockedFinancial ? 'opacity-50' : '' }}" {{ $lockedFinancial ? 'disabled' : '' }}>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2">Rate per 1000 Views</label>
                    <input type="number" name="price_per_1k" value="{{ old('price_per_1k', $campaign->price_per_1k) }}" min="0" class="w-full bg-black border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white outline-none focus:border-emerald-400 {{ $lockedFinancial ? 'opacity-50' : '' }}" {{ $lockedFinancial ? 'disabled' : '' }}>
                </div>
            </div>
        </div>

        <div class="p-6 bg-[#050505] border-t border-white/5 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('brand.campaigns') }}" class="bg-transparent text-[#a1a1aa] px-8 py-3.5 rounded-2xl text-sm font-extrabold hover:text-white hover:bg-white/5 text-center w-full sm:w-auto">Batal</a>
            @if(!$lockedFinancial && $campaign->status === 'draft')
                <button type="submit" name="action" value="draft" class="px-6 py-3.5 rounded-2xl font-bold text-sm bg-neutral-900 border border-neutral-700 text-slate-300 hover:bg-neutral-800 hover:text-white transition w-full sm:w-auto">Simpan Draft</button>
                <button type="submit" name="action" value="active" class="bg-gradient-to-br from-emerald-600 to-green-600 text-white px-10 py-3.5 rounded-2xl text-sm font-extrabold w-full sm:w-auto">Luncurkan</button>
            @else
                <button type="submit" class="bg-gradient-to-br from-emerald-600 to-green-600 text-white px-10 py-3.5 rounded-2xl text-sm font-extrabold w-full sm:w-auto">Simpan Perubahan</button>
            @endif
        </div>
    </form>
</div>
@endsection
