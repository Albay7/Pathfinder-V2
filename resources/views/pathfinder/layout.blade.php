<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Pathfinder - Career Guidance Platform')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('pathfinder.index') }}" class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-xl font-bold text-gray-900">Pathfinder</span>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('pathfinder.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.index') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('pathfinder.career-guidance') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.career-guidance*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Career Guidance
                    </a>
                    <a href="{{ route('pathfinder.career-path') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.career-path*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Career Path
                    </a>
                    <a href="{{ route('pathfinder.skill-gap') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pathfinder.skill-gap*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Skill Gap
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
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
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Register
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600" aria-label="toggle menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50">
                <a href="{{ route('pathfinder.index') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pathfinder.index') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Home
                </a>
                <a href="{{ route('pathfinder.career-guidance') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pathfinder.career-guidance*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Career Guidance
                </a>
                <a href="{{ route('pathfinder.career-path') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pathfinder.career-path*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Career Path
                </a>
                <a href="{{ route('pathfinder.skill-gap') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pathfinder.skill-gap*') ? 'text-blue-600 bg-blue-50' : '' }}">
                    Skill Gap
                </a>
                
                @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('tutorials.index') }}" class="text-gray-700 hover:text-purple-600 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">
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
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 mt-2">
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
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
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
    
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    
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
    
    @stack('scripts')
</body>
</html>