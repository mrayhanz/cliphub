<!-- ===== SIDEBAR BRAND ===== -->
<aside class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Logo -->
    <div class="h-16 flex items-center px-5 border-b border-white/[0.05] flex-shrink-0">
        <a href="{{ route('brand.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110">
                <img src="{{ asset('images/brand/logo-icon.png') }}" alt="ClipHub" class="w-8 h-8 object-contain" style="filter: drop-shadow(0 0 10px rgba(16,185,129,0.5));">
            </div>
            <div>
                <h1 class="text-lg font-black text-white tracking-tight leading-none">Clip<span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, #10b981, #34d399); -webkit-background-clip: text;">Hub</span></h1>
                <p class="text-[9px] text-slate-600 font-semibold tracking-widest uppercase mt-0.5">Brand Portal</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        <a href="{{ route('brand.dashboard') }}" class="brand-link {{ request()->routeIs('brand.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <!-- Dropdown: Campaign Saya -->
        <div x-data="{ open: {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white transition-all duration-200 {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'text-brand' : '' }}"></i>
                    Campaign Saya
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-600" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'block' : 'none' }};">
                <div class="ml-4 pl-4 mt-1 space-y-0.5" style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('brand.campaigns') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('brand.campaigns') ? 'text-brand' : 'text-slate-500' }}">Daftar Campaign</a>
                    <a href="{{ route('brand.campaigns.create') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('brand.campaigns.create') ? 'text-brand' : 'text-slate-500' }}">Buat Campaign Baru</a>
                    <a href="{{ route('brand.submissions') }}" class="flex items-center justify-between py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('brand.submissions*') ? 'text-brand' : 'text-slate-500' }}">
                        Review UGC <span class="badge-red text-[9px]">0</span>
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('brand.finance') }}" class="brand-link {{ request()->routeIs('brand.finance*') ? 'active' : '' }}">
            <i data-lucide="wallet" class="w-4 h-4 flex-shrink-0"></i>
            Keuangan & Deposit
        </a>

        <a href="{{ route('brand.settings') }}" class="brand-link {{ request()->routeIs('brand.settings*') ? 'active' : '' }}">
            <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
            Pengaturan
        </a>

    </nav>

    <!-- Footer / User Actions -->
    <div class="px-3 py-3 border-t border-white/[0.05]">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl mb-2" style="background: rgba(255,255,255,0.02);">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0"
                 style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 0 10px rgba(16,185,129,0.3);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-600 truncate">Brand</p>
            </div>
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/5 rounded-xl transition-all duration-200">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>
