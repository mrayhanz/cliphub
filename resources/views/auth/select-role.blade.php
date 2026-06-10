@extends('layouts.auth')

@section('title', 'Pilih Role')

@section('body')
<div class="min-h-screen flex items-center justify-center px-6 py-12 relative">
    
    <!-- Background subtle -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-500/[0.04] rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-2xl relative z-10">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 mb-10 justify-center">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center shadow-[0_0_20px_rgba(52,211,153,0.25)]">
                <i data-lucide="play" class="w-4 h-4 text-white fill-white"></i>
            </div>
            <span class="font-extrabold text-lg tracking-tight text-white">Clip<span class="text-emerald-400">Hub</span></span>
        </a>

        <!-- Heading -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Selamat Datang!</h1>
            <p class="text-zinc-500 text-sm">Pilih role Anda untuk melanjutkan</p>
        </div>

        <!-- Role Selection Form -->
        <form action="{{ route('auth.google.role.submit') }}" method="POST" x-data="{ selectedRole: '' }">
            @csrf

            @if ($errors->any())
            <div class="bg-red-500/[0.08] border border-red-500/20 text-red-400 text-[13px] rounded-xl p-3 flex items-start gap-2 mb-6">
                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Kreator Card -->
                <div class="relative cursor-pointer group" @click="selectedRole = 'kreator'; $refs.kreatorInput.checked = true;">
                    <input type="radio" name="role" value="kreator" class="sr-only" required x-ref="kreatorInput">
                    <div class="h-full p-6 rounded-2xl border-2 transition-all duration-300 transform"
                         :class="selectedRole === 'kreator' ? 
                            'border-emerald-500 bg-emerald-500/[0.08] shadow-[0_0_24px_rgba(16,185,129,0.25)] scale-[1.02]' : 
                            'border-white/[0.07] bg-white/[0.03] hover:bg-white/[0.05] hover:border-white/[0.12] hover:scale-[1.01]'
                         ">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mb-4 shadow-lg transition-all duration-300"
                                 :class="selectedRole === 'kreator' ? 'shadow-[0_0_20px_rgba(168,85,247,0.5)] scale-110' : 'group-hover:shadow-xl'">
                                <i data-lucide="video" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2 transition-colors duration-300"
                                :class="selectedRole === 'kreator' ? 'text-emerald-400' : 'text-white'">Kreator</h3>
                            <p class="text-sm leading-relaxed transition-colors duration-300"
                               :class="selectedRole === 'kreator' ? 'text-zinc-400' : 'text-zinc-500'">
                                Buat konten video, ikuti campaign, dan dapatkan penghasilan dari karya Anda
                            </p>
                        </div>
                        <!-- Checkmark -->
                        <div class="absolute top-4 right-4 w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                             :class="selectedRole === 'kreator' ? 
                                'border-emerald-500 bg-emerald-500 scale-110' : 
                                'border-white/[0.2]'
                             ">
                            <i data-lucide="check" class="w-4 h-4 text-white transition-opacity duration-300"
                               :class="selectedRole === 'kreator' ? 'opacity-100' : 'opacity-0'"></i>
                        </div>
                        <!-- Selected Indicator -->
                        <div class="absolute inset-0 rounded-2xl pointer-events-none transition-opacity duration-300"
                             :class="selectedRole === 'kreator' ? 'opacity-100' : 'opacity-0'"
                             style="box-shadow: inset 3px 0 0 0 #10b981;"></div>
                    </div>
                </div>

                <!-- Brand Card -->
                <div class="relative cursor-pointer group" @click="selectedRole = 'brand'; $refs.brandInput.checked = true;">
                    <input type="radio" name="role" value="brand" class="sr-only" required x-ref="brandInput">
                    <div class="h-full p-6 rounded-2xl border-2 transition-all duration-300 transform"
                         :class="selectedRole === 'brand' ? 
                            'border-emerald-500 bg-emerald-500/[0.08] shadow-[0_0_24px_rgba(16,185,129,0.25)] scale-[1.02]' : 
                            'border-white/[0.07] bg-white/[0.03] hover:bg-white/[0.05] hover:border-white/[0.12] hover:scale-[1.01]'
                         ">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mb-4 shadow-lg transition-all duration-300"
                                 :class="selectedRole === 'brand' ? 'shadow-[0_0_20px_rgba(59,130,246,0.5)] scale-110' : 'group-hover:shadow-xl'">
                                <i data-lucide="briefcase" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2 transition-colors duration-300"
                                :class="selectedRole === 'brand' ? 'text-emerald-400' : 'text-white'">Brand</h3>
                            <p class="text-sm leading-relaxed transition-colors duration-300"
                               :class="selectedRole === 'brand' ? 'text-zinc-400' : 'text-zinc-500'">
                                Buat campaign marketing, kelola konten, dan jangkau audience lebih luas
                            </p>
                        </div>
                        <!-- Checkmark -->
                        <div class="absolute top-4 right-4 w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                             :class="selectedRole === 'brand' ? 
                                'border-emerald-500 bg-emerald-500 scale-110' : 
                                'border-white/[0.2]'
                             ">
                            <i data-lucide="check" class="w-4 h-4 text-white transition-opacity duration-300"
                               :class="selectedRole === 'brand' ? 'opacity-100' : 'opacity-0'"></i>
                        </div>
                        <!-- Selected Indicator -->
                        <div class="absolute inset-0 rounded-2xl pointer-events-none transition-opacity duration-300"
                             :class="selectedRole === 'brand' ? 'opacity-100' : 'opacity-0'"
                             style="box-shadow: inset 3px 0 0 0 #10b981;"></div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full flex justify-center items-center gap-2 py-3.5 rounded-xl text-sm font-bold text-black transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100"
                :class="selectedRole ? 
                    'bg-emerald-400 hover:bg-emerald-300 shadow-[0_0_24px_rgba(52,211,153,0.2)] hover:shadow-[0_0_32px_rgba(52,211,153,0.35)]' : 
                    'bg-zinc-700 cursor-not-allowed'
                "
                :disabled="!selectedRole"
                x-data="{ submitting: false }"
                @click="submitting = true"
                :disabled="submitting || !selectedRole">
                <span x-show="!submitting">
                    <span x-show="!selectedRole">Pilih Role Terlebih Dahulu</span>
                    <span x-show="selectedRole">Lanjutkan</span>
                </span>
                <span x-show="submitting" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
                <i data-lucide="arrow-right" class="w-4 h-4" x-show="!submitting && selectedRole"></i>
            </button>
        </form>

        <!-- Footer -->
        <p class="mt-8 text-[11px] text-zinc-700 text-center">
            &copy; {{ date('Y') }} ClipHub Inc.
        </p>
    </div>
</div>
@endsection
