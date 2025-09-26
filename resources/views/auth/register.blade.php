<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pathfinder Career Guidance</title>
    <style>
        /* Reset and base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }

        /* Custom colors */
        .bg-blue-zodiac { background-color: #13264D; }
        .bg-fountain-blue { background-color: #5AA7C6; }
        .text-fountain-blue { color: #5AA7C6; }
        .border-fountain-blue { border-color: #5AA7C6; }
        .bg-tiara { background-color: #BEC0BF; }
        .text-tiara { color: #BEC0BF; }

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
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-12 { margin-bottom: 3rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-6 { margin-top: 1.5rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-3 > * + * { margin-left: 0.75rem; }
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
        .leading-tight { line-height: 1.25; }

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
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .min-w-\[1024px\] { min-width: 1024px; }

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

        /* Grid */
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        /* Positioning */
        .relative { position: relative; }
        .block { display: block; }
        .hidden { display: none; }
        .cursor-pointer { cursor: pointer; }

        /* Forms */
        input[type="text"], input[type="email"], input[type="password"], input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
        }

        input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            background-color: #F3F4F6;
            border: 1px solid #D1D5DB;
            border-radius: 0.25rem;
            position: relative;
        }

        input[type="checkbox"]:checked {
            background-color: #5AA7C6;
            border-color: #5AA7C6;
        }

        input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 0.75rem;
        }

        /* Links */
        a {
            text-decoration: none;
        }

        .nav-link {
            color: #4B5563;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #13264D;
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
            background-color: #13264D;
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
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Hover and focus effects */
        .hover\:bg-blue-zodiac:hover { background-color: #13264D; }
        .hover\:text-white:hover { color: white; }
        .hover\:bg-blue-600:hover { background-color: #2563eb; }
        .hover\:text-blue-zodiac:hover { color: #13264D; }
        .hover\:shadow-sm:hover { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .hover\:shadow-md:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .hover\:bg-white\/20:hover { background-color: rgba(255, 255, 255, 0.2); }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .hover\:-translate-y-0\.5:hover { transform: translateY(-2px); }
        .hover\:bg-pink-400:hover { background-color: #F472B6; }
        .hover\:bg-blue-400:hover { background-color: #60A5FA; }
        .hover\:bg-teal-400:hover { background-color: #2DD4BF; }

        .focus\:ring-2:focus { box-shadow: 0 0 0 2px rgba(90, 167, 198, 0.5); }
        .focus\:ring-4:focus { box-shadow: 0 0 0 4px rgba(90, 167, 198, 0.3); }
        .focus\:ring-fountain-blue:focus { --tw-ring-color: #5AA7C6; }
        .focus\:border-fountain-blue:focus { border-color: #5AA7C6; }
        .focus\:ring-fountain-blue\/30:focus { --tw-ring-color: rgba(90, 167, 198, 0.3); }

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
                <a href="{{ route('login') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-fountain-blue hover:text-white transition-all duration-200 hover:shadow-sm">Login</a>
                <a href="#" class="bg-fountain-blue text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-zodiac transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">Register</a>
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
                <h1 class="text-4xl font-bold mb-4">Welcome to Pathfinder</h1>
                <h2 class="text-2xl font-semibold mb-6 text-white/90">Start Your Career Journey</h2>
                <p class="text-lg text-white/80 mb-12 leading-relaxed">
                    Join thousands of professionals who have discovered their path with Pathfinder. Get personalized career guidance and unlock your potential.
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

        <!-- Right Panel - Registration Form -->
        <div class="w-1/2 bg-blue-50 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Register Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
                        <p class="text-gray-600">Start your personalized career guidance journey</p>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name Fields Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input
                                    id="first_name"
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required
                                    autocomplete="given-name"
                                    autofocus
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                    placeholder="First Name"
                                >
                                @error('first_name')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input
                                    id="last_name"
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required
                                    autocomplete="family-name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                    placeholder="Last Name"
                                >
                                @error('last_name')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
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
                                autocomplete="new-password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg focus:shadow-xl focus:ring-2 focus:ring-fountain-blue focus:border-fountain-blue transition-all duration-300 bg-white"
                                placeholder="Password"
                            >
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
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
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start space-x-3">
                            <input
                                type="checkbox"
                                id="agreeTerms"
                                name="terms"
                                class="w-4 h-4 text-fountain-blue bg-gray-100 border-gray-300 rounded focus:ring-fountain-blue focus:ring-2 mt-1"
                                required
                            >
                            <label for="agreeTerms" class="text-sm text-gray-600 leading-tight">
                                I agree to the
                                <a href="#" class="text-fountain-blue hover:text-blue-zodiac transition-colors">Terms and Conditions</a>
                            </label>
                        </div>

                        <!-- Create Account Button -->
                        <button
                            type="submit"
                            class="w-full bg-fountain-blue text-white py-3 px-4 rounded-lg font-medium shadow-lg hover:shadow-xl hover:bg-blue-600 focus:ring-4 focus:ring-fountain-blue/30 transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            Create Account
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-fountain-blue hover:text-blue-zodiac font-medium transition-colors">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
