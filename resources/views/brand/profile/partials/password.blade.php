@php
    $inputClass = 'bg-[#09090b] border border-white/[0.08] rounded-xl px-5 py-4 text-slate-100 w-full transition-all duration-200 outline-none text-base font-semibold focus:border-emerald-500/50 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)] placeholder:text-slate-600';
@endphp

<form x-show="activeTab === 'password'" method="POST" action="{{ route('brand.profile.password') }}" class="glass-card p-6 lg:p-8 animate-fade-in-up flex-1 flex flex-col" style="display: none;" x-transition.fade>
    @csrf
    @method('PUT')

    <div class="border-b border-white/[0.05] pb-4 mb-6">
        <h3 class="text-lg lg:text-xl font-black text-white">Ganti Password</h3>
        <p class="text-sm text-slate-500 mt-0.5">Ubah kata sandi login akun brand Anda secara berkala untuk menjaga keamanan data.</p>
    </div>

    <div class="flex flex-col gap-4 max-w-xl mb-6">
        <div class="w-full">
            <label for="current_password" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password Saat Ini</label>
            <input id="current_password" name="current_password" type="password" class="{{ $inputClass }}" placeholder="••••••••" required>
            @error('current_password')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="w-full">
            <label for="password" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password Baru</label>
            <input id="password" name="password" type="password" class="{{ $inputClass }}" placeholder="••••••••" required>
            @error('password')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
        </div>

        <div class="w-full">
            <label for="password_confirmation" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="{{ $inputClass }}" placeholder="••••••••" required>
            @error('password_confirmation')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="border-t border-white/[0.05] pt-5 flex flex-col sm:flex-row justify-end gap-3 mt-auto">
        <button type="button" @click="activeTab = 'info'" class="btn-ghost !py-3.5 !px-6 text-sm lg:text-base font-bold text-center w-auto">Batal</button>
        <button type="submit" class="btn-primary !py-3.5 !px-6 text-sm lg:text-base font-extrabold flex items-center justify-center gap-1.5 w-auto">
            <i data-lucide="key" class="w-4 h-4"></i> Simpan Password
        </button>
    </div>
</form>
