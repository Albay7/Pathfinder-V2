@extends('pathfinder.layout')

@section('title', 'Your Career Path - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-green-600 to-emerald-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <div class="flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mx-auto mb-6">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Your Career Path
            </h1>
            <p class="text-xl text-green-100 max-w-3xl mx-auto">
                From <span class="font-semibold">{{ $currentRole }}</span> to <span class="font-semibold">{{ $targetRole }}</span>
            </p>
        </div>
    </div>
</div>

<!-- Career Path Timeline -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Overview Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Your Journey to {{ $targetRole }}
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Here's your personalized roadmap with {{ count($pathSteps) }} key steps to achieve your career goal.
                </p>
                <div class="flex items-center justify-center space-x-8 text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Estimated Total Time: 12-24 months
                    </div>
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2H9m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ count($pathSteps) }} Key Milestones
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timeline Steps -->
        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-green-200"></div>
            
            @foreach($pathSteps as $index => $step)
                <div class="relative flex items-start mb-8 {{ $loop->last ? 'mb-0' : '' }}">
                    <!-- Step Number Circle -->
                    <div class="flex-shrink-0 w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center text-xl font-bold shadow-lg z-10">
                        {{ $step['step'] }}
                    </div>
                    
                    <!-- Step Content -->
                    <div class="ml-6 flex-1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $step['title'] }}</h3>
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $step['duration'] }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">{{ $step['description'] }}</p>
                            
                            <!-- Action Items -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Key Actions:</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    @switch($step['step'])
                                        @case(1)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Complete a comprehensive skills inventory
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Research {{ $targetRole }} job requirements
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Identify skill gaps using our analyzer
                                            </li>
                                            @break
                                        @case(2)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Enroll in relevant online courses
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Practice with hands-on tutorials
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Join relevant communities and forums
                                            </li>
                                            @break
                                        @case(3)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Create 3-5 portfolio projects
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Document your learning journey
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Get feedback from professionals
                                            </li>
                                            @break
                                        @case(4)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Apply for internships or junior roles
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Volunteer for relevant projects
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Seek mentorship opportunities
                                            </li>
                                            @break
                                        @case(5)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Attend industry events and meetups
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Optimize LinkedIn and resume
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Apply strategically to target companies
                                            </li>
                                            @break
                                        @case(6)
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Excel in your new role
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Continue learning and growing
                                            </li>
                                            <li class="flex items-center">
                                                <svg class="h-3 w-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Plan for future career advancement
                                            </li>
                                            @break
                                    @endswitch
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Tips and Resources Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Tips for Success
            </h2>
            <p class="text-lg text-gray-600">
                Additional guidance to help you succeed on your career journey
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-blue-900">Stay Consistent</h3>
                </div>
                <p class="text-blue-800">
                    Dedicate regular time to your career development. Even 30 minutes a day can lead to significant progress over time.
                </p>
            </div>
            
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-green-900">Network Actively</h3>
                </div>
                <p class="text-green-800">
                    Build relationships with professionals in your target field. Many opportunities come through networking.
                </p>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-purple-900">Track Progress</h3>
                </div>
                <p class="text-purple-800">
                    Regularly review your progress and adjust your plan as needed. Celebrate small wins along the way.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">
            Ready to Take the Next Step?
        </h2>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analyze Your Skills
            </a>
            <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Get Course Recommendations
            </a>
            <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Create Another Path
            </a>
        </div>
    </div>
</div>
@endsection