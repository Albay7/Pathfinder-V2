@extends('pathfinder.layout')

@section('title', 'Resume Analysis - Upload Your Resume')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Resume Analysis & Job Matching
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                Upload your Resume and discover the best job opportunities that match your skills using advanced TF-IDF analysis
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Analysis Failed</h3>
                        <div class="mt-2 text-sm text-red-700">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Validation Error</h3>
                        <div class="mt-2 text-sm text-red-700">{{ $errors->first('cv_file') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Upload Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-2xl font-bold mb-6" style="color: #13264D;">Upload Your Resume</h2>

                <form method="POST" action="{{ route('pathfinder.cv-upload.analyze') }}" enctype="multipart/form-data" id="cv-upload-form">
                    @csrf

                    <!-- Drop Zone -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-colors duration-200 cursor-pointer" id="drop-zone">
                        <div class="mb-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="text-lg text-gray-600 mb-2">Drop your Resume here or click to browse</p>
                        <p class="text-sm text-gray-500 mb-4">Supports PDF, DOC, DOCX, TXT files (max 10MB)</p>
                        <input type="file" name="cv_file" id="cv-file-input" class="hidden" accept=".pdf,.doc,.docx,.txt">
                        <button type="button" id="browse-btn" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white transition-colors duration-200" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose File
                        </button>
                    </div>

                    <!-- Selected File Info -->
                    <div id="file-info" class="hidden mt-4 p-4 rounded-lg" style="background-color: #EFF6FF;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 mr-3" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900" id="file-name"></p>
                                    <p class="text-sm text-gray-500" id="file-size"></p>
                                </div>
                            </div>
                            <button type="button" id="remove-file" class="text-red-600 hover:text-red-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" id="upload-btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="mr-2 h-5 w-5" id="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span id="btn-text">Analyze Resume</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- How It Works -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold mb-6" style="color: #13264D;">How It Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 text-white font-bold" style="background-color: #5AA7C6;">1</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Upload Your Resume</h3>
                        <p class="text-sm text-gray-600">Upload your resume in PDF, DOC, DOCX, or TXT format.</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 text-white font-bold" style="background-color: #2D5A7B;">2</div>
                        <h3 class="font-semibold text-gray-900 mb-2">TF-IDF Analysis</h3>
                        <p class="text-sm text-gray-600">Our algorithm extracts and scores your skills using TF-IDF.</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 text-white font-bold" style="background-color: #13264D;">3</div>
                        <h3 class="font-semibold text-gray-900 mb-2">Get Matched</h3>
                        <p class="text-sm text-gray-600">See your best career matches with similarity scores.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('cv-file-input');
    const browseBtn = document.getElementById('browse-btn');
    const uploadBtn = document.getElementById('upload-btn');
    const fileInfo = document.getElementById('file-info');
    const fileNameEl = document.getElementById('file-name');
    const fileSizeEl = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');
    const form = document.getElementById('cv-upload-form');

    browseBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.click();
    });

    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            showFileInfo(this.files[0]);
        }
    });

    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        uploadBtn.disabled = true;
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-green-400', 'bg-green-50');
    });
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-green-400', 'bg-green-50');
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-green-400', 'bg-green-50');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showFileInfo(e.dataTransfer.files[0]);
        }
    });

    // Show loading state on submit
    form.addEventListener('submit', function() {
        if (uploadBtn.disabled) return false;
        uploadBtn.disabled = true;
        document.getElementById('btn-text').textContent = 'Analyzing...';
        document.getElementById('btn-icon').innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
        document.getElementById('btn-icon').classList.add('animate-spin');
    });

    function showFileInfo(file) {
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|doc|docx|txt)$/i)) {
            alert('Please select a valid file type (PDF, DOC, DOCX, or TXT)');
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }
        fileNameEl.textContent = file.name;
        fileSizeEl.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        uploadBtn.disabled = false;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
@endsection
