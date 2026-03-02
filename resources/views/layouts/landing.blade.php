<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Clipfluence - Platform UGC & Clipper No. 1 di Indonesia</title>
    
    <!-- Memuat aset CSS & JS Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js untuk interaktivitas UI ringan seperti dropdown & mobile menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS.js untuk animasi scroll yang smooth -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons (Ringan dan modern) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        /* Terapkan font Inter ke body */
        body { font-family: 'Inter', sans-serif; }
        
        /* Sembunyikan scrollbar untuk tampilan lebih clean */
        ::-webkit-scrollbar { display: none; } /* Chrome, Safari, dan Opera */
        * { -ms-overflow-style: none; scrollbar-width: none; } /* IE, Edge, dan Firefox */
    </style>
</head>
<body class="bg-black text-slate-50 antialiased selection:bg-brand/30 selection:text-brand-light">

    <!-- Memanggil komponen Navbar Global -->
    @include('landing.partials.navbar')

    <!-- Konten Utama Halaman -->
    <main>
        @yield('content')
    </main>

    <!-- Memanggil komponen Footer Global -->
    @include('landing.partials.footer')

    <!-- Inisialisasi library animasi AOS dan Lucide Icons -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS langsung 
        AOS.init({
            once: false,  // Animasi diputar setiap elemen masuk viewport 
            mirror: true, // Animasi "bermain mundur" saat elemen dilewati agar saat scroll ke atas otomatis masuk lagi
            offset: 50, 
            duration: 800,
            easing: 'ease-out-cubic',
            anchorPlacement: 'top-bottom',
        });
        
        // Render semua icon lucide
        lucide.createIcons();

        // Refresh AOS persis setelah *semua* aset & font beres ter-load
        window.addEventListener('load', function() {
            AOS.refresh();
        });
    </script>
</body>
</html>
