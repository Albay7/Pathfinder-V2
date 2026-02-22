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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 items-start">
            <!-- Skills You Have -->
            @if(count($analysis['matching_skills']) > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div class="flex items-center">
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
                        @if(count($analysis['matching_skills']) > 10)
                            <button id="toggle-matching-skills" class="text-blue-500 hover:text-blue-600 text-sm font-semibold transition-colors duration-200">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    Show All ({{ count($analysis['matching_skills']) }})
                                </span>
                            </button>
                        @endif
                    </div>

                    @php
                        // Skills are already sorted by controller
                        $sortedMatchingSkills = $analysis['matching_skills'];
                    @endphp

                    <!-- First 10 matching skills with hierarchy -->
                    <div class="space-y-2" id="visible-matching-skills">
                        @foreach(array_slice($sortedMatchingSkills, 0, 10) as $index => $skill)
                            @php
                                // Determine priority based on skill category
                                $category = is_array($skill) ? $skill['category'] : 'unknown';
                                $skillName = is_array($skill) ? $skill['name'] : $skill;

                                $priority = match($category) {
                                    'advanced' => ['label' => 'High Priority', 'text' => 'text-gray-900'],
                                    'medium' => ['label' => 'Medium Priority', 'text' => 'text-gray-900'],
                                    'fundamental' => ['label' => 'Low Priority', 'text' => 'text-gray-900'],
                                    'soft' => ['label' => 'Priority', 'text' => 'text-gray-900'],
                                    default => ['label' => 'Skill', 'text' => 'text-gray-900']
                                };
                            @endphp
                            <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-100">
                                <span class="flex items-center justify-center w-6 h-6 bg-green-600 text-white text-xs font-bold rounded-full mr-3">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <span class="text-gray-900 font-medium">{{ $skillName }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Additional matching skills (hidden by default) -->
                    @if(count($analysis['matching_skills']) > 10)
                        <div class="space-y-2 mt-2 hidden" id="additional-matching-skills">
                            @foreach(array_slice($sortedMatchingSkills, 10) as $index => $skill)
                                @php
                                    $category = is_array($skill) ? $skill['category'] : 'unknown';
                                    $skillName = is_array($skill) ? $skill['name'] : $skill;

                                    $priority = match($category) {
                                        'advanced' => ['label' => 'High Priority', 'text' => 'text-gray-900'],
                                        'medium' => ['label' => 'Medium Priority', 'text' => 'text-gray-900'],
                                        'fundamental' => ['label' => 'Low Priority', 'text' => 'text-gray-900'],
                                        'soft' => ['label' => 'Priority', 'text' => 'text-gray-900'],
                                        default => ['label' => 'Skill', 'text' => 'text-gray-900']
                                    };
                                @endphp
                                <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-100">
                                    <span class="flex items-center justify-center w-6 h-6 bg-green-600 text-white text-xs font-bold rounded-full mr-3">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                    <span class="text-gray-900 font-medium">{{ $skillName }}</span>
                                </div>
                            @endforeach
                        </div>

                        <script>
                            document.getElementById('toggle-matching-skills').addEventListener('click', function() {
                                const additionalSkills = document.getElementById('additional-matching-skills');
                                const button = this;

                                if (additionalSkills.classList.contains('hidden')) {
                                    additionalSkills.classList.remove('hidden');
                                    button.innerHTML = `
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                            Show Less
                                        </span>
                                    `;
                                } else {
                                    additionalSkills.classList.add('hidden');
                                    button.innerHTML = `
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            Show All ({{ count($analysis['matching_skills']) }})
                                        </span>
                                    `;
                                }
                            });
                        </script>
                    @endif
                </div>
            @endif

            <!-- Skills You Need -->
            @if(count($analysis['missing_skills']) > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
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
                                <p class="text-sm text-gray-600">Prioritized by skill level and importance</p>
                            </div>
                        </div>
                        @if(count($analysis['missing_skills']) > 10)
                            <button id="toggle-skills" class="text-blue-500 hover:text-blue-600 text-sm font-semibold transition-colors duration-200 whitespace-nowrap flex-shrink-0">
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    Show All ({{ count($analysis['missing_skills']) }})
                                </span>
                            </button>
                        @endif
                    </div>

                    @php
                        // Skills are already sorted by controller, but fallback sorting for safety
                        $sortedMissingSkills = $analysis['missing_skills'];
                        if (!empty($sortedMissingSkills)) {
                            $priorityOrder = [
                                'advanced' => 1,
                                'medium' => 2,
                                'fundamental' => 3,
                                'soft' => 4,
                                'unknown' => 5,
                            ];

                            usort($sortedMissingSkills, function ($left, $right) use ($priorityOrder) {
                                $leftCategory = is_array($left) ? ($left['category'] ?? 'unknown') : 'unknown';
                                $rightCategory = is_array($right) ? ($right['category'] ?? 'unknown') : 'unknown';
                                return ($priorityOrder[$leftCategory] ?? 5) <=> ($priorityOrder[$rightCategory] ?? 5);
                            });
                        }
                    @endphp

                    <!-- All Skills with Category-Based Priority (First 10 always visible) -->
                    <div class="space-y-2" id="priority-skills">
                        @foreach(array_slice($sortedMissingSkills, 0, 10) as $index => $skill)
                            @php
                                // Determine priority based on skill category
                                $category = is_array($skill) ? $skill['category'] : 'unknown';
                                $skillName = is_array($skill) ? $skill['name'] : $skill;

                                $priority = match($category) {
                                    'advanced' => ['label' => 'High Priority', 'text' => 'text-red-600'],
                                    'medium' => ['label' => 'Medium Priority', 'text' => 'text-orange-600'],
                                    'fundamental' => ['label' => 'Low Priority', 'text' => 'text-yellow-600'],
                                    'soft' => ['label' => 'Priority', 'text' => 'text-purple-600'],
                                    default => ['label' => 'Priority', 'text' => 'text-gray-600']
                                };
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="flex items-center justify-center w-6 h-6 bg-red-600 text-white text-xs font-bold rounded-full mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-gray-900 font-medium">{{ $skillName }}</span>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $priority['text'] }}">
                                    {{ $priority['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Additional Skills (Collapsible, Unified Design) -->
                    @if(count($analysis['missing_skills']) > 10)
                        <div class="space-y-2 mt-2 hidden" id="additional-skills">
                            @foreach(array_slice($sortedMissingSkills, 10) as $index => $skill)
                                @php
                                    // Determine priority based on skill category
                                    $category = is_array($skill) ? $skill['category'] : 'unknown';
                                    $skillName = is_array($skill) ? $skill['name'] : $skill;

                                    $priority = match($category) {
                                        'advanced' => ['label' => 'High Priority', 'text' => 'text-red-600'],
                                        'medium' => ['label' => 'Medium Priority', 'text' => 'text-orange-600'],
                                        'fundamental' => ['label' => 'Low Priority', 'text' => 'text-yellow-600'],
                                        'soft' => ['label' => 'Priority', 'text' => 'text-purple-600'],
                                        default => ['label' => 'Priority', 'text' => 'text-gray-600']
                                    };
                                @endphp
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="flex items-center justify-center w-6 h-6 bg-red-600 text-white text-xs font-bold rounded-full mr-3">
                                            {{ $index + 11 }}
                                        </span>
                                        <span class="text-gray-900 font-medium">{{ $skillName }}</span>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full font-medium {{ $priority['text'] }}">
                                        {{ $priority['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(count($analysis['missing_skills']) > 10)
                        <script>
                            document.getElementById('toggle-skills').addEventListener('click', function() {
                                const additionalSkills = document.getElementById('additional-skills');
                                const button = this;

                                if (additionalSkills.classList.contains('hidden')) {
                                    additionalSkills.classList.remove('hidden');
                                    button.innerHTML = `
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                            Show Less
                                        </span>
                                    `;
                                } else {
                                    additionalSkills.classList.add('hidden');
                                    button.innerHTML = `
                                        <span class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            Show All ({{ count($analysis['missing_skills']) }})
                                        </span>
                                    `;
                                }
                            });
                        </script>
                    @endif
                </div>
            @endif
        </div>



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

        <!-- Important Disclaimer -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl shadow-xl border-l-8 border-amber-500 p-8 mt-8">
            <div>
                <h4 class="text-lg font-extrabold text-amber-900 mb-3 flex items-center">
                    Important Notice
                    <span class="ml-2 inline-block w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                </h4>
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4">
                    <p class="text-sm text-gray-800 leading-relaxed">
                        This skill gap analysis provides general guidance based on typical requirements for the selected role.
                        <span class="font-bold text-gray-900 bg-yellow-200 px-1.5 py-0.5 rounded">The results do not guarantee job placement or acceptance.</span>
                        Actual hiring decisions depend on specific company standards, job requirements, industry demands,
                        and individual qualifications. Use this analysis as a learning guide to improve your skills and increase your competitiveness in the job market.
                    </p>
                </div>
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
            <a href="{{ route('pathfinder.external-resources') }}" class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Find Learning Resources
            </a>
            <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
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
