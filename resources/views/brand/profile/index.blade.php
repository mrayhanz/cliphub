@extends('layouts.brand')

@section('title', 'Profil Brand')
@section('page_title', 'Profil Brand')

@section('content')
<div class="flex flex-col gap-5 pb-8 min-h-[calc(100vh-96px)]" x-data="{ 
    activeTab: '{{ $errors->hasAny(['current_password', 'password', 'password_confirmation']) ? 'password' : ($errors->any() ? 'edit' : 'info') }}',
    logoPreviewUrl: '{{ $profile?->logo_path ? route('media.public', $profile->logo_path) : '' }}'
}">
    <!-- Header Section -->
    <div class="hero-card p-5 lg:p-7 animate-fade-in-up shrink-0">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold mb-3" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25); color: #34d399;">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    Keamanan & Profil
                </div>
                <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-snug">
                    Pengaturan Profil
                </h1>
                <p class="text-emerald-200/70 text-base mt-2 leading-relaxed max-w-xl">
                    Kelola informasi instansi brand Anda, PIC operasional, dan pastikan kredensial keamanan akun Anda tetap terlindungi.
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Success Notification -->
    @if (session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 px-4 py-3.5 text-xs font-semibold text-emerald-400 animate-fade-in-up shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Content Sections -->
    <!-- 1. Read-Only Info View -->
    <div x-show="activeTab === 'info'" class="flex-1 flex flex-col min-h-0">
        @include('brand.profile.partials.info')
    </div>

    <!-- 2. Edit Profile Form View -->
    <div x-show="activeTab === 'edit'" style="display: none;" class="flex-1 flex flex-col min-h-0">
        @include('brand.profile.partials.edit')
    </div>

    <!-- 3. Ganti Password Form View -->
    <div x-show="activeTab === 'password'" style="display: none;" class="flex-1 flex flex-col min-h-0">
        @include('brand.profile.partials.password')
    </div>

</div>
@endsection
