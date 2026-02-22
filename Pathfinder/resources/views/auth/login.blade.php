<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pathfinder Career Guidance</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Use the same CSS as main layout -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        /* Additional custom styles for auth pages */
        body { min-width: 1024px; }

        /* Custom gradient for left panel */
        .bg-gradient-custom {
            background: linear-gradient(135deg, #13264D 0%, #5AA7C6 100%);
        }

        /* Icon backgrounds */
        .icon-bg {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .icon-bg-card {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Ensure dropdown works properly */
        .group:hover > div.absolute {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Register button */
        .register-btn {
            background-color: #5AA7C6;
            color: white;
            display: inline-block;
        }
        .register-btn:hover {
            background-color: #13264D;
        }

        /* Primary action button styling */
        .primary-btn {
            background-color: #5AA7C6;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .primary-btn:hover {
            background-color: #13264D;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .primary-btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(90, 167, 198, 0.35);
        }
    </style>
    <script src="{{ asset('js/page-transitions.js') }}"></script>
</head>
<body class="h-screen bg-white flex flex-col min-w-[1024px]">
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
                    <a href="{{ route('pathfinder.home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Home
                    </a>

                    <!-- Explore Dropdown -->
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Explore
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-xl transition-all duration-200" style="opacity: 0; visibility: hidden; pointer-events: none; z-index: 50;">
                            <a href="{{ route('pathfinder.career-guidance') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 first:rounded-t-md transition-colors">
                                Career Guidance
                            </a>
                            <a href="{{ route('pathfinder.career-path') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Career Path
                            </a>
                            <a href="{{ route('pathfinder.skill-gap') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                Skill Gap
                            </a>
                            <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 last:rounded-b-md transition-colors">
                                MBTI Assessment
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="register-btn px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 flex">
        <!-- Left Panel -->
        <div class="w-1/2 bg-gradient-custom bg-gradient-to-br from-blue-zodiac to-fountain-blue flex items-center justify-center p-12">
            <div class="max-w-md text-center text-white">
                <!-- Icon -->
                <div class="mx-auto w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center mb-8">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl font-bold mb-4">Welcome Back</h1>
                <h2 class="text-2xl font-semibold mb-6 text-white/90">Continue Your Career Journey</h2>
                <p class="text-lg text-white/80 mb-12 leading-relaxed">
                    Sign in to access your personalized career guidance dashboard and continue building your professional path.
                </p>

                <!-- Feature Cards -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Personalized Guidance -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-pink-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 7.5V9M15 11.5C15.8 11.5 16.5 12.2 16.5 13S15.8 14.5 15 14.5 13.5 13.8 13.5 13 14.2 11.5 15 11.5M5 7V9L11 8.5V7L5 7ZM11 11.5C11.8 11.5 12.5 12.2 12.5 13S11.8 14.5 11 14.5 9.5 13.8 9.5 13 10.2 11.5 11 11.5M12 15C12 16.66 10.66 18 9 18S6 16.66 6 15H12ZM18 15C18 16.66 16.66 18 15 18S12 16.66 12 15H18Z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Personalized</h3>
                        <p class="text-sm text-white/70">Guidance</p>
                    </div>

                    <!-- Skills Assessment -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-blue-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22 22 17.52 22 12 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Skills Assessment</h3>
                        <p class="text-sm text-white/70">Evaluation</p>
                    </div>

                    <!-- Career Mapping -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-teal-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.5 3L20.34 3.03L15 5.1L9 3L3.36 4.9C3.15 4.97 3 5.15 3 5.38V20.5C3 20.78 3.22 21 3.5 21L3.66 20.97L9 18.9L15 21L20.64 19.1C20.85 19.03 21 18.85 21 18.62V3.5C21 3.22 20.78 3 20.5 3ZM10 5.47L14 6.87V18.53L10 17.13V5.47ZM5 6.46L8 5.45V17.15L5 18.31V6.46ZM19 17.54L16 18.55V6.86L19 5.7V17.54Z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Career Mapping</h3>
                        <p class="text-sm text-white/70">Planning</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-1/2 bg-blue-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Login Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                        <p class="text-gray-600">Sign in to your account</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Email Address"
                            >
                            @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Password"
                            >
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 text-fountain-blue focus:ring-fountain-blue border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="link text-sm">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" class="w-full primary-btn py-3 px-4 rounded-lg font-semibold transition-all duration-200">
                            Sign in
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="link font-medium">
                                Sign up
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
