<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pathfinder Career Guidance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-blue-zodiac { background-color: #13264D; }
        .bg-fountain-blue { background-color: #5AA7C6; }
        .text-fountain-blue { color: #5AA7C6; }
        .border-fountain-blue { border-color: #5AA7C6; }
        .focus\:ring-fountain-blue:focus { --tw-ring-color: #5AA7C6; }
        .focus\:border-fountain-blue:focus { border-color: #5AA7C6; }
        .hover\:bg-blue-600:hover { background-color: #2563eb; }
        .focus\:ring-fountain-blue\/30:focus { --tw-ring-color: rgba(90, 167, 198, 0.3); }
    </style>
    <script src="{{ asset('js/page-transitions.js') }}"></script>
</head>
<body class="h-screen bg-white flex flex-col min-w-[1024px]">
    <!-- Navigation Header -->
    <nav class="bg-white border-b border-gray-200 px-8 py-4">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-fountain-blue rounded flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Pathfinder</h2>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6 mr-4">
                <a href="{{ route('pathfinder.index') }}" class="text-gray-600 hover:text-fountain-blue text-sm font-medium transition-colors duration-200">Home</a>
                <a href="{{ route('pathfinder.career-guidance') }}" class="text-gray-600 hover:text-fountain-blue text-sm font-medium transition-colors duration-200">Career Guidance</a>
                <a href="{{ route('pathfinder.career-path') }}" class="text-gray-600 hover:text-fountain-blue text-sm font-medium transition-colors duration-200">Career Path</a>
                <a href="{{ route('pathfinder.skill-gap') }}" class="text-gray-600 hover:text-fountain-blue text-sm font-medium transition-colors duration-200">Skill Gap</a>
                <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="text-gray-600 hover:text-fountain-blue text-sm font-medium transition-colors duration-200">MBTI Assessment</a>
                <a href="#" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-fountain-blue hover:text-white transition-all duration-200 hover:shadow-sm">Login</a>
                <a href="{{ route('register') }}" class="bg-fountain-blue text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-zodiac transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">Register</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 flex">
        <!-- Left Panel -->
        <div class="w-1/2 bg-gradient-to-br from-blue-zodiac to-fountain-blue flex items-center justify-center p-12">
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
                                    class="w-4 h-4 text-fountain-blue bg-gray-100 border-gray-300 rounded focus:ring-fountain-blue focus:ring-2"
                                >
                                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-fountain-blue hover:text-blue-zodiac transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Sign In Button -->
                        <button
                            type="submit"
                            class="w-full bg-fountain-blue text-white py-3 px-4 rounded-lg font-medium shadow-lg hover:shadow-xl hover:bg-blue-600 focus:ring-4 focus:ring-fountain-blue/30 transition-all duration-200 transform hover:-translate-y-0.5"
                        >
                            Sign In
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-fountain-blue hover:text-blue-zodiac font-medium transition-colors">
                                Create one here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
