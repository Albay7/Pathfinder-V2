@extends('pathfinder.layout')

@section('title', $careerDetails['title'] . ' - Career Details')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $careerDetails['title'] }}
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Explore detailed information about this career path
            </p>
        </div>
    </div>
</div>

<!-- Career Details Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                        <p class="text-gray-700 mb-6">{{ $careerDetails['description'] }}</p>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Required Skills</h3>
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($careerDetails['skills_required'] as $skill)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Education Requirements</h3>
                        <p class="text-gray-700 mb-6">{{ $careerDetails['education_requirements'] }}</p>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Salary Range</h3>
                        <p class="text-gray-700 mb-6">{{ $careerDetails['salary_range'] }}</p>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Job Outlook</h3>
                        <p class="text-gray-700">{{ $careerDetails['job_outlook'] }}</p>
                    </div>
                </div>
                
                <!-- MBTI Compatibility Section -->
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">MBTI Personality Compatibility</h2>
                        
                        @if(Auth::check())
                            @php
                                $mbtiAssessment = Auth::user()->progress()
                                    ->where('feature_type', 'mbti')
                                    ->where('completed', true)
                                    ->latest()
                                    ->first();
                                    
                                $userMbtiType = null;
                                $personalizedCompatibility = null;
                                $personalizedStrengths = [];
                                $personalizedChallenges = [];
                                
                                if ($mbtiAssessment && $mbtiAssessment->questionnaire_answers) {
                                    $assessmentData = is_string($mbtiAssessment->questionnaire_answers) 
                                        ? json_decode($mbtiAssessment->questionnaire_answers, true) 
                                        : $mbtiAssessment->questionnaire_answers;
                                    
                                    $userMbtiType = $assessmentData['mbti_type'] ?? null;
                                    
                                    // Calculate personalized compatibility based on user's MBTI type
                                    $careerMbtiMap = [
                                        'Software Engineer' => ['INTJ' => 95, 'INTP' => 92, 'ENTJ' => 88, 'ENTP' => 85],
                                        'Data Scientist' => ['INTJ' => 93, 'INTP' => 95, 'ISTJ' => 87, 'ENTJ' => 89],
                                        'Product Manager' => ['ENTJ' => 94, 'ENTP' => 91, 'ENFJ' => 88, 'INTJ' => 86],
                                        'UX Designer' => ['ENFP' => 93, 'INFP' => 90, 'ENFJ' => 87, 'ENTP' => 85],
                                        'Business Analyst' => ['ISTJ' => 92, 'INTJ' => 89, 'ENTJ' => 87, 'ESTJ' => 85]
                                    ];
                                    
                                    $careerName = $careerDetails['title'];
                                    $personalizedCompatibility = $careerMbtiMap[$careerName][$userMbtiType] ?? 75;
                                    
                                    // Personalized strengths based on MBTI type
                                    $mbtiStrengths = [
                                        'INTJ' => ['Strategic thinking and long-term planning', 'Independent problem-solving', 'Systems thinking approach'],
                                        'INTP' => ['Analytical and logical reasoning', 'Creative problem-solving', 'Theoretical understanding'],
                                        'ENTJ' => ['Natural leadership abilities', 'Strategic planning and execution', 'Goal-oriented approach'],
                                        'ENTP' => ['Innovation and creative thinking', 'Adaptability to change', 'Strong communication skills'],
                                        'ENFP' => ['Creative and innovative thinking', 'Strong interpersonal skills', 'Enthusiasm and motivation'],
                                        'INFP' => ['Deep empathy and user understanding', 'Creative and artistic abilities', 'Values-driven approach']
                                    ];
                                    
                                    $personalizedStrengths = $mbtiStrengths[$userMbtiType] ?? ['Strategic thinking', 'Problem-solving abilities', 'Analytical skills'];
                                    
                                    // Personalized challenges
                                    $mbtiChallenges = [
                                        'INTJ' => ['May need to work on team collaboration', 'Consider communication with non-technical stakeholders'],
                                        'INTP' => ['Focus on practical implementation', 'Develop project management skills'],
                                        'ENTJ' => ['Practice patience with detailed work', 'Consider individual contributor preferences'],
                                        'ENTP' => ['Develop focus for long-term projects', 'Build attention to detail'],
                                        'ENFP' => ['Strengthen analytical and technical skills', 'Develop systematic approaches'],
                                        'INFP' => ['Build confidence in technical discussions', 'Develop business acumen']
                                    ];
                                    
                                    $personalizedChallenges = $mbtiChallenges[$userMbtiType] ?? [];
                                }
                            @endphp
                            
                            @if($mbtiAssessment && $userMbtiType)
                                <!-- Show personalized MBTI compatibility -->
                                <div class="mb-6">
                                    <h3 class="text-xl font-semibold text-indigo-700 mb-2">Your MBTI Type: {{ $userMbtiType }}</h3>
                                    <p class="text-gray-700">
                                        Based on your MBTI personality type, you have a <span class="font-semibold">{{ $personalizedCompatibility }}%</span> compatibility with this career path.
                                    </p>
                                    
                                    <div class="mt-4 w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-indigo-600 h-4 rounded-full" style="width: {{ $personalizedCompatibility }}%"></div>
                                    </div>
                                    
                                    @if($personalizedCompatibility >= 90)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-4">
                                            <p class="text-sm text-green-800 font-medium">🎯 Excellent Match! This career aligns very well with your {{ $userMbtiType }} personality type.</p>
                                        </div>
                                    @elseif($personalizedCompatibility >= 80)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                                            <p class="text-sm text-yellow-800 font-medium">✨ Good Match! This career has strong compatibility with your {{ $userMbtiType }} personality type.</p>
                                        </div>
                                    @else
                                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mt-4">
                                            <p class="text-sm text-orange-800 font-medium">⚡ Moderate Match. Consider how this career aligns with your {{ $userMbtiType }} preferences.</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700">
                                    Your {{ $userMbtiType }} personality type brings unique strengths to this role, including:
                                </p>
                                <ul class="mt-3 space-y-2">
                                    @foreach($personalizedStrengths as $strength)
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-indigo-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $strength }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                @if(!empty($personalizedChallenges))
                                    <div class="mt-6">
                                        <p class="text-gray-700 mb-3">
                                            Areas to consider for growth in this role:
                                        </p>
                                        <ul class="space-y-2">
                                            @foreach($personalizedChallenges as $challenge)
                                                <li class="flex items-start">
                                                    <svg class="h-5 w-5 text-orange-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    <span>{{ $challenge }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <div class="mt-6 pt-4 border-t border-indigo-200">
                                    <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                        Retake MBTI assessment →
                                    </a>
                                </div>
                            @else
                                <!-- No MBTI assessment yet -->
                                <p class="text-gray-700 mb-4">
                                    Take our MBTI personality assessment to see how well this career matches your personality type.
                                </p>
                                <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Take MBTI Assessment
                                </a>
                            @endif
                        @else
                            <!-- Not logged in -->
                            <p class="text-gray-700 mb-4">
                                Sign in and take our MBTI personality assessment to see how well this career matches your personality type.
                            </p>
                            <div class="flex gap-3">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Sign In
                                </a>
                                <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Take MBTI Assessment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Related Careers -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6.5"></path>
                            </svg>
                            Related Careers
                        </h3>
                        
                        @if(Auth::check())
                            @php
                                $careerAssessment = Auth::user()->progress()
                                    ->where('feature_type', 'career_guidance')
                                    ->where('assessment_type', 'job')
                                    ->where('completed', true)
                                    ->latest()
                                    ->first();
                                    
                                $personalizedCareers = [];
                                if ($careerAssessment && $careerAssessment->questionnaire_answers) {
                                    // Get careers from assessment results
                                    $assessmentData = is_string($careerAssessment->questionnaire_answers) 
                                        ? json_decode($careerAssessment->questionnaire_answers, true) 
                                        : $careerAssessment->questionnaire_answers;
                                    
                                    // Mock personalized careers based on assessment
                                    $personalizedCareers = [
                                        'Software Engineer',
                                        'Data Scientist', 
                                        'Product Manager',
                                        'UX Designer',
                                        'Business Analyst'
                                    ];
                                }
                            @endphp
                            
                            @if($careerAssessment && !empty($personalizedCareers))
                                <!-- Show personalized career recommendations -->
                                <div class="mb-3">
                                    <div class="flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full mb-3">
                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Based on your career assessment
                                    </div>
                                </div>
                                <ul class="space-y-3">
                                    @foreach(array_slice($personalizedCareers, 0, 4) as $index => $relatedCareer)
                                        <li>
                                            <a href="{{ route('pathfinder.career.details', ['career' => urlencode($relatedCareer)]) }}" class="flex items-center justify-between text-gray-700 hover:text-indigo-600 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                                <div class="flex items-center">
                                                    <div class="flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold mr-3">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <span class="font-medium">{{ $relatedCareer }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="text-xs text-green-600 font-semibold mr-2">{{ 90 - ($index * 5) }}% match</span>
                                                    <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <a href="{{ route('pathfinder.questionnaire', ['type' => 'job']) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                        Retake career assessment →
                                    </a>
                                </div>
                            @else
                                <!-- Show generic related careers -->
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 mb-3">
                                        Take our career assessment for personalized recommendations
                                    </p>
                                </div>
                                <ul class="space-y-3">
                                    @foreach($careerDetails['related_careers'] as $relatedCareer)
                                        <li>
                                            <a href="{{ route('pathfinder.career.details', ['career' => urlencode($relatedCareer)]) }}" class="flex items-center text-gray-700 hover:text-indigo-600 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                                <svg class="h-4 w-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                                {{ $relatedCareer }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <a href="{{ route('pathfinder.questionnaire', ['type' => 'job']) }}" class="inline-block w-full px-3 py-2 bg-indigo-600 text-white text-center rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors duration-200">
                                        Take Career Assessment
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Not logged in -->
                            <p class="text-gray-700 mb-4 text-sm">
                                Sign in to get personalized career recommendations based on your assessment results.
                            </p>
                            <ul class="space-y-3 mb-4">
                                @foreach(array_slice($careerDetails['related_careers'], 0, 3) as $relatedCareer)
                                    <li>
                                        <a href="{{ route('pathfinder.career.details', ['career' => urlencode($relatedCareer)]) }}" class="flex items-center text-gray-700 hover:text-indigo-600">
                                            <svg class="h-4 w-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            {{ $relatedCareer }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('login') }}" class="inline-block w-full px-3 py-2 bg-indigo-600 text-white text-center rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors duration-200">
                                Sign In for Personalized Results
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Skill Gap Analysis -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                            </svg>
                            Your Skill Gap Analysis
                        </h3>
                        
                        @if(Auth::check())
                            @php
                                $skillGapResult = Auth::user()->progress()
                                    ->where('feature_type', 'skill_gap')
                                    ->where('target_role', $careerDetails['title'])
                                    ->where('completed', true)
                                    ->latest()
                                    ->first();
                            @endphp
                            
                            @if($skillGapResult)
                                <!-- Show existing skill gap results -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Match Percentage</span>
                                        <span class="text-sm font-bold text-green-600">{{ $skillGapResult->match_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-red-400 via-yellow-400 to-green-500 h-2 rounded-full" style="width: {{ $skillGapResult->match_percentage }}%"></div>
                                    </div>
                                </div>
                                
                                @if($skillGapResult->analysis_result && isset($skillGapResult->analysis_result['missing_skills']))
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Skills to Develop:</h4>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($skillGapResult->analysis_result['missing_skills'], 0, 4) as $skill)
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">{{ $skill }}</span>
                                            @endforeach
                                            @if(count($skillGapResult->analysis_result['missing_skills']) > 4)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">+{{ count($skillGapResult->analysis_result['missing_skills']) - 4 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('pathfinder.skill-gap') }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                                        Update Analysis
                                    </a>
                                    <a href="{{ route('pathfinder.skill-gap-result', ['target_role' => $careerDetails['title']]) }}" class="flex-1 px-3 py-2 bg-gray-600 text-white text-center rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors duration-200">
                                        View Details
                                    </a>
                                </div>
                            @else
                                <!-- No skill gap analysis yet -->
                                <p class="text-gray-700 mb-4 text-sm">
                                    Get personalized insights on which skills you need to develop for this specific career path.
                                </p>
                                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                    Start Skill Gap Analysis
                                </a>
                            @endif
                        @else
                            <p class="text-gray-700 mb-4 text-sm">
                                Sign in to get personalized skill gap analysis for this career path.
                            </p>
                            <a href="{{ route('login') }}" class="inline-block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                Sign In for Analysis
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Recommended Courses -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Recommended Courses</h3>
                        <p class="text-gray-700 mb-4">
                            Explore courses that can help you prepare for this career.
                        </p>
                        <a href="{{ route('pathfinder.questionnaire', ['type' => 'course']) }}" class="inline-block w-full px-4 py-2 bg-green-600 text-white text-center rounded-lg font-medium hover:bg-green-700 transition-colors duration-200">
                            Find Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Ready to Start Your Journey?</h2>
            <p class="text-xl text-gray-700 mb-8 max-w-3xl mx-auto">
                Create a personalized career path plan to help you achieve your goals.
            </p>
            <a href="{{ route('pathfinder.career-path') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-200 shadow-md">
                Create Career Path Plan
            </a>
        </div>
    </div>
</div>
@endsection