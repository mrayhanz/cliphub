@extends('layouts.auth')

@section('title', 'Daftar')

@section('body')
<div class="min-h-screen flex">

    <!-- ============ PANEL KIRI: Greeting / CTA Login ============ -->
    <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
        
        <!-- Background gradient hijau -->
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 via-green-500 to-emerald-500"></div>
        
        <!-- Overlay pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px] opacity-30"></div>
        
        <!-- Decorative circles -->
        <div class="absolute top-16 left-16 w-40 h-40 border border-white/10 rounded-full"></div>
        <div class="absolute bottom-20 right-16 w-60 h-60 border border-white/10 rounded-full"></div>
        <div class="absolute bottom-1/3 right-1/4 w-20 h-20 border border-white/10 rounded-full"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-12 max-w-md">
            <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mx-auto mb-8 shadow-lg">
                <i data-lucide="sparkles" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight mb-4 leading-tight">Selamat Datang!</h2>
            <p class="text-white/80 text-base leading-relaxed mb-8">
                Sudah punya akun? Masuk untuk melanjutkan kampanye atau mengelola klip Anda di ClipHub.
            </p>
            <a href="/login" 
                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full border-2 border-white text-white text-sm font-bold hover:bg-white hover:text-emerald-600 transition-all duration-300 shadow-[0_0_30px_rgba(255,255,255,0.1)] hover:shadow-[0_0_40px_rgba(255,255,255,0.25)]">
                MASUK <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- ============ PANEL KANAN: Form Register ============ -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 sm:px-12 py-12 relative">
        
        <!-- Background subtle -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-0 right-1/2 translate-x-1/2 -translate-y-1/2 w-[500px] h-[400px] bg-emerald-500/[0.04] rounded-full blur-[120px]"></div>
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
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Buat Akun</h1>
            <p class="text-zinc-500 text-sm mb-8">Bergabung dengan ekosistem kreator & brand terbesar.</p>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4" x-data="{ role: '{{ old('role', 'kreator') }}' }">
                @csrf

                <!-- Pilihan Role -->
                <div>
                    <label class="block text-[13px] font-medium text-zinc-400 mb-2">Daftar Sebagai</label>
                    <div class="grid grid-cols-2 gap-2 p-1 bg-white/[0.02] rounded-xl border border-white/[0.05]">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="kreator" x-model="role" class="peer sr-only">
                            <div class="py-2.5 text-center text-[13px] font-semibold rounded-lg text-zinc-500 transition-all duration-200 peer-checked:bg-emerald-400 peer-checked:text-black peer-checked:shadow-[0_0_16px_rgba(52,211,153,0.2)] hover:text-zinc-300 flex items-center justify-center gap-1.5">
                                <i data-lucide="clapperboard" class="w-3.5 h-3.5"></i> Kreator
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="brand" x-model="role" class="peer sr-only">
                            <div class="py-2.5 text-center text-[13px] font-semibold rounded-lg text-zinc-500 transition-all duration-200 peer-checked:bg-white peer-checked:text-black peer-checked:shadow-[0_0_16px_rgba(255,255,255,0.08)] hover:text-zinc-300 flex items-center justify-center gap-1.5">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i> Brand
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-[13px] font-medium text-zinc-400 mb-1.5" x-text="role === 'kreator' ? 'Nama / Channel' : 'Nama Brand'"></label>
                    <input type="text" name="name" id="name" 
                        class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                        placeholder="ClipMasterID" value="{{ old('name') }}" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-[13px] font-medium text-zinc-400 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" 
                        class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                        placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[13px] font-medium text-zinc-400 mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" id="password" 
                            class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                            placeholder="Min. 8 karakter" required>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[13px] font-medium text-zinc-400 mb-1.5">Konfirmasi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-200" 
                            placeholder="Ulangi sandi" required>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-1">
                    <button type="submit" x-show="role === 'kreator'"
                        class="w-full flex justify-center items-center gap-2 py-3 rounded-xl text-sm font-bold text-black bg-emerald-400 hover:bg-emerald-300 transition-all duration-200 shadow-[0_0_24px_rgba(52,211,153,0.2)] hover:shadow-[0_0_32px_rgba(52,211,153,0.35)] active:scale-[0.98]">
                        Daftar Sebagai Kreator <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                    <button type="submit" x-show="role === 'brand'" style="display: none;"
                        class="w-full flex justify-center items-center gap-2 py-3 rounded-xl text-sm font-bold text-black bg-white hover:bg-zinc-200 transition-all duration-200 shadow-[0_0_24px_rgba(255,255,255,0.08)] hover:shadow-[0_0_32px_rgba(255,255,255,0.15)] active:scale-[0.98]">
                        Daftar Sebagai Brand <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <p class="mt-8 text-[11px] text-zinc-700 text-center">
                &copy; {{ date('Y') }} ClipHub Inc. &mdash; <a href="#" class="underline decoration-zinc-800 underline-offset-2 hover:text-zinc-400 transition-colors">Persyaratan Layanan</a>
            </p>
        </div>
    </div>

    <!-- Mobile: Link masuk -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-neutral-950/90 backdrop-blur-lg border-t border-white/[0.05] p-4 text-center z-50">
        <p class="text-sm text-zinc-500">
            Sudah punya akun? 
            <a href="/login" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">Masuk Disini</a>
        </p>
    </div>
</div>
@endsection
