<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Pathfinder Career Guidance</title>

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
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl font-bold mb-4">Set New Password</h1>
                <h2 class="text-2xl font-semibold mb-6 text-white/90">Almost There!</h2>
                <p class="text-lg text-white/80 mb-12 leading-relaxed">
                    Choose a strong password to secure your account. Once reset, you'll be able to log in with your new credentials right away.
                </p>

                <!-- Feature Cards -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-pink-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Strong Password</h3>
                        <p class="text-sm text-white/70">Security First</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-blue-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Account Safe</h3>
                        <p class="text-sm text-white/70">Protected</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105 cursor-pointer">
                        <div class="w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center mx-auto mb-3 hover:bg-teal-400 transition-colors duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold mb-1">Ready to Login</h3>
                        <p class="text-sm text-white/70">Instant Access</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Reset Password Form -->
        <div class="w-1/2 bg-blue-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Set New Password</h2>
                        <p class="text-gray-600">Choose a strong password for your account</p>
                    </div>

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Email Address"
                            >
                            @error('email')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="New Password"
                            >
                            @error('password')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Confirm Password"
                            >
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Reset Password Button -->
                        <button type="submit" class="w-full primary-btn py-3 px-4 rounded-lg font-semibold transition-all duration-200">
                            Reset Password
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
