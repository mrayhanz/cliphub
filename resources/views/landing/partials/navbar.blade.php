<!-- Navbar dengan fitur Alpine.js untuk handle scroll effect (glassmorphism) dan toggle mobile menu -->
<nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-black/80 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }"
     class="fixed w-full top-0 z-50 transition-all duration-300">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Bagian Logo Kiri -->
            <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                
                <!-- Typografi teks Logo -->
                <span class="font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">
                    Clip<span class="text-brand-light">fluence</span>
                </span>
            </div>
            
            <!-- Link Navigasi Tengah (Desktop) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#fitur" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Cara Kerja</a>
                <a href="#kreator" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Untuk Kreator</a>
                <a href="#brand" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Untuk Brand</a>
            </div>

            <!-- Tombol Aksi Kanan (Desktop) -->
            <div class="hidden md:flex items-center space-x-4">
                
                <!-- Tombol CTA Login dengan brand color premium -->
                <a href="/login" class="px-6 py-2 rounded-full bg-gradient-to-r from-brand to-brand text-white text-sm font-bold shadow-lg shadow-brand/25 hover:shadow-brand/40 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
                    Masuk <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Tombol Toggle Mobile Menu -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-300 hover:text-white p-2 focus:outline-none">
                    <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-6 h-6"></i>
                    <i data-lucide="x" x-show="mobileMenuOpen" class="w-6 h-6" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Dropdown Mobile Menu (Dikontrol oleh Alpine.js) -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden absolute top-20 left-0 w-full bg-black border-b border-white/10 shadow-2xl"
         style="display: none;">
        <div class="px-4 py-6 space-y-4 flex flex-col">
            <a href="#fitur" class="text-slate-300 hover:text-white text-base font-medium transition-colors">Cara Kerja</a>
            <a href="#kreator" class="text-slate-300 hover:text-white text-base font-medium transition-colors">Untuk Kreator</a>
            <a href="#brand" class="text-slate-300 hover:text-white text-base font-medium transition-colors">Untuk Brand</a>
            <!-- Garis Pemisah -->
            <div class="h-px w-full bg-neutral-900 my-4"></div>
            <a href="/login" class="w-full text-center px-5 py-3 rounded-xl bg-gradient-to-r from-brand to-brand text-white text-base font-bold shadow-lg shadow-brand/25">
                Masuk
            </a>
        </div>
    </div>
</nav>
