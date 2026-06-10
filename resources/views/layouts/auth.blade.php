<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Autentikasi') - ClipHub</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        :root {
            --auth-slide-duration: 560ms;
            --auth-slide-ease: cubic-bezier(0.22, 1, 0.36, 1);
        }

        #auth-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background:
                linear-gradient(115deg, rgba(16,185,129,0.05), transparent 32%),
                linear-gradient(245deg, rgba(20,184,166,0.05), transparent 38%),
                #0a0a0a;
        }

        #page-content {
            width: 100%;
            min-height: 100vh;
            transition: transform var(--auth-slide-duration) var(--auth-slide-ease), opacity 240ms ease;
            will-change: transform;
            backface-visibility: hidden;
        }

        #page-content.sliding-left {
            transform: translate3d(-100%, 0, 0);
        }

        #page-content.sliding-right {
            transform: translate3d(100%, 0, 0);
        }

        #page-content.reset {
            transition: none;
            transform: translate3d(0, 0, 0);
        }

        #next-page-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100vh;
            transition: transform var(--auth-slide-duration) var(--auth-slide-ease), opacity 240ms ease;
            will-change: transform;
            backface-visibility: hidden;
            z-index: 2;
        }

        #next-page-content.from-right {
            transform: translate3d(100%, 0, 0);
        }

        #next-page-content.from-left {
            transform: translate3d(-100%, 0, 0);
        }

        #next-page-content.slide-in {
            transform: translate3d(0, 0, 0);
        }

        .transitioning {
            pointer-events: none;
            cursor: progress;
        }

        .auth-shell {
            position: relative;
            isolation: isolate;
        }

        .auth-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.55;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: linear-gradient(to bottom, transparent, black 16%, black 82%, transparent);
        }

        .auth-form-panel,
        .auth-visual-panel {
            position: relative;
            z-index: 1;
        }

        .auth-card {
            animation: authCardIn 760ms var(--auth-slide-ease) both;
        }

        .auth-visual-content {
            animation: authVisualIn 860ms var(--auth-slide-ease) 90ms both;
        }

        .auth-float {
            animation: authFloat 6.5s ease-in-out infinite;
        }

        .auth-stagger > * {
            animation: authItemIn 620ms var(--auth-slide-ease) both;
        }

        .auth-stagger > *:nth-child(1) { animation-delay: 120ms; }
        .auth-stagger > *:nth-child(2) { animation-delay: 180ms; }
        .auth-stagger > *:nth-child(3) { animation-delay: 240ms; }
        .auth-stagger > *:nth-child(4) { animation-delay: 300ms; }
        .auth-stagger > *:nth-child(5) { animation-delay: 360ms; }
        .auth-stagger > *:nth-child(6) { animation-delay: 420ms; }
        .auth-stagger > *:nth-child(7) { animation-delay: 480ms; }

        #auth-container.is-sliding .auth-card,
        #auth-container.is-sliding .auth-visual-content,
        #auth-container.is-sliding .auth-stagger > * {
            animation: none !important;
        }

        .auth-link-pulse {
            position: relative;
            overflow: hidden;
        }

        .auth-link-pulse::after {
            content: "";
            position: absolute;
            inset: -2px;
            background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.28) 45%, transparent 70%);
            transform: translateX(-130%);
            transition: transform 680ms var(--auth-slide-ease);
        }

        .auth-link-pulse:hover::after {
            transform: translateX(130%);
        }

        @keyframes authCardIn {
            from {
                opacity: 0;
                transform: translateY(22px) scale(0.985);
                filter: blur(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes authVisualIn {
            from {
                opacity: 0;
                transform: translateY(28px);
                filter: blur(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
        }

        @keyframes authItemIn {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes authFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @media (max-width: 1024px) {
            #page-content,
            #next-page-content {
                transition-duration: 420ms;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #page-content,
            #next-page-content,
            .auth-card,
            .auth-visual-content,
            .auth-stagger > *,
            .auth-float {
                animation: none !important;
                transition-duration: 1ms !important;
            }
        }
    </style>
</head>
<body class="bg-neutral-950 text-white antialiased min-h-screen selection:bg-emerald-500/20 selection:text-emerald-300" 
      x-data="pageTransition()" 
      x-init="init()">

    <div id="auth-container">
        <div id="page-content">
            @yield('body')
        </div>
        <div id="next-page-content" style="display: none;"></div>
    </div>

    <script>
        // Smooth Page Transition Controller
        function pageTransition() {
            return {
                isTransitioning: false,
                
                init() {
                    this.setupPageTransitions();
                },
                
                setupPageTransitions() {
                    // Intercept navigation links for auth pages ONLY (not forms)
                    document.addEventListener('click', (e) => {
                        const link = e.target.closest('a[href]');
                        
                        // Make sure it's a link and not inside a form or button
                        if (link && !e.target.closest('form') && !e.target.closest('button[type="submit"]')) {
                            if (this.shouldTransition(link)) {
                                e.preventDefault();
                                const href = link.getAttribute('href');
                                this.performSmoothSlide(href);
                            }
                        }
                    });
                    
                    // Ensure forms submit normally
                    document.addEventListener('submit', (e) => {
                        // Let forms submit normally - don't intercept
                        return true;
                    });
                },
                
                shouldTransition(link) {
                    const href = link.getAttribute('href');
                    
                    // Skip if it's a form submit, OAuth, or has data-no-transition
                    if (link.hasAttribute('data-no-transition') || 
                        href.includes('/auth/google') ||
                        href.includes('/auth/') ||
                        href === '/' ||
                        href.startsWith('http') && !href.includes(window.location.hostname)) {
                        return false;
                    }
                    
                    // Only handle auth page transitions (login <-> register only)
                    if (!href || 
                        href.startsWith('#') || 
                        href.startsWith('mailto:') || 
                        href.startsWith('tel:') ||
                        link.hasAttribute('target')) {
                        return false;
                    }
                    
                    // Only transition between /login and /register
                    const currentPath = window.location.pathname;
                    const targetUrl = new URL(href, window.location.origin);
                    const targetPath = targetUrl.pathname;
                    
                    const isCurrentLogin = currentPath === '/login' || currentPath.endsWith('/login');
                    const isCurrentRegister = currentPath === '/register' || currentPath.endsWith('/register');
                    const isTargetLogin = targetPath === '/login' || targetPath.endsWith('/login');
                    const isTargetRegister = targetPath === '/register' || targetPath.endsWith('/register');
                    
                    // Only allow transition between login and register pages
                    return (isCurrentLogin && isTargetRegister) || (isCurrentRegister && isTargetLogin);
                },
                
                async performSmoothSlide(url) {
                    if (this.isTransitioning) return;
                    
                    this.isTransitioning = true;
                    
                    const currentPath = window.location.pathname;
                    const targetPath = new URL(url, window.location.origin).pathname;
                    
                    // Determine slide direction
                    let direction = '';
                    if (currentPath.includes('/login') && targetPath.includes('/register')) {
                        direction = 'right'; // Login to Register: slide right
                    } else if (currentPath.includes('/register') && targetPath.includes('/login')) {
                        direction = 'left'; // Register to Login: slide left
                    }
                    
                    if (!direction) {
                        window.location.href = url;
                        return;
                    }
                    
                    try {
                        const response = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        });
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        const html = await response.text();
                        
                        // Parse the HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const incomingPage = doc.querySelector('#page-content');
                        if (!incomingPage) throw new Error('Auth page content not found');
                        const newContent = incomingPage.innerHTML;
                        
                        // Get containers
                        const currentPage = document.getElementById('page-content');
                        const nextPage = document.getElementById('next-page-content');
                        const container = document.getElementById('auth-container');

                        // Mark as sliding before inserting the next page so enter animations stay paused.
                        container.classList.add('transitioning');
                        container.classList.add('is-sliding');
                        
                        // Setup next page
                        nextPage.innerHTML = newContent;
                        nextPage.style.display = 'block';
                        
                        // Position next page based on direction
                        if (direction === 'right') {
                            nextPage.classList.add('from-right');
                        } else {
                            nextPage.classList.add('from-left');
                        }

                        this.reinitialize(nextPage);
                        
                        // Force reflow
                        nextPage.offsetHeight;
                        
                        // Start slide animation
                        requestAnimationFrame(() => {
                            // Slide current page out
                            if (direction === 'right') {
                                currentPage.classList.add('sliding-left');
                            } else {
                                currentPage.classList.add('sliding-right');
                            }
                            
                            // Slide next page in
                            nextPage.classList.remove('from-right', 'from-left');
                            nextPage.classList.add('slide-in');
                        });
                        
                        // Wait for animation to complete
                        await new Promise(resolve => setTimeout(resolve, window.matchMedia('(max-width: 1024px)').matches ? 420 : 560));
                        
                        // Update browser history and title
                        window.history.pushState({}, '', url);
                        document.title = doc.title;
                        
                        // Swap content
                        currentPage.innerHTML = newContent;
                        currentPage.classList.remove('sliding-left', 'sliding-right');
                        currentPage.classList.add('reset');
                        this.reinitialize(currentPage);
                        
                        // Hide next page
                        nextPage.style.display = 'none';
                        nextPage.innerHTML = '';
                        nextPage.classList.remove('slide-in');
                        
                        // Force reflow and remove reset class
                        currentPage.offsetHeight;
                        currentPage.classList.remove('reset');
                        
                        // Re-initialize icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                        
                        // Remove transitioning class
                        container.classList.remove('transitioning');
                        container.classList.remove('is-sliding');
                        
                    } catch (error) {
                        console.error('Transition error:', error);
                        // Fallback to normal navigation
                        window.location.href = url;
                    } finally {
                        this.isTransitioning = false;
                    }
                },

                reinitialize(root) {
                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(root);
                    }

                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    </script>
</body>
</html>
