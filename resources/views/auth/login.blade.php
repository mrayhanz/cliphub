@extends('layouts.auth')

@section('title', 'Masuk')

@section('subtitle')
    <p class="text-slate-400 text-sm mt-3">Selamat datang kembali! Masuk ke akun Anda untuk melanjutkan kampanye atau mengelola klip.</p>
@endsection

@section('content')
<form action="#" method="POST" class="space-y-5">
    @csrf

    <!-- Input Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Alamat Email</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="mail" class="h-5 w-5 text-slate-500 group-focus-within:text-brand transition-colors"></i>
            </div>
            <input type="email" name="email" id="email" 
                class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all shadow-inner" 
                placeholder="nama@email.com" required>
        </div>
    </div>

    <!-- Input Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Kata Sandi</label>
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="lock" class="h-5 w-5 text-slate-500 group-focus-within:text-brand transition-colors"></i>
            </div>
            <input type="password" name="password" id="password" 
                class="block w-full pl-10 pr-3 py-3 border border-neutral-700 rounded-xl leading-5 bg-neutral-900/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all shadow-inner" 
                placeholder="••••••••" required>
        </div>
    </div>

    <!-- Options: Remember Me & Forgot Password -->
    <div class="flex items-center justify-between pb-2">
        <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox" 
                class="h-4 w-4 bg-neutral-900 border-neutral-700 rounded text-brand focus:ring-brand focus:ring-offset-neutral-900 accent-brand transition-colors cursor-pointer">
            <label for="remember-me" class="ml-2 block text-sm text-slate-400 cursor-pointer hover:text-slate-300 transition-colors">
                Ingat saya
            </label>
        </div>

        <div class="text-sm">
            <a href="#" class="font-medium text-brand hover:text-brand-light transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-[1px] auto after:w-full after:origin-bottom-right after:scale-x-0 hover:after:origin-bottom-left hover:after:scale-x-100 after:bg-brand-light after:transition-transform">
                Lupa sandi?
            </a>
        </div>
    </div>

    <!-- Submit Button -->
    <div>
        <button type="submit" 
            class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/20 text-sm font-bold text-white bg-gradient-to-r from-brand to-brand hover:from-brand-hover hover:to-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-brand transform transition-all active:scale-[0.98]">
            Masuk ke Dashboard <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </div>
</form>

<!-- Divider -->
<div class="mt-8 mb-6 relative">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-neutral-800"></div>
    </div>
    <div class="relative flex justify-center text-sm">
        <span class="px-3 bg-neutral-900 text-slate-500 border border-neutral-800 rounded-full">
            Atau
        </span>
    </div>
</div>

<!-- Social Login -->
<div>
    <button type="button" 
        class="w-full flex justify-center items-center gap-3 py-3 px-4 rounded-xl text-sm font-medium text-slate-300 bg-neutral-800 border border-neutral-700 hover:bg-neutral-700 hover:text-white focus:outline-none transition-colors">
        <svg class="w-5 h-5" aria-hidden="true" viewBox="0 0 24 24">
            <path d="M12.0003 4.75C13.7703 4.75 15.3553 5.36002 16.6053 6.54998L20.0303 3.125C17.9502 1.19 15.2353 0 12.0003 0C7.31028 0 3.25527 2.69 1.28027 6.60998L5.27028 9.70498C6.21525 6.86002 8.87028 4.75 12.0003 4.75Z" fill="#EA4335"></path>
            <path d="M23.49 12.275C23.49 11.49 23.415 10.73 23.3 10H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.945 21.1C22.2 19.01 23.49 15.92 23.49 12.275Z" fill="#4285F4"></path>
            <path d="M5.26498 14.2949C5.02498 13.5699 4.88501 12.7999 4.88501 11.9999C4.88501 11.1999 5.01998 10.4299 5.26498 9.7049L1.275 6.60986C0.46 8.22986 0 10.0599 0 11.9999C0 13.9399 0.46 15.7699 1.28 17.3899L5.26498 14.2949Z" fill="#FBBC05"></path>
            <path d="M12.0004 24.0001C15.2404 24.0001 17.9654 22.935 19.9454 21.095L16.0804 18.095C15.0054 18.82 13.6204 19.245 12.0004 19.245C8.8704 19.245 6.21537 17.135 5.26537 14.29L1.27539 17.385C3.25539 21.31 7.3104 24.0001 12.0004 24.0001Z" fill="#34A853"></path>
        </svg>
        Masuk dengan Google
    </button>
</div>

<!-- Register Link -->
<div class="mt-8 text-center text-sm text-slate-400">
    Belum punya akun? 
    <a href="/register" class="font-bold text-white hover:text-brand transition-colors">Buat Akun Sekarang</a>
</div>
@endsection
