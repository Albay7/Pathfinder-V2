<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pathfinder Career Guidance</title>
    <style>
        /* Reset and base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        
        /* Custom colors */
        .bg-blue-zodiac { background-color: #13264D; }
        .bg-fountain-blue { background-color: #5AA7C6; }
        .text-fountain-blue { color: #5AA7C6; }
        .border-fountain-blue { border-color: #5AA7C6; }
        
        /* Layout utilities */
        .h-screen { height: 100vh; }
        .bg-white { background-color: white; }
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .flex-1 { flex: 1; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .min-w-1024 { min-width: 1024px; }
        .w-1\/2 { width: 50%; }
        .w-full { width: 100%; }
        .max-w-md { max-width: 28rem; }
        .max-w-7xl { max-width: 80rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-5 { width: 1.25rem; }
        .h-5 { height: 1.25rem; }
        .w-16 { width: 4rem; }
        .h-16 { height: 4rem; }
        .w-12 { width: 3rem; }
        .h-12 { height: 3rem; }
        .w-6 { width: 1.5rem; }
        .h-6 { height: 1.5rem; }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-12 { margin-bottom: 3rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mr-4 { margin-right: 1rem; }
        .p-12 { padding: 3rem; }
        .p-4 { padding: 1rem; }
        .px-8 { padding-left: 2rem; padding-right: 2rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-6 > * + * { margin-left: 1.5rem; }
        .space-x-4 > * + * { margin-left: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .hidden { display: none; }
        .md\:flex { display: flex; }
        .grid { display: grid; }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        .text-center { text-align: center; }
        .leading-relaxed { line-height: 1.625; }
        
        /* Spacing */
        .p-12 { padding: 3rem; }
        .p-8 { padding: 2rem; }
        .p-4 { padding: 1rem; }
        .px-8 { padding-left: 2rem; padding-right: 2rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-12 { margin-bottom: 3rem; }
        .mt-6 { margin-top: 1.5rem; }
        .ml-2 { margin-left: 0.5rem; }
        .mr-4 { margin-right: 1rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-6 > * + * { margin-left: 1.5rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .gap-4 { gap: 1rem; }
        
        /* Typography */
        .text-base { font-size: 1rem; }
        .text-xl { font-size: 1.25rem; }
        .text-2xl { font-size: 1.5rem; }
        .text-4xl { font-size: 2.25rem; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: 0.875rem; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .font-medium { font-weight: 500; }
        .text-white { color: white; }
        .text-gray-900 { color: #111827; }
        .text-gray-700 { color: #374151; }
        .text-gray-600 { color: #4B5563; }
        .text-red-500 { color: #EF4444; }
        .text-center { text-align: center; }
        .leading-relaxed { line-height: 1.625; }
        
        /* Backgrounds and gradients */
        .bg-gray-100 { background-color: #F3F4F6; }
        .bg-blue-50 { background-color: #EFF6FF; }
        .bg-gradient-to-br { background: linear-gradient(to bottom right, #13264D, #5AA7C6); }
        .from-blue-zodiac { --tw-gradient-from: #13264D; }
        .to-fountain-blue { --tw-gradient-to: #5AA7C6; }
        .bg-white\/10 { background-color: rgba(255, 255, 255, 0.1); }
        .bg-white\/20 { background-color: rgba(255, 255, 255, 0.2); }
        .text-white\/90 { color: rgba(255, 255, 255, 0.9); }
        .text-white\/80 { color: rgba(255, 255, 255, 0.8); }
        .text-white\/70 { color: rgba(255, 255, 255, 0.7); }
        .backdrop-blur-sm { backdrop-filter: blur(4px); }
        .bg-pink-500 { background-color: #EC4899; }
        .bg-pink-400 { background-color: #F472B6; }
        .bg-blue-500 { background-color: #3B82F6; }
        .bg-blue-400 { background-color: #60A5FA; }
        .bg-teal-500 { background-color: #14B8A6; }
        .bg-teal-400 { background-color: #2DD4BF; }
        
        /* Borders and shadows */
        .border { border-width: 1px; }
        .border-b { border-bottom-width: 1px; }
        .border-gray-200 { border-color: #E5E7EB; }
        .border-gray-300 { border-color: #D1D5DB; }
        .rounded { border-radius: 0.25rem; }
        .rounded-md { border-radius: 0.375rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .min-w-\[1024px\] { min-width: 1024px; }
        
        /* Grid */
        .grid { display: grid; }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        
        /* Positioning */
        .relative { position: relative; }
        .block { display: block; }
        .hidden { display: none; }
        
        /* Forms */
        input[type="email"], input[type="password"], input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            background-color: white;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #5AA7C6;
            box-shadow: 0 0 0 3px rgba(90, 167, 198, 0.1);
        }
        
        .form-input:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .checkbox {
            width: 1rem;
            height: 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.25rem;
            background-color: #F9FAFB;
        }
        
        .checkbox:checked {
            background-color: #5AA7C6;
            border-color: #5AA7C6;
        }
        
        .btn {
            width: 100%;
            background-color: #5AA7C6;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            background-color: #13264D;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Links */
        a {
            text-decoration: none;
        }
        
        .link {
            color: #5AA7C6;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .link:hover {
            color: #13264D;
        }
        
        .nav-link {
            color: #4B5563;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .nav-link:hover {
            color: #5AA7C6;
        }
        
        .nav-btn {
            background-color: #F3F4F6;
            color: #374151;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .nav-btn:hover {
            background-color: #5AA7C6;
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .nav-btn-primary {
            background-color: #5AA7C6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .nav-btn-primary:hover {
            background-color: #13264D;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive utilities */
        @media (min-width: 768px) {
            .md\:flex { display: flex; }
        }
        
        /* Transitions */
        .transition-colors { transition-property: color, background-color, border-color; transition-duration: 0.2s; }
        .transition-all { transition-property: all; transition-duration: 0.2s; }
        .duration-200 { transition-duration: 0.2s; }
        .duration-300 { transition-duration: 0.3s; }
        .transform { transform: translateZ(0); }
        
        /* Hover and focus effects */
        .hover\:bg-fountain-blue:hover { background-color: #5AA7C6; }
        .hover\:bg-blue-zodiac:hover { background-color: #13264D; }
        .hover\:text-white:hover { color: white; }
        .hover\:text-fountain-blue:hover { color: #5AA7C6; }
        .hover\:shadow-sm:hover { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .hover\:shadow-md:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .hover\:bg-white\/20:hover { background-color: rgba(255, 255, 255, 0.2); }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .hover\:-translate-y-0\.5:hover { transform: translateY(-2px); }
        .hover\:bg-pink-400:hover { background-color: #F472B6; }
        .hover\:bg-blue-400:hover { background-color: #60A5FA; }
        .hover\:bg-teal-400:hover { background-color: #2DD4BF; }
        .cursor-pointer { cursor: pointer; }
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
                                class="form-input"
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
                                class="form-input"
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
                                class="checkbox"
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
                        <button type="submit" class="btn">
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
