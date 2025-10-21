@extends('pathfinder.layout')

@section('title', 'Skill Gap Analysis Results - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <div class="flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mx-auto mb-6">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Your Skill Analysis
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                Detailed analysis for <span class="font-semibold">{{ $targetRole }}</span> position
            </p>
        </div>
    </div>
</div>

<!-- Overall Score Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Score Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-8 mb-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold mb-6" style="color: #13264D;">
                    Your Skill Match Score
                </h2>

                <!-- Circular Progress -->
                <div class="relative inline-flex items-center justify-center mb-6">
                    <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none"></circle>
                        <circle cx="60" cy="60" r="50" stroke="#5AA7C6" stroke-width="8" fill="none" stroke-linecap="round" stroke-dasharray="{{ 2 * pi() * 50 }}" stroke-dashoffset="{{ 2 * pi() * 50 * (1 - $analysis['match_percentage'] / 100) }}"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $analysis['match_percentage'] }}%</span>
                    </div>
                </div>

                <div class="mb-6">
                    @if($analysis['match_percentage'] >= 80)
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium" style="background-color: #EFF6FF; color: #13264D;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Excellent Match!
                        </div>
                        <p class="text-gray-600 mt-2">You have most of the skills needed for this role. Focus on the remaining gaps to become fully qualified.</p>
                    @elseif($analysis['match_percentage'] >= 60)
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium" style="background-color: #EFF6FF; color: #13264D;">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Good Foundation
                        </div>
                        <p class="text-gray-600 mt-2">You have a solid foundation. With focused learning, you can bridge the skill gaps effectively.</p>
                    @elseif($analysis['match_percentage'] >= 40)
                        <div class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-lg font-medium">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Moderate Gap
                        </div>
                        <p class="text-gray-600 mt-2">There's a moderate skill gap. Consider taking courses or gaining experience in the missing areas.</p>
                    @else
                        <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-lg font-medium">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Significant Gap
                        </div>
                        <p class="text-gray-600 mt-2">There's a significant skill gap. Consider starting with foundational courses and building up gradually.</p>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ count($analysis['matching_skills']) }} skills acquired</span>
                        <span>{{ count($analysis['missing_skills']) }} skills to learn</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $analysis['match_percentage'] }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">{{ count($analysis['matching_skills']) }}</div>
                        <div class="text-sm text-green-800">Skills You Have</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-red-600">{{ count($analysis['missing_skills']) }}</div>
                        <div class="text-sm text-red-800">Skills to Learn</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ count($analysis['required_skills']) }}</div>
                        <div class="text-sm text-blue-800">Total Required</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Skills You Have -->
            @if(count($analysis['matching_skills']) > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xl font-bold text-gray-900">Skills You Have</h3>
                            <p class="text-sm text-gray-600">Great! You already possess these skills</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @foreach($analysis['matching_skills'] as $skill)
                            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                <svg class="h-4 w-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">{{ $skill }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Skills You Need -->
            @if(count($analysis['missing_skills']) > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-xl font-bold text-gray-900">Skills to Develop</h3>
                                <p class="text-sm text-gray-600">Your top learning priorities</p>
                            </div>
                        </div>
                        @if(count($analysis['missing_skills']) > 8)
                            <button id="toggle-skills" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Show All ({{ count($analysis['missing_skills']) }})
                            </button>
                        @endif
                    </div>

                    <!-- Top Priority Skills (Always visible) -->
                    <div class="space-y-2" id="priority-skills">
                        @foreach(array_slice($analysis['missing_skills'], 0, 8) as $index => $skill)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="flex items-center justify-center w-6 h-6 bg-red-600 text-white text-xs font-bold rounded-full mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-gray-900 font-medium">{{ $skill }}</span>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full font-medium
                                    {{ $index < 3 ? 'bg-red-100 text-red-600' : ($index < 6 ? 'bg-orange-100 text-orange-600' : 'bg-yellow-100 text-yellow-600') }}">
                                    {{ $index < 3 ? 'High Priority' : ($index < 6 ? 'Medium Priority' : 'Low Priority') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Additional Skills (Collapsible) -->
                    @if(count($analysis['missing_skills']) > 8)
                        <div class="space-y-2 mt-4 hidden" id="additional-skills">
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Additional Skills to Consider</h4>
                                @foreach(array_slice($analysis['missing_skills'], 8) as $index => $skill)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="flex items-center justify-center w-5 h-5 bg-gray-400 text-white text-xs font-bold rounded-full mr-3">
                                                {{ $index + 9 }}
                                            </span>
                                            <span class="text-gray-800 text-sm">{{ $skill }}</span>
                                        </div>
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-full font-medium">
                                            Future Learning
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(count($analysis['missing_skills']) > 8)
                        <script>
                            document.getElementById('toggle-skills').addEventListener('click', function() {
                                const additionalSkills = document.getElementById('additional-skills');
                                const button = document.getElementById('toggle-skills');

                                if (additionalSkills.classList.contains('hidden')) {
                                    additionalSkills.classList.remove('hidden');
                                    button.textContent = 'Show Less';
                                } else {
                                    additionalSkills.classList.add('hidden');
                                    button.textContent = 'Show All ({{ count($analysis['missing_skills']) }})';
                                }
                            });
                        </script>
                    @endif
                </div>
            @endif
        </div>



        <!-- Tutorial Recommendations -->
        @if(isset($analysis['tutorial_recommendations']) && count($analysis['tutorial_recommendations']) > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Recommended Tutorials</h3>
                <p class="text-gray-600 mb-6">Start learning with these curated tutorials for your missing skills:</p>

                @foreach($analysis['tutorial_recommendations'] as $skill => $tutorials)
                    <div class="mb-8">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full mr-3">
                                {{ $skill }}
                            </span>
                            Learning Resources
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($tutorials as $tutorial)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-900 text-sm mb-1">{{ $tutorial->title }}</h5>
                                            <p class="text-xs text-gray-600 mb-2">{{ Str::limit($tutorial->description, 80) }}</p>
                                        </div>
                                        <div class="flex items-center ml-2">
                                            @if($tutorial->type === 'video')
                                                <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6V7a3 3 0 00-3-3H6a3 3 0 00-3 3v1M5 10h14l-5 7H5V10z"></path>
                                                </svg>
                                            @elseif($tutorial->type === 'article')
                                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $tutorial->formatted_duration }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            {{ $tutorial->rating }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center px-2 py-1 bg-{{ $tutorial->level === 'beginner' ? 'green' : ($tutorial->level === 'intermediate' ? 'yellow' : 'red') }}-100 text-{{ $tutorial->level === 'beginner' ? 'green' : ($tutorial->level === 'intermediate' ? 'yellow' : 'red') }}-800 text-xs font-medium rounded">
                                            {{ ucfirst($tutorial->level) }}
                                        </span>

                                        <div class="flex space-x-2">
                                            @auth
                                                <form action="{{ route('tutorials.bookmark') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="tutorial_id" value="{{ $tutorial->id }}">
                                                    <button type="submit" class="text-gray-400 hover:text-yellow-500 transition-colors duration-200" title="Bookmark">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endauth

                                            <a href="{{ $tutorial->url }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors duration-200">
                                                Start
                                                <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Action Plan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Your Action Plan</h3>

            <div class="space-y-4">
                @if($analysis['match_percentage'] >= 80)
                    <div class="flex items-start p-4 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Polish Your Existing Skills</h4>
                            <p class="text-gray-600">Focus on deepening your knowledge in areas where you're already strong.</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Start Applying for Positions</h4>
                            <p class="text-gray-600">You're well-qualified! Start applying and mention your strong skill match.</p>
                        </div>
                    </div>
                @elseif($analysis['match_percentage'] >= 60)
                    <div class="flex items-start p-4 bg-yellow-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-yellow-600 text-white rounded-full text-sm font-bold">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Focus on High-Priority Skills</h4>
                            <p class="text-gray-600">Concentrate on the top 3 missing skills to quickly improve your match score.</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-sm font-bold">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Build a Portfolio</h4>
                            <p class="text-gray-600">Create projects that showcase both your existing and newly learned skills.</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start p-4 bg-red-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-full text-sm font-bold">
                                1
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Start with Fundamentals</h4>
                            <p class="text-gray-600">Begin with foundational courses in your target field before moving to specialized skills.</p>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                2
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Consider a Structured Program</h4>
                            <p class="text-gray-600">Look into bootcamps or comprehensive courses that cover multiple required skills.</p>
                        </div>
                    </div>
                @endif

                <div class="flex items-start p-4 bg-purple-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-purple-600 text-white rounded-full text-sm font-bold">
                            💡
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Track Your Progress</h4>
                        <p class="text-gray-600">Retake this analysis every few months to see your improvement and adjust your learning plan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">
            What's Next?
        </h2>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('pathfinder.external-resources') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Find Learning Resources
            </a>
            <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                Plan Career Path
            </a>
            <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Analyze Another Role
            </a>
        </div>
    </div>
</div>
@endsection
