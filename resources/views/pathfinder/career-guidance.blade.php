@extends('pathfinder.layout')

@section('title', 'Career Guidance - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Career Guidance
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Discover your perfect career path through our comprehensive assessment. Choose whether you're looking for courses to enhance your skills or exploring new job opportunities.
            </p>
        </div>
    </div>
</div>

<!-- Selection Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                What are you looking for?
            </h2>
            <p class="text-lg text-gray-600">
                Select your focus area to get personalized recommendations
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Course Option -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mx-auto mb-6">
                        <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Find Courses</h3>
                    <p class="text-gray-600 mb-6">
                        Looking to learn new skills or enhance existing ones? Get personalized course recommendations based on your interests and career goals.
                    </p>
                    <ul class="text-sm text-gray-500 mb-8 space-y-2">
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Skill-based matching
                        </li>
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Industry-relevant programs
                        </li>
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Learning path guidance
                        </li>
                    </ul>
                    <a href="{{ route('pathfinder.questionnaire', ['type' => 'course']) }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Start Course Assessment
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Job Option -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mx-auto mb-6">
                        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Find Jobs</h3>
                    <p class="text-gray-600 mb-6">
                        Ready to explore new career opportunities? Discover job roles that match your skills, interests, and professional aspirations.
                    </p>
                    <ul class="text-sm text-gray-500 mb-8 space-y-2">
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Personality-based matching
                        </li>
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Market demand insights
                        </li>
                        <li class="flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Career growth potential
                        </li>
                    </ul>
                    <a href="{{ route('pathfinder.questionnaire', ['type' => 'job']) }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Start Job Assessment
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Why Choose Our Career Guidance?
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our assessment is designed by career experts and uses proven methodologies to provide accurate, actionable recommendations.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Scientifically Validated</h3>
                <p class="text-gray-600">Our assessment is based on proven career psychology principles and validated methodologies.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Quick & Efficient</h3>
                <p class="text-gray-600">Get personalized recommendations in just 5-10 minutes with our streamlined assessment process.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
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