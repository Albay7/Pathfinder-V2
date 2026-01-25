@extends('pathfinder.layout')

@section('title', 'MBTI Results - Pathfinder')

@section('content')
@php
    $mbtiType = $mbtiType ?? session('mbti_type', 'Unknown');
    $mbtiDescription = $mbtiDescription ?? session('mbti_description', '');
    $mbtiScores = $mbtiScores ?? session('mbti_scores', []);
    $personalityType = $personalityType ?? session('personality_type');

    // Define personality type names and descriptions
    $typeNames = [
        'INTJ' => 'The Architect',
        'INTP' => 'The Logician',
        'ENTJ' => 'The Commander',
        'ENTP' => 'The Debater',
        'INFJ' => 'The Advocate',
        'INFP' => 'The Mediator',
        'ENFJ' => 'The Protagonist',
        'ENFP' => 'The Campaigner',
        'ISTJ' => 'The Logistician',
        'ISFJ' => 'The Defender',
        'ESTJ' => 'The Executive',
        'ESFJ' => 'The Consul',
        'ISTP' => 'The Virtuoso',
        'ISFP' => 'The Adventurer',
        'ESTP' => 'The Entrepreneur',
        'ESFP' => 'The Entertainer'
    ];

    $typeName = $typeNames[$mbtiType] ?? 'The ' . $mbtiType;

    // Define dimension explanations
    $dimensions = [
        'E_I' => [
            'title' => 'Energy Direction',
            'E' => ['label' => 'Extraversion', 'desc' => 'Energized by social interaction, outgoing, expressive'],
            'I' => ['label' => 'Introversion', 'desc' => 'Energized by solitude, reflective, reserved']
        ],
        'S_N' => [
            'title' => 'Information Processing',
            'S' => ['label' => 'Sensing', 'desc' => 'Focuses on facts and details, practical, present-oriented'],
            'N' => ['label' => 'Intuition', 'desc' => 'Focuses on patterns and possibilities, imaginative, future-oriented']
        ],
        'T_F' => [
            'title' => 'Decision Making',
            'T' => ['label' => 'Thinking', 'desc' => 'Logic-based decisions, objective, analytical'],
            'F' => ['label' => 'Feeling', 'desc' => 'Value-based decisions, empathetic, people-oriented']
        ],
        'J_P' => [
            'title' => 'Lifestyle Approach',
            'J' => ['label' => 'Judging', 'desc' => 'Structured and organized, plans ahead, decisive'],
            'P' => ['label' => 'Perceiving', 'desc' => 'Flexible and adaptable, spontaneous, open-ended']
        ]
    ];
@endphp

