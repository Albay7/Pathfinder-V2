@extends('pathfinder.layout')

@section('title', 'Pathfinder - Find Your Perfect Career Path')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Find Your Perfect
                <span class="text-blue-200">Career Path</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Discover your ideal career, visualize your journey, and bridge skill gaps with our comprehensive career guidance platform.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors duration-200 shadow-lg">
                    Start Career Assessment
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-600 transition-colors duration-200">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Powerful Tools for Your Career Journey
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Our comprehensive platform provides everything you need to make informed career decisions and achieve your professional goals.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Career Guidance Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mb-6">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Career Guidance</h3>
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
                <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Start Assessment
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Career Path Visualizer Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                    Visualize Path
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Skill Gap Analyzer Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mb-6">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                    Analyze Skills
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    1
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Take Assessment</h3>
                <p class="text-gray-600">Answer questions about your interests, skills, and career preferences.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    2
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Recommendations</h3>
                <p class="text-gray-600">Receive personalized course and job recommendations based on your profile.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    3
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Plan Your Path</h3>
                <p class="text-gray-600">Visualize your career journey and identify skill gaps to bridge.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    4
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Achieve Goals</h3>
                <p class="text-gray-600">Follow your personalized roadmap to reach your career objectives.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Join thousands of professionals who have found their perfect career path with Pathfinder.
            </p>
            <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors duration-200 shadow-lg">
                Get Started Now
                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection