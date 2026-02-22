@extends('pathfinder.layout')

@section('title', 'My Tutorials - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 sm:mb-4">
                My Learning Journey
            </h1>
            <p class="text-base sm:text-lg md:text-xl max-w-3xl mx-auto px-2" style="color: #EFF6FF; opacity: 0.9;">
                Track your progress through tutorials and continue building your skills
            </p>
        </div>
    </div>
</div>

<!-- Tutorial Dashboard -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $inProgress->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completed->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bookmarked</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $bookmarked->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg" style="background-color: #EFF6FF;">
                            <svg class="h-6 w-6" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Time</p>
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->getTotalTutorialTimeSpent() }}m</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tutorial Sections -->
        <div class="space-y-8">
            <!-- In Progress Tutorials -->
            @if($inProgress->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Continue Learning
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($inProgress as $progress)
                            <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $progress->tutorial->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($progress->tutorial->description, 100) }}</p>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $progress->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full" style="width: {{ $progress->progress_percentage }}%; background-color: #5AA7C6;"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <span>{{ $progress->formatted_time_spent }}</span>
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                        {{ ucfirst($progress->tutorial->level) }}
                                    </span>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ $progress->tutorial->url }}" target="_blank" class="flex-1 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                                        Continue
                                    </a>
                                    <form action="{{ route('tutorials.complete') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id }}">
                                        <button type="submit" class="bg-green-600 text-white py-2 px-3 rounded text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                                            Complete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Bookmarked Tutorials -->
            @if($bookmarked->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        Bookmarked Tutorials
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($bookmarked as $progress)
                            <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $progress->tutorial->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($progress->tutorial->description, 100) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <span>{{ $progress->tutorial->formatted_duration }}</span>
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                                        {{ ucfirst($progress->tutorial->level) }}
                                    </span>
                                </div>

                                <div class="flex space-x-2">
                                    <form action="{{ route('tutorials.start') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id }}">
                                        <button type="submit" class="w-full text-white py-2 px-3 rounded text-sm font-medium transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                                            Start Learning
                                        </button>
                                    </form>
                                    <form action="{{ route('tutorials.remove') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id }}">
                                        <button type="submit" class="bg-red-600 text-white py-2 px-3 rounded text-sm font-medium hover:bg-red-700 transition-colors duration-200">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Completed Tutorials -->
            @if($completed->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Completed Tutorials
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($completed as $progress)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $progress->tutorial->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($progress->tutorial->description, 100) }}</p>
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                            Completed
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <span>Completed: {{ $progress->completed_at->format('M j, Y') }}</span>
                                    <span>Time: {{ $progress->formatted_time_spent }}</span>
                                </div>

                                @if($progress->user_rating)
                                    <div class="flex items-center mb-4">
                                        <span class="text-sm text-gray-600 mr-2">Your rating:</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $progress->user_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ $progress->tutorial->url }}" target="_blank" class="inline-flex items-center text-sm font-medium transition-colors duration-200" style="color: #5AA7C6;" onmouseover="this.style.color='#13264D';" onmouseout="this.style.color='#5AA7C6';">
                                    Review Tutorial
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($inProgress->count() === 0 && $bookmarked->count() === 0 && $completed->count() === 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12">
                    <div class="flex flex-col items-center justify-center text-center mb-8">
                        <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No tutorials yet</h3>
                        <p class="text-gray-600">Start your learning journey by taking a skill gap analysis to get personalized tutorial recommendations.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                            Analyze Your Skills
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('pathfinder.external-resources') }}" class="inline-flex items-center justify-center px-6 py-3 text-white font-medium rounded-lg transition-all duration-200 hover:shadow-lg" style="background: linear-gradient(135deg, #5AA7C6 0%, #13264D 100%);">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Learning Resources
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
