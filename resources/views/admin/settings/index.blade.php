@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan Platform')
@section('page_subtitle', 'Konfigurasi admin dan sistem ClipHub')

@section('content')
@php $activeTab = session('success_tab', 'profile'); @endphp

<div x-data="{ tab: '{{ $activeTab }}' }" class="max-w-4xl mx-auto space-y-6">

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl px-4 py-3 text-sm">
        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm">
        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
        <ul class="space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Tab Nav --}}
    <div class="flex gap-1 bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-1.5">
        @foreach([
            ['key'=>'profile',     'icon'=>'user-cog',      'label'=>'Profil Admin'],
            ['key'=>'commission',  'icon'=>'percent',        'label'=>'Komisi & Fee'],
            ['key'=>'maintenance', 'icon'=>'triangle-alert', 'label'=>'Maintenance'],
        ] as $m)
        <button @click="tab='{{ $m['key'] }}'"
            :class="tab==='{{ $m['key'] }}' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25' : 'text-slate-500 hover:text-slate-300 border border-transparent'"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200">
            <i data-lucide="{{ $m['icon'] }}" class="w-3.5 h-3.5"></i>
            <span class="hidden sm:inline">{{ $m['label'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- ===== TAB: PROFIL ===== --}}
    <div x-show="tab==='profile'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.profile') }}" class="space-y-4">
            @csrf

            {{-- Info Akun --}}
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <i data-lucide="user-cog" class="w-3.5 h-3.5 text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Informasi Akun</h3>
                        <p class="text-[11px] text-slate-500">Nama dan email login admin</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1">Alamat Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Role</label>
                        <div class="flex items-center gap-2 bg-neutral-800/50 border border-neutral-700/40 rounded-xl px-3 py-2 text-sm text-slate-500">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i> Administrator
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-1">Bergabung Sejak</label>
                        <div class="bg-neutral-800/50 border border-neutral-700/40 rounded-xl px-3 py-2 text-sm text-slate-500">
                            {{ auth()->user()->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                        <i data-lucide="lock-keyhole" class="w-3.5 h-3.5 text-amber-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Ganti Password</h3>
                        <p class="text-[11px] text-slate-500">Kosongkan jika tidak ingin mengubah</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1">Password Baru</label>
                        <input type="password" name="password" placeholder="Min. 8 karakter"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- ===== TAB: KOMISI ===== --}}
    <div x-show="tab==='commission'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.commission') }}" class="space-y-4">
            @csrf
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5">
                <div class="flex items-center gap-2.5 mb-5">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <i data-lucide="percent" class="w-3.5 h-3.5 text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white">Komisi & Fee Platform</h3>
                        <p class="text-[11px] text-slate-500">Atur persentase komisi dari setiap transaksi</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Fee Brand --}}
                    <div class="bg-neutral-800/40 border border-neutral-700/40 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-md bg-purple-500/20 flex items-center justify-center">
                                <i data-lucide="briefcase-business" class="w-2.5 h-2.5 text-purple-400"></i>
                            </div>
                            <label class="text-[11px] font-semibold text-slate-300">Fee dari Brand</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" name="commission_brand"
                                value="{{ old('commission_brand', $commission['commission_brand'] ?? 10) }}"
                                min="0" max="100" step="0.5"
                                class="flex-1 bg-neutral-900 border border-neutral-700 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                            <span class="text-slate-400 text-sm font-semibold">%</span>
                        </div>
                        <p class="text-[10px] text-slate-600 mt-1.5">Dipotong dari budget campaign</p>
                    </div>

                    {{-- Fee Kreator --}}
                    <div class="bg-neutral-800/40 border border-neutral-700/40 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-md bg-emerald-500/20 flex items-center justify-center">
                                <i data-lucide="clapperboard" class="w-2.5 h-2.5 text-emerald-400"></i>
                            </div>
                            <label class="text-[11px] font-semibold text-slate-300">Fee dari Kreator</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" name="commission_kreator"
                                value="{{ old('commission_kreator', $commission['commission_kreator'] ?? 5) }}"
                                min="0" max="100" step="0.5"
                                class="flex-1 bg-neutral-900 border border-neutral-700 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                            <span class="text-slate-400 text-sm font-semibold">%</span>
                        </div>
                        <p class="text-[10px] text-slate-600 mt-1.5">Dipotong dari penghasilan kreator</p>
                    </div>

                    {{-- Min Payout --}}
                    <div class="bg-neutral-800/40 border border-neutral-700/40 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-md bg-amber-500/20 flex items-center justify-center">
                                <i data-lucide="arrow-down-to-line" class="w-2.5 h-2.5 text-amber-400"></i>
                            </div>
                            <label class="text-[11px] font-semibold text-slate-300">Minimum Payout Kreator</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 text-xs font-medium">Rp</span>
                            <input type="number" name="min_payout"
                                value="{{ old('min_payout', $commission['min_payout'] ?? 100000) }}"
                                min="0" step="1000"
                                class="flex-1 bg-neutral-900 border border-neutral-700 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                        </div>
                        <p class="text-[10px] text-slate-600 mt-1.5">Nominal minimal untuk request withdraw</p>
                    </div>

                    {{-- Max Budget --}}
                    <div class="bg-neutral-800/40 border border-neutral-700/40 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-5 h-5 rounded-md bg-red-500/20 flex items-center justify-center">
                                <i data-lucide="arrow-up-from-line" class="w-2.5 h-2.5 text-red-400"></i>
                            </div>
                            <label class="text-[11px] font-semibold text-slate-300">Maks. Budget Campaign</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500 text-xs font-medium">Rp</span>
                            <input type="number" name="max_campaign_budget"
                                value="{{ old('max_campaign_budget', $commission['max_campaign_budget'] ?? 500000000) }}"
                                min="0" step="1000000"
                                class="flex-1 bg-neutral-900 border border-neutral-700 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors">
                        </div>
                        <p class="text-[10px] text-slate-600 mt-1.5">Batas atas budget yang bisa di-input brand</p>
                    </div>
                </div>

                <div class="mt-4 flex items-start gap-2.5 bg-blue-500/5 border border-blue-500/15 rounded-xl px-3.5 py-3">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-blue-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-[11px] text-slate-500">Perubahan komisi hanya berlaku untuk campaign yang dibuat setelah pengaturan ini disimpan.</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Komisi
                </button>
            </div>
        </form>
    </div>

    {{-- ===== TAB: MAINTENANCE ===== --}}
    <div x-show="tab==='maintenance'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.maintenance') }}" class="space-y-4">
            @csrf
            @php $isOn = ($maintenance['maintenance_mode'] ?? '0') === '1'; @endphp

            <div class="bg-neutral-900/60 border {{ $isOn ? 'border-red-500/30' : 'border-neutral-800/60' }} rounded-2xl p-5">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg {{ $isOn ? 'bg-red-500/10' : 'bg-neutral-700/40' }} flex items-center justify-center">
                            <i data-lucide="triangle-alert" class="w-3.5 h-3.5 {{ $isOn ? 'text-red-400' : 'text-slate-500' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Maintenance Mode</h3>
                            <p class="text-[11px] text-slate-500">Blokir akses pengguna sementara</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full {{ $isOn ? 'bg-red-500/15 text-red-400 border border-red-500/30' : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isOn ? 'bg-red-400' : 'bg-emerald-400' }} animate-pulse"></span>
                        {{ $isOn ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>

                @if($isOn)
                <div class="flex items-start gap-2.5 bg-red-500/8 border border-red-500/20 rounded-xl px-3.5 py-3 mb-4">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-red-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-[11px] text-red-300">Maintenance sedang aktif. Brand & Kreator tidak bisa mengakses platform.</p>
                </div>
                @endif

                <div class="space-y-4" x-data="{ mode: '{{ $isOn ? '1' : '0' }}' }">
                    {{-- Toggle --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-2">Status Mode</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label :class="mode==='0' ? 'border-emerald-500/30 bg-emerald-500/8 text-emerald-400' : 'border-neutral-700 bg-neutral-800/40 text-slate-500'"
                                class="flex items-center gap-2.5 px-4 py-3 rounded-xl border cursor-pointer transition-all">
                                <input type="radio" name="maintenance_mode" value="0" x-model="mode" class="hidden">
                                <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                <div>
                                    <p class="text-xs font-semibold">Platform Aktif</p>
                                    <p class="text-[10px] opacity-60">Semua fitur normal</p>
                                </div>
                            </label>
                            <label :class="mode==='1' ? 'border-red-500/30 bg-red-500/8 text-red-400' : 'border-neutral-700 bg-neutral-800/40 text-slate-500'"
                                class="flex items-center gap-2.5 px-4 py-3 rounded-xl border cursor-pointer transition-all">
                                <input type="radio" name="maintenance_mode" value="1" x-model="mode" class="hidden">
                                <i data-lucide="triangle-alert" class="w-4 h-4 flex-shrink-0"></i>
                                <div>
                                    <p class="text-xs font-semibold">Maintenance</p>
                                    <p class="text-[10px] opacity-60">Akses pengguna diblokir</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-400 mb-1.5">Pesan untuk Pengguna</label>
                        <textarea name="maintenance_message" rows="3" maxlength="500"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 outline-none focus:border-emerald-500 transition-colors resize-none"
                            placeholder="Pesan yang tampil di halaman maintenance...">{{ old('maintenance_message', $maintenance['maintenance_message'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl transition-colors">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
