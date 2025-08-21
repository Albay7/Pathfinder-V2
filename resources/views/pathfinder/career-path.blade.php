@extends('pathfinder.layout')

@section('title', 'Career Path Visualizer - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-green-600 to-emerald-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Career Path Visualizer
            </h1>
            <p class="text-xl text-green-100 max-w-3xl mx-auto">
                Map out your journey from where you are now to where you want to be. Get a clear, step-by-step roadmap with timelines and milestones.
            </p>
        </div>
    </div>
</div>

<!-- Form Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Plan Your Career Journey
                </h2>
                <p class="text-lg text-gray-600">
                    Tell us about your current position and where you want to go, and we'll create a personalized roadmap for you.
                </p>
            </div>
            
            <form action="{{ route('pathfinder.career-path.show') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Current Role Section -->
                <div>
                    <label for="current_role" class="block text-lg font-semibold text-gray-900 mb-4">
                        What is your current role or situation?
                    </label>
                    <select name="current_role" id="current_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900" required>
                        <option value="">Select your current situation...</option>
                        <option value="Student">Student</option>
                        <option value="Recent Graduate">Recent Graduate</option>
                        <option value="Entry Level Professional">Entry Level Professional</option>
                        <option value="Junior Developer">Junior Developer</option>
                        <option value="Marketing Assistant">Marketing Assistant</option>
                        <option value="Sales Representative">Sales Representative</option>
                        <option value="Customer Service Rep">Customer Service Representative</option>
                        <option value="Administrative Assistant">Administrative Assistant</option>
                        <option value="Freelancer">Freelancer</option>
                        <option value="Career Changer">Career Changer</option>
                        <option value="Unemployed">Currently Unemployed</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <!-- Target Role Section -->
                <div>
                    <label for="target_role" class="block text-lg font-semibold text-gray-900 mb-4">
                        What is your target role or career goal?
                    </label>
                    <select name="target_role" id="target_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900" required>
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
                            <option value="Software Architect">Software Architect</option>
                        </optgroup>
                        <optgroup label="Design">
                            <option value="UX Designer">UX Designer</option>
                            <option value="UI Designer">UI Designer</option>
                            <option value="Graphic Designer">Graphic Designer</option>
                            <option value="Web Designer">Web Designer</option>
                            <option value="Creative Director">Creative Director</option>
                        </optgroup>
                        <optgroup label="Marketing">
                            <option value="Digital Marketing Specialist">Digital Marketing Specialist</option>
                            <option value="Content Marketing Manager">Content Marketing Manager</option>
                            <option value="SEO Specialist">SEO Specialist</option>
                            <option value="Social Media Manager">Social Media Manager</option>
                            <option value="Marketing Director">Marketing Director</option>
                        </optgroup>
                        <optgroup label="Business">
                            <option value="Business Analyst">Business Analyst</option>
                            <option value="Project Manager">Project Manager</option>
                            <option value="Operations Manager">Operations Manager</option>
                            <option value="Consultant">Consultant</option>
                            <option value="Entrepreneur">Entrepreneur</option>
                        </optgroup>
                        <optgroup label="Finance">
                            <option value="Financial Analyst">Financial Analyst</option>
                            <option value="Investment Banker">Investment Banker</option>
                            <option value="Accountant">Accountant</option>
                            <option value="Financial Advisor">Financial Advisor</option>
                        </optgroup>
                    </select>
                </div>
                
                <!-- Additional Information -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">💡 Pro Tip</h3>
                    <p class="text-blue-800">
                        The more specific you are about your current situation and target role, the more accurate and helpful your career path will be. Don't worry if you're not sure about everything - we'll provide guidance for various scenarios.
                    </p>
                </div>
                
                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="inline-flex items-center px-8 py-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-lg">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Generate My Career Path
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                What You'll Get
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our career path visualizer provides comprehensive guidance to help you navigate your professional journey.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2H9m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Step-by-Step Roadmap</h3>
                <p class="text-gray-600">Clear, actionable steps from your current position to your dream job, with detailed descriptions for each milestone.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Timeline Estimates</h3>
                <p class="text-gray-600">Realistic timeframes for each step, helping you plan and set achievable goals for your career progression.</p>
            </div>
            
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-lg mx-auto mb-4">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Actionable Insights</h3>
                <p class="text-gray-600">Practical advice and resources for each step, including skills to develop and experiences to gain.</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Stories Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Success Stories
            </h2>
            <p class="text-lg text-gray-600">
                See how others have used our career path visualizer to achieve their goals
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                        S
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold text-gray-900">Sarah M.</h4>
                        <p class="text-sm text-gray-600">Student → UX Designer</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm">
                    "The career path helped me transition from a psychology student to a UX designer in just 18 months. The step-by-step approach made it feel achievable!"
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                        M
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold text-gray-900">Mike R.</h4>
                        <p class="text-sm text-gray-600">Sales Rep → Product Manager</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm">
                    "I followed the roadmap exactly and landed my dream product manager role. The timeline estimates were spot on!"
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        A
                    </div>
                    <div class="ml-3">
                        <h4 class="font-semibold text-gray-900">Anna L.</h4>
                        <p class="text-sm text-gray-600">Career Changer → Data Scientist</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm">
                    "At 35, I thought it was too late to change careers. The visualizer showed me it was possible and gave me confidence to make the switch."
                </p>
            </div>
        </div>
    </div>
</div>
@endsection