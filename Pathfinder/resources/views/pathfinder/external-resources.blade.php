@extends('pathfinder.layout')

@section('title', 'External Learning Resources - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-purple-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                External Learning Resources
            </h1>
            <p class="text-xl text-purple-100 max-w-3xl mx-auto">
                Curated RSS feeds and external resources to help you develop your skills.
            </p>
        </div>
    </div>
</div>

<!-- RSS Feeds Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">RSS Learning Feeds</h2>
            <p class="text-lg text-gray-700">
                Stay updated with the latest content from top learning platforms tailored to your skill gaps.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Technical Skills Feed -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Technical Skills</h3>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-1">Introduction to Machine Learning</h4>
                            <p class="text-gray-600 text-sm mb-2">Learn the fundamentals of machine learning algorithms and applications.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Coursera • Updated 2 days ago</span>
                                <a href="https://www.coursera.org/learn/machine-learning" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-1">Advanced JavaScript Techniques</h4>
                            <p class="text-gray-600 text-sm mb-2">Master modern JavaScript patterns and best practices for web development.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">MDN Web Docs • Updated 5 days ago</span>
                                <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                        
                        <div class="pb-2">
                            <h4 class="font-medium text-gray-900 mb-1">Data Structures and Algorithms</h4>
                            <p class="text-gray-600 text-sm mb-2">Comprehensive guide to essential data structures and algorithms.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">freeCodeCamp • Updated 1 week ago</span>
                                <a href="https://www.freecodecamp.org/news/algorithms-and-data-structures-free-treehouse-course/" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                        Subscribe to feed
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Soft Skills Feed -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 rounded-full p-2 mr-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Soft Skills</h3>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-1">Effective Communication in the Workplace</h4>
                            <p class="text-gray-600 text-sm mb-2">Learn strategies for clear and impactful communication with colleagues and clients.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">LinkedIn Learning • Updated 3 days ago</span>
                                <a href="https://www.linkedin.com/learning/topics/communication" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                        
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-1">Time Management and Productivity</h4>
                            <p class="text-gray-600 text-sm mb-2">Master techniques to optimize your workflow and increase productivity.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Harvard Business Review • Updated 1 week ago</span>
                                <a href="https://hbr.org/topic/time-management" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                        
                        <div class="pb-2">
                            <h4 class="font-medium text-gray-900 mb-1">Leadership and Team Management</h4>
                            <p class="text-gray-600 text-sm mb-2">Develop essential leadership skills to effectively manage teams and projects.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">MindTools • Updated 4 days ago</span>
                                <a href="https://www.mindtools.com/pages/main/newMN_LDR.htm" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Read more</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                        Subscribe to feed
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Industry-Specific Feeds -->
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Industry-Specific Resources</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Software Development</h4>
                        <p class="text-gray-600 mb-4">
                            Latest trends, tools, and best practices in software development from industry leaders.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">10+ sources • Daily updates</span>
                            <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                View feed
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Data Science</h4>
                        <p class="text-gray-600 mb-4">
                            Cutting-edge research, tutorials, and resources for data scientists and analysts.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">8+ sources • Weekly updates</span>
                            <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                View feed
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-2">UX/UI Design</h4>
                        <p class="text-gray-600 mb-4">
                            Design principles, case studies, and inspiration for creating exceptional user experiences.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">6+ sources • Bi-weekly updates</span>
                            <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                View feed
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- External Learning Platforms -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Recommended Learning Platforms</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e3/Udemy_logo.svg" alt="Udemy" class="h-12 mx-auto mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Udemy</h3>
                <p class="text-gray-600 mb-4">Practical, skills-based courses taught by industry experts.</p>
                <a href="https://www.udemy.com/" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">Visit platform</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e5/Coursera_logo.PNG" alt="Coursera" class="h-12 mx-auto mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Coursera</h3>
                <p class="text-gray-600 mb-4">University-level courses from top institutions worldwide.</p>
                <a href="https://www.coursera.org/" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">Visit platform</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a7/Pluralsight-logo.png" alt="Pluralsight" class="h-12 mx-auto mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pluralsight</h3>
                <p class="text-gray-600 mb-4">Technology and creative skills courses for professionals.</p>
                <a href="https://www.pluralsight.com/" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">Visit platform</a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/01/LinkedIn_Logo.svg" alt="LinkedIn Learning" class="h-12 mx-auto mb-4">
                <h3 class="text-xl font-bold text-gray-900 mb-2">LinkedIn Learning</h3>
                <p class="text-gray-600 mb-4">Business, creative, and technology courses with professional focus.</p>
                <a href="https://www.linkedin.com/learning/" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">Visit platform</a>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-indigo-700 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to accelerate your learning?</h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">
            Subscribe to our personalized learning recommendations based on your skill gaps and career goals.
        </p>
        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50">
                Subscribe to updates
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Return to dashboard
            </a>
        </div>
    </div>
</div>

@endsection