<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center">
            <div class="inline-block px-4 py-2 rounded-full mb-4" style="background-color: rgba(255, 255, 255, 0.2);">
                <span class="text-white font-semibold text-sm">MBTI Assessment Complete</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4">
                {{ $mbtiType }}
            </h1>
            <p class="text-2xl sm:text-3xl font-semibold mb-6" style="color: #EFF6FF;">
                {{ $typeName }}
            </p>
            <p class="text-base sm:text-lg max-w-3xl mx-auto px-2" style="color: #EFF6FF; opacity: 0.9;">
                Based on your responses to 60 questions, we've identified your unique personality type and preferences.
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Personality Overview -->
        @if($personalityType && !empty($personalityType->description))
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold mb-3" style="color: #13264D;">Personality Overview</h2>
            <p class="text-gray-700 text-lg leading-relaxed">{{ $personalityType->description }}</p>
        </div>
        @endif

        @if(!empty($compatiblePaths))
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-2xl font-bold" style="color: #13264D;">Recommended Compatible Paths</h3>
                <span class="text-xs text-gray-500">Matched to Pathfinder roles</span>
            </div>
            <p class="text-gray-600 mb-4">These Pathfinder roles align with your MBTI results and are available for you to explore.</p>
            <div class="flex flex-wrap gap-2">
                @foreach($compatiblePaths as $role)
                    <span class="px-3 py-2 rounded-full text-sm bg-gray-50 border border-gray-200 text-gray-800">{{ $role }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($evidenceMatches))
        <!-- Evidence-backed job fits from web research -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #13264D;">Evidence-backed ESTP Career Fits</h2>
                    <p class="text-gray-600 text-sm">Matched to web sources: Truity (ESTP careers) and PersonalityPage (ESTP career paths).</p>
                </div>
                <div class="flex gap-2 text-xs text-gray-500">
                    <span class="px-3 py-1 bg-blue-50 border border-blue-200 rounded-full">Source-backed</span>
                    <span class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-full">MBTI-weighted</span>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($evidenceMatches as $match)
                <div class="border rounded-lg p-5 bg-gray-50 hover:shadow transition-shadow">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">{{ $match['job_title'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Source: {{ $match['evidence_source'] }} ({{ $match['evidence_title'] }})</p>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-full text-white" style="background-color:#5AA7C6;">{{ $match['score'] }}% fit</span>
                    </div>
                    @if(!empty($match['top_skills']))
                        <div class="flex flex-wrap gap-1 mt-3">
                            @foreach($match['top_skills'] as $ts)
                                <span class="text-xxs px-2 py-1 rounded-full bg-white border border-gray-200 text-gray-600">{{ str_replace('_',' ', $ts) }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($match['url']))
                        <a href="{{ $match['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center mt-3 text-sm font-medium" style="color:#13264D;">
                            View role
                            <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Personality Dimensions with Calculations -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold mb-6" style="color: #13264D;">Your Personality Dimensions</h2>


            @if(!empty($mbtiScores))
                <div class="space-y-8">
                    @foreach(['E_I', 'S_N', 'T_F', 'J_P'] as $dimension)
                        @php
                            $dim = $dimensions[$dimension];
                            $letters = explode('_', $dimension);
                            $letter1 = $letters[0];
                            $letter2 = $letters[1];
                            $score1 = $mbtiScores[$dimension][$letter1] ?? 50;
                            $score2 = $mbtiScores[$dimension][$letter2] ?? 50;
                            $dominant = $score1 > $score2 ? $letter1 : $letter2;
                            $preference = $score1 > $score2 ? $dim[$letter1] : $dim[$letter2];
                            $strength = max($score1, $score2);
                            $strengthLabel = $strength >= 70 ? 'Strong' : ($strength >= 55 ? 'Moderate' : 'Slight');
                        @endphp

                        <div class="border-l-4 pl-6 py-2" style="border-color: #5AA7C6;">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $dim['title'] }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span class="font-semibold" style="color: #5AA7C6;">{{ $strengthLabel }} preference for {{ $preference['label'] }}</span>
                                        ({{ $strength }}%)
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold text-white" style="background-color: #5AA7C6;">
                                        {{ $dominant }}
                                    </span>
                                </div>
                            </div>

                            <!-- Dual Progress Bar -->
                            <div class="mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold text-gray-600 w-20">{{ $dim[$letter1]['label'] }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                        <div class="absolute inset-0 flex">
                                            <div class="h-full transition-all duration-500"
                                                 style="width: {{ $score1 }}%; background: linear-gradient(to right, #5AA7C6, #13264D);">
                                            </div>
                                            <div class="h-full"
                                                 style="width: {{ $score2 }}%; background: linear-gradient(to right, #e5e7eb, #9ca3af);">
                                            </div>
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-xs font-bold text-white drop-shadow-lg">
                                                {{ $score1 }}% vs {{ $score2 }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 w-20 text-right">{{ $dim[$letter2]['label'] }}</span>
                                </div>
                            </div>

                            <!-- Explanations -->
                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                <div class="bg-gray-50 rounded-lg p-4 {{ $dominant == $letter1 ? 'ring-2 ring-blue-400' : '' }}">
                                    <div class="flex items-start">
                                        @if($dominant == $letter1)
                                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $dim[$letter1]['label'] }} ({{ $letter1 }})</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $dim[$letter1]['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 {{ $dominant == $letter2 ? 'ring-2 ring-blue-400' : '' }}">
                                    <div class="flex items-start">
                                        @if($dominant == $letter2)
                                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-900">{{ $dim[$letter2]['label'] }} ({{ $letter2 }})</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $dim[$letter2]['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Calculation Methodology -->
                <div class="mt-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="font-bold text-gray-900 mb-2">How We Calculated Your Results</h3>
                    <p class="text-sm text-gray-700">
                        Your MBTI type was determined by analyzing 60 questions (15 per dimension). Each response was scored from 1-7, with questions targeting opposite preferences.
                        The cumulative scores for each preference were compared, and percentages were calculated to show the strength of your preference.
                        A 70%+ score indicates a strong preference, 55-69% shows moderate preference, and below 55% suggests slight preference.
                    </p>
                </div>
            @endif
        </div>

        <!-- Strengths and Weaknesses -->
        @if($personalityType && (!empty($personalityType->strengths) || !empty($personalityType->weaknesses)))
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            @if(!empty($personalityType->strengths))
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-xl font-bold mb-4" style="color: #13264D;">Key Strengths</h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($personalityType->strengths)) !!}
                </div>
            </div>
            @endif

            @if(!empty($personalityType->weaknesses))
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-xl font-bold mb-4" style="color: #13264D;">Areas for Growth</h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($personalityType->weaknesses)) !!}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Career Paths -->
        @if(!empty($recommendedJobs ?? []))
        <!-- Recommended Jobs from system -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold mb-4" style="color: #13264D;">Top Job Matches</h3>
            <p class="text-gray-600 mb-6">These roles are calculated using your MBTI preferences combined with our job skill vectors. Scores indicate overall fit.</p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendedJobs as $job)
                <div class="rounded-lg border border-gray-200 p-5 hover:shadow-lg transition-shadow bg-white">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-bold text-gray-900 leading-snug mr-2">{{ $job['job_title'] }}</h4>
                        <span class="text-xs font-bold px-2 py-1 rounded-full text-white" style="background-color:#5AA7C6;">{{ $job['score'] }}%</span>
                    </div>
                    @if(!empty($job['company']))
                        <p class="text-xs text-gray-500 mb-2">{{ $job['company'] }}</p>
                    @endif
                    @if(!empty($job['description']))
                        <p class="text-sm text-gray-700 line-clamp-3 mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($job['description']), 140) }}</p>
                    @endif
                    @if(!empty($job['top_skills']))
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($job['top_skills'] as $ts)
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-700">{{ str_replace('_',' ', $ts) }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex gap-2">
                        @if(!empty($job['url']))
                            <a href="{{ $job['url'] }}" target="_blank" rel="noopener" class="px-3 py-2 rounded text-white text-sm" style="background-color:#13264D;">View</a>
                        @endif
                        @if(!empty($job['source']))
                            <span class="text-xs text-gray-400 self-center">Source: {{ $job['source'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Next Steps -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl shadow-lg border border-blue-200 p-8">
            <h3 class="text-2xl font-bold mb-4" style="color: #13264D;">What's Next?</h3>
            <p class="text-gray-700 mb-6">
                Now that you understand your personality type, explore these pathways to make the most of your unique strengths:
            </p>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('pathfinder.career-guidance') }}" class="flex items-center justify-center px-6 py-4 bg-white text-gray-900 font-medium rounded-lg shadow hover:shadow-lg transition-all duration-200 border-2 border-transparent hover:border-blue-400">
                    <svg class="h-5 w-5 mr-2" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h2"></path>
                    </svg>
                    Explore Careers
                </a>
                <a href="{{ route('pathfinder.skill-gap') }}" class="flex items-center justify-center px-6 py-4 bg-white text-gray-900 font-medium rounded-lg shadow hover:shadow-lg transition-all duration-200 border-2 border-transparent hover:border-blue-400">
                    <svg class="h-5 w-5 mr-2" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analyze Skills
                </a>
                <a href="{{ route('pathfinder.career-path') }}" class="flex items-center justify-center px-6 py-4 bg-white text-gray-900 font-medium rounded-lg shadow hover:shadow-lg transition-all duration-200 border-2 border-transparent hover:border-blue-400">
                    <svg class="h-5 w-5 mr-2" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Career Path
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
