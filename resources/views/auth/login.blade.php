@extends('layouts.auth')

@section('title', 'Masuk')

@section('body')
<div class="min-h-screen flex">

    <!-- ============ PANEL KIRI: Form Login ============ -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 sm:px-12 py-12 relative">
        
        <!-- Background subtle -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[400px] bg-emerald-500/[0.04] rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-sm relative z-10">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 mb-10">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center shadow-[0_0_20px_rgba(52,211,153,0.25)]">
                    <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
                </div>
                <span class="font-extrabold text-lg tracking-tight text-white">Clip<span class="text-emerald-400">Hub</span></span>
            </a>

            <!-- Heading -->
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Masuk</h1>
            <p class="text-zinc-500 text-sm mb-8">Selamat datang kembali! Masukkan detail akun Anda.</p>

            <!-- Social Login -->
            <div class="flex gap-3 mb-6">
                <button type="button" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white/[0.04] border border-white/[0.07] hover:bg-white/[0.07] transition-all duration-200">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24">
                        <path d="M12.0003 4.75C13.7703 4.75 15.3553 5.36002 16.6053 6.54998L20.0303 3.125C17.9502 1.19 15.2353 0 12.0003 0C7.31028 0 3.25527 2.69 1.28027 6.60998L5.27028 9.70498C6.21525 6.86002 8.87028 4.75 12.0003 4.75Z" fill="#EA4335"/>
                        <path d="M23.49 12.275C23.49 11.49 23.415 10.73 23.3 10H12V14.51H18.47C18.18 15.99 17.34 17.25 16.08 18.1L19.945 21.1C22.2 19.01 23.49 15.92 23.49 12.275Z" fill="#4285F4"/>
                        <path d="M5.26498 14.2949C5.02498 13.5699 4.88501 12.7999 4.88501 11.9999C4.88501 11.1999 5.01998 10.4299 5.26498 9.7049L1.275 6.60986C0.46 8.22986 0 10.0599 0 11.9999C0 13.9399 0.46 15.7699 1.28 17.3899L5.26498 14.2949Z" fill="#FBBC05"/>
                        <path d="M12.0004 24.0001C15.2404 24.0001 17.9654 22.935 19.9454 21.095L16.0804 18.095C15.0054 18.82 13.6204 19.245 12.0004 19.245C8.8704 19.245 6.21537 17.135 5.26537 14.29L1.27539 17.385C3.25539 21.31 7.3104 24.0001 12.0004 24.0001Z" fill="#34A853"/>
                    </svg>
                    <span class="text-xs font-medium text-zinc-400">Google</span>
                </button>
                <button type="button" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white/[0.04] border border-white/[0.07] hover:bg-white/[0.07] transition-all duration-200">
                    <svg class="w-[18px] h-[18px] text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    <span class="text-xs font-medium text-zinc-400">GitHub</span>
                </button>
            </div>

            <!-- Divider -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/[0.06]"></div></div>
                <div class="relative flex justify-center"><span class="px-3 bg-neutral-950 text-zinc-600 text-xs">atau masuk dengan email</span></div>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                @if ($errors->any())
                <div class="bg-red-500/[0.08] border border-red-500/20 text-red-400 text-[13px] rounded-xl p-3 flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[13px] font-medium text-zinc-400 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                        placeholder="nama@email.com" required>
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-[13px] font-medium text-zinc-400 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" id="password" 
                            class="block w-full px-4 py-3 pr-12 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                            placeholder="••••••••" required>
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-600 hover:text-zinc-300 transition-colors">
                            <span x-show="!show"><i data-lucide="eye" class="h-4 w-4"></i></span>
                            <span x-show="show" style="display: none;"><i data-lucide="eye-off" class="h-4 w-4"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input id="remember-me" name="remember" type="checkbox" 
                            class="h-4 w-4 bg-white/[0.03] border-white/[0.1] rounded text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-neutral-950 accent-emerald-500 cursor-pointer">
                        <span class="text-[13px] text-zinc-500 hover:text-zinc-300 transition-colors">Ingat saya</span>
                    </label>
                    <a href="#" class="text-[13px] font-medium text-emerald-400/80 hover:text-emerald-400 transition-colors">Lupa sandi?</a>
                </div>

                <!-- Submit -->
                <button type="submit" 
                    class="w-full flex justify-center items-center gap-2 py-3 rounded-xl text-sm font-bold text-black bg-emerald-400 hover:bg-emerald-300 transition-all duration-200 shadow-[0_0_24px_rgba(52,211,153,0.2)] hover:shadow-[0_0_32px_rgba(52,211,153,0.35)] active:scale-[0.98]">
                    Masuk <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-8 text-[11px] text-zinc-700 text-center">
                &copy; {{ date('Y') }} ClipHub Inc. &mdash; <a href="#" class="underline decoration-zinc-800 underline-offset-2 hover:text-zinc-400 transition-colors">Persyaratan Layanan</a>
            </p>
        </div>
    </div>

    <!-- ============ PANEL KANAN: Greeting / CTA Daftar ============ -->
    <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
        
        <!-- Background gradient hijau -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600"></div>
        
        <!-- Overlay pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px] opacity-30"></div>
        
        <!-- Decorative circles -->
        <div class="absolute top-16 right-16 w-40 h-40 border border-white/10 rounded-full"></div>
        <div class="absolute bottom-20 left-16 w-60 h-60 border border-white/10 rounded-full"></div>
        <div class="absolute top-1/3 left-1/4 w-20 h-20 border border-white/10 rounded-full"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-12 max-w-md">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mx-auto mb-8 shadow-lg">
                <i data-lucide="hand-metal" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight mb-4 leading-tight">Halo, Teman!</h2>
            <p class="text-white/80 text-base leading-relaxed mb-8">
                Daftarkan diri Anda dan mulai gunakan layanan kami segera. Bergabung dengan ribuan Kreator & Brand di ClipHub.
            </p>
            <a href="/register" 
                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full border-2 border-white text-white text-sm font-bold hover:bg-white hover:text-emerald-600 transition-all duration-300 shadow-[0_0_30px_rgba(255,255,255,0.1)] hover:shadow-[0_0_40px_rgba(255,255,255,0.25)]">
                DAFTAR <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- Mobile: Link daftar (terlihat hanya di mobile karena panel kanan hidden) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-neutral-950/90 backdrop-blur-lg border-t border-white/[0.05] p-4 text-center z-50">
        <p class="text-sm text-zinc-500">
            Belum punya akun? 
            <a href="/register" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">Daftar Sekarang</a>
        </p>
    </div>
</div>
@endsection
