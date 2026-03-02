<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Autentikasi') - Clipfluence</title>
    
    <!-- Memuat aset CSS & JS Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-black text-slate-50 antialiased min-h-screen relative flex items-center justify-center selection:bg-brand/30 selection:text-brand-light">

    <!-- Ornamen Background Geometris Auth -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Grid pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_70%,transparent_100%)] opacity-20"></div>
        <!-- Glow top left -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand/30 rounded-full blur-[100px] mix-blend-screen"></div>
        <!-- Glow bottom right -->
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-brand/10 rounded-full blur-[100px] mix-blend-screen"></div>
    </div>

    <!-- Tombol kembali ke Landing -->
    {{-- <a href="/" class="fixed top-6 left-6 z-20 flex items-center gap-2 text-slate-400 hover:text-white transition-colors pt-2 pl-2">
        <i data-lucide="arrow-left" class="w-5 h-5"></i> <span class="text-sm font-medium">Kembali</span>
    </a> --}}

    <!-- Main Content Container -->
    <main class="relative z-10 w-full max-w-md px-6 py-12">
        <!-- Logo -->
        <div class="flex flex-col items-center justify-center mb-10 text-center">
            <a href="/" class="flex flex-col items-center gap-3">
                <span class="font-bold text-2xl tracking-tight text-white mt-1">
                    Clip<span class="text-brand-light">fluence</span>
                </span>
            </a>
            @yield('subtitle')
        </div>

        <!-- Form Card (Glassmorphism) -->
        <div class="bg-neutral-900/60 backdrop-blur-xl border border-neutral-800/60 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            <!-- Glow halus di dalam card -->
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-brand/50 to-transparent"></div>
            
            @yield('content')
        </div>
        
        <!-- Footer Auth -->
        <div class="mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Clipfluence Inc. Melanjutkan berarti menyetujui <a href="#" class="text-slate-400 hover:text-white underline decoration-neutral-700">Persyaratan Layanan</a> kami.
        </div>
    </main>

    <!-- Init Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
