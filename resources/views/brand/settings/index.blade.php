@extends('layouts.brand')

@section('title', 'Pengaturan Brand')

@section('content')
<div class="max-w-6xl mx-auto pb-12 pt-2">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Pengaturan Brand</h1>
        <p class="text-xs text-slate-400">Atur profil perusahaan, integrasi media sosial, dan preferensi akun Anda.</p>
    </div>

    <div class="flex flex-col lg:flex-row w-full gap-6">
        
        {{-- LEFT NAVBAR (Tabs) --}}
        <div class="flex-shrink-0 w-full max-w-full lg:max-w-xs xl:max-w-[250px]">
            <div class="flex flex-row lg:flex-col gap-2 overflow-x-auto pb-2 lg:pb-0 w-full">
                <a href="#" class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 bg-violet-500/10 text-violet-400 border border-violet-500/20">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Brand
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 text-zinc-400 hover:bg-white/[0.03] hover:text-white border border-transparent">
                    <i data-lucide="building-2" class="w-4 h-4"></i> Informasi Bisnis
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 text-zinc-400 hover:bg-white/[0.03] hover:text-white border border-transparent">
                    <i data-lucide="bell" class="w-4 h-4"></i> Notifikasi
                </a>
                <a href="#" class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 text-zinc-400 hover:bg-white/[0.03] hover:text-white border border-transparent">
                    <i data-lucide="key" class="w-4 h-4"></i> Keamanan
                </a>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 w-full min-w-0 space-y-6">
            
            {{-- Profile Card --}}
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Profil Brand</h2>
                    <p class="text-xs text-slate-400">Pembaruan foto profil dan detail identitas yang dilihat kreator.</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start mb-8 w-full">
                    {{-- Avatar Section --}}
                    <div class="flex flex-col items-center gap-4 flex-shrink-0">
                        <div class="w-24 h-24 lg:w-28 lg:h-28 rounded-2xl bg-violet-900/40 border-2 border-dashed border-violet-500/30 flex items-center justify-center p-1 relative group cursor-pointer overflow-hidden transition-all hover:border-violet-500/60">
                            <div class="w-full h-full rounded-xl bg-violet-800/20 text-violet-400 flex flex-col items-center justify-center gap-1.5 transition-all group-hover:bg-violet-800/40">
                                <span class="text-2xl drop-shadow-md">🏢</span>
                            </div>
                            <!-- Overlay Upload -->
                            <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="text-[11px] font-bold text-violet-400 hover:text-violet-300">Ubah Logo</button>
                            <p class="text-[9px] text-slate-500 mt-1">Format JPG, PNG (Maks. 2MB)</p>
                        </div>
                    </div>

                    {{-- Identity Form --}}
                    <div class="flex-1 w-full flex flex-col gap-5">
                        <div class="w-full">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Nama Brand</label>
                            <input type="text" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]" value="{{ auth()->user()->name ?? 'Skincare Brand' }}">
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-5 w-full">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Industri Kategori</label>
                                <select class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)] appearance-none opacity-90 cursor-pointer">
                                    <option>Kecantikan & Kosmetik</option>
                                    <option>Teknologi</option>
                                    <option>Fashion & Apparel</option>
                                    <option>Makanan & Minuman</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Website Resmi URL</label>
                                <input type="url" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]" placeholder="https://www.brandanda.com">
                            </div>
                        </div>

                        <div class="w-full">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Bio Singkat</label>
                            <textarea class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)] min-h-[100px] resize-y" placeholder="Ceritakan singkat tentang brand Anda dan produk unggulan yang ditawarkan..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 flex justify-end gap-3">
                    <button class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
                    <button class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95 border-none outline-none">Simpan Perubahan</button>
                </div>
            </div>

            {{-- Social Accounts Card --}}
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Tautan Sosial Media</h2>
                    <p class="text-xs text-slate-400">Hubungkan profil sosial media brand agar kreator mudah menandai kontennya.</p>
                </div>

                <div class="space-y-4">
                    {{-- Instagram --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border border-white/5 bg-white/[0.02]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-tr from-yellow-500 via-pink-500 to-purple-500 flex items-center justify-center text-white flex-shrink-0">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">Instagram</p>
                                <p class="text-[10px] text-slate-400">Belum terhubung</p>
                            </div>
                        </div>
                        <button class="text-xs font-bold text-slate-300 bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            Hubungkan
                        </button>
                    </div>

                    {{-- TikTok --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border border-white/5 bg-white/[0.02]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-black border border-white/10 flex items-center justify-center text-white flex-shrink-0">
                                <!-- Tiktok alternative icon (because lucide might not have perfect tiktok) -->
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">TikTok</p>
                                <p class="text-[10px] text-slate-400">Belum terhubung</p>
                            </div>
                        </div>
                        <button class="text-xs font-bold text-slate-300 bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            Hubungkan
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
