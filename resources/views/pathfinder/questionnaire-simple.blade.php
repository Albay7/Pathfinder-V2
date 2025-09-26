@extends('pathfinder.layout')

@section('title', 'Course Assessment - Simple Test')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Course Assessment - Simple Test</h1>
            <p class="text-xl text-gray-600">Testing basic questionnaire functionality.</p>
        </div>

        <!-- Question Container -->
        <div id="question-container" class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="text-center text-gray-500">
                <p>Loading questionnaire...</p>
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mt-4"></div>
            </div>
        </div>

        <!-- Debug Information -->
        <div id="debug-info" class="mt-4 p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
            <h4 class="font-bold text-yellow-800">Debug Information:</h4>
            <div id="debug-content" class="text-sm text-yellow-700 mt-2">JavaScript not yet loaded...</div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8">
            <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg">Previous</button>
            <button type="button" id="next-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg">Next</button>
        </div>
    </div>
</div>

<script>
// Simple test script
document.addEventListener('DOMContentLoaded', function() {
    const debugContent = document.getElementById('debug-content');
    const questionContainer = document.getElementById('question-container');
    
    debugContent.innerHTML = 'DOM loaded successfully!';
    
    // Test basic functionality
    if (questionContainer) {
        debugContent.innerHTML += '<br>Question container found!';
        
        // Simple question display
        questionContainer.innerHTML = `
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-4">Test Question</h3>
                <p class="text-gray-700 mb-4">Which career field interests you most?</p>
                <div class="space-y-3">
                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="test_question" value="business" class="mr-3">
                        <span>Business Administration</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="test_question" value="engineering" class="mr-3">
                        <span>Engineering & Technology</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="test_question" value="health" class="mr-3">
                        <span>Health Sciences</span>
                    </label>
                </div>
            </div>
        `;
        
        debugContent.innerHTML += '<br>Question rendered successfully!';
    } else {
        debugContent.innerHTML += '<br>ERROR: Question container not found!';
    }
});
</script>
@endsection