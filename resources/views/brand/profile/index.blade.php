@extends('layouts.brand')

@section('title', 'Profil Brand')

@section('content')
<div class="max-w-4xl pb-12 pt-2">
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Profil Brand</h1>
        <p class="text-xs text-slate-400 max-w-xl leading-relaxed">
            Simpan informasi dasar yang membantu kreator memahami brand dan kebutuhan campaign Anda.
        </p>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
        <div class="border-b border-white/10 pb-5 mb-6">
            <h2 class="text-lg font-bold text-white mb-1">Informasi Dasar</h2>
            <p class="text-xs text-slate-400">Untuk tahap awal, profil cukup berisi identitas, kontak, dan website brand.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start mb-8 w-full">
            <div class="flex flex-col items-center gap-4 flex-shrink-0">
                <div class="w-24 h-24 lg:w-28 lg:h-28 rounded-2xl bg-emerald-500/10 border-2 border-dashed border-emerald-500/30 flex items-center justify-center p-1 relative group cursor-pointer overflow-hidden transition-all hover:border-emerald-500/60">
                    <div class="w-full h-full rounded-xl bg-emerald-500/10 text-emerald-400 flex flex-col items-center justify-center gap-1.5 transition-all group-hover:bg-emerald-500/15">
                        <i data-lucide="building-2" class="w-8 h-8"></i>
                    </div>
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <div class="text-center">
                    <button class="text-[11px] font-bold text-emerald-400 hover:text-emerald-300">Ubah Logo</button>
                    <p class="text-[9px] text-slate-500 mt-1">JPG atau PNG, maks. 2MB</p>
                </div>
            </div>

            <div class="flex-1 w-full flex flex-col gap-5">
                <div class="w-full">
                    <label class="block text-xs font-bold text-zinc-400 mb-2">Nama Brand</label>
                    <input type="text" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-emerald-400 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.12)]" value="{{ auth()->user()->name ?? 'Skincare Brand' }}">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 w-full">
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Kategori Industri</label>
                        <select class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-emerald-400 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.12)] appearance-none">
                            <option>Kecantikan & Kosmetik</option>
                            <option>Teknologi</option>
                            <option>Fashion</option>
                            <option>Makanan & Minuman</option>
                            <option>E-Commerce</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Kontak Brand</label>
                        <input type="text" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-emerald-400 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.12)]" placeholder="Email atau WhatsApp PIC">
                    </div>
                </div>

                <div class="w-full">
                    <label class="block text-xs font-bold text-zinc-400 mb-2">Website / Link Toko</label>
                    <input type="url" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-emerald-400 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.12)]" placeholder="https://www.brandanda.com">
                </div>

                <div class="w-full">
                    <label class="block text-xs font-bold text-zinc-400 mb-2">Deskripsi Singkat</label>
                    <textarea class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-emerald-400 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.12)] min-h-[110px] resize-y" placeholder="Ceritakan singkat tentang brand, produk utama, dan gaya komunikasi yang diharapkan dari kreator."></textarea>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row justify-end gap-3">
            <button class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
            <button class="bg-emerald-500 text-black px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 hover:bg-emerald-400 active:scale-95 border-none outline-none">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Profil
            </button>
        </div>
    </div>
</div>
@endsection
