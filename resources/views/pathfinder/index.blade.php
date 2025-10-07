@extends('pathfinder.layout')

@section('title', 'Pathfinder - Find Your Perfect Career Path')

@section('content')
<!-- Hero Section -->
    <section class="py-12 sm:py-16 md:py-20" style="background: linear-gradient(to bottom right, #13264D, #5AA7C6); min-height: 60vh; display: flex; align-items: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="hero-title font-bold text-white leading-tight" style="font-size: 1.875rem; line-height: 1.2; margin-bottom: 1rem; text-align: center;">
                    <style>
                    @media (min-width: 640px) { .hero-title { font-size: 2.25rem !important; } }
                    @media (min-width: 768px) { .hero-title { font-size: 3rem !important; } }
                    @media (min-width: 1024px) { .hero-title { font-size: 3.75rem !important; } }
                    </style>
                    Find Your <span style="color: #EFF6FF; display: block;">Perfect Career Path</span>
                </h1>
                <p class="hero-subtitle max-w-3xl mx-auto leading-relaxed" style="color: #EFF6FF; opacity: 0.9; font-size: 1rem; line-height: 1.5; margin-bottom: 1.5rem; padding: 0 1rem; text-align: center;">
                    <style>
                    @media (min-width: 640px) { .hero-subtitle { font-size: 1.125rem !important; } }
                    @media (min-width: 768px) { .hero-subtitle { font-size: 1.25rem !important; } }
                    </style>
                    Discover your strengths, explore career opportunities, and create a personalized roadmap to your professional success with our comprehensive career guidance platform.
                </p>
                        <div class="hero-buttons" style="display: flex; flex-direction: column; gap: 0.75rem; justify-content: center; padding: 0 1rem;">
                <style>
                @media (min-width: 640px) {
                    .hero-buttons {
                        flex-direction: row !important;
                        gap: 1rem !important;
                        padding: 0 !important;
                    }
                }
                </style>
                <a href="{{ route('pathfinder.career-guidance') }}" class="btn-mobile" style="display: flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem; background-color: white; color: #13264D; font-weight: 600; border-radius: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); min-height: 48px; width: 100%;" onmouseover="this.style.backgroundColor='#EFF6FF';" onmouseout="this.style.backgroundColor='white';">
                    <span style="font-size: 0.875rem;">Start Career Assessment</span>
                    <svg style="margin-left: 0.5rem; width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="#features" class="btn-mobile" style="display: flex; align-items: center; justify-content: center; padding: 0.75rem 1.5rem; border: 2px solid #13264D; color: #13264D; font-weight: 600; border-radius: 0.5rem; transition: all 0.2s; min-height: 48px; width: 100%; background-color: transparent;" onmouseover="this.style.backgroundColor='#13264D'; this.style.color='white'; this.style.borderColor='#13264D';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#13264D'; this.style.borderColor='#13264D';">
                    <span style="font-size: 0.875rem;">Learn More</span>
                </a>
            </div>
        </div>
    </section>

<!-- Features Section -->
<div id="features" class="py-16 sm:py-20 md:py-24" style="background-color: #EFF6FF;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                Powerful Tools for Your Career Journey
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-2">
                Our comprehensive platform provides everything you need to make informed career decisions and achieve your professional goals.
            </p>
        </div>

        <div class="feature-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
        <style>
        @media (min-width: 640px) { .feature-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (min-width: 1024px) { .feature-grid { grid-template-columns: repeat(3, 1fr) !important; } }
        </style>
            <!-- Career Guidance Card -->
            <div class="feature-card bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300" style="padding: 1.5rem; margin-bottom: 1rem;">
                <div class="flex items-center justify-center rounded-lg mb-4" style="width: 4rem; height: 4rem; background-color: #EFF6FF;">
                    <svg style="width: 2rem; height: 2rem; color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-3" style="font-size: 1.25rem; line-height: 1.4;">Career Guidance</h3>
                <p class="text-gray-600 mb-6">
                    Take our comprehensive questionnaire to discover courses and jobs that match your interests, skills, and career aspirations.
                </p>
                <ul class="text-sm text-gray-500 mb-6 space-y-2">
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Personalized recommendations
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Course and job matching
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Interest-based assessment
                    </li>
                </ul>
                <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <span>Start Assessment</span>
                    <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Career Path Visualizer Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mb-6" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Career Path Visualizer</h3>
                <p class="text-gray-600 mb-6">
                    See the step-by-step journey from your current position to your dream job. Get a clear roadmap with timelines and milestones.
                </p>
                <ul class="text-sm text-gray-500 mb-6 space-y-2">
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Step-by-step roadmap
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Timeline estimates
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Progress tracking
                    </li>
                </ul>
                <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <span>Visualize Path</span>
                    <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Skill Gap Analyzer Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mb-6" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Skill Gap Analyzer</h3>
                <p class="text-gray-600 mb-6">
                    Identify the difference between your current skills and what's required for your target career. Get actionable improvement plans.
                </p>
                <ul class="text-sm text-gray-500 mb-6 space-y-2">
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Skill comparison
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Gap identification
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Improvement recommendations
                    </li>
                </ul>
                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <span>Analyze Skills</span>
                    <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                How Pathfinder Works
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Simple steps to discover and achieve your career goals
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #13264D;">
                    1
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Take Assessment</h3>
                <p class="text-gray-600">Answer questions about your interests, skills, and career preferences.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #5AA7C6;">
                    2
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Recommendations</h3>
                <p class="text-gray-600">Receive personalized course and job recommendations based on your profile.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #13264D;">
                    3
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Plan Your Path</h3>
                <p class="text-gray-600">Visualize your career journey and identify skill gaps to bridge.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #5AA7C6;">
                    4
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Achieve Goals</h3>
                <p class="text-gray-600">Follow your personalized roadmap to reach your career objectives.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div style="background: linear-gradient(to right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                Join thousands of professionals who have found their perfect career path with Pathfinder.
            </p>
            <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center px-8 py-4 bg-white font-semibold rounded-lg transition-colors duration-200 shadow-lg" style="color: #13264D;" onmouseover="this.style.backgroundColor='#EFF6FF';" onmouseout="this.style.backgroundColor='white';">
                Get Started Now
                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
