@php
    $inputClass = 'bg-[#09090b] border border-white/[0.08] rounded-xl px-5 py-4 text-slate-100 w-full transition-all duration-200 outline-none text-base font-semibold focus:border-emerald-500/50 focus:shadow-[0_0_0_3px_rgba(16,185,129,0.08)] placeholder:text-slate-600';
@endphp

<form x-show="activeTab === 'edit'" method="POST" action="{{ route('brand.profile.update') }}" enctype="multipart/form-data" class="glass-card p-6 lg:p-8 animate-fade-in-up flex-1 flex flex-col" style="display: none;" x-transition.fade>
    @csrf
    @method('PUT')

    <div class="border-b border-white/[0.05] pb-4 mb-6">
        <h3 class="text-lg lg:text-xl font-black text-white">Edit Profil Brand</h3>
        <p class="text-sm text-slate-500 mt-0.5">Ubah data dasar perusahaan, PIC kontak, dan website brand Anda.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start mb-6 w-full">
        <!-- Logo Upload Card with Instant Preview -->
        <div class="flex flex-col items-center gap-3.5 flex-shrink-0 w-full lg:w-auto">
            <label for="brand-logo" class="w-24 h-24 lg:w-28 lg:h-28 rounded-2xl bg-white/[0.01] border border-dashed border-white/[0.15] flex items-center justify-center p-1.5 relative group cursor-pointer overflow-hidden transition-all hover:border-emerald-500/50">
                <template x-if="logoPreviewUrl">
                    <img :src="logoPreviewUrl" alt="Preview Logo" class="h-full w-full rounded-xl object-cover">
                </template>
                <template x-if="!logoPreviewUrl">
                    <div class="w-full h-full rounded-xl bg-white/[0.02] text-emerald-400 flex flex-col items-center justify-center gap-1.5 transition-all group-hover:bg-white/[0.04]">
                        <i data-lucide="building-2" class="w-8 h-8"></i>
                    </div>
                </template>
                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                </div>
            </label>
            <input id="brand-logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="sr-only"
                @change="const file = $event.target.files[0]; if (file) { logoPreviewUrl = URL.createObjectURL(file); }">
            
            <div class="text-center">
                <label for="brand-logo" class="cursor-pointer text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors">Ubah Logo</label>
                <p class="text-[10px] text-slate-600 mt-1 uppercase tracking-wider">JPG, PNG, WEBP, SVG max. 2MB</p>
                @error('logo')
                    <p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form fields on the right -->
        <div class="flex-1 w-full flex flex-col gap-4">
            <div class="w-full">
                <label for="company_name" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Brand / Perusahaan</label>
                <input id="company_name" name="company_name" type="text" class="{{ $inputClass }}" value="{{ old('company_name', $profile?->company_name ?? auth()->user()->name) }}" required>
                @error('company_name')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                <div>
                    <label for="contact_name" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama PIC Kontak</label>
                    <input id="contact_name" name="contact_name" type="text" class="{{ $inputClass }}" value="{{ old('contact_name', $profile?->contact_name) }}" placeholder="Nama PIC utama">
                    @error('contact_name')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">WhatsApp / Telepon</label>
                    <input id="phone" name="phone" type="text" class="{{ $inputClass }}" value="{{ old('phone', $profile?->phone) }}" placeholder="0812xxxxxxx">
                    @error('phone')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="w-full">
                <label for="website" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Website / Link Toko</label>
                <input id="website" name="website" type="url" class="{{ $inputClass }}" value="{{ old('website', $profile?->website) }}" placeholder="https://www.brandanda.com">
                @error('website')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
            </div>

            <div class="w-full">
                <label for="address" class="block text-xs lg:text-sm font-bold text-slate-500 uppercase tracking-widest mb-1.5">Alamat Brand</label>
                <textarea id="address" name="address" class="{{ $inputClass }} min-h-[90px] resize-y" placeholder="Alamat kantor atau domisili brand">{{ old('address', $profile?->address) }}</textarea>
                @error('address')<p class="text-[10px] text-red-400 font-semibold mt-1.5">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="border-t border-white/[0.05] pt-5 flex flex-col sm:flex-row justify-end gap-3 mt-auto">
        <button type="button" @click="activeTab = 'info'" class="btn-ghost !py-3.5 !px-6 text-sm lg:text-base font-bold text-center w-auto">Batal</button>
        <button type="submit" class="btn-primary !py-3.5 !px-6 text-sm lg:text-base font-extrabold flex items-center justify-center gap-1.5 w-auto">
            <i data-lucide="save" class="w-4 h-4"></i> Simpan Profil
        </button>
    </div>
</form>
