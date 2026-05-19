@extends('layouts.brand')

@section('title', 'Buat Campaign Baru')

@section('content')
<form action="{{ route('brand.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="w-full space-y-8 pb-20 pt-2 animate-fade-in-up" x-data="{ imagePreview: null }">
    @csrf

    {{-- BREADCRUMB & HEADER SECTION --}}
    <div class="pb-4 border-b border-white/5">
        <div class="space-y-8">
            <a href="{{ route('brand.campaigns') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-xs lg:text-sm font-extrabold text-slate-300 hover:text-white hover:bg-white/10 hover:border-emerald-500/30 transition-all duration-200">
                <i data-lucide="arrow-left" class="w-4 h-4 text-emerald-400"></i> Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-none">Buat Campaign Baru</h1>
                <p class="text-xs lg:text-sm text-slate-400 mt-2">Brief, panduan ketentuan, target konversi, dan alokasi saldo jaminan escrow.</p>
            </div>
        </div>
    </div>

    {{-- GRID CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        {{-- LEFT COLUMN: FORM DETAILS & SETTINGS (lg:col-span-2) --}}
        <div class="lg:col-span-2 space-y-8">
            
            @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs lg:text-sm font-bold space-y-1">
                <div class="flex items-center gap-2 mb-1 font-black">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> Terdapat Kesalahan Pengisian:
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            {{-- CARD 1: INFORMASI DASAR --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 lg:p-8 space-y-6 relative overflow-hidden">
                <div class="absolute -top-32 -left-32 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>
                
                <div class="flex items-center gap-4 pb-4 border-b border-white/5 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/25 text-emerald-400 font-black text-sm lg:text-base shrink-0">1</div>
                    <div>
                        <h2 class="text-sm lg:text-base font-black text-white uppercase tracking-widest">Informasi Dasar</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Atur nama kampanye, format video, dan kuota slot.</p>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Nama Campaign <span class="text-emerald-500">*</span></label>
                        <input type="text" name="title" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="Contoh: Honest Review Serum Skincare Varian Baru" required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Jenis Campaign <span class="text-emerald-500">*</span></label>
                            <div class="relative">
                                <select name="type" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 cursor-pointer appearance-none">
                                    <option value="video">UGC Video Biasa (Review Mandiri)</option>
                                    <option value="clip">Clip Video (Materi Mentah + Voiceover)</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4.5 h-4.5 absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Batas Kuota Kreator (Slot) <span class="text-emerald-500">*</span></label>
                            <input type="number" name="slots" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="Contoh: 10" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: BRIEF & INSTRUKSI SPESIFIK --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 lg:p-8 space-y-6">
                
                <div class="flex items-center gap-4 pb-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/25 text-emerald-400 font-black text-sm lg:text-base shrink-0">2</div>
                    <div>
                        <h2 class="text-sm lg:text-base font-black text-white uppercase tracking-widest">Brief & Instruksi Spesifik</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Panduan konten, target platform, dilarang melanggar, dan tanggal deadline.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Tujuan Singkat & Cara Kerja <span class="text-emerald-500">*</span></label>
                        <textarea name="desc" rows="3" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700 resize-y" placeholder="Garis besar tujuan promo campaign Anda..." required></textarea>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Isi Konten Harus Begini (Full Brief) <span class="text-emerald-500">*</span></label>
                        <textarea name="full_brief" rows="6" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700 resize-y" placeholder="1. Mulai video dengan hook opening yang menarik...&#10;2. Demokan kelebihan produk...&#10;3. Ajak penonton klik link bio..." required></textarea>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">JANGAN Lakukan Ini! (Don'ts) <span class="text-rose-400">*</span></label>
                        <textarea name="donts" rows="3" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700 resize-y" placeholder="- Jangan menjelek-jelekkan merk kompetitor&#10;- Jangan klaim khasiat berlebihan" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Link Aset Pendukung (Optional)</label>
                            <input type="url" name="assets_url" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="Google Drive/Dropbox URL berisikan bahan foto/video mentah...">
                        </div>
                        <div class="w-full">
                            <label class="block text-[10px] font-extrabold text-slate-400 mb-2 uppercase tracking-wider">Deadline Pengumpulan <span class="text-emerald-500">*</span></label>
                            <input type="date" name="deadline" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-slate-300" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Durasi Video <span class="text-emerald-500">*</span></label>
                            <input type="text" name="video_length" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="Contoh: 15 - 30 Detik" required>
                        </div>
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">URL Target Konversi <span class="text-emerald-500">*</span></label>
                            <input type="url" name="link" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="Contoh: https://shopee.co.id/toko-saya" required>
                        </div>
                        <div class="w-full">
                            <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Target Platform Sosmed</label>
                            <div class="relative">
                                <select name="platform" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-bold transition-all duration-200 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 cursor-pointer appearance-none">
                                    <option value="TikTok">TikTok</option>
                                    <option value="Instagram">Instagram Reels</option>
                                    <option value="YouTube">YouTube Shorts</option>
                                </select>
                                <i data-lucide="chevron-down" class="w-4.5 h-4.5 absolute right-5 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 3: ALOKASI BUDGET & ESCROW --}}
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 lg:p-8 space-y-6">
                
                <div class="flex items-center gap-4 pb-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/25 text-emerald-400 font-black text-sm lg:text-base shrink-0">3</div>
                    <div>
                        <h2 class="text-sm lg:text-base font-black text-white uppercase tracking-widest">Alokasi Budget & Escrow</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Atur pendanaan sistem Pay-per-View yang aman untuk kampanye ini.</p>
                    </div>
                </div>

                {{-- Escrow alert banner --}}
                <div class="bg-gradient-to-br from-[#0c0d0c] to-[#080808] border border-emerald-500/20 rounded-xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/5 rounded-full blur-[50px] pointer-events-none"></div>
                    <div class="flex gap-4 items-start relative z-10">
                        <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400 shrink-0"></i>
                        <div>
                            <h4 class="text-sm lg:text-base font-black text-emerald-400 mb-2 uppercase tracking-wide">Sistem Pembayaran Escrow Aktif</h4>
                            <p class="text-xs lg:text-sm text-slate-400 leading-relaxed mb-4">ClipHub menahan anggaran campaign secara aman di escrow. Dana hanya dilepas ke dompet kreator setelah pengajuan review mereka disetujui secara resmi oleh Anda.</p>
                            
                            <div class="inline-flex items-center gap-3 px-4.5 py-2.5 rounded-xl bg-black border border-white/5 text-xs lg:text-sm">
                                <span class="font-bold text-slate-500">Saldo Dompet Anda:</span>
                                <span class="font-black text-white">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Deposit Anggaran Campaign <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-sm lg:text-base">Rp</span>
                            <input type="number" name="budget" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-black pl-12 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="0" min="0" required>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="block text-xs lg:text-sm font-extrabold text-[#a1a1aa] mb-2.5 uppercase tracking-wider">Rate Bidding (per 1,000 views) <span class="text-emerald-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-sm lg:text-base">Rp</span>
                            <input type="number" name="price_per_1k" class="w-full bg-[#070707] border border-white/5 rounded-2xl px-5 py-4 text-sm lg:text-base text-white font-black pl-12 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder:text-slate-700" placeholder="0" min="0" required>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: STICKY IMAGE UPLOAD (lg:col-span-1) --}}
        <div class="lg:col-span-1 lg:sticky lg:top-20 space-y-6 self-start">
            <div class="bg-[#111] border border-white/5 rounded-2xl p-6 lg:p-8 space-y-6 relative overflow-hidden">
                <div class="absolute -top-32 -right-32 w-64 h-64 bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none z-0"></div>
                
                <div class="flex items-center gap-3.5 pb-4 border-b border-white/5 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/25 text-emerald-400 font-black text-sm lg:text-base shrink-0">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-xs lg:text-sm font-black text-white uppercase tracking-widest">Gambar Cover <span class="text-emerald-500">*</span></h2>
                        <p class="text-[10px] text-slate-500 mt-0.5">Banner visual utama kampanye Anda.</p>
                    </div>
                </div>

                <div class="space-y-5 relative z-10">
                    <div class="border border-dashed border-white/10 rounded-2xl p-4 text-center cursor-pointer bg-[#0c0c0c] transition-all duration-200 hover:border-emerald-500/50 hover:bg-emerald-500/5 flex flex-col items-center justify-center relative overflow-hidden h-[220px]" onclick="document.getElementById('thumbnail-upload').click()">
                        <input type="file" name="thumbnail" class="hidden" accept="image/*" id="thumbnail-upload" required 
                               @change="
                                   const file = $event.target.files[0];
                                   if (file) {
                                       const reader = new FileReader();
                                       reader.onload = (e) => { imagePreview = e.target.result; };
                                       reader.readAsDataURL(file);
                                       document.getElementById('file-name').innerText = file.name;
                                   } else {
                                       imagePreview = null;
                                       document.getElementById('file-name').innerText = 'Belum ada file dipilih';
                                   }
                               ">
                        
                        <!-- VISUAL WRAPPER WHEN NO PREVIEW -->
                        <div x-show="!imagePreview" class="flex flex-col items-center justify-center space-y-2">
                            <div class="w-10 h-10 rounded-full bg-neutral-900 flex items-center justify-center border border-white/10">
                                <i data-lucide="image" class="w-5 h-5 text-emerald-400"></i>
                            </div>
                            <h4 class="text-xs font-bold text-white">Unggah Gambar Cover</h4>
                            <p class="text-[10px] text-slate-500 leading-relaxed">Resolusi memanjang 16:9<br>(File Maksimal 5MB)</p>
                            <span class="px-3.5 py-1.5 rounded-xl text-[10px] font-bold text-white bg-white/5 border border-white/10">Cari File</span>
                        </div>

                        <!-- PREVIEW WRAPPER WHEN LOADED -->
                        <div x-show="imagePreview" class="absolute inset-0 w-full h-full bg-neutral-950 animate-fade-in-up" style="display: none;">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                                <span class="px-3 py-2 rounded-xl text-xs font-bold text-white bg-black/60 border border-white/15">Ganti Gambar</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-400 mt-2 font-bold text-center truncate px-2" id="file-name">Belum ada file dipilih</p>
                    
                    <div class="rounded-xl bg-white/[0.02] border border-white/5 p-5 space-y-3">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">Spesifikasi Banner</h4>
                        <ul class="text-xs text-slate-400 space-y-2">
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0"></span>
                                <span>Rasio rekomendasi: 16:9 atau 21:9</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0"></span>
                                <span>Ukuran file maksimal: 5 Megabytes</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 shrink-0"></span>
                                <span>Banner mempresentasikan brand dan produk Anda ke ribuan kreator</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ACTION BUTTON FOOTER --}}
    <div class=" flex flex-col sm:flex-row items-center justify-end gap-4 mt-8 relative z-10">
        <a href="{{ route('brand.campaigns') }}" class="w-full sm:w-auto px-6 py-4 rounded-xl text-sm font-black text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-200 text-center">
            Batal
        </a>
        
        <button type="submit" name="action" value="draft" class="w-full sm:w-auto px-6 py-4 rounded-xl text-sm font-black text-slate-300 bg-neutral-900 border border-neutral-700 hover:bg-neutral-800 hover:text-white transition-all active:scale-97">
            Simpan Draft
        </button>
        
        <button type="submit" name="action" value="active" class="w-full sm:w-auto px-8 py-4 rounded-xl text-sm font-black text-white bg-gradient-to-br from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 shadow-lg shadow-emerald-500/10 transition-all duration-200 active:scale-97 flex items-center justify-center gap-2">
            <i data-lucide="rocket" class="w-4.5 h-4.5"></i> Luncurkan Campaign
        </button>
    </div>

</form>
@endsection
