<!-- ===== SIDEBAR ===== -->
<aside
    class="glass-sidebar fixed inset-y-0 left-0 z-40 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <!-- Logo -->
    <div class="h-16 flex items-center px-5 border-b border-white/[0.05] flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                 style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 0 16px rgba(16,185,129,0.4);">
                <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-black text-white tracking-tight leading-none">Clip<span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, #10b981, #34d399); -webkit-background-clip: text;">Hub</span></h1>
                <p class="text-[9px] text-slate-600 font-semibold tracking-widest uppercase mt-0.5">Admin Panel</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <!-- Dropdown: Pengguna -->
        <div x-data="{ open: {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white transition-all duration-200 {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'text-white' : 'text-slate-400' }}"
                    style="hover: background: rgba(255,255,255,0.04);">
                <div class="flex items-center gap-3">
                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'text-brand' : '' }}"></i>
                    Pengguna & Entitas
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-600" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'block' : 'none' }};">
                <div class="ml-4 pl-4 mt-1 space-y-0.5" style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.users') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.users*') ? 'text-brand' : 'text-slate-500' }}">Staf Internal</a>
                    <a href="{{ route('admin.kreators') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.kreators*') ? 'text-brand' : 'text-slate-500' }}">Daftar Kreator</a>
                    <a href="{{ route('admin.brands') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.brands*') ? 'text-brand' : 'text-slate-500' }}">Daftar Brand</a>
                    <a href="{{ route('admin.kyc') }}" class="flex items-center justify-between py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.kyc*') ? 'text-brand' : 'text-slate-500' }}">
                        Verifikasi KYC <span class="badge-amber text-[9px]">5</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Campaign & Konten -->
        <div x-data="{ open: {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white transition-all duration-200 {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'text-brand' : '' }}"></i>
                    Campaign & Konten
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-600" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'block' : 'none' }};">
                <div class="ml-4 pl-4 mt-1 space-y-0.5" style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.campaigns') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.campaigns*') ? 'text-brand' : 'text-slate-500' }}">Semua Campaign</a>
                    <a href="{{ route('admin.ugc') }}" class="flex items-center justify-between py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.ugc*') ? 'text-brand' : 'text-slate-500' }}">
                        Moderasi UGC <span class="badge-red text-[9px]">12</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Keuangan -->
        <div x-data="{ open: {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white transition-all duration-200 {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="wallet" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'text-brand' : '' }}"></i>
                    Keuangan
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-600" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'block' : 'none' }};">
                <div class="ml-4 pl-4 mt-1 space-y-0.5" style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.payouts') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.payouts*') ? 'text-brand' : 'text-slate-500' }}">Pembayaran & Escrow</a>
                    <a href="{{ route('admin.withdrawals') }}" class="flex items-center justify-between py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.withdrawals*') ? 'text-brand' : 'text-slate-500' }}">
                        Penarikan Dana <span class="badge-amber text-[9px]">3</span>
                    </a>
                    <a href="{{ route('admin.disputes') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.disputes*') ? 'text-brand' : 'text-slate-500' }}">Tiket Bantuan</a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Laporan & Sistem -->
        <div x-data="{ open: {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white transition-all duration-200 {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="server" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'text-brand' : '' }}"></i>
                    Sistem Operasional
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 text-slate-600" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'block' : 'none' }};">
                <div class="ml-4 pl-4 mt-1 space-y-0.5" style="border-left: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.analytics') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.analytics*') ? 'text-brand' : 'text-slate-500' }}">Analitik Platform</a>
                    {{-- <a href="{{ route('admin.fraud') }}" class="...">Anti-Fraud Monitor</a> --}}
                    <a href="{{ route('admin.notifications') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.notifications*') ? 'text-brand' : 'text-slate-500' }}">Notifikasi Broadcast</a>
                    <a href="{{ route('admin.logs') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.logs*') ? 'text-brand' : 'text-slate-500' }}">Log Aktivitas</a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center py-2 px-2 text-xs font-medium rounded-lg hover:text-white transition-colors {{ request()->routeIs('admin.settings*') ? 'text-brand' : 'text-slate-500' }}">Pengaturan</a>
                </div>
            </div>
        </div>

    </nav>

    <!-- User Profile Footer -->
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
