@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna')
@section('page_subtitle', 'Perbarui informasi pengguna')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Card -->
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-5 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-brand"></i>
                Informasi Dasar
            </h3>
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Role <span class="text-red-400">*</span></label>
                    <select name="role" required
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kreator" {{ old('role', $user->role) === 'kreator' ? 'selected' : '' }}>Kreator</option>
                        <option value="brand" {{ old('role', $user->role) === 'brand' ? 'selected' : '' }}>Brand</option>
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if($user->google_id)
                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-xs font-medium text-blue-400 mb-1">Akun Google OAuth</p>
                            <p class="text-xs text-slate-400">Pengguna ini terdaftar menggunakan Google OAuth. Password tidak diperlukan untuk login.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Password -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('password') border-red-500 @enderror"
                        placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all">
                </div>
            </div>
        </div>

        <!-- Financial Information Card -->
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-white mb-5 flex items-center gap-2">
                <i data-lucide="wallet" class="w-4 h-4 text-emerald-400"></i>
                Informasi Keuangan
            </h3>
            
            <div class="space-y-4">
                <!-- Balance -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Saldo</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">Rp</span>
                        <input type="number" name="balance" value="{{ old('balance', $user->balance) }}" min="0" step="1000"
                            class="w-full bg-neutral-800 border border-neutral-700 rounded-xl pl-12 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('balance') border-red-500 @enderror">
                    </div>
                    @error('balance')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bank Name -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('bank_name') border-red-500 @enderror"
                        placeholder="Contoh: BCA, Mandiri, BNI">
                    @error('bank_name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bank Account -->
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2">Nomor Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-all @error('bank_account') border-red-500 @enderror"
                        placeholder="Nomor rekening bank">
                    @error('bank_account')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary flex items-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
