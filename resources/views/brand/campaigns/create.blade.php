@extends('layouts.brand')

@section('title', 'Buat Campaign Baru')

@section('content')
<div class="w-full space-y-6 pb-20 pt-2">

    {{-- FORM --}}
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.5rem] relative overflow-hidden">
        {{-- Top decoration glow --}}
        <div class="absolute -top-32 -left-32 w-64 h-64 bg-violet-600/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

        <form action="{{ route('brand.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10 flex flex-col">
            @csrf
            
            @if ($errors->any())
            <div class="m-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            {{-- ==========================================
                 SECTION 1: INFORMASI DASAR
                 ========================================== --}}
            <div class="p-6 lg:p-8 border-b border-white/5 space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center border border-violet-500/20 text-violet-400 font-black text-lg shrink-0">1</div>
                    <div>
                        <h2 class="text-base font-black text-white">Informasi Dasar & Media</h2>
                        <p class="text-[10px] font-medium text-slate-400">Atur identitas utama campaign, cover, dan metadata.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Nama Campaign <span class="text-violet-500">*</span></label>
                        <input type="text" name="title" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" placeholder="Contoh: Promo Flash Sale Spesial Lebaran" required>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6 w-full">
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Jenis Campaign <span class="text-violet-500">*</span></label>
                            <select name="type" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 cursor-pointer appearance-none">
                                <option value="video">UGC Video Biasa (Kreator mereview sendiri)</option>
                                <option value="clip">Clip Video (Reupload materi mentah + voiceover)</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Batas Kuota Kreator (Slot) <span class="text-violet-500">*</span></label>
                            <input type="number" name="slots" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" placeholder="Contoh: 50" required>
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Banner / Gambar Thumbnail <span class="text-violet-500">*</span></label>
                        <div class="border border-dashed border-white/15 rounded-2xl p-10 text-center cursor-pointer bg-black/50 transition-all duration-200 hover:border-violet-500/50 hover:bg-violet-500/5 hover:shadow-[inset_0_0_0_1px_rgba(139,92,246,0.1)] flex flex-col items-center justify-center" onclick="document.getElementById('thumbnail-upload').click()">
                            <div class="w-12 h-12 rounded-full bg-[#111] flex items-center justify-center mb-4 border border-white/10">
                                <i data-lucide="image" class="w-5 h-5 text-violet-400"></i>
                            </div>
                            <h4 class="text-sm font-bold text-white mb-1">Unggah Gambar Cover Resolusi Tinggi</h4>
                            <p class="text-[10px] text-slate-500 mb-4 max-w-sm">Mendukung resolusi memanjang 16:9 atau 21:9. Format JPG, PNG (Max 5MB).</p>
                            <input type="file" name="thumbnail" class="hidden" accept="image/*" id="thumbnail-upload" required onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Belum ada file dipilih'">
                            <span class="px-5 py-2.5 rounded-lg text-xs font-bold text-white bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">Jelajahi File</span>
                            <p class="text-xs text-violet-400 mt-3 font-semibold" id="file-name"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==========================================
                 SECTION 2: BRIEF & INSTRUKSI KERJA
                 ========================================== --}}
            <div class="p-6 lg:p-8 border-b border-white/5 space-y-8 bg-black/40">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center border border-violet-500/20 text-violet-400 font-black text-lg shrink-0">2</div>
                    <div>
                        <h2 class="text-base font-black text-white">Brief & Instruksi Spesifik</h2>
                        <p class="text-[10px] font-medium text-slate-400">Aturan main yang wajib ditaati kreator agar lolos peninjauan.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-6">
                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Tujuan Singkat & Cara Kerja <span class="text-violet-500">*</span></label>
                        <p class="text-[9px] text-slate-500 mb-2 leading-relaxed">Penjelasan awal agar kreator paham garis besarnya secara cepat.</p>
                        <textarea name="desc" rows="3" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium resize-y" placeholder="Campaign ini bertujuan mempromosikan produk Skincare X varian terbaru lewat honest review..." required></textarea>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Isi Konten Harus Begini (Full Brief) <span class="text-violet-500">*</span></label>
                        <p class="text-[9px] text-slate-500 mb-2 leading-relaxed">Tulis secara detail script, hook opening, atau pesan-pesan utama yang wajib disebutkan.</p>
                        <textarea name="full_brief" rows="6" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium resize-y" placeholder="1. Mulai video dengan masalah / pain point (contoh: Jerawat membandel).&#10;2. Masukkan screen recording Shopee Toko Kami.&#10;3. Gunakan hashtag wajib #SkincareX #BebasJerawat di caption." required></textarea>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">JANGAN Lakukan Ini! (Don'ts) <span class="text-rose-500">*</span></label>
                        <p class="text-[9px] text-slate-500 mb-2 leading-relaxed">Hal-hal terlarang yang jika dilanggar maka pengajuan akan otomatis tertolak.</p>
                        <textarea name="donts" rows="3" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium resize-y" placeholder="- Jangan sebut merk lain&#10;- Jangan menampilkan harga palsu&#10;- Tidak boleh berkata kotor" required></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6 w-full">
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Link Aset/Bahan Eksternal</label>
                            <input type="url" name="assets_url" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" placeholder="Google Drive URL untuk foto/video mentahan...">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Batas Waktu Target Upload (Deadline) <span class="text-violet-500">*</span></label>
                            <input type="date" name="deadline" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" required>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6 w-full">
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Durasi Video yang Diizinkan <span class="text-violet-500">*</span></label>
                            <input type="text" name="video_length" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" placeholder="Contoh: 30 - 60 Detik" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">URL / Link Target Konversi <span class="text-violet-500">*</span></label>
                            <input type="url" name="link" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium" placeholder="Link Affiliate / Link Toko yang dicantumkan kreator" required>
                        </div>
                    </div>
                    
                    <div class="w-full">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Platform Sosmed yang Diterima</label>
                        <select name="platform" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 cursor-pointer appearance-none">
                            <option value="all">🚀 Semua Platform (TikTok, IG, YT)</option>
                            <option value="tiktok">🎵 Khusus TikTok</option>
                            <option value="ig_reels">📸 Khusus Instagram Reels</option>
                            <option value="yt_shorts">▶️ Khusus YouTube Shorts</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ==========================================
                 SECTION 3: BUDGET & ESCROW
                 ========================================== --}}
            <div class="p-6 lg:p-8 space-y-8 bg-[#0a0a0a]">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center border border-violet-500/20 text-violet-400 font-black text-lg shrink-0">3</div>
                    <div>
                        <h2 class="text-base font-black text-white">Alokasi Budget & Escrow</h2>
                        <p class="text-[10px] font-medium text-slate-400">Atur pendanaan sistem Pay-per-View untuk kampanye ini.</p>
                    </div>
                </div>

                {{-- Alert Info Escrow --}}
                <div class="rounded-xl p-5 flex gap-4 items-start relative overflow-hidden bg-violet-500/5 border border-violet-500/20">
                    <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-violet-500/10 to-transparent pointer-events-none"></div>
                    <i data-lucide="shield-check" class="w-6 h-6 text-violet-400 flex-shrink-0 relative z-10"></i>
                    <div class="relative z-10 w-full">
                        <h4 class="text-sm font-black text-violet-400 mb-1.5 uppercase tracking-wide">Sistem Pembayaran Escrow Aktif</h4>
                        <p class="text-[11px] text-slate-400 leading-relaxed mb-4 pr-8">Semua budget yang Anda setorkan akan ditahan dengan aman oleh sistem pintar ClipHub. Saldo hanya akan dipotong ketika kreator sukses memproduksi views yang *tervalidasi* oleh algoritma API kami.</p>
                        
                        <div class="inline-flex items-center gap-2.5 px-3 py-2 rounded-lg bg-black border border-white/5 shadow-inner">
                            <span class="text-[10px] font-bold text-slate-500">Saldo Akun Anda Sekarang:</span>
                            <span class="text-xs font-black text-white">Rp 12.500.000</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 w-full relative">
                    <div class="flex-1">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Deposit Total Campaign<span class="text-violet-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-violet-500 font-bold text-sm">Rp</span>
                            <input type="number" name="budget" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium font-black text-lg tracking-wide pl-12" placeholder="0" min="0" required>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="block text-xs font-extrabold text-[#a1a1aa] mb-2 tracking-wide">Rate Bidding per 1000 Views<span class="text-violet-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-violet-500 font-bold text-sm">Rp</span>
                            <input type="number" name="price_per_1k" class="w-full bg-[#000] border border-[#27272a] rounded-2xl px-5 py-3.5 text-sm text-white font-medium transition-all duration-200 focus:outline-none focus:border-violet-400 focus:ring-4 focus:ring-violet-500/15 placeholder:text-[#52525b] placeholder:font-medium font-black text-lg tracking-wide pl-12" placeholder="0" min="0" required>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Submit Action --}}
            <div class="p-6 bg-[#050505] border-t border-white/5 flex flex-col sm:flex-row items-center justify-end gap-3 sticky bottom-0 z-20 shadow-[0_-15px_40px_rgba(0,0,0,0.8)]">
                <a href="{{ route('brand.campaigns') }}" class="bg-transparent text-[#a1a1aa] px-8 py-3.5 rounded-2xl text-sm font-extrabold transition-all duration-200 cursor-pointer border-none hover:text-white hover:bg-white/5 text-center w-full sm:w-auto">Batal</a>
                <button type="submit" name="action" value="draft" class="px-6 py-3.5 rounded-2xl font-bold text-sm bg-neutral-900 border border-neutral-700 text-slate-300 hover:bg-neutral-800 hover:text-white transition w-full sm:w-auto">
                    Simpan Draft
                </button>
                <button type="submit" name="action" value="active" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-10 py-3.5 rounded-2xl text-sm font-extrabold inline-flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 cursor-pointer border-none outline-none w-full sm:w-auto shadow-[0_0_20px_rgba(139,92,246,0.2)]">
                    <i data-lucide="rocket" class="w-5 h-5"></i> Luncurkan Campaign
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
