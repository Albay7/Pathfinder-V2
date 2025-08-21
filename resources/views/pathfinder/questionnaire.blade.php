@extends('pathfinder.layout')

@section('title', 'Assessment Questionnaire - Pathfinder')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-{{ $type === 'course' ? 'blue' : 'green' }}-600 to-{{ $type === 'course' ? 'indigo' : 'emerald' }}-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                {{ $type === 'course' ? 'Course' : 'Job' }} Assessment
            </h1>
            <p class="text-xl text-{{ $type === 'course' ? 'blue' : 'green' }}-100 max-w-3xl mx-auto">
                Answer these questions to get personalized {{ $type === 'course' ? 'course' : 'job' }} recommendations tailored to your interests and goals.
            </p>
        </div>
    </div>
</div>

<!-- Progress Bar -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Progress</span>
            <span class="text-sm font-medium text-gray-700"><span id="current-question">1</span> of <span id="total-questions">7</span></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="progress-bar" class="bg-{{ $type === 'course' ? 'blue' : 'green' }}-600 h-2 rounded-full transition-all duration-300" style="width: 12.5%"></div>
        </div>
    </div>
</div>

<!-- Questionnaire Form -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form id="questionnaire-form" action="{{ route('pathfinder.questionnaire.process') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div id="questions-container">
                @if($type === 'course')
                    <!-- Course Questions -->
                    <div class="question-slide active" data-question="1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What is your current education level?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_level" value="high_school" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">High School / Secondary Education</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_level" value="bachelor" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Bachelor's Degree</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_level" value="master" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Master's Degree</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_level" value="professional" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Professional Certification</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="2">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which field interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="engineering" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Engineering & Technology (Civil, Electrical, Computer, Mechanical)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="computer_science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Computer Science & Information Technology</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="business" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business Administration & Management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="education" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Education & Teaching</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="accounting" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Accounting & Finance</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="liberal_arts" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Liberal Arts & Communication</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="tourism" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Tourism & Management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Science & Research</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="criminal_justice" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Criminal Justice Education</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="3">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What is your preferred learning style?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="learning_style" value="visual" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Visual (videos, diagrams, infographics)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="learning_style" value="hands_on" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Hands-on (practical exercises, projects)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="learning_style" value="reading" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Reading (books, articles, documentation)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="learning_style" value="interactive" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Interactive (discussions, group work)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="4">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">How much time can you dedicate to learning per week?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="time_commitment" value="1-5" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">1-5 hours per week</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="time_commitment" value="6-10" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">6-10 hours per week</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="time_commitment" value="11-20" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">11-20 hours per week</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="time_commitment" value="20+" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">More than 20 hours per week</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Engineering & Technology Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="engineering" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which engineering discipline interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_discipline" value="civil" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Civil Engineering - Infrastructure, buildings, roads</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_discipline" value="electrical" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Electrical Engineering - Power systems, electronics</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_discipline" value="computer" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Computer Engineering - Hardware and software integration</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_discipline" value="mechanical" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Mechanical Engineering - Machines, manufacturing</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_discipline" value="industrial" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Industrial Engineering - Process optimization</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="engineering" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of engineering work appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_type" value="design" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Design and planning new systems</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_type" value="construction" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Construction and implementation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_type" value="research" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Research and development</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_type" value="maintenance" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Maintenance and troubleshooting</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Computer Science & IT Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="computer_science" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which area of computer science interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="cs_specialization" value="software_development" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Software Development - Creating applications and systems</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="cs_specialization" value="web_development" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Web Development - Websites and web applications</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="cs_specialization" value="data_science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Data Science - Analytics and machine learning</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="cs_specialization" value="cybersecurity" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Cybersecurity - Protecting systems and data</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="cs_specialization" value="it_systems" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">IT Systems - Network and infrastructure management</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="computer_science" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What programming experience do you have?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="programming_experience" value="none" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">No programming experience</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="programming_experience" value="basic" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Basic programming (HTML, CSS, simple scripts)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="programming_experience" value="intermediate" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Intermediate (Python, Java, JavaScript)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="programming_experience" value="advanced" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Advanced programming and project experience</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Business Administration Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="business" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which business area interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_area" value="management" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">General Management - Leading teams and operations</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_area" value="marketing" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Marketing - Promoting products and services</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_area" value="finance" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Finance - Managing money and investments</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_area" value="entrepreneurship" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Entrepreneurship - Starting your own business</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_area" value="hr" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Human Resources - Managing people and talent</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="business" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of business environment do you prefer?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_environment" value="corporate" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Large corporation with structured processes</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_environment" value="startup" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Startup with fast-paced, dynamic environment</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_environment" value="sme" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Small to medium enterprise</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_environment" value="consulting" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Consulting firm working with various clients</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Education Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="education" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which age group would you prefer to teach?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_level" value="early_childhood" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Early Childhood (Ages 3-6)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_level" value="elementary" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Elementary (Ages 6-12)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_level" value="secondary" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Secondary/High School (Ages 12-18)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_level" value="adult" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Adult Education and Training</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="education" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What subject area would you like to specialize in?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_subject" value="general" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">General Education (All subjects)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_subject" value="mathematics" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Mathematics</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_subject" value="science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Science (Biology, Chemistry, Physics)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_subject" value="english" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">English and Literature</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="teaching_subject" value="social_studies" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Social Studies and History</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Accounting & Finance Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="accounting" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which area of accounting/finance interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="accounting_area" value="public_accounting" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Public Accounting - Auditing and tax services</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="accounting_area" value="corporate_finance" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Corporate Finance - Company financial management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="accounting_area" value="investment" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Investment and Portfolio Management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="accounting_area" value="banking" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Banking and Financial Services</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="accounting" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of financial work appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="financial_work" value="analysis" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Financial analysis and reporting</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="financial_work" value="planning" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Financial planning and budgeting</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="financial_work" value="compliance" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Compliance and regulatory work</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="financial_work" value="advisory" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Financial advisory and consulting</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Liberal Arts Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="liberal_arts" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which liberal arts area interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_area" value="communication" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Communication - Media, journalism, public relations</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_area" value="psychology" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Psychology - Understanding human behavior</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_area" value="english" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">English - Literature, writing, language</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_area" value="social_work" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Social Work - Helping communities and individuals</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide field-specific" data-question="6" data-field="liberal_arts" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of career in liberal arts appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_career" value="media" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Media and journalism</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_career" value="counseling" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Counseling and therapy</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_career" value="writing" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Writing and content creation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="liberal_arts_career" value="research" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Research and academia</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tourism & Management Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="tourism" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which area of tourism and management interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_area" value="hospitality" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Hospitality Management - Hotels, resorts, restaurants</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_area" value="travel_tourism" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Travel and Tourism - Tour operations, travel agencies</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_area" value="event_management" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Event Management - Conferences, weddings, corporate events</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_area" value="destination_marketing" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Destination Marketing - Promoting tourist destinations</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="tourism" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of tourism work environment appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_environment" value="luxury_resort" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Luxury resorts and high-end hotels</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_environment" value="adventure_tourism" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Adventure and eco-tourism</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_environment" value="cultural_heritage" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Cultural and heritage tourism</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_environment" value="business_travel" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business travel and corporate events</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Science & Research Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="science" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which science field interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_field" value="biology" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Biology - Life sciences, genetics, ecology</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_field" value="chemistry" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Chemistry - Chemical research, pharmaceuticals</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_field" value="physics" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Physics - Physical sciences, technology applications</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_field" value="environmental" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Environmental Science - Conservation, sustainability</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_field" value="mathematics" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Mathematics - Applied mathematics, statistics</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="science" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of scientific work appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_work" value="laboratory_research" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Laboratory research and experimentation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_work" value="field_research" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Field research and data collection</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_work" value="teaching_academia" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Teaching and academic research</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="science_work" value="industry_application" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Industry applications and product development</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Criminal Justice Education Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="criminal_justice" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which area of criminal justice interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_area" value="law_enforcement" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Law Enforcement - Police work, investigation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_area" value="forensics" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Forensic Science - Crime scene investigation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_area" value="corrections" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Corrections - Prison management, rehabilitation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_area" value="legal_studies" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Legal Studies - Court systems, legal research</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_area" value="cybersecurity" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Cybersecurity - Digital crime prevention</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="criminal_justice" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of criminal justice work environment appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_environment" value="field_patrol" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Field work and patrol duties</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_environment" value="office_investigation" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Office-based investigation and analysis</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_environment" value="courtroom" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Courtroom and legal proceedings</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="criminal_justice_environment" value="community_outreach" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Community outreach and prevention programs</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final Question for all paths -->
                    <div class="question-slide" data-question="7">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What motivates you most in choosing a course?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="passion_interest" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Personal passion and genuine interest</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="job_prospects" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">High job demand and career prospects</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="salary_potential" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">High salary potential</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="family_influence" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Family expectations and influence</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="social_impact" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Opportunity to make a positive social impact</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="5">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which activities do you enjoy most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="preferred_activities" value="problem_solving" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Solving complex problems and puzzles</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="preferred_activities" value="building_creating" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Building, designing, or creating things</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="preferred_activities" value="helping_teaching" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Helping and teaching others</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="preferred_activities" value="analyzing_data" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Analyzing data and finding patterns</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="preferred_activities" value="leading_organizing" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Leading teams and organizing projects</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="6">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What are your strongest subjects in school?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="strong_subjects" value="math_science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Mathematics and Science (Physics, Chemistry)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="strong_subjects" value="computer_tech" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Computer Science and Technology</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="strong_subjects" value="business_economics" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business Studies and Economics</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="strong_subjects" value="languages_social" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Languages and Social Studies</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="strong_subjects" value="arts_humanities" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Arts and Humanities</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="7">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of career do you envision for yourself?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_vision" value="technical_specialist" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Technical specialist or engineer</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_vision" value="business_leader" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business leader or entrepreneur</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_vision" value="educator_trainer" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Educator or trainer</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_vision" value="consultant_advisor" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Consultant or advisor</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_vision" value="researcher_analyst" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Researcher or analyst</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="8">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which DLSU Dasmariñas program area interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="dlsu_program_interest" value="ceat" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">College of Engineering, Architecture & Technology (CEAT)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="dlsu_program_interest" value="ccs" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">College of Computer Studies (CCS)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="dlsu_program_interest" value="cbaa" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">College of Business Administration & Accountancy (CBAA)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="dlsu_program_interest" value="coed" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">College of Education (COEd)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="dlsu_program_interest" value="cla" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">College of Liberal Arts (CLA)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="9">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What motivates you most in choosing a course?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="passion_interest" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Personal passion and genuine interest</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="job_prospects" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">High job demand and career prospects</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="salary_potential" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">High salary potential</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="family_influence" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Family expectations and influence</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="course_motivation" value="social_impact" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Opportunity to make a positive social impact</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="10">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">How do you prefer to work on projects?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_preference" value="individual_focused" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Individual work with deep focus</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_preference" value="small_team" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Small team collaboration</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_preference" value="large_group" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Large group projects</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_preference" value="leadership_role" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Leading and coordinating others</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_preference" value="mentoring_teaching" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Mentoring and teaching others</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="11">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which skills would you like to develop most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="skill_development" value="technical_programming" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Technical and programming skills</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="skill_development" value="analytical_research" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Analytical and research skills</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="skill_development" value="communication_presentation" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Communication and presentation skills</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="skill_development" value="business_management" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business and management skills</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="skill_development" value="creative_design" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Creative and design skills</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="12">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of work environment appeals to you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment_preference" value="corporate_office" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Corporate office environment</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment_preference" value="tech_startup" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Tech startup or innovation hub</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment_preference" value="educational_institution" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Educational institution or training center</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment_preference" value="field_construction" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Field work or construction sites</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment_preference" value="government_public" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Government or public service</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Job Assessment Questions -->
                    <div class="question-slide active" data-question="1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What is your primary career goal?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_goal" value="entry_level" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Find my first professional job</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_goal" value="career_advancement" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Advance to a higher position</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_goal" value="career_change" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Switch to a different industry</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_goal" value="skill_development" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Develop new professional skills</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="career_goal" value="entrepreneurship" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Start my own business</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="2">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which industry offers the career opportunities you're seeking?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="technology" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Technology & Software Development</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="business" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Business & Management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="finance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Finance & Banking</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="healthcare" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Healthcare & Medical</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="education" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Education & Training</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="marketing" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Marketing & Creative</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="engineering" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Engineering & Construction</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="government" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Government & Public Service</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_industry" value="tourism" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Tourism & Hospitality</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="3">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of work schedule do you prefer?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_schedule" value="standard" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Standard 9-5 weekday schedule</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_schedule" value="flexible" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Flexible hours with core time</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_schedule" value="shift_work" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Shift work (evenings, nights, weekends)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_schedule" value="project_based" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Project-based with varying hours</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_schedule" value="part_time" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Part-time or contract work</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="question-slide" data-question="4">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of responsibilities do you want in your job?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_responsibilities" value="technical_execution" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Technical execution and hands-on work</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_responsibilities" value="people_management" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Managing and leading people</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_responsibilities" value="strategic_planning" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Strategic planning and decision making</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_responsibilities" value="client_interaction" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Client interaction and relationship building</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_responsibilities" value="problem_solving" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Problem solving and troubleshooting</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technology Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="technology" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which technology role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="frontend" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Frontend Developer - User interfaces and web design</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="backend" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Backend Developer - Server-side and databases</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="fullstack" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Full-Stack Developer - Complete web applications</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="mobile" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Mobile Developer - iOS/Android applications</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="data" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Data Scientist/Analyst - Data analysis and insights</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_role" value="cybersecurity" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Cybersecurity Specialist - Security and protection</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="technology" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of technology company appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_company" value="startup" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Tech startup - Fast-paced, innovative environment</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_company" value="big_tech" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Large tech company - Established, structured environment</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_company" value="consulting" class="mr-4 text-green-600">
                                    <span class="text-gray-700">IT consulting firm - Variety of client projects</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tech_company" value="freelance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Freelance/Contract work - Flexible, independent</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="business" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which business role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_role" value="management" class="mr-4 text-green-600">
                                    <span class="text-gray-700">General Management - Leading teams and operations</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_role" value="operations" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Operations Manager - Process optimization</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_role" value="hr" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Human Resources - Talent management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_role" value="sales" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Sales Representative - Client acquisition</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="business_role" value="consultant" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Business Consultant - Strategic advisory</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="business" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What size company do you prefer?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="company_size" value="startup" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Startup (1-50 employees) - Dynamic, wear many hats</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="company_size" value="sme" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Small-Medium Enterprise (50-500 employees)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="company_size" value="large" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Large Corporation (500+ employees) - Structured, specialized roles</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="company_size" value="multinational" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Multinational Corporation - Global opportunities</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Finance Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="finance" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which finance role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_role" value="banking" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Banking - Loans, deposits, customer service</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_role" value="investment" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Investment Banking - Mergers, acquisitions, IPOs</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_role" value="accounting" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Accounting - Financial reporting, auditing</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_role" value="financial_planning" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Financial Planning - Personal wealth management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_role" value="insurance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Insurance - Risk assessment and claims</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="finance" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of financial work appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_work_type" value="analysis" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Financial analysis and research</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_work_type" value="client_service" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Client service and relationship management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_work_type" value="trading" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Trading and investment management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="finance_work_type" value="compliance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Compliance and regulatory work</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Healthcare Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="healthcare" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which healthcare role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_role" value="nursing" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Nursing - Direct patient care</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_role" value="medical_tech" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Medical Technology - Lab work, diagnostics</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_role" value="administration" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Healthcare Administration - Hospital management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_role" value="pharmacy" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Pharmacy - Medication management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_role" value="therapy" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Therapy - Physical, occupational, speech</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="healthcare" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What healthcare setting appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_setting" value="hospital" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Hospital - Acute care, emergency situations</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_setting" value="clinic" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Clinic - Outpatient care, routine visits</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_setting" value="home_care" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Home Healthcare - In-home patient visits</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="healthcare_setting" value="research" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Research Facility - Medical research and trials</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Education Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="education" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which education role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_role" value="teacher" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Classroom Teacher - Direct instruction</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_role" value="administrator" class="mr-4 text-green-600">
                                    <span class="text-gray-700">School Administrator - Principal, vice-principal</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_role" value="counselor" class="mr-4 text-green-600">
                                    <span class="text-gray-700">School Counselor - Student guidance</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_role" value="trainer" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Corporate Trainer - Professional development</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_role" value="curriculum" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Curriculum Developer - Educational content creation</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="education" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What age group would you prefer to work with?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_age_group" value="early_childhood" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Early Childhood (Ages 3-6)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_age_group" value="elementary" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Elementary (Ages 6-12)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_age_group" value="secondary" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Secondary/High School (Ages 12-18)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="education_age_group" value="adult" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Adult Education and Professional Training</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Marketing Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="marketing" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which marketing role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_role" value="digital" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Digital Marketing - Social media, online campaigns</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_role" value="content" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Content Marketing - Writing, video, creative content</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_role" value="brand" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Brand Management - Brand strategy and positioning</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_role" value="advertising" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Advertising - Campaign creation and media buying</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_role" value="market_research" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Market Research - Consumer insights and analytics</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="marketing" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of marketing work appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_work_type" value="creative" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Creative development - Design, copywriting, campaigns</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_work_type" value="analytical" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Data analysis - Metrics, ROI, performance tracking</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_work_type" value="strategic" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Strategic planning - Market positioning, growth strategies</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="marketing_work_type" value="execution" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Campaign execution - Implementation and coordination</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Engineering Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="engineering" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which engineering role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_job_role" value="civil" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Civil Engineer - Infrastructure, roads, buildings</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_job_role" value="mechanical" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Mechanical Engineer - Machines, manufacturing</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_job_role" value="electrical" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Electrical Engineer - Power systems, electronics</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_job_role" value="project_manager" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Project Manager - Construction project coordination</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_job_role" value="quality_control" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Quality Control Engineer - Standards and compliance</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="engineering" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of engineering work environment appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_environment" value="office_design" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Office-based design and planning</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_environment" value="field_construction" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Field work and construction sites</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_environment" value="manufacturing" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Manufacturing plants and facilities</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="engineering_work_environment" value="consulting" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Engineering consulting firm</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Government Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="government" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which government role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_role" value="civil_service" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Civil Service - Administrative and policy work</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_role" value="law_enforcement" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Law Enforcement - Police, investigation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_role" value="social_services" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Social Services - Community support programs</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_role" value="regulatory" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Regulatory Affairs - Compliance and oversight</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_role" value="public_health" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Public Health - Community health programs</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="government" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What level of government appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_level" value="local" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Local Government - City, municipal level</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_level" value="provincial" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Provincial Government - Regional administration</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_level" value="national" class="mr-4 text-green-600">
                                    <span class="text-gray-700">National Government - Federal departments</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="government_level" value="international" class="mr-4 text-green-600">
                                    <span class="text-gray-700">International Organizations - UN, NGOs</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tourism Industry Specific Questions -->
                    <div class="question-slide field-specific" data-question="5" data-field="tourism" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which tourism role interests you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_role" value="hotel_management" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Hotel Management - Operations and guest services</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_role" value="tour_guide" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Tour Guide - Leading tours and experiences</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_role" value="travel_agent" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Travel Agent - Trip planning and booking</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_role" value="event_coordinator" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Event Coordinator - Conferences and special events</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_role" value="restaurant_manager" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Restaurant Manager - Food service operations</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide field-specific" data-question="6" data-field="tourism" style="display: none;">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of tourism environment appeals to you?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_environment" value="luxury_resort" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Luxury resorts and high-end hotels</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_environment" value="adventure_tourism" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Adventure and eco-tourism</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_environment" value="cultural_heritage" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Cultural and heritage tourism</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="tourism_job_environment" value="business_travel" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Business travel and corporate events</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final Question for all job paths -->
                    <div class="question-slide" data-question="7">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What motivates you most in your career?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="financial" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Financial rewards and compensation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="impact" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Making a positive impact on society</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="creativity" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Creative expression and innovation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="growth" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Personal and professional growth</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="stability" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Job security and stability</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="job_motivation" value="work_life_balance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Work-life balance and flexibility</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Previous
                </button>
                <button type="button" id="next-btn" class="px-6 py-3 bg-{{ $type === 'course' ? 'blue' : 'green' }}-600 text-white font-medium rounded-lg hover:bg-{{ $type === 'course' ? 'blue' : 'green' }}-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Next
                </button>
                <button type="submit" id="submit-btn" class="hidden px-6 py-3 bg-{{ $type === 'course' ? 'blue' : 'green' }}-600 text-white font-medium rounded-lg hover:bg-{{ $type === 'course' ? 'blue' : 'green' }}-700 transition-colors duration-200">
                    Get Recommendations
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allQuestions = document.querySelectorAll('.question-slide');
    const progressBar = document.getElementById('progress-bar');
    const currentQuestionSpan = document.getElementById('current-question');
    const totalQuestionsSpan = document.getElementById('total-questions');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    let currentQuestion = 1;
    let selectedField = null;
    let questionFlow = [1, 2, 3, 4]; // Base questions
    let totalQuestions = 7; // Will be updated based on field selection

    // Set initial total questions
    totalQuestionsSpan.textContent = totalQuestions;

    // Function to update progress
    function updateProgress() {
        const progress = (currentQuestion / totalQuestions) * 100;
        progressBar.style.width = progress + '%';
        currentQuestionSpan.textContent = currentQuestion;
    }

    // Function to determine question flow based on field selection
    function updateQuestionFlow(fieldInterest) {
        selectedField = fieldInterest;

        // Reset all field-specific questions to hidden
        document.querySelectorAll('.field-specific').forEach(q => {
            q.style.display = 'none';
            q.classList.remove('active');
        });

        // Update question flow: base questions (1-4) + field-specific (5-6) + final question (7)
        questionFlow = [1, 2, 3, 4, 5, 6, 7];
        totalQuestions = 7;
        totalQuestionsSpan.textContent = totalQuestions;
    }

    // Function to show current question
    function showQuestion(questionNumber) {
        // Hide all questions first
        allQuestions.forEach(question => {
            question.classList.remove('active');
            question.style.display = 'none';
        });

        // Show the appropriate question based on current flow
        if (questionNumber <= 4) {
            // Show base questions (1-4)
            const targetQuestion = document.querySelector(`[data-question="${questionNumber}"]:not(.field-specific)`);
            if (targetQuestion) {
                targetQuestion.classList.add('active');
                targetQuestion.style.display = 'block';
            }
        } else if (questionNumber === 5 || questionNumber === 6) {
            // Show field-specific questions (5-6)
            if (selectedField) {
                const targetQuestion = document.querySelector(`[data-question="${questionNumber}"][data-field="${selectedField}"]`);
                if (targetQuestion) {
                    targetQuestion.classList.add('active');
                    targetQuestion.style.display = 'block';
                }
            }
        } else if (questionNumber === 7) {
            // Show final question
            const targetQuestion = document.querySelector(`[data-question="7"]:not(.field-specific)`);
            if (targetQuestion) {
                targetQuestion.classList.add('active');
                targetQuestion.style.display = 'block';
            }
        }

        // Update button states
        prevBtn.disabled = questionNumber === 1;

        if (questionNumber === totalQuestions) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }

        updateProgress();
    }

    // Function to check if current question is answered
    function isCurrentQuestionAnswered() {
        if (currentQuestion <= 4) {
            const currentQuestionElement = document.querySelector(`[data-question="${currentQuestion}"]:not(.field-specific)`);
            if (currentQuestionElement) {
                const radios = currentQuestionElement.querySelectorAll('input[type="radio"]');
                return Array.from(radios).some(radio => radio.checked);
            }
        } else if (currentQuestion === 5 || currentQuestion === 6) {
            if (selectedField) {
                const currentQuestionElement = document.querySelector(`[data-question="${currentQuestion}"][data-field="${selectedField}"]`);
                if (currentQuestionElement) {
                    const radios = currentQuestionElement.querySelectorAll('input[type="radio"]');
                    return Array.from(radios).some(radio => radio.checked);
                }
            }
        } else if (currentQuestion === 7) {
            const currentQuestionElement = document.querySelector(`[data-question="7"]:not(.field-specific)`);
            if (currentQuestionElement) {
                const radios = currentQuestionElement.querySelectorAll('input[type="radio"]');
                return Array.from(radios).some(radio => radio.checked);
            }
        }
        return false;
    }

    // Function to update next button state
    function updateNextButtonState() {
        nextBtn.disabled = !isCurrentQuestionAnswered();
    }

    // Add event listeners to radio buttons for field interest (question 2)
    // Handle both course (field_interest) and job (job_industry) questionnaires
    const fieldInterestInputs = document.querySelectorAll('input[name="field_interest"], input[name="job_industry"]');
    fieldInterestInputs.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked && currentQuestion === 2) {
                updateQuestionFlow(this.value);
            }
            updateNextButtonState();
        });
    });

    // Add event listeners to all radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', updateNextButtonState);
    });

    // Previous button click
    prevBtn.addEventListener('click', function() {
        if (currentQuestion > 1) {
            currentQuestion--;
            showQuestion(currentQuestion);
            updateNextButtonState();
        }
    });

    // Next button click
    nextBtn.addEventListener('click', function() {
        if (currentQuestion < totalQuestions && isCurrentQuestionAnswered()) {
            currentQuestion++;
            showQuestion(currentQuestion);
            updateNextButtonState();
        }
    });

    // Initialize - hide all field-specific questions on page load
    document.querySelectorAll('.field-specific').forEach(q => {
        q.style.display = 'none';
        q.classList.remove('active');
    });

    // Initialize
    showQuestion(1);
    updateNextButtonState();
});
</script>
@endpush
@endsection
