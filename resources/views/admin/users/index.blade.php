@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')
@section('page_subtitle', 'Kelola semua akun pengguna platform')

@section('content')
<div class="space-y-5">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    <!-- Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-brand"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-slate-500">Total Pengguna</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 flex items-center justify-center">
                    <i data-lucide="clapperboard" class="w-4 h-4 text-violet-400"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalKreators) }}</p>
            <p class="text-xs text-slate-500">Kreator</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-4 h-4 text-emerald-400"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalBrands) }}</p>
            <p class="text-xs text-slate-500">Brand</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue/10 border border-blue/20 flex items-center justify-center">
                    <i data-lucide="shield" class="w-4 h-4 text-blue-400"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-white">{{ number_format($totalAdmins) }}</p>
            <p class="text-xs text-slate-500">Admin</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Semua Pengguna</h3>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                    <div class="flex items-center gap-2 bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..." class="bg-transparent text-xs text-slate-300 placeholder-slate-500 outline-none w-40">
                    </div>
                    <select name="role" onchange="this.form.submit()" class="bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-xs text-slate-300 outline-none">
                        <option value="all" {{ request('role') === 'all' ? 'selected' : '' }}>Semua Role</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kreator" {{ request('role') === 'kreator' ? 'selected' : '' }}>Kreator</option>
                        <option value="brand" {{ request('role') === 'brand' ? 'selected' : '' }}>Brand</option>
                    </select>
                    <button type="submit" class="btn-primary text-xs px-3 py-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
                <a href="{{ route('admin.users.create') }}" class="btn-primary text-xs px-3 py-2 flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Tambah
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bergabung</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Views</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($users as $u)
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($u->avatar)
                                <img src="{{ $u->avatar }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full flex-shrink-0">
                                @else
                                <div class="w-8 h-8 rounded-full {{ $u->role==='kreator' ? 'bg-brand/20 text-brand' : ($u->role==='brand' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-blue-500/20 text-blue-400') }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($u->name,0,1)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-white">{{ $u->name }}</p>
                                        @if($u->google_id)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 font-semibold">Google</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $u->role==='kreator' ? 'bg-brand/10 text-brand border border-brand/20' : ($u->role==='brand' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
                                Aktif
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">
                            @if($u->role === 'kreator')
                            {{ number_format(\App\Models\Submission::where('user_id', $u->id)->where('status', 'approved')->sum('views_claimed')) }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <a href="{{ route('admin.users.show', $u) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $u) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/10 transition-colors" title="Edit">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </a>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @else
                                <span class="p-1.5 rounded-lg text-slate-700 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-500 text-sm">
                            Belum ada pengguna terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between px-5 py-3.5 border-t border-neutral-800/60">
            <p class="text-xs text-slate-500">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ number_format($users->total()) }} pengguna
            </p>
            <div class="flex items-center gap-1">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
