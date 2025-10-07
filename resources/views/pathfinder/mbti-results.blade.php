@extends('pathfinder.layout')

@section('title', 'Your MBTI Results - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Your MBTI Personality Type: {{ $mbtiType }}
            </h1>
            @if($personalityType)
                <h2 class="text-2xl font-semibold mb-4" style="color: #EFF6FF; opacity: 0.9;">
                    {{ $personalityType->name }}
                </h2>
                <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                    {{ $personalityType->description }}
                </p>
            @else
                <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                    {{ $mbtiDescription }}
                </p>
            @endif
        </div>
    </div>
</div>

<!-- Results Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($personalityType)
        <!-- Personality Overview -->
        <div class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Strengths -->
                <div class="rounded-xl shadow-lg border p-8" style="background-color: #EFF6FF; border-color: #5AA7C6;">
                    <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: #13264D;">
                        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Your Strengths
                    </h2>
                    <div class="text-gray-700 leading-relaxed">
                        {{ $personalityType->strengths }}
                    </div>
                </div>

                <!-- Areas for Growth -->
                <div class="rounded-xl shadow-lg border p-8" style="background-color: #EFF6FF; border-color: #5AA7C6;">
                    <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: #13264D;">
                        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Areas for Growth
                    </h2>
                    <div class="text-gray-700 leading-relaxed">
                        {{ $personalityType->weaknesses }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- MBTI Breakdown -->
            <div class="bg-gray-50 rounded-xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Your MBTI Breakdown</h2>

                <!-- E/I Dimension -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Extraversion (E)</span>
                        <span class="font-medium">Introversion (I)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="h-4 rounded-l-full" style="width: {{ $mbtiScores['E_I']['E'] }}%; background-color: #5AA7C6;"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-sm text-gray-600">
                        <span>{{ round($mbtiScores['E_I']['E']) }}%</span>
                        <span>{{ round($mbtiScores['E_I']['I']) }}%</span>
                    </div>
                </div>

                <!-- S/N Dimension -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Sensing (S)</span>
                        <span class="font-medium">Intuition (N)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-green-600 h-4 rounded-l-full" style="width: {{ $mbtiScores['S_N']['S'] }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-sm text-gray-600">
                        <span>{{ round($mbtiScores['S_N']['S']) }}%</span>
                        <span>{{ round($mbtiScores['S_N']['N']) }}%</span>
                    </div>
                </div>

                <!-- T/F Dimension -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Thinking (T)</span>
                        <span class="font-medium">Feeling (F)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-yellow-600 h-4 rounded-l-full" style="width: {{ $mbtiScores['T_F']['T'] }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-sm text-gray-600">
                        <span>{{ round($mbtiScores['T_F']['T']) }}%</span>
                        <span>{{ round($mbtiScores['T_F']['F']) }}%</span>
                    </div>
                </div>

                <!-- J/P Dimension -->
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Judging (J)</span>
                        <span class="font-medium">Perceiving (P)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="h-4 rounded-l-full" style="width: {{ $mbtiScores['J_P']['J'] }}%; background-color: #5AA7C6;"></div>
                    </div>
                    <div class="flex justify-between mt-1 text-sm text-gray-600">
                        <span>{{ round($mbtiScores['J_P']['J']) }}%</span>
                        <span>{{ round($mbtiScores['J_P']['P']) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Learning Style -->
            <div class="bg-gray-50 rounded-xl shadow-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Learning Style</h2>
                <h3 class="text-xl font-semibold mb-3" style="color: #13264D;">{{ $learningStyle['style'] }}</h3>
                <p class="text-gray-700 mb-6">{{ $learningStyle['description'] }}</p>

                <h4 class="font-medium text-gray-900 mb-3">Learning Recommendations:</h4>
                <ul class="space-y-2">
                    @foreach($learningStyle['recommendations'] as $recommendation)
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ $recommendation }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Career Recommendations -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Recommended Career Paths</h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto mb-12 text-center">
            Based on your MBTI personality type ({{ $mbtiType }}), these career paths may be particularly well-suited to your natural strengths and preferences.
        </p>

        <!-- Enhanced Career Overview -->
        @if($personalityType && $personalityType->career_paths)
        <div class="rounded-xl shadow-lg p-8 mb-8" style="background: linear-gradient(to bottom right, #EFF6FF, #DBEAFE); border: 1px solid #5AA7C6;">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="h-6 w-6 mr-2" style="color: #13264D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Career Insights for {{ $mbtiType }} Personalities
            </h3>
            <div class="prose prose-lg text-gray-700 leading-relaxed mb-6">
                {{ $personalityType->career_paths }}
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($careerRecommendations as $index => $career)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                    <div class="p-6">
                        <!-- Career Rank Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: #EFF6FF; color: #13264D;">
                                #{{ $index + 1 }} Match
                            </span>
                            <div class="flex items-center">
                                @php
                                    $compatibilityScore = 85 + ($index * -5); // Decreasing compatibility for demo
                                @endphp
                                <span class="text-sm font-semibold text-green-600">{{ $compatibilityScore }}% Match</span>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $career }}</h3>

                        <!-- Compatibility Bar -->
                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: {{ $compatibilityScore }}%"></div>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                            Highly compatible with {{ $mbtiType }} traits including
                            @if($mbtiType[0] == 'E') leadership, @endif
                            @if($mbtiType[1] == 'N') innovation, @endif
                            @if($mbtiType[2] == 'T') analytical thinking, @else empathy, @endif
                            @if($mbtiType[3] == 'J') organization. @else adaptability. @endif
                        </p>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('pathfinder.career.details', ['career' => urlencode($career)]) }}" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                                Explore Career
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Personalized Course Recommendations -->
@if(Auth::check() && count($courseRecommendations) > 0)
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Recommended Courses for You</h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto mb-12 text-center">
            These courses are specifically matched to your {{ $mbtiType }} personality type and learning preferences.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courseRecommendations as $course)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Compatibility Score Badge -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course['title'] }}</h3>
                                <p class="text-sm text-gray-600 mb-2">by {{ $course['provider'] }}</p>
                            </div>
                            <div class="ml-4">
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $course['compatibility_score'] }}% Match
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-4 line-clamp-3">{{ $course['description'] }}</p>

                        <!-- Compatibility Explanation -->
                        @if($course['compatibility_explanation'])
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                            <p class="text-sm text-blue-800">
                                <strong>Why this matches you:</strong> {{ $course['compatibility_explanation'] }}
                            </p>
                        </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <a href="{{ $course['url'] }}" target="_blank" class="inline-flex items-center font-medium transition-colors duration-200" style="color: #5AA7C6;" onmouseover="this.style.color='#13264D';" onmouseout="this.style.color='#5AA7C6';">
                                View Course
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Personalized Job Recommendations -->
@if(Auth::check() && count($jobRecommendations) > 0)
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Job Opportunities for You</h2>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto mb-12 text-center">
            These job opportunities align perfectly with your {{ $mbtiType }} personality traits and career preferences.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($jobRecommendations as $job)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Compatibility Score Badge -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $job['title'] }}</h3>
                                <p class="text-sm text-gray-600 mb-1">{{ $job['company'] }}</p>
                                <p class="text-sm text-gray-500 mb-2">{{ $job['location'] }}</p>
                                @if($job['salary_range'])
                                <p class="text-sm font-medium text-green-600">{{ $job['salary_range'] }}</p>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #EFF6FF; color: #13264D;">
                                    {{ $job['compatibility_score'] }}% Match
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-4 line-clamp-3">{{ $job['description'] }}</p>

                        <!-- Compatibility Explanation -->
                        @if($job['compatibility_explanation'])
                        <div class="p-3 mb-4" style="background-color: #EFF6FF; border-left: 4px solid #5AA7C6;">
                            <p class="text-sm" style="color: #13264D;">
                                <strong>Why this suits you:</strong> {{ $job['compatibility_explanation'] }}
                            </p>
                        </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <a href="{{ $job['url'] }}" target="_blank" class="inline-flex items-center font-medium transition-colors duration-200" style="color: #5AA7C6;" onmouseover="this.style.color='#13264D';" onmouseout="this.style.color='#5AA7C6';">
                                View Job
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Next Steps -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Next Steps</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-xl shadow-md p-8 text-center" style="background: linear-gradient(to bottom right, #EFF6FF, #DBEAFE);">
                <div class="rounded-full p-3 inline-flex mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Explore Career Paths</h3>
                <p class="text-gray-600 mb-4">Discover detailed information about your recommended career paths and what they entail.</p>
                <a href="{{ route('pathfinder.career-guidance') }}" class="inline-block px-6 py-3 text-white rounded-lg font-medium transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    View Careers
                </a>
            </div>

            <div class="rounded-xl shadow-md p-8 text-center" style="background: linear-gradient(to bottom right, #EFF6FF, #DBEAFE);">
                <div class="rounded-full p-3 inline-flex mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Find Learning Resources</h3>
                <p class="text-gray-600 mb-4">Discover tutorials and learning materials tailored to your personality type and learning style.</p>
                <a href="{{ route('pathfinder.external-resources') }}" class="inline-block px-6 py-3 text-white rounded-lg font-medium transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                    Learning Resources
                </a>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md p-8 text-center">
                <div class="bg-green-100 rounded-full p-3 inline-flex mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Skill Gap Analysis</h3>
                <p class="text-gray-600 mb-4">Identify the skills you need to develop to succeed in your chosen career path.</p>
                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                    Analyze Skills
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
