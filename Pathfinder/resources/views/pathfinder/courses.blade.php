@extends('pathfinder.layout')

@section('title', 'Learning Resources - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-purple-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Learning Resources
            </h1>
            <p class="text-xl text-purple-100 max-w-3xl mx-auto">
                Discover courses and learning materials tailored to your interests and learning style.
            </p>
        </div>
    </div>
</div>

<!-- Courses Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Recommended Courses</h2>
            <p class="text-lg text-gray-700">
                Based on your profile and learning style, we've curated these courses to help you achieve your career goals.
            </p>
        </div>
        
        @foreach($courses as $category => $categoryCourses)
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 capitalize">{{ $category }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($categoryCourses as $course)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $course }}</h4>
                                <p class="text-gray-600 mb-4">
                                    Learn the fundamentals and advanced concepts of {{ $course }} through hands-on projects and expert instruction.
                                </p>
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('pathfinder.course.details', ['course' => urlencode($course)]) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                        View details
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
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
</div>

<!-- Learning Resources Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Additional Learning Resources</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="bg-blue-100 rounded-full p-3 inline-flex mb-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Free Online Libraries</h3>
                <p class="text-gray-600 mb-4">Access thousands of free books, articles, and research papers to supplement your learning.</p>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Explore libraries</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="bg-green-100 rounded-full p-3 inline-flex mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Study Groups</h3>
                <p class="text-gray-600 mb-4">Join virtual study groups with peers who share your learning goals and interests.</p>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Find a group</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="bg-purple-100 rounded-full p-3 inline-flex mb-4">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Video Tutorials</h3>
                <p class="text-gray-600 mb-4">Watch expert-led video tutorials on a wide range of topics to enhance your skills.</p>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Browse videos</a>
            </div>
        </div>
    </div>
</div>

@endsection