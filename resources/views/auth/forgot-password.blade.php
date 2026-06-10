@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('body')
<div class="auth-shell min-h-screen flex">
    <div class="auth-form-panel w-full lg:w-1/2 flex items-center justify-center px-6 sm:px-12 py-12 relative">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[400px] bg-emerald-500/[0.04] rounded-full blur-[120px]"></div>
        </div>

        <div class="auth-card auth-stagger w-full max-w-sm relative z-10">
            <a href="/" class="flex items-center gap-2.5 mb-10 transition-all duration-500 hover:scale-105">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center shadow-[0_0_20px_rgba(52,211,153,0.25)]">
                    <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
                </div>
                <span class="font-extrabold text-lg tracking-tight text-white">Clip<span class="text-emerald-400">Hub</span></span>
            </a>

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Reset Password</h1>
                <p class="text-zinc-500 text-sm">Masukkan email akun Anda. Kami akan mengirim link untuk membuat password baru.</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                @if (session('status'))
                <div class="bg-emerald-500/[0.08] border border-emerald-500/20 text-emerald-300 text-[13px] rounded-xl p-3 flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-500/[0.08] border border-red-500/20 text-red-400 text-[13px] rounded-xl p-3 flex items-start gap-2 animate-shake">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <div class="group">
                    <label for="email" class="block text-[13px] font-medium text-zinc-400 mb-1.5 transition-colors duration-300 group-focus-within:text-emerald-400">Email Pemulihan</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="block w-full px-4 py-3 border border-white/[0.07] rounded-xl bg-white/[0.03] text-white text-sm placeholder-zinc-600 focus:outline-none focus:border-emerald-500/40 focus:shadow-[0_0_0_3px_rgba(52,211,153,0.08)] transition-all duration-300 hover:border-white/[0.12]"
                        placeholder="nama@email.com" required autofocus>
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 rounded-xl text-sm font-bold text-black bg-emerald-400 hover:bg-emerald-300 transition-all duration-300 shadow-[0_0_24px_rgba(52,211,153,0.2)] hover:shadow-[0_0_32px_rgba(52,211,153,0.35)] active:scale-[0.98] hover:scale-[1.02] group">
                    Kirim Link Reset <i data-lucide="send" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"></i>
                </button>

                <div class="rounded-xl border border-white/[0.06] bg-white/[0.03] p-3 text-[12px] text-zinc-500 leading-relaxed">
                    Akun yang dibuat lewat Google tetap masuk menggunakan tombol Google. Akun lama tanpa email pemulihan perlu menghubungi admin untuk reset manual.
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-emerald-400/80 hover:text-emerald-400 transition-colors duration-300">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke login
                </a>
            </div>
        </div>
    </div>

    <div class="auth-visual-panel hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:40px_40px] opacity-30"></div>
        <div class="absolute top-16 right-16 w-40 h-40 border border-white/10 rounded-full"></div>
        <div class="absolute bottom-20 left-16 w-60 h-60 border border-white/10 rounded-full"></div>
        <div class="auth-visual-content relative z-10 text-center px-12 max-w-md">
            <div class="auth-float w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center mx-auto mb-8 shadow-lg">
                <i data-lucide="key-round" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="text-4xl font-extrabold text-white tracking-tight mb-4 leading-tight">Aman, pelan-pelan.</h2>
            <p class="text-white/80 text-base leading-relaxed">Link reset hanya dikirim ke email akun yang terdaftar di ClipHub.</p>
        </div>
    </div>
</div>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>
@endsection
