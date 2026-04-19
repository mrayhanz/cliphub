<!-- ===== TOP NAVBAR KREATOR ===== -->
<header class="glass-navbar h-16 px-5 flex items-center justify-between gap-4 sticky top-0 z-20">

    <!-- Left: Mobile menu toggle -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:text-white transition-all duration-200"
            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
            <i data-lucide="menu" class="w-4 h-4"></i>
        </button>

        <div class="hidden sm:block">
            <p class="text-xs font-semibold text-slate-400">@yield('page_title', 'Dashboard')</p>
        </div>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-2.5">

        <!-- Search -->
        <div class="hidden md:flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-sm text-slate-500 hover:text-slate-300 transition-all duration-200 cursor-text w-52 group"
             style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
            <i data-lucide="search" class="w-3.5 h-3.5 flex-shrink-0 group-hover:text-brand transition-colors"></i>
            <span class="text-xs">Cari Campaign...</span>
        </div>

        <!-- Notifications -->
        <button class="relative w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-white transition-all duration-200 group"
                style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
            <i data-lucide="bell" class="w-4 h-4 group-hover:scale-110 transition-transform text-brand"></i>
            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full animate-pulse"
                  style="background: #10b981; box-shadow: 0 0 8px rgba(16,185,129,0.8);"></span>
        </button>

        <!-- Avatar -->
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold cursor-default transition-all duration-200 hover:scale-105"
             style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 0 16px rgba(16,185,129,0.3); border: 1px solid rgba(16,185,129,0.2);">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>
