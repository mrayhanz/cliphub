<!-- ===== SIDEBAR ===== -->
<aside
    class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="h-16 flex items-center px-5 border-b border-white/[0.05] flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-[0_0_20px_rgba(52,211,153,0.3)] group-hover:shadow-[0_0_28px_rgba(52,211,153,0.5)] transition-shadow duration-300">
                <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-white tracking-tight leading-none">Clip<span class="text-emerald-400">Hub</span></h1>
                <p class="text-[9px] text-slate-600 font-semibold tracking-widest uppercase mt-0.5">Admin Panel</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Operasional Inti</div>

        <a href="{{ route('admin.submissions') }}" class="sidebar-link {{ request()->routeIs('admin.submissions*') ? 'active' : '' }}">
            <i data-lucide="file-check-2" class="w-4 h-4 flex-shrink-0"></i>
            <span class="flex-1">Review Submission</span>
            <span class="badge-red text-[9px]">12</span>
        </a>

        <a href="{{ route('admin.withdrawals') }}" class="sidebar-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
            <i data-lucide="banknote" class="w-4 h-4 flex-shrink-0"></i>
            <span class="flex-1">Approval Withdrawal</span>
            <span class="badge-amber text-[9px]">3</span>
        </a>

        <a href="{{ route('admin.campaigns') }}" class="sidebar-link {{ request()->routeIs('admin.campaigns*') ? 'active' : '' }}">
            <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0"></i>
            Campaign
        </a>

        <a href="{{ route('admin.payouts') }}" class="sidebar-link {{ request()->routeIs('admin.payouts*') ? 'active' : '' }}">
            <i data-lucide="wallet-cards" class="w-4 h-4 flex-shrink-0"></i>
            Transaksi & Escrow
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Pengguna</div>

        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i data-lucide="users" class="w-4 h-4 flex-shrink-0"></i>
            Semua Pengguna
        </a>

        <a href="{{ route('admin.kreators') }}" class="sidebar-link {{ request()->routeIs('admin.kreators*') ? 'active' : '' }}">
            <i data-lucide="clapperboard" class="w-4 h-4 flex-shrink-0"></i>
            Kreator
        </a>

        <a href="{{ route('admin.brands') }}" class="sidebar-link {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
            <i data-lucide="briefcase-business" class="w-4 h-4 flex-shrink-0"></i>
            Brand
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-700">Sistem</div>

        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
            Pengaturan
        </a>
    </nav>

    <div class="px-3 py-3 border-t border-white/[0.05] flex-shrink-0">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 cursor-pointer group"
             style="background: rgba(255,255,255,0.02);">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0 transition-all duration-300 group-hover:scale-105"
                 style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 0 12px rgba(16,185,129,0.3);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-600 truncate">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" title="Keluar" class="text-slate-700 hover:text-red-400 transition-colors p-1 rounded-lg hover:bg-red-500/10">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
