<!-- ===== SIDEBAR BRAND ===== -->
<aside class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="h-16 flex items-center px-5 border-b border-white/[0.05] flex-shrink-0">
        <a href="{{ route('brand.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-[0_0_20px_rgba(52,211,153,0.3)] group-hover:shadow-[0_0_28px_rgba(52,211,153,0.5)] transition-shadow duration-300">
                <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-white tracking-tight leading-none">Clip<span class="text-emerald-400">Hub</span></h1>
                <p class="text-[9px] text-slate-600 font-semibold tracking-widest uppercase mt-0.5">Brand Portal</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
        <a href="{{ route('brand.dashboard') }}" class="brand-link {{ request()->routeIs('brand.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Campaign</div>

        <a href="{{ route('brand.campaigns') }}" class="brand-link {{ request()->routeIs('brand.campaigns') ? 'active' : '' }}">
            <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0"></i>
            Campaign Saya
        </a>

        <a href="{{ route('brand.campaigns.create') }}" class="brand-link {{ request()->routeIs('brand.campaigns.create') ? 'active' : '' }}">
            <i data-lucide="plus-circle" class="w-4 h-4 flex-shrink-0"></i>
            Buat Campaign
        </a>

        <a href="{{ route('brand.submissions') }}" class="brand-link {{ request()->routeIs('brand.submissions*') ? 'active' : '' }}">
            <i data-lucide="file-check-2" class="w-4 h-4 flex-shrink-0"></i>
            <span class="flex-1">Review Submission</span>
            <span class="badge-red text-[9px]">0</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Keuangan</div>

        <a href="{{ route('brand.finance') }}" class="brand-link {{ request()->routeIs('brand.finance*') ? 'active' : '' }}">
            <i data-lucide="wallet" class="w-4 h-4 flex-shrink-0"></i>
            Saldo & Top-up
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Akun</div>

        <a href="{{ route('brand.profile') }}" class="brand-link {{ request()->routeIs('brand.profile*') ? 'active' : '' }}">
            <i data-lucide="building-2" class="w-4 h-4 flex-shrink-0"></i>
            Profil Brand
        </a>
    </nav>

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
