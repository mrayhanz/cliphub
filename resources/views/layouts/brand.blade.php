<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Brand ClipHub</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.07); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16,185,129,0.35); }
        main > * { animation: fadeInUp 0.45s ease both; }
        main > *:nth-child(1) { animation-delay: 0ms; }
        main > *:nth-child(2) { animation-delay: 60ms; }
        main > *:nth-child(3) { animation-delay: 120ms; }
        main > *:nth-child(4) { animation-delay: 180ms; }
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#060606] text-slate-50 antialiased h-full" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"
        @click="sidebarOpen = false" style="display: none;">
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- ===== SIDEBAR ===== -->
        @include('brand.partials.sidebar')

        <!-- ===== MAIN CONTENT ===== -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- ===== TOP NAVBAR ===== -->
            @include('brand.partials.navbar')

            <!-- ===== PAGE CONTENT ===== -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
    @stack('scripts')

    {{-- GLOBAL CONFIRMATION MODAL --}}
    <div x-data="{
        open: false,
        title: '',
        message: '',
        action: '',
        method: 'POST',
        buttonText: 'Ya, Lanjutkan',
        buttonClass: 'bg-emerald-500 hover:bg-emerald-400 text-black'
    }" 
    x-show="open"
    x-on:open-confirm-modal.window="
        open = true;
        title = $event.detail.title;
        message = $event.detail.message;
        action = $event.detail.action;
        method = $event.detail.method || 'POST';
        buttonText = $event.detail.buttonText || 'Ya, Lanjutkan';
        buttonClass = $event.detail.buttonClass || 'bg-emerald-500 hover:bg-emerald-400 text-black';
    "
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/75 backdrop-blur-md" 
    x-cloak 
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95">
        <div @click.away="open = false" class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-sm w-full shadow-2xl relative space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-black text-white uppercase tracking-widest" x-text="title"></h3>
            </div>
            
            <div class="text-xs text-slate-400 leading-relaxed" x-html="message"></div>
            
            <div class="flex items-center justify-end gap-3 pt-2">
                <button @click="open = false" type="button" class="px-4 py-2.5 rounded-xl border border-white/5 bg-white/5 text-xs font-extrabold text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                    Batal
                </button>
                <form :action="action" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="_method" :value="method">
                    <button type="submit" :class="buttonClass" class="px-4 py-2.5 rounded-xl text-xs font-black transition-all active:scale-95 flex items-center gap-1.5">
                        <span x-text="buttonText"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
