<!-- ===== SIDEBAR KREATOR ===== -->
<aside class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="h-16 flex items-center px-5 border-b border-white/[0.05] flex-shrink-0">
        <a href="{{ route('kreator.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-[0_0_20px_rgba(52,211,153,0.3)] group-hover:shadow-[0_0_28px_rgba(52,211,153,0.5)] transition-shadow duration-300">
                <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-white tracking-tight leading-none">Clip<span class="text-emerald-400">Hub</span></h1>
                <p class="text-[9px] text-slate-600 font-semibold tracking-widest uppercase mt-0.5">Creator Space</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
        <span class="nav-section-label">Utama</span>

        <a href="{{ route('kreator.dashboard') }}" class="kreator-link {{ request()->routeIs('kreator.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-grid" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <a href="{{ route('kreator.campaigns') }}" class="kreator-link flex items-center justify-between {{ request()->routeIs('kreator.campaigns*') ? 'active' : '' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="shopping-bag" class="w-4 h-4 flex-shrink-0"></i>
                Cari Campaign
            </div>
            <span class="flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-full" style="background: linear-gradient(135deg, #059669, #10b981); color: white; box-shadow: 0 0 8px rgba(16,185,129,0.3);">
                {{ \App\Models\Campaign::where('status', 'active')->count() }}
            </span>
        </a>

        <a href="{{ route('kreator.ai_clipper') }}" class="kreator-link relative overflow-hidden group border border-transparent {{ request()->routeIs('kreator.ai_clipper*') ? 'active' : '' }}">
            <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"
                 style="background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(52,211,153,0.04));"></div>
            <i data-lucide="sparkles" class="w-4 h-4 flex-shrink-0 text-emerald-400 relative z-10"></i>
            <span class="relative z-10 text-emerald-300 font-semibold group-hover:text-white transition-colors">AI Auto-Clipper</span>
        </a>

        <span class="nav-section-label">Submission</span>

        <a href="{{ route('kreator.submissions.create') }}" class="kreator-link {{ request()->routeIs('kreator.submissions.create') ? 'active' : '' }}">
            <i data-lucide="upload-cloud" class="w-4 h-4 flex-shrink-0"></i>
            Klaim Views
        </a>

        <a href="{{ route('kreator.submissions') }}" class="kreator-link {{ request()->routeIs('kreator.submissions') ? 'active' : '' }}">
            <i data-lucide="history" class="w-4 h-4 flex-shrink-0"></i>
            Riwayat Submission
        </a>

        <span class="nav-section-label">Keuangan</span>

        <a href="{{ route('kreator.finance') }}" class="kreator-link {{ request()->routeIs('kreator.finance*') ? 'active' : '' }}">
            <i data-lucide="wallet-cards" class="w-4 h-4 flex-shrink-0"></i>
            Wallet & Penarikan
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
                <p class="text-[10px] text-slate-600 truncate">Kreator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/5 rounded-xl transition-all duration-200">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>
