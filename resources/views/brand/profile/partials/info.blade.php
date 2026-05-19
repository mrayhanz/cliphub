<div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-fade-in-up flex-1">
    <!-- Left Card: Identity & Brief Overview -->
    <div class="glass-card p-6 flex flex-col items-center text-center relative overflow-hidden h-full">
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 70%);"></div>
        
        <div class="w-24 h-24 rounded-2xl bg-white/[0.02] border border-white/[0.08] p-1.5 mb-4 relative overflow-hidden flex items-center justify-center">
            @if ($profile?->logo_path)
                <img src="{{ route('media.public', $profile->logo_path) }}" alt="Logo {{ $profile->company_name }}" class="h-full w-full rounded-xl object-cover">
            @else
                <div class="w-full h-full rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-10 h-10"></i>
                </div>
            @endif
        </div>
        
        <h2 class="text-2xl font-black text-white tracking-tight leading-snug">{{ $profile->company_name ?? auth()->user()->name }}</h2>
        <span class="mt-2 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/[0.04] border border-white/[0.08] text-slate-400 uppercase tracking-widest">
            {{ auth()->user()->role }}
        </span>

        <div class="w-full border-t border-white/[0.05] mt-6 pt-5 flex flex-col gap-4 text-left">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Email</span>
                <span class="text-base font-semibold text-slate-200 truncate block">{{ auth()->user()->email }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Saldo Tersedia</span>
                <span class="text-lg font-black text-emerald-400">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Right Card: Detailed Metadata Info -->
    <div class="glass-card p-6 md:col-span-2 flex flex-col h-full min-h-[320px]">
        <div>
            <div class="flex items-center justify-between border-b border-white/[0.05] pb-4 mb-5">
                <div>
                    <h3 class="text-lg lg:text-xl font-black text-white">Detail Brand</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Informasi penanggung jawab dan instansi brand Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nama Brand / Perusahaan</span>
                    <span class="text-base font-bold text-slate-200">{{ $profile->company_name ?? auth()->user()->name }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Penanggung Jawab (PIC)</span>
                    <span class="text-base font-bold text-slate-200">{{ $profile->contact_name ?? 'Belum diatur' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">WhatsApp / Telepon</span>
                    <span class="text-base font-bold text-slate-200">{{ $profile->phone ?? 'Belum diatur' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Website / Toko Online</span>
                    @if ($profile?->website)
                        <a href="{{ $profile->website }}" target="_blank" class="text-base font-bold text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-1 w-fit">
                            {{ $profile->website }} <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        </a>
                    @else
                        <span class="text-base font-bold text-slate-500">Belum diatur</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Alamat Kantor / Domisili</span>
                <p class="text-base text-slate-300 leading-relaxed font-medium">{{ $profile->address ?? 'Belum diatur' }}</p>
            </div>
        </div>

        <div class="border-t border-white/[0.05] pt-5 flex flex-wrap gap-3 justify-end mt-auto">
            <button @click="activeTab = 'password'" class="btn-ghost !py-3 !px-6 text-sm lg:text-base font-bold flex items-center gap-1.5 w-auto">
                <i data-lucide="lock" class="w-4 h-4"></i> Ganti Password
            </button>
            <button @click="activeTab = 'edit'" class="btn-primary !py-3 !px-6 text-sm lg:text-base font-extrabold flex items-center gap-1.5 w-auto">
                <i data-lucide="edit" class="w-4 h-4"></i> Edit Profil
            </button>
        </div>
    </div>
</div>
