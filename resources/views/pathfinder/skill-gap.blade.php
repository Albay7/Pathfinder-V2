@extends('pathfinder.layout')

@section('title', 'Skill Gap Analyzer - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-purple-600 to-indigo-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Skill Gap Analyzer
            </h1>
            <p class="text-xl text-purple-100 max-w-3xl mx-auto">
                Identify the difference between your current skills and what's required for your target career. Get actionable insights to bridge the gap.
            </p>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mx-auto mb-4">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Analyze Your Skill Gap
                </h2>
                <p class="text-lg text-gray-600">
                    Tell us about your current skills and target role to get a detailed analysis of what you need to improve.
                </p>
            </div>
            
            <form action="{{ route('pathfinder.skill-gap.analyze') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Target Role Section -->
                <div>
                    <label for="target_role" class="block text-lg font-semibold text-gray-900 mb-4">
                        What is your target role?
                    </label>
                    <select name="target_role" id="target_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-gray-900" required>
                        <option value="">Select your target role...</option>
                        <optgroup label="Technology">
                            <option value="Frontend Developer">Frontend Developer</option>
                            <option value="Backend Developer">Backend Developer</option>
                            <option value="Full Stack Developer">Full Stack Developer</option>
                            <option value="Data Scientist">Data Scientist</option>
                            <option value="Data Analyst">Data Analyst</option>
                            <option value="DevOps Engineer">DevOps Engineer</option>
                            <option value="Cybersecurity Analyst">Cybersecurity Analyst</option>
                            <option value="Product Manager">Product Manager</option>
                        </optgroup>
                        <optgroup label="Design">
                            <option value="UX Designer">UX Designer</option>
                            <option value="UI Designer">UI Designer</option>
                            <option value="Graphic Designer">Graphic Designer</option>
                            <option value="Web Designer">Web Designer</option>
                        </optgroup>
                        <optgroup label="Marketing">
                            <option value="Digital Marketer">Digital Marketer</option>
                            <option value="Content Marketing Manager">Content Marketing Manager</option>
                            <option value="SEO Specialist">SEO Specialist</option>
                            <option value="Social Media Manager">Social Media Manager</option>
                        </optgroup>
                        <optgroup label="Business">
                            <option value="Business Analyst">Business Analyst</option>
                            <option value="Project Manager">Project Manager</option>
                            <option value="Operations Manager">Operations Manager</option>
                            <option value="Consultant">Consultant</option>
                        </optgroup>
                    </select>
                </div>
                
                <!-- Current Skills Section -->
                <div>
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        What are your current skills?
                    </label>
                    <p class="text-sm text-gray-600 mb-4">
                        Select all skills that you currently possess. Be honest about your skill level to get accurate recommendations.
                    </p>
                    
                    <!-- Skill Categories -->
                    <div class="space-y-6">
                        <!-- Technical Skills -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Technical Skills</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $technicalSkills = [
                                        'HTML', 'CSS', 'JavaScript', 'React', 'Vue.js', 'Angular',
                                        'PHP', 'Python', 'Java', 'Node.js', 'SQL', 'MongoDB',
                                        'Git', 'Docker', 'AWS', 'Azure', 'Linux', 'API Development',
                                        'Machine Learning', 'Data Analysis', 'Statistics', 'R',
                                        'Pandas', 'Numpy', 'TensorFlow', 'PyTorch'
                                    ];
                                @endphp
                                @foreach($technicalSkills as $skill)
                                    <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors duration-200">
                                        <input type="checkbox" name="current_skills[]" value="{{ $skill }}" class="mr-2 text-purple-600">
                                        <span class="text-sm text-gray-700">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Design Skills -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Design Skills</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $designSkills = [
                                        'Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator',
                                        'User Research', 'Wireframing', 'Prototyping', 'Design Thinking',
                                        'UI Design', 'UX Design', 'Visual Design', 'Interaction Design'
                                    ];
                                @endphp
                                @foreach($designSkills as $skill)
                                    <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors duration-200">
                                        <input type="checkbox" name="current_skills[]" value="{{ $skill }}" class="mr-2 text-purple-600">
                                        <span class="text-sm text-gray-700">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Marketing Skills -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Marketing Skills</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $marketingSkills = [
                                        'Google Analytics', 'SEO', 'SEM', 'Social Media Marketing',
                                        'Content Marketing', 'Email Marketing', 'PPC', 'Facebook Ads',
                                        'Google Ads', 'Marketing Automation', 'Copywriting', 'Brand Strategy'
                                    ];
                                @endphp
                                @foreach($marketingSkills as $skill)
                                    <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors duration-200">
                                        <input type="checkbox" name="current_skills[]" value="{{ $skill }}" class="mr-2 text-purple-600">
                                        <span class="text-sm text-gray-700">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Soft Skills -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Soft Skills</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $softSkills = [
                                        'Communication', 'Leadership', 'Teamwork', 'Problem Solving',
                                        'Critical Thinking', 'Time Management', 'Project Management',
                                        'Presentation Skills', 'Negotiation', 'Adaptability', 'Creativity'
                                    ];
                                @endphp
                                @foreach($softSkills as $skill)
                                    <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-purple-50 cursor-pointer transition-colors duration-200">
                                        <input type="checkbox" name="current_skills[]" value="{{ $skill }}" class="mr-2 text-purple-600">
                                        <span class="text-sm text-gray-700">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Pro Tip</h3>
                    <p class="text-blue-800">
                        Be honest about your current skill level. Our analysis will help you identify exactly what you need to focus on to reach your career goals. Don't worry if you don't have many skills selected - everyone starts somewhere!
                    </p>
                </div>
                
                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="inline-flex items-center px-8 py-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors duration-200 shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Analyze My Skills
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                What You'll Discover
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our skill gap analysis provides detailed insights to help you focus your learning efforts effectively.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Skills You Have</h3>
                <p class="text-gray-600">See which skills you already possess that are relevant to your target role and build confidence.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Skills You Need</h3>
                <p class="text-gray-600">Identify the specific skills you need to develop to qualify for your target role.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Learning Priorities</h3>
                <p class="text-gray-600">Get prioritized recommendations on which skills to focus on first for maximum impact.</p>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                How Our Analysis Works
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    1
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Select Target Role</h3>
                <p class="text-gray-600">Choose the role you want to pursue from our comprehensive list.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    2
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mark Your Skills</h3>
                <p class="text-gray-600">Honestly assess and select your current skill set across different categories.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    3
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Analysis</h3>
                <p class="text-gray-600">Receive detailed comparison between your skills and role requirements.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full mx-auto mb-4 text-xl font-bold">
                    4
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Plan Learning</h3>
                <p class="text-gray-600">Use insights to create a focused learning plan and track your progress.</p>
            </div>
        </div>
    </div>
</div>
@endsection