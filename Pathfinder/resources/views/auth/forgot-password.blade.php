<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Pathfinder Career Guidance</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Use the same CSS as main layout -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        body { min-width: 1024px; }

        .bg-gradient-custom {
            background: linear-gradient(135deg, #13264D 0%, #5AA7C6 100%);
        }

        .group:hover > div.absolute {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        .register-btn {
            background-color: #5AA7C6;
            color: white;
            display: inline-block;
        }
        .register-btn:hover {
            background-color: #13264D;
        }

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
                    <a href="{{ route('pathfinder.index') }}" class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-7 w-7 md:h-8 md:w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-lg md:text-xl font-bold text-gray-900">Pathfinder</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('pathfinder.home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Home
                    </a>

                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            Explore
                        </button>

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
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl font-bold mb-4">Forgot Password?</h1>
                <h2 class="text-2xl font-semibold mb-6 text-white/90">No Worries, We've Got You</h2>
                <p class="text-lg text-white/80 mb-12 leading-relaxed">
                    Enter your email address and we'll send you a secure link to reset your password and regain access to your career guidance dashboard.
                </p>

                <!-- Feature Cards -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-pink-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Check Email</h3>
                        <p class="text-sm text-white/70">Reset Link</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-blue-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">New Password</h3>
                        <p class="text-sm text-white/70">Secure Reset</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-teal-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Access Restored</h3>
                        <p class="text-sm text-white/70">Welcome Back</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Forgot Password Form -->
        <div class="w-1/2 bg-blue-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
                        <p class="text-gray-600">Enter your email to receive a password reset link</p>
                    </div>

                    <!-- Session Status (success message after sending link) -->
                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-lg text-sm font-medium" style="background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                autocomplete="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Email Address"
                            >
                            @error('email')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Send Reset Link Button -->
                        <button type="submit" class="w-full primary-btn py-3 px-4 rounded-lg font-semibold transition-all duration-200">
                            Send Password Reset Link
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Remember your password?
                            <a href="{{ route('login') }}" class="text-fountain-blue hover:text-blue-zodiac font-medium transition-colors">
                                Back to Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
