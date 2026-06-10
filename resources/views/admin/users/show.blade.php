@extends('layouts.admin')
@section('title', 'Detail Pengguna')
@section('page_title', 'Detail Pengguna')
@section('page_subtitle', 'Informasi lengkap pengguna')

@section('content')
<div class="space-y-5">
    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary flex items-center gap-2">
            <i data-lucide="pencil" class="w-4 h-4"></i>
            Edit Pengguna
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
        @if($user->id !== auth()->id())
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="ml-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-xl text-sm font-medium text-red-400 transition-all flex items-center gap-2">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                Hapus Pengguna
            </button>
        </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Left Column - User Info -->
        <div class="lg:col-span-1 space-y-5">
            <!-- Profile Card -->
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <div class="text-center">
                    @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full mx-auto mb-4">
                    @else
                    <div class="w-24 h-24 rounded-full {{ $user->role==='kreator' ? 'bg-brand/20 text-brand' : ($user->role==='brand' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-blue-500/20 text-blue-400') }} flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    @endif
                    
                    <h3 class="text-lg font-bold text-white mb-1">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-400 mb-3">{{ $user->email }}</p>
                    
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $user->role==='kreator' ? 'bg-brand/10 text-brand border border-brand/20' : ($user->role==='brand' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->google_id)
                        <span class="text-xs px-2 py-1 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 font-semibold">
                            <i data-lucide="shield-check" class="w-3 h-3 inline"></i> Google
                        </span>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-neutral-800/60">
                        <div class="flex items-center justify-center gap-1.5 text-xs text-slate-400">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Info Card -->
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
                <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                    Informasi Keuangan
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Saldo</p>
                        <p class="text-lg font-bold text-white">Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                    </div>
                    
                    @if($user->bank_name || $user->bank_account)
                    <div class="pt-3 border-t border-neutral-800/60">
                        @if($user->bank_name)
                        <div class="mb-2">
                            <p class="text-xs text-slate-500 mb-0.5">Bank</p>
                            <p class="text-sm text-white font-medium">{{ $user->bank_name }}</p>
                        </div>
                        @endif
                        @if($user->bank_account)
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Nomor Rekening</p>
                            <p class="text-sm text-white font-mono">{{ $user->bank_account }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="pt-3 border-t border-neutral-800/60">
                        <p class="text-xs text-slate-500 italic">Belum ada informasi bank</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics & Activity -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 gap-4">
                @if($user->isBrand())
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center">
                            <i data-lucide="megaphone" class="w-4 h-4 text-brand"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['total_campaigns']) }}</p>
                    <p class="text-xs text-slate-500">Total Campaign</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-4 h-4 text-emerald-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['active_campaigns']) }}</p>
                    <p class="text-xs text-slate-500">Campaign Aktif</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 flex items-center justify-center">
                            <i data-lucide="wallet" class="w-4 h-4 text-violet-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">Rp {{ number_format($stats['total_deposits'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Total Deposit</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['pending_deposits']) }}</p>
                    <p class="text-xs text-slate-500">Deposit Pending</p>
                </div>
                @elseif($user->isKreator())
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center">
                            <i data-lucide="video" class="w-4 h-4 text-brand"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['total_submissions']) }}</p>
                    <p class="text-xs text-slate-500">Total Submission</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald/10 border border-emerald/20 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['approved_submissions']) }}</p>
                    <p class="text-xs text-slate-500">Disetujui</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-violet/10 border border-violet/20 flex items-center justify-center">
                            <i data-lucide="eye" class="w-4 h-4 text-violet-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">{{ number_format($stats['total_views']) }}</p>
                    <p class="text-xs text-slate-500">Total Views</p>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-amber/10 border border-amber/20 flex items-center justify-center">
                            <i data-lucide="arrow-down-circle" class="w-4 h-4 text-amber-400"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-white">Rp {{ number_format($stats['total_withdrawals'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500">Total Penarikan</p>
                </div>
                @else
                <div class="stat-card col-span-2">
                    <div class="text-center py-4">
                        <i data-lucide="shield" class="w-8 h-8 text-blue-400 mx-auto mb-2"></i>
                        <p class="text-sm text-slate-400">Administrator Account</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Activity -->
            @if($user->isBrand())
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-neutral-800/60">
                    <h3 class="text-sm font-semibold text-white">Campaign Terbaru</h3>
                </div>
                <div class="divide-y divide-neutral-800/40">
                    @forelse($user->campaigns()->latest()->take(5)->get() as $campaign)
                    <div class="p-4 hover:bg-white/[2%] transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="{{ $campaign->image }}" alt="{{ $campaign->title }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $campaign->title }}</p>
                                <p class="text-xs text-slate-500">{{ $campaign->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $campaign->status==='active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-400 border border-slate-500/20' }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        Belum ada campaign
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- Recent Deposits -->
            @if($user->isBrand() && $user->deposits()->exists())
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-neutral-800/60">
                    <h3 class="text-sm font-semibold text-white">Riwayat Deposit</h3>
                </div>
                <div class="divide-y divide-neutral-800/40">
                    @foreach($user->deposits()->latest()->take(5)->get() as $deposit)
                    <div class="p-4 hover:bg-white/[2%] transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500">{{ $deposit->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $deposit->status==='success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($deposit->status==='pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20') }}">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
