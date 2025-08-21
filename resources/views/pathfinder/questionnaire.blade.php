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
            <span class="text-sm font-medium text-gray-700"><span id="current-question">1</span> of <span id="total-questions">8</span></span>
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
                                    <input type="radio" name="field_interest" value="technology" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Technology & Programming</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="business" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Business & Management</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="creative" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Creative & Design</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="healthcare" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Healthcare & Medicine</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="field_interest" value="science" class="mr-4 text-blue-600">
                                    <span class="text-gray-700">Science & Research</span>
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
                @else
                    <!-- Job Questions -->
                    <div class="question-slide active" data-question="1">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What type of work environment do you prefer?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment" value="office" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Traditional office setting</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment" value="remote" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Remote/Work from home</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment" value="hybrid" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Hybrid (mix of office and remote)</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_environment" value="field" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Field work/On-site</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide" data-question="2">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which work style suits you best?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_style" value="independent" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Independent work with minimal supervision</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_style" value="collaborative" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Collaborative team environment</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_style" value="leadership" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Leadership and management roles</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="work_style" value="client_facing" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Client-facing and customer interaction</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide" data-question="3">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">What motivates you most in your career?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="motivation" value="financial" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Financial rewards and compensation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="motivation" value="impact" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Making a positive impact on society</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="motivation" value="creativity" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Creative expression and innovation</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="motivation" value="growth" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Personal and professional growth</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="motivation" value="stability" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Job security and stability</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="question-slide" data-question="4">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Which industry appeals to you most?</h2>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="industry" value="technology" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Technology & Software</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="industry" value="finance" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Finance & Banking</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="industry" value="healthcare" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Healthcare & Medical</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="industry" value="education" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Education & Training</span>
                                </label>
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer transition-colors duration-200">
                                    <input type="radio" name="industry" value="marketing" class="mr-4 text-green-600">
                                    <span class="text-gray-700">Marketing & Advertising</span>
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
    const questions = document.querySelectorAll('.question-slide');
    const totalQuestions = questions.length;
    const progressBar = document.getElementById('progress-bar');
    const currentQuestionSpan = document.getElementById('current-question');
    const totalQuestionsSpan = document.getElementById('total-questions');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    let currentQuestion = 1;
    
    // Set total questions
    totalQuestionsSpan.textContent = totalQuestions;
    
    // Function to update progress
    function updateProgress() {
        const progress = (currentQuestion / totalQuestions) * 100;
        progressBar.style.width = progress + '%';
        currentQuestionSpan.textContent = currentQuestion;
    }
    
    // Function to show current question
    function showQuestion(questionNumber) {
        questions.forEach((question, index) => {
            if (index + 1 === questionNumber) {
                question.classList.add('active');
                question.style.display = 'block';
            } else {
                question.classList.remove('active');
                question.style.display = 'none';
            }
        });
        
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
        const currentQuestionElement = document.querySelector('.question-slide.active');
        const radioInputs = currentQuestionElement.querySelectorAll('input[type="radio"]');
        return Array.from(radioInputs).some(input => input.checked);
    }
    
    // Function to update next button state
    function updateNextButtonState() {
        nextBtn.disabled = !isCurrentQuestionAnswered();
    }
    
    // Add event listeners to radio buttons
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
    
    // Initialize
    showQuestion(1);
    updateNextButtonState();
});
</script>
@endpush
@endsection