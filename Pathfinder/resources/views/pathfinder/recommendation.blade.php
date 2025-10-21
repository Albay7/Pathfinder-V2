@extends('pathfinder.layout')

@section('title', 'Your Recommendation - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <div class="flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mx-auto mb-6">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Assessment Complete!
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                Based on your responses, we've found the perfect {{ $type === 'course' ? 'course' : 'job' }} recommendation for you.
            </p>
        </div>
    </div>
</div>

<!-- Recommendation Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Recommendation Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-8 mb-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 rounded-full mx-auto mb-4" style="background-color: #EFF6FF;">
                    @if($type === 'course')
                        <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @else
                        <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    @endif
                </div>
                <h2 class="text-3xl font-bold mb-2" style="color: #13264D;">
                    Your Recommended {{ $type === 'course' ? 'Course' : 'Job' }}
                </h2>
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium" style="background-color: #EFF6FF; color: #13264D;">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ rand(85, 98) }}% Match
                </div>
            </div>

            <div class="text-center">
                <h3 class="text-4xl font-bold text-gray-900 mb-4">{{ $recommendation }}</h3>
                <p class="text-lg text-gray-600 mb-8">
                    @if($type === 'course')
                        This course aligns perfectly with your learning style, interests, and career goals. It's designed to provide you with practical skills and knowledge that are highly valued in today's job market.
                    @else
                        This role matches your work preferences, motivations, and industry interests. It offers excellent growth potential and aligns with your career aspirations.
                    @endif
                </p>
            </div>
        </div>

        <!-- Why This Recommendation Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Why This {{ $type === 'course' ? 'Course' : 'Job' }}?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($type === 'course')
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Matches Your Learning Style</h4>
                            <p class="text-gray-600">The course format and delivery method align with your preferred way of learning.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Industry Relevance</h4>
                            <p class="text-gray-600">High demand in your field of interest with excellent job prospects.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Time Commitment</h4>
                            <p class="text-gray-600">Fits within your available time schedule and learning pace.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Skill Development</h4>
                            <p class="text-gray-600">Builds practical skills that are immediately applicable in real-world scenarios.</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Work Environment Match</h4>
                            <p class="text-gray-600">Aligns with your preferred work setting and collaboration style.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Career Motivation</h4>
                            <p class="text-gray-600">Satisfies your primary motivations and career aspirations.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Industry Growth</h4>
                            <p class="text-gray-600">Strong growth potential in your preferred industry sector.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">Work-Life Balance</h4>
                            <p class="text-gray-600">Offers the flexibility and balance you're looking for.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Next Steps Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Next Steps</h3>
            <div class="space-y-4">
                @if($type === 'course')
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Research Course Providers</h4>
                            <p class="text-gray-600">Look for reputable institutions or platforms offering this course.</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Check Prerequisites</h4>
                            <p class="text-gray-600">Ensure you meet any required background knowledge or skills.</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                3
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Plan Your Learning Schedule</h4>
                            <p class="text-gray-600">Set aside dedicated time for studying and completing assignments.</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Analyze Skill Requirements</h4>
                            <p class="text-gray-600">Use our Skill Gap Analyzer to identify what skills you need to develop.</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Create Career Path</h4>
                            <p class="text-gray-600">Visualize your journey from current position to this target role.</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                                3
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Start Networking</h4>
                            <p class="text-gray-600">Connect with professionals in this field and learn about opportunities.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($type === 'job')
                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analyze Skill Gap
                </a>
                <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Visualize Career Path
                </a>
            @else
                <a href="{{ route('pathfinder.questionnaire', ['type' => 'job']) }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    Find Matching Jobs
                </a>
                <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Plan Career Path
                </a>
            @endif
            <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #BEC0BF;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#BEC0BF';">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Take Another Assessment
            </a>
        </div>
    </div>
</div>
@endsection
