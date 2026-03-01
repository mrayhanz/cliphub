<!-- Footer modern dengan layout 4 grid kolom pada desktop -->
<footer class="bg-black border-t border-neutral-900 pt-20 pb-10" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            
            <!-- Kolom 1: Informasi Brand & Sosial Media -->
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-brand flex items-center justify-center">
                        <i data-lucide="scissors" class="text-white w-4 h-4"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">
                        Clip<span class="text-brand-light">fluence</span>
                    </span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Platform AI terdepan untuk memotong video podcast atau stream menjadi klip viral, lengkap dengan fitur monetisasi instan.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-slate-500 hover:text-brand-light transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-brand-light transition-colors">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-brand-light transition-colors">
                        <i data-lucide="youtube" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Kolom 2: Produk & Layanan -->
            <div>
                <h3 class="text-white font-semibold mb-4">Produk</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">AI Auto-Clipper</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Smart Captions</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Payment Gateway</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Creator Analytics</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Perusahaan -->
            <div>
                <h3 class="text-white font-semibold mb-4">Perusahaan</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Tentang Kami</a></li>
                    <!-- Link unik dengan badge -->
                    <li>
                        <a href="#" class="inline-flex items-center text-slate-400 hover:text-white text-sm transition-colors">
                            Karir <span class="ml-2 py-0.5 px-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-full">Hiring</span>
                        </a>
                    </li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Blog Tech</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Kontak Support</a></li>
                </ul>
            </div>

            <!-- Kolom 4: Legalitas -->
            <div>
                <h3 class="text-white font-semibold mb-4">Legal</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Panduan Keamanan</a></li>
                </ul>
            </div>
        </div>

        <!-- Bagian Bawah: Copyright & System Status -->
        <div class="pt-8 border-t border-neutral-900 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} Clipfluence Inc. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>
