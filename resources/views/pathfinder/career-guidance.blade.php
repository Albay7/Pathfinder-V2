@extends('pathfinder.layout')

@section('title', 'Career Guidance - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 sm:mb-4">
                Career Guidance
            </h1>
            <p class="text-base sm:text-lg md:text-xl max-w-3xl mx-auto px-2" style="color: #EFF6FF; opacity: 0.9;">
                Discover your perfect career path through our comprehensive assessment. Choose whether you're looking for courses to enhance your skills or exploring new job opportunities.
            </p>
        </div>
    </div>
</div>

<!-- Selection Section -->
<div class="py-12 sm:py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 sm:mb-4">
                What are you looking for?
            </h2>
            <p class="text-base sm:text-lg text-gray-600 px-2">
                Select your focus area to get personalized recommendations
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
            <!-- Course Option -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 flex flex-col h-full">
                <div class="text-center flex flex-col flex-grow">
                    <div class="flex items-center justify-center w-20 h-20 rounded-full mx-auto mb-6" style="background-color: #EFF6FF;">
                        <svg class="h-10 w-10" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4" style="color: #13264D;">Find Courses</h3>
                    <p class="text-gray-600 mb-6 text-sm sm:text-base flex-grow">
                        Looking to learn new skills or enhance existing ones? Get personalized course recommendations based on your interests and career goals.
                    </p>
                    <ul class="text-sm text-gray-500 mb-8 space-y-3">
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Skill-based matching</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Industry-relevant programs</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Learning path guidance</span>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('pathfinder.questionnaire', ['type' => 'courses']) }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                            <span>Start Course Assessment</span>
                            <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Job Option -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 flex flex-col h-full">
                <div class="text-center flex flex-col flex-grow">
                    <div class="flex items-center justify-center w-20 h-20 rounded-full mx-auto mb-6" style="background-color: #EFF6FF;">
                        <svg class="h-10 w-10" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4" style="color: #13264D;">Find Jobs</h3>
                    <p class="text-gray-600 mb-6 text-sm sm:text-base flex-grow">
                        Ready to explore new career opportunities? Discover job roles that match your skills, interests, and professional aspirations.
                    </p>
                    <ul class="text-sm text-gray-500 mb-8 space-y-3">
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Personality-based matching</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Market demand insights</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Career growth potential</span>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('pathfinder.questionnaire', ['type' => 'job']) }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                            <span>Start Job Assessment</span>
                            <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CV Upload Option -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 flex flex-col h-full">
                <div class="text-center flex flex-col flex-grow">
                    <div class="flex items-center justify-center w-20 h-20 rounded-full mx-auto mb-6" style="background-color: #EFF6FF;">
                        <svg class="h-10 w-10" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4" style="color: #13264D;">Upload CV</h3>
                    <p class="text-gray-600 mb-6 text-sm sm:text-base flex-grow">
                        Already have a CV? Upload it to get instant career analysis and personalized recommendations based on your existing experience and skills.
                    </p>
                    <ul class="text-sm text-gray-500 mb-8 space-y-3">
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>AI-powered CV analysis</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Skill gap identification</span>
                        </li>
                        <li class="flex items-center justify-start">
                            <svg class="h-4 w-4 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Instant recommendations</span>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        @auth
                            <a href="{{ route('pathfinder.cv-upload') }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                                <span>Upload Your CV</span>
                                <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </a>
                        @else
                            <div class="space-y-3">
                                <p class="text-sm text-gray-500 italic mb-3">Login required to upload CV</p>
                                <a href="{{ route('login') }}" class="inline-flex items-center w-full justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200 min-h-[48px]" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                                    <span>Login to Upload CV</span>
                                    <svg class="ml-2 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                                    </svg>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4" style="color: #13264D;">
                Why Choose Our Career Guidance?
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our assessment is designed by career experts and uses proven methodologies to provide accurate, actionable recommendations.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Scientifically Validated</h3>
                <p class="text-gray-600">Our assessment is based on proven career psychology principles and validated methodologies.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Quick & Efficient</h3>
                <p class="text-gray-600">Get personalized recommendations in just 5-10 minutes with our streamlined assessment process.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Personalized Results</h3>
                <p class="text-gray-600">Receive tailored recommendations that match your unique profile, interests, and career goals.</p>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4" style="color: #13264D;">
                Frequently Asked Questions
            </h2>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How long does the assessment take?</h3>
                <p class="text-gray-600">The assessment typically takes 5-10 minutes to complete. We've designed it to be comprehensive yet efficient.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Are the recommendations accurate?</h3>
                <p class="text-gray-600">Our recommendations are based on validated career assessment methodologies and are continuously refined based on user feedback and outcomes.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I take both assessments?</h3>
                <p class="text-gray-600">Absolutely! You can take both the course and job assessments to get a complete picture of your career development opportunities.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens after I complete the assessment?</h3>
                <p class="text-gray-600">You'll receive personalized recommendations along with detailed explanations. You can then explore our Career Path Visualizer and Skill Gap Analyzer for deeper insights.</p>
            </div>
        </div>
    </div>
</div>
@endsection
