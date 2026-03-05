<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pathfinder - Career Guidance Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Mobile Enhancement Styles -->
    <style>
        /* Mobile-first responsive improvements */
        @media (max-width: 767px) {
            /* Improve form input touch targets */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="file"],
            select,
            textarea {
                min-height: 44px !important;
                font-size: 16px !important; /* Prevents zoom on iOS */
            }

            /* Better button touch targets */
            button,
            .btn,
            a[role="button"] {
                min-height: 44px !important;
                padding: 12px 16px !important;
            }

            /* Improved radio button and checkbox areas */
            input[type="radio"],
            input[type="checkbox"] {
                transform: scale(1.2);
                margin: 8px;
            }

            /* Better card spacing on mobile */
            .card, .bg-white.rounded-xl {
                margin-bottom: 1rem;
            }

            /* Improved question layout for questionnaires */
            .question-option {
                margin-bottom: 0.75rem !important;
                padding: 1rem !important;
            }

            /* Better typography hierarchy */
            h1 { font-size: 1.875rem !important; line-height: 1.2 !important; }
            h2 { font-size: 1.5rem !important; line-height: 1.3 !important; }
            h3 { font-size: 1.25rem !important; line-height: 1.4 !important; }

            /* Improved spacing for mobile */
            .container, .max-w-7xl, .max-w-4xl {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Better mobile menu */
            .mobile-menu {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            /* Smoother scrolling */
            html {
                scroll-behavior: smooth;
            }

            /* Prevent horizontal scroll */
            body {
                overflow-x: hidden;
            }
        }

        /* Touch improvements for all devices */
        button:active,
        .btn:active,
        a[role="button"]:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }

        /* Better focus states for accessibility */
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible,
        a:focus-visible {
            outline: 2px solid #5AA7C6 !important;
            outline-offset: 2px !important;
        }

        /* Navigation link styles */
        .nav-link.nav-active {
            color: #5AA7C6 !important;
            background-color: #EFF6FF !important;
        }

        .nav-link.nav-inactive {
            color: #374151 !important;
            background-color: transparent !important;
        }

        .nav-link:hover {
            color: #5AA7C6 !important;
            background-color: #EFF6FF !important;
        }

        /* Dropdown styles - button hover */
        .relative.group:hover > div.absolute {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Keep dropdown visible when hovering over the menu itself */
        .relative.group > div.absolute:hover {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Extend hover zone - creates invisible area between button and dropdown */
        .relative.group > div.absolute::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            pointer-events: auto;
        }

        /* Make sure dropdown items stay interactive */
        .relative.group:hover > div.absolute,
        .relative.group > div.absolute:hover {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            z-index: 50 !important;
        }

        /* Ensure dropdown items are always clickable when menu is visible */
        .relative.group:hover > div.absolute a,
        .relative.group > div.absolute:hover a {
            pointer-events: auto !important;
        }

        /* Mobile navigation styles */
        .mobile-nav-link.nav-active {
            color: #5AA7C6 !important;
            background-color: #EFF6FF !important;
        }

        .mobile-nav-link.nav-inactive {
            color: #374151 !important;
            background-color: transparent !important;
        }

        .mobile-nav-link:hover {
            color: #5AA7C6 !important;
            background-color: #EFF6FF !important;
        }

        /* Register button styles */
        .register-btn {
            background-color: #5AA7C6 !important;
            color: white !important;
        }

        .register-btn:hover {
            background-color: #13264D !important;
        }

        /* Enhanced mobile navigation - Fixed and improved */
        .mobile-menu {
            display: none;
            position: relative;
            z-index: 50;
            background: white;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .mobile-menu.hidden {
            display: none !important;
        }

        .mobile-menu:not(.hidden) {
            display: block !important;
        }

        @media (max-width: 767px) {
            nav {
                border-bottom: 1px solid #e5e7eb;
            }

            .mobile-menu a {
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                min-height: 48px !important;
                width: 100% !important;
                text-decoration: none !important;
                padding: 12px 16px !important;
                transition: all 0.2s ease !important;
            }

            .mobile-menu-button {
                cursor: pointer !important;
                -webkit-tap-highlight-color: transparent !important;
                border: none !important;
                background: none !important;
                outline: none !important;
            }

            .mobile-menu-button:active {
                transform: scale(0.95) !important;
                transition: transform 0.1s ease !important;
            }

            .mobile-menu-button:hover {
                background-color: rgba(90, 167, 198, 0.1) !important;
                border-radius: 4px !important;
            }
        }

        @media (min-width: 768px) {
            .mobile-menu {
                display: none !important;
            }
        }

        /* Ensure gradients work properly */
        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)) !important;
        }

        /* Force proper background colors */
        section[style*="background"] {
            background-attachment: scroll !important;
        }

        /* Force responsive behavior with utility classes */
        .responsive-container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
        }

        @media screen and (min-width: 640px) {
            .responsive-container {
                padding: 0 1.5rem !important;
            }
        }

        @media screen and (min-width: 1024px) {
            .responsive-container {
                padding: 0 2rem !important;
            }
        }

        /* Debug helper - remove in production */
        .debug-responsive::before {
            content: 'XS' !important;
            position: fixed !important;
            top: 10px !important;
            right: 10px !important;
            background: red !important;
            color: white !important;
            padding: 4px 8px !important;
            font-size: 12px !important;
            z-index: 9999 !important;
        }

        @media screen and (min-width: 640px) {
            .debug-responsive::before { content: 'SM' !important; background: orange !important; }
        }

        @media screen and (min-width: 768px) {
            .debug-responsive::before { content: 'MD' !important; background: blue !important; }
        }

        @media screen and (min-width: 1024px) {
            .debug-responsive::before { content: 'LG' !important; background: green !important; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('pathfinder.index') }}" class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-7 w-7 md:h-8 md:w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-lg md:text-xl font-bold text-gray-900">Pathfinder</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('pathfinder.home') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.home') ? 'nav-active' : 'nav-inactive' }}">
                        Home
                    </a>

                    <!-- Explore Dropdown -->
                    <div class="relative group">
                        <button class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.career-guidance*') || request()->routeIs('pathfinder.career-path*') || request()->routeIs('pathfinder.skill-gap*') || request()->routeIs('pathfinder.mbti-intro') || request()->routeIs('pathfinder.mbti-questionnaire*') || request()->routeIs('pathfinder.mbti.results*') ? 'nav-active' : 'nav-inactive' }}">
                            Explore
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 w-48 bg-white rounded-md shadow-xl transition-all duration-200" style="top: 100%; opacity: 0; visibility: hidden; pointer-events: none; z-index: 50; margin-top: 0.25rem;">
                            <a href="{{ route('pathfinder.career-guidance') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 first:rounded-t-md transition-colors">
                                Career Guidance
                            </a>
                            <a href="{{ route('pathfinder.career-path') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Career Path
                            </a>
                            <a href="{{ route('pathfinder.skill-gap') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Skill Gap
                            </a>
                            <a href="{{ route('pathfinder.mbti-intro') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 last:rounded-b-md transition-colors">
                                MBTI Assessment
                            </a>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard*') ? 'nav-active' : 'nav-inactive' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('tutorials*') ? 'nav-active' : 'nav-inactive' }}">
                            My Tutorials
                        </a>
                        <div class="relative ml-3">
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="register-btn px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button p-2 text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:rounded-md" aria-label="toggle menu" style="min-height: 44px; min-width: 44px;">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu hidden md:hidden border-t border-gray-200">
            <div class="px-3 pt-3 pb-4 space-y-2 bg-white shadow-inner">
                <a href="{{ route('pathfinder.home') }}" class="mobile-nav-link px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center {{ request()->routeIs('pathfinder.home') ? 'nav-active' : 'nav-inactive' }}" style="min-height: 44px;">
                    Home
                </a>

                <!-- Mobile Explore Menu -->
                <div class="relative">
                    <button class="mobile-nav-link w-full text-left px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center justify-between {{ request()->routeIs('pathfinder.career-guidance*') || request()->routeIs('pathfinder.career-path*') || request()->routeIs('pathfinder.skill-gap*') || request()->routeIs('pathfinder.mbti-intro') || request()->routeIs('pathfinder.mbti-questionnaire*') || request()->routeIs('pathfinder.mbti.results*') ? 'nav-active' : 'nav-inactive' }}" style="min-height: 44px;" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        Explore
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="hidden pl-4 space-y-2 mt-2 border-l-2 border-gray-200">
                        <a href="{{ route('pathfinder.career-guidance') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            Career Guidance
                        </a>
                        <a href="{{ route('pathfinder.career-path') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            Career Path
                        </a>
                        <a href="{{ route('pathfinder.skill-gap') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            Skill Gap
                        </a>
                        <a href="{{ route('pathfinder.mbti-intro') }}" class="block px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            MBTI Assessment
                        </a>
                    </div>
                </div>

                @auth
                        <a href="{{ route('dashboard') }}" class="mobile-nav-link px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center {{ request()->routeIs('dashboard*') ? 'nav-active' : 'nav-inactive' }}" style="min-height: 44px;">
                            Dashboard
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="mobile-nav-link px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 flex items-center {{ request()->routeIs('tutorials*') ? 'nav-active' : 'nav-inactive' }}" style="min-height: 44px;">
                            My Tutorials
                        </a>
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-3 py-2">
                            <span class="text-gray-700 text-sm font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 block w-full text-left px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 rounded-lg text-base font-medium transition-colors duration-200 mt-2 text-center" style="background-color: #5AA7C6; color: white; min-height: 44px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    @if(!View::hasSection('hide_footer'))
    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center">
                        <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold">Pathfinder</span>
                    </div>
                    <p class="mt-4 text-gray-300">
                        Your ultimate career guidance platform. Discover your path, visualize your journey, and bridge your skill gaps.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Features</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>Career Guidance</li>
                        <li>Path Visualization</li>
                        <li>Skill Gap Analysis</li>
                        <li>Personalized Recommendations</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Get Started</h3>
                    <p class="text-gray-300 mb-4">
                        Ready to find your perfect career path? Start with our career guidance tool.
                    </p>
                    <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center px-4 py-2 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                        Get Started
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Pathfinder. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    <!-- Flash Messages Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b'
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
            @endif

            // Handle validation errors
            @if($errors->any())
                let errorMessages = [];
                @foreach($errors->all() as $error)
                    errorMessages.push('{{ $error }}');
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessages.map(msg => `<div class="text-left">• ${msg}</div>`).join(''),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            @endif
        });
    </script>

    @stack('scripts')

    <!-- Force Responsive Behavior Script -->
    <script>
        (function() {
            'use strict';

            // Add debug class to body for responsive testing
            document.body.classList.add('debug-responsive');

            // Force responsive layout on resize
            function forceResponsiveLayout() {
                const viewport = window.innerWidth;
                const body = document.body;

                // Remove all responsive classes first
                body.classList.remove('mobile-view', 'tablet-view', 'desktop-view');

                // Add appropriate class based on viewport
                if (viewport <= 640) {
                    body.classList.add('mobile-view');

                    // Force mobile layout for specific elements
                    document.querySelectorAll('.grid').forEach(grid => {
                        if (grid.classList.contains('md:grid-cols-2') ||
                            grid.classList.contains('md:grid-cols-3') ||
                            grid.classList.contains('lg:grid-cols-3')) {
                            grid.style.gridTemplateColumns = '1fr';
                            grid.style.gap = '1rem';
                        }
                    });

                    // Force button responsiveness
                    document.querySelectorAll('button, .btn, a[href]').forEach(btn => {
                        if (btn.closest('.flex') && btn.closest('.flex').classList.contains('sm:flex-row')) {
                            btn.style.width = '100%';
                            btn.style.marginBottom = '0.75rem';
                        }
                    });

                    // Force hero section responsiveness
                    document.querySelectorAll('h1').forEach(h1 => {
                        if (h1.classList.contains('text-4xl') ||
                            h1.classList.contains('text-5xl') ||
                            h1.classList.contains('text-6xl')) {
                            h1.style.fontSize = '1.875rem';
                            h1.style.lineHeight = '1.2';
                        }
                    });

                } else if (viewport <= 1024) {
                    body.classList.add('tablet-view');

                    // Tablet specific adjustments
                    document.querySelectorAll('.grid').forEach(grid => {
                        if (grid.classList.contains('md:grid-cols-3') || grid.classList.contains('lg:grid-cols-3')) {
                            grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                        }
                    });

                } else {
                    body.classList.add('desktop-view');

                    // Reset desktop styles
                    document.querySelectorAll('.grid').forEach(grid => {
                        grid.style.gridTemplateColumns = '';
                        grid.style.gap = '';
                    });

                    document.querySelectorAll('button, .btn, a[href]').forEach(btn => {
                        btn.style.width = '';
                        btn.style.marginBottom = '';
                    });

                    document.querySelectorAll('h1').forEach(h1 => {
                        h1.style.fontSize = '';
                        h1.style.lineHeight = '';
                    });
                }

                console.log(`Responsive layout applied for viewport: ${viewport}px`);
            }

            // Initial load
            document.addEventListener('DOMContentLoaded', forceResponsiveLayout);

            // On resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(forceResponsiveLayout, 100);
            });

            // Force immediate execution
            forceResponsiveLayout();

        })();

        // Simple and reliable mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing mobile menu...');

            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            console.log('Found elements:', {
                button: !!mobileMenuButton,
                menu: !!mobileMenu
            });

            if (mobileMenuButton && mobileMenu) {
                // Add click event to toggle menu
                mobileMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    console.log('Menu button clicked');

                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                        console.log('Menu opened');
                    } else {
                        mobileMenu.classList.add('hidden');
                        console.log('Menu closed');
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                            console.log('Menu closed by outside click');
                        }
                    }
                });

                // Close menu when clicking menu items
                const menuLinks = mobileMenu.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                        console.log('Menu closed by menu item click');
                    });
                });

                console.log('Mobile menu initialized successfully');
            } else {
                console.error('Mobile menu elements not found');
            }
        });

        // Backup initialization for immediate execution
        if (document.readyState !== 'loading') {
            const event = new Event('DOMContentLoaded');
            document.dispatchEvent(event);
        }        })();
    </script>
</body>
</html>
