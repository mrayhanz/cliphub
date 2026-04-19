<!-- Navbar modern dengan glassmorphism on scroll & mobile menu -->
<nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-neutral-950/70 backdrop-blur-2xl border-b border-white/[0.04] shadow-[0_4px_30px_rgba(0,0,0,0.4)]': scrolled, 'bg-transparent': !scrolled }"
     class="fixed w-full top-0 z-50 transition-all duration-500">
    
    <div class="max-w-6xl mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-[72px]">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center shadow-[0_0_20px_rgba(52,211,153,0.3)] group-hover:shadow-[0_0_28px_rgba(52,211,153,0.5)] transition-shadow duration-300">
                    <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
                </div>
                <span class="font-extrabold text-lg tracking-tight text-white">
                    Clip<span class="text-emerald-400">Hub</span>
                </span>
            </a>
            
            <!-- Nav Links Tengah (Desktop) -->
            <div class="hidden md:flex items-center gap-1">
                <a href="#cara-kerja" class="px-4 py-2 text-[13px] font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-white/[0.04] transition-all duration-200">Cara Kerja</a>
                <a href="#fitur" class="px-4 py-2 text-[13px] font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-white/[0.04] transition-all duration-200">Fitur</a>
                <a href="#kreator" class="px-4 py-2 text-[13px] font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-white/[0.04] transition-all duration-200">Untuk Kreator</a>
                <a href="#brand" class="px-4 py-2 text-[13px] font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-white/[0.04] transition-all duration-200">Untuk Brand</a>
            </div>

            <!-- CTA Kanan (Desktop) -->
            <div class="hidden md:flex items-center gap-3">
                <a href="/login" class="px-5 py-2 text-[13px] font-semibold text-zinc-300 hover:text-white transition-colors">Masuk</a>
                <a href="/register" class="px-5 py-2.5 rounded-full text-[13px] font-bold text-black bg-emerald-400 hover:bg-emerald-300 transition-all duration-200 shadow-[0_0_20px_rgba(52,211,153,0.25)] hover:shadow-[0_0_28px_rgba(52,211,153,0.4)] hover:-translate-y-px">
                    Mulai Gratis
                </a>
            </div>

            <!-- Toggle Mobile -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-zinc-400 hover:text-white p-2 focus:outline-none transition-colors">
                <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-5 h-5"></i>
                <i data-lucide="x" x-show="mobileMenuOpen" class="w-5 h-5" style="display: none;"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="md:hidden absolute top-[72px] left-0 w-full bg-neutral-950/95 backdrop-blur-2xl border-b border-white/[0.04]"
         style="display: none;">
        <div class="px-5 py-6 space-y-1 flex flex-col">
            <a href="#cara-kerja" class="text-zinc-300 hover:text-white text-sm font-medium py-3 px-3 rounded-lg hover:bg-white/[0.04] transition-all">Cara Kerja</a>
            <a href="#fitur" class="text-zinc-300 hover:text-white text-sm font-medium py-3 px-3 rounded-lg hover:bg-white/[0.04] transition-all">Fitur</a>
            <a href="#kreator" class="text-zinc-300 hover:text-white text-sm font-medium py-3 px-3 rounded-lg hover:bg-white/[0.04] transition-all">Untuk Kreator</a>
            <a href="#brand" class="text-zinc-300 hover:text-white text-sm font-medium py-3 px-3 rounded-lg hover:bg-white/[0.04] transition-all">Untuk Brand</a>
            <div class="h-px w-full bg-white/[0.06] my-3"></div>
            <a href="/login" class="text-zinc-300 text-sm font-medium py-3 px-3 rounded-lg hover:bg-white/[0.04] transition-all">Masuk</a>
            <a href="/register" class="w-full text-center py-3 rounded-xl bg-emerald-400 text-black text-sm font-bold shadow-[0_0_20px_rgba(52,211,153,0.2)]">
                Mulai Gratis
            </a>
        </div>
    </div>
</nav>
