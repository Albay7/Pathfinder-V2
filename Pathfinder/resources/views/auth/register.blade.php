<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Pathfinder Career Guidance</title>

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

        /* Verification modal overlay */
        .verification-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .verification-overlay.active {
            display: flex;
        }

        /* Modal card */
        .verification-modal {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            max-width: 440px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Email icon circle */
        .email-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5AA7C6 0%, #13264D 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        /* Pulsing dot for "waiting" state */
        .pulse-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #5AA7C6;
            animation: pulse 1.4s ease-in-out infinite;
            margin: 0 3px;
        }
        .pulse-dot:nth-child(2) { animation-delay: 0.2s; }
        .pulse-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes pulse {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Verified checkmark */
        .verified-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        /* Resend button in modal */
        .resend-btn {
            background: none;
            border: none;
            color: #5AA7C6;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .resend-btn:hover {
            background: rgba(90, 167, 198, 0.1);
        }
        .resend-btn:disabled {
            color: #aaa;
            cursor: not-allowed;
        }

        /* Inline warning for form validation */
        .inline-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            display: none;
        }
        .inline-warning.show {
            display: block;
        }

        /* Terms and Conditions modal */
        .terms-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }
        .terms-overlay.active {
            display: flex;
        }
        .terms-modal {
            background: #ffffff;
            border-radius: 16px;
            max-width: 560px;
            width: 90%;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
        }
        .terms-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .terms-header-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #5AA7C6 0%, #13264D 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .terms-body {
            padding: 24px 32px;
            overflow-y: auto;
            flex: 1;
        }
        .terms-body h3 {
            color: #13264D;
            font-size: 15px;
            font-weight: 600;
            margin: 20px 0 8px;
        }
        .terms-body h3:first-child {
            margin-top: 0;
        }
        .terms-body p {
            color: #555;
            font-size: 13.5px;
            line-height: 1.65;
            margin: 0 0 6px;
        }
        .terms-footer {
            padding: 16px 32px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
        }
        .terms-close-btn {
            background: linear-gradient(135deg, #5AA7C6 0%, #13264D 100%);
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .terms-close-btn:hover {
            opacity: 0.9;
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

                    <!-- Inline Warning -->
                    <div id="form-warning" class="inline-warning"></div>

                    <!-- Register Form -->
                    <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-6">
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
                                <a href="#" id="terms-link" class="text-fountain-blue hover:text-blue-zodiac transition-colors">Terms and Conditions</a>
                            </label>
                        </div>

                        <!-- Create Account Button -->
                        <button
                            type="button"
                            id="register-btn"
                            class="w-full primary-btn py-3 px-4 rounded-lg font-medium transform transition-all duration-200"
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

    <!-- Terms and Conditions Modal -->
    <div id="terms-overlay" class="terms-overlay">
        <div class="terms-modal">
            <div class="terms-header">
                <div class="terms-header-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div>
                    <h2 style="color: #13264D; font-size: 18px; font-weight: 700; margin: 0;">Terms and Conditions</h2>
                    <p style="color: #888; font-size: 12px; margin: 2px 0 0;">Pathfinder Career Guidance System</p>
                </div>
            </div>
            <div class="terms-body">
                <h3>1. Acceptance of Terms</h3>
                <p>By creating an account and using the Pathfinder Career Guidance System, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use this platform.</p>

                <h3>2. Purpose of the Platform</h3>
                <p>Pathfinder is a career guidance system developed for academic and research purposes. The platform provides personality assessments, career recommendations, and educational resources to help users explore potential career paths. The results and recommendations provided are intended as general guidance and should not be considered as professional career counseling.</p>

                <h3>3. Collection and Use of Information</h3>
                <p>We collect personal information such as your name and email address solely for the purpose of account creation and platform functionality. Any data generated through your use of the platform, including assessment results and interaction data, will be used exclusively for academic research and the improvement of the system.</p>
                <p>We will not sell, share, or distribute your personal information to any third parties for commercial purposes. Your data will only be used in an aggregated and anonymized form for research analysis.</p>

                <h3>4. Data Privacy and Security</h3>
                <p>We are committed to protecting your personal information. Reasonable security measures are in place to safeguard your data against unauthorized access, alteration, or disclosure. Your password is stored in an encrypted format and is never visible to system administrators.</p>

                <h3>5. User Responsibilities</h3>
                <p>You agree to provide accurate and truthful information during registration and while using the platform. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>

                <h3>6. Research Use Disclaimer</h3>
                <p>As this platform is developed for research purposes, the career guidance, personality assessments, and recommendations provided are based on established frameworks but are not a substitute for professional advice. Users should exercise their own judgment when making career-related decisions.</p>

                <h3>7. Intellectual Property</h3>
                <p>All content, design, and functionality of the Pathfinder platform are protected by intellectual property rights. Users may not reproduce, distribute, or modify any part of the platform without prior written consent.</p>

                <h3>8. Modification of Terms</h3>
                <p>We reserve the right to update or modify these Terms and Conditions at any time. Continued use of the platform after any changes constitutes your acceptance of the revised terms.</p>

                <h3>9. Contact</h3>
                <p>If you have any questions or concerns regarding these terms or how your data is handled, please contact the Pathfinder development team through the platform.</p>
            </div>
            <div class="terms-footer">
                <button type="button" id="terms-close-btn" class="terms-close-btn">I Understand</button>
            </div>
        </div>
    </div>

    <!-- Verification Modal Overlay -->
    <div id="verification-overlay" class="verification-overlay">
        <div class="verification-modal" id="verification-modal">
            <!-- Waiting state (default) -->
            <div id="modal-waiting">
                <div class="email-icon-circle">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="M22 4L12 13L2 4"/>
                    </svg>
                </div>
                <h2 style="color: #13264D; font-size: 22px; font-weight: 700; margin: 0 0 8px;">Check Your Email</h2>
                <p style="color: #666; font-size: 15px; line-height: 1.5; margin: 0 0 8px;">
                    We've sent a verification link to
                </p>
                <p id="modal-email" style="color: #13264D; font-size: 15px; font-weight: 600; margin: 0 0 20px;"></p>
                <p style="color: #888; font-size: 13px; line-height: 1.5; margin: 0 0 20px;">
                    Click the link in the email to verify your account. This page will automatically redirect once verified.
                </p>
                <div style="margin-bottom: 20px;">
                    <span class="pulse-dot"></span>
                    <span class="pulse-dot"></span>
                    <span class="pulse-dot"></span>
                    <span style="color: #999; font-size: 13px; margin-left: 8px;">Waiting for verification</span>
                </div>
                <div style="border-top: 1px solid #eee; padding-top: 16px;">
                    <p style="color: #999; font-size: 13px; margin: 0 0 8px;">Didn't receive the email?</p>
                    <button type="button" id="resend-btn" class="resend-btn">Resend Verification Email</button>
                    <p id="resend-status" style="color: #22c55e; font-size: 13px; margin: 8px 0 0; display: none;"></p>
                </div>
            </div>

            <!-- Verified state (hidden by default) -->
            <div id="modal-verified" style="display: none;">
                <div class="verified-icon-circle">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <h2 style="color: #13264D; font-size: 22px; font-weight: 700; margin: 0 0 8px;">Email Verified!</h2>
                <p style="color: #666; font-size: 15px; line-height: 1.5; margin: 0 0 16px;">
                    Your account has been verified successfully. Redirecting you now...
                </p>
                <div style="width: 40px; height: 40px; border: 3px solid #eee; border-top-color: #5AA7C6; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto;"></div>
                <style>
                    @keyframes spin {
                        to { transform: rotate(360deg); }
                    }
                </style>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var registerBtn = document.getElementById('register-btn');
            var form = document.getElementById('register-form');
            var warning = document.getElementById('form-warning');
            var overlay = document.getElementById('verification-overlay');
            var modalEmail = document.getElementById('modal-email');
            var modalWaiting = document.getElementById('modal-waiting');
            var modalVerified = document.getElementById('modal-verified');
            var resendBtn = document.getElementById('resend-btn');
            var resendStatus = document.getElementById('resend-status');
            var pollingInterval = null;
            var registrationToken = null;

            // Terms and Conditions dialog
            var termsLink = document.getElementById('terms-link');
            var termsOverlay = document.getElementById('terms-overlay');
            var termsCloseBtn = document.getElementById('terms-close-btn');

            termsLink.addEventListener('click', function(e) {
                e.preventDefault();
                termsOverlay.className = 'terms-overlay active';
            });

            termsCloseBtn.addEventListener('click', function() {
                termsOverlay.className = 'terms-overlay';
            });

            termsOverlay.addEventListener('click', function(e) {
                if (e.target === termsOverlay) {
                    termsOverlay.className = 'terms-overlay';
                }
            });

            function showWarning(msg) {
                warning.textContent = msg;
                warning.className = 'inline-warning show';
            }

            function hideWarning() {
                warning.className = 'inline-warning';
            }

            // Register button click handler
            registerBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Clear previous errors
                hideWarning();
                form.querySelectorAll('.ajax-error').forEach(function(el) { el.remove(); });

                // Basic client-side validation
                var firstName = form.querySelector('[name="first_name"]').value.trim();
                var lastName = form.querySelector('[name="last_name"]').value.trim();
                var email = form.querySelector('[name="email"]').value.trim();
                var password = form.querySelector('[name="password"]').value;
                var passwordConfirm = form.querySelector('[name="password_confirmation"]').value;
                var terms = form.querySelector('[name="terms"]').checked;

                if (!firstName || !lastName || !email || !password || !passwordConfirm) {
                    showWarning('Please fill in all fields.');
                    return;
                }
                if (!terms) {
                    showWarning('Please agree to the Terms and Conditions.');
                    return;
                }

                var originalText = registerBtn.textContent;
                registerBtn.disabled = true;
                registerBtn.textContent = 'Creating Account...';

                var formData = new FormData(form);

                fetch('{{ route("register") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(function(response) {
                    return response.json().then(function(data) {
                        return { ok: response.ok, status: response.status, data: data };
                    });
                })
                .then(function(result) {
                    registerBtn.disabled = false;
                    registerBtn.textContent = originalText;

                    if (result.ok) {
                        // Store the token for polling and resend
                        registrationToken = result.data.token;
                        // Show the verification modal
                        showVerificationModal(result.data.email || email);
                    } else if (result.data.errors) {
                        // Show field-level errors
                        var fields = Object.keys(result.data.errors);
                        for (var i = 0; i < fields.length; i++) {
                            var field = fields[i];
                            var input = form.querySelector('[name="' + field + '"]');
                            if (input) {
                                var errorSpan = document.createElement('span');
                                errorSpan.className = 'ajax-error text-red-500 text-sm mt-1 block';
                                errorSpan.textContent = result.data.errors[field][0];
                                input.parentNode.appendChild(errorSpan);
                            }
                        }
                    } else {
                        showWarning(result.data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(function(err) {
                    registerBtn.disabled = false;
                    registerBtn.textContent = originalText;
                    console.error('Registration error:', err);
                    showWarning('Something went wrong. Please try again.');
                });
            });

            function showVerificationModal(email) {
                modalEmail.textContent = email;
                modalWaiting.style.display = 'block';
                modalVerified.style.display = 'none';
                overlay.className = 'verification-overlay active';

                // Start polling for verification
                startPolling();
            }

            function startPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                }
                pollingInterval = setInterval(function() {
                    fetch('{{ route("verification.check") }}?token=' + encodeURIComponent(registrationToken), {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.verified) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                            showVerifiedState();
                        }
                    })
                    .catch(function(err) {
                        console.error('Polling error:', err);
                    });
                }, 3000);
            }

            function showVerifiedState() {
                modalWaiting.style.display = 'none';
                modalVerified.style.display = 'block';

                // Redirect after a short delay
                setTimeout(function() {
                    window.location.href = '{{ route("pathfinder.home") }}';
                }, 2000);
            }

            // Resend verification email
            resendBtn.addEventListener('click', function() {
                resendBtn.disabled = true;
                resendBtn.textContent = 'Sending...';
                resendStatus.style.display = 'none';

                fetch('{{ route("verification.resend") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ token: registrationToken })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    resendStatus.textContent = data.message || 'Verification email sent!';
                    resendStatus.style.color = '#22c55e';
                    resendStatus.style.display = 'block';
                    resendBtn.textContent = 'Resend Verification Email';

                    // Disable for 30 seconds to prevent spam
                    var countdown = 30;
                    resendBtn.textContent = 'Resend in ' + countdown + 's';
                    var timer = setInterval(function() {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(timer);
                            resendBtn.disabled = false;
                            resendBtn.textContent = 'Resend Verification Email';
                        } else {
                            resendBtn.textContent = 'Resend in ' + countdown + 's';
                        }
                    }, 1000);
                })
                .catch(function() {
                    resendStatus.textContent = 'Failed to resend. Please try again.';
                    resendStatus.style.color = '#ef4444';
                    resendStatus.style.display = 'block';
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Verification Email';
                });
            });
        });
    </script>
</body>
</html>
