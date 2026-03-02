@extends('layouts.auth')

@section('title', 'Daftar')

@section('subtitle')
    <p class="text-slate-400 text-sm mt-3">Bergabung dengan ekosistem kreator & brand terbesar di Indonesia.</p>
@endsection

@section('content')
<form action="#" method="POST" class="space-y-5" x-data="{ role: 'creator' }">
    @csrf

    <!-- Pilihan Role (Tabs/Radio) -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-slate-300 mb-3 text-center">Daftar Sebagai</label>
        <div class="grid grid-cols-2 gap-3 p-1 bg-neutral-900/50 rounded-xl border border-neutral-800">
            <!-- Pilihan Kreator -->
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="creator" x-model="role" class="peer sr-only">
                <div class="py-2.5 text-center text-sm font-medium rounded-lg text-slate-400 transition-all peer-checked:bg-gradient-to-r peer-checked:from-brand peer-checked:to-brand peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-brand/20 hover:text-white flex items-center justify-center gap-2">
                    <i data-lucide="clapperboard" class="w-4 h-4"></i> Kreator / Clipper
                </div>
            </label>
            
            <!-- Pilihan Brand -->
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="brand" x-model="role" class="peer sr-only">
                <div class="py-2.5 text-center text-sm font-medium rounded-lg text-slate-400 transition-all peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-emerald-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 hover:text-white flex items-center justify-center gap-2">
                    <i data-lucide="building-2" class="w-4 h-4"></i> Brand / Agensi
                </div>
            </label>
        </div>
    </div>

    <!-- Input Nama Lengkap / Perusahaan -->
    <div>
        <label for="name" class="block text-sm font-medium text-slate-300 mb-2" x-text="role === 'creator' ? 'Nama Lengkap / Channel' : 'Nama Brand / Perusahaan'"></label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="user" class="h-5 w-5 text-slate-500 group-focus-within:text-brand transition-colors" :class="role === 'brand' ? 'group-focus-within:text-emerald-500' : 'group-focus-within:text-brand'"></i>
            </div>
            <input type="text" name="name" id="name" 
                class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 sm:text-sm transition-all shadow-inner" 
                :class="role === 'brand' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-brand focus:border-brand'"
                placeholder="ClipMasterID" required>
        </div>
    </div>

    <!-- Input Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Alamat Email</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="mail" class="h-5 w-5 text-slate-500 group-focus-within:text-brand transition-colors" :class="role === 'brand' ? 'group-focus-within:text-emerald-500' : 'group-focus-within:text-brand'"></i>
            </div>
            <input type="email" name="email" id="email" 
                class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 sm:text-sm transition-all shadow-inner" 
                :class="role === 'brand' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-brand focus:border-brand'"
                placeholder="nama@email.com" required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Input Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Kata Sandi</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="lock" class="h-5 w-5 text-slate-500 transition-colors" :class="role === 'brand' ? 'group-focus-within:text-emerald-500' : 'group-focus-within:text-brand'"></i>
                </div>
                <input type="password" name="password" id="password" 
                    class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 sm:text-sm transition-all shadow-inner" 
                    :class="role === 'brand' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-brand focus:border-brand'"
                    placeholder="Minimal 8 karakter" required>
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Sandi</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="shield-check" class="h-5 w-5 text-slate-500 transition-colors" :class="role === 'brand' ? 'group-focus-within:text-emerald-500' : 'group-focus-within:text-brand'"></i>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 sm:text-sm transition-all shadow-inner" 
                    :class="role === 'brand' ? 'focus:ring-emerald-500 focus:border-emerald-500' : 'focus:ring-brand focus:border-brand'"
                    placeholder="Ulangi sandi" required>
            </div>
        </div>
    </div>

    <!-- Submit Button (Dinamis berdasarkan role) -->
    <div class="pt-2">
        <!-- Tombol Kreator -->
        <button type="submit" x-show="role === 'creator'"
            class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/20 text-sm font-bold text-white bg-gradient-to-r from-brand to-brand hover:from-brand-hover hover:to-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-brand transform transition-all active:scale-[0.98]">
            Daftar Sebagai Kreator <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>

        <!-- Tombol Brand -->
        <button type="submit" x-show="role === 'brand'" style="display: none;"
            class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-500/20 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-emerald-500 transform transition-all active:scale-[0.98]">
            Daftar Sebagai Brand <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </div>
</form>

<!-- Login Link -->
<div class="mt-8 text-center text-sm text-slate-400">
    Sudah punya akun? 
    <a href="/login" class="font-bold text-white transition-colors" :class="role === 'brand' ? 'hover:text-emerald-500' : 'hover:text-brand'">Masuk Disini</a>
</div>
@endsection
