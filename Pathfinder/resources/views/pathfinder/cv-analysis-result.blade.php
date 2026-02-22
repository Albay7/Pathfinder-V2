@extends('pathfinder.layout')

@section('title', 'CV Analysis Results - Pathfinder')

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
                CV Analysis Complete!
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                We've analyzed <strong>{{ $fileName }}</strong> and matched your skills against 64 career profiles using TF-IDF analysis.
            </p>
        </div>
    </div>
</div>

<!-- Results Content -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($topMatch)
        <!-- Top Match Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-8 mb-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 rounded-full mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-2" style="color: #13264D;">
                    Your Best Career Match
                </h2>
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium" style="background-color: #EFF6FF; color: #13264D;">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ $topMatch['similarity_score'] }}% Match
                </div>
            </div>

            <div class="text-center">
                <h3 class="text-4xl font-bold text-gray-900 mb-2">{{ $topMatch['job_title'] }}</h3>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide mb-4" style="background-color: #EFF6FF; color: #5AA7C6;">
                    {{ $topMatch['category'] }}
                </span>
                <p class="text-lg text-gray-600 mb-4">
                    {{ $topMatch['description'] }}
                </p>
            </div>
        </div>
        @endif

        <!-- Analysis Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold" style="color: #5AA7C6;">{{ $analysisSummary['total_skills_found'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Skills Detected</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold" style="color: #2D5A7B;">{{ $analysisSummary['total_job_matches'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Career Matches</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl font-bold" style="color: #13264D;">
                    {{ $topMatch ? $topMatch['similarity_score'] . '%' : 'N/A' }}
                </div>
                <div class="text-sm text-gray-600 mt-1">Best Match Score</div>
            </div>
        </div>

        <!-- Extracted Skills -->
        @if(!empty($analysisSummary['top_skills']))
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Skills Found in Your CV</h3>
            <div class="flex flex-wrap gap-3">
                @foreach($analysisSummary['top_skills'] as $skill)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium" style="background-color: #EFF6FF; color: #13264D;">
                        <svg class="h-3 w-3 mr-2" style="color: #5AA7C6;" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        {{ $skill }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Why This Job? -->
        @if($topMatch && !empty($topMatch['matching_dimensions']))
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Why {{ $topMatch['job_title'] }}?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($topMatch['matching_dimensions'] as $dimension)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $dimension['dimension'] }}</h4>
                            <p class="text-gray-600">
                                Your score: <strong>{{ round($dimension['user_score'] * 100) }}%</strong>
                                &middot; Role needs: <strong>{{ round($dimension['job_score'] * 100) }}%</strong>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Other Matches -->
        @if(count($allMatches) > 1)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Other Career Matches</h3>
            <div class="space-y-4">
                @foreach(array_slice($allMatches, 1) as $match)
                    <div class="flex items-center justify-between p-4 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors duration-200" style="background-color: #FAFAFA;">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-semibold text-gray-900">{{ $match['job_title'] }}</h4>
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium uppercase" style="background-color: #EFF6FF; color: #5AA7C6;">
                                    {{ $match['category'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $match['description'] }}</p>
                        </div>
                        <div class="flex-shrink-0 ml-4 text-right">
                            <div class="text-xl font-bold" style="color: #13264D;">{{ $match['similarity_score'] }}%</div>
                            <div class="text-xs text-gray-500">match</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Next Steps -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Next Steps</h3>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                            1
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Analyze Your Skill Gap</h4>
                        <p class="text-gray-600">See which skills you need to develop for your target role.</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                            2
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Visualize Your Career Path</h4>
                        <p class="text-gray-600">Map out the journey from your current position to your best-fit career.</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                            3
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Explore Learning Resources</h4>
                        <p class="text-gray-600">Find courses and tutorials to upskill for your target career.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
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
            <a href="{{ route('pathfinder.cv-upload') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #BEC0BF;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#BEC0BF';">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Upload Another CV
            </a>
        </div>
    </div>
</div>
@endsection
