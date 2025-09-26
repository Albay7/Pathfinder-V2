@extends('pathfinder.layout')

@section('title', 'CV Analysis - Upload Your Resume')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-br from-green-600 to-teal-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                CV Analysis & Job Matching
            </h1>
            <p class="text-xl text-green-100 max-w-3xl mx-auto">
                Upload your CV and discover the best job opportunities that match your skills using advanced TF-IDF analysis
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Upload Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Upload Your CV</h2>
                
                <!-- Upload Form -->
                <div id="upload-section">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-400 transition-colors duration-200" id="drop-zone">
                        <div class="mb-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="text-lg text-gray-600 mb-2">Drop your CV here or click to browse</p>
                        <p class="text-sm text-gray-500 mb-4">Supports PDF, DOC, DOCX, TXT files (max 10MB)</p>
                        <input type="file" id="cv-file-input" class="hidden" accept=".pdf,.doc,.docx,.txt">
                        <button type="button" id="browse-btn" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Choose File
                        </button>
                    </div>
                    
                    <!-- Selected File Info -->
                    <div id="file-info" class="hidden mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    
                    <!-- Upload Button -->
                    <div class="mt-6">
                        <button type="button" id="upload-btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Analyze CV
                        </button>
                    </div>
                </div>
                
                <!-- Progress Section -->
                <div id="progress-section" class="hidden">
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                                <svg class="animate-spin h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Analyzing Your CV</h3>
                        <p class="text-sm text-gray-600 mb-4" id="progress-text">Extracting skills and matching with job opportunities...</p>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%" id="progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Section -->
        <div id="results-section" class="hidden">
            <!-- Analysis Summary -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Analysis Results</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-6 bg-blue-50 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600 mb-2" id="skills-count">0</div>
                            <div class="text-sm text-gray-600">Skills Identified</div>
                        </div>
                        <div class="text-center p-6 bg-green-50 rounded-lg">
                            <div class="text-3xl font-bold text-green-600 mb-2" id="matches-count">0</div>
                            <div class="text-sm text-gray-600">Job Matches</div>
                        </div>
                        <div class="text-center p-6 bg-purple-50 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600 mb-2" id="best-match">0%</div>
                            <div class="text-sm text-gray-600">Best Match</div>
                        </div>
                    </div>
                    
                    <!-- Top Skills -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Skills Identified</h3>
                        <div class="flex flex-wrap gap-2" id="top-skills">
                            <!-- Skills will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Job Matches -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Recommended Jobs</h2>
                    <div id="job-matches">
                        <!-- Job matches will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Section -->
        <div id="error-section" class="hidden bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Analysis Failed</h3>
                    <div class="mt-2 text-sm text-red-700" id="error-message">
                        <!-- Error message will be populated here -->
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
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');
    
    const uploadSection = document.getElementById('upload-section');
    const progressSection = document.getElementById('progress-section');
    const resultsSection = document.getElementById('results-section');
    const errorSection = document.getElementById('error-section');
    
    let selectedFile = null;
    
    // File input change handler
    fileInput.addEventListener('change', handleFileSelect);
    browseBtn.addEventListener('click', () => fileInput.click());
    uploadBtn.addEventListener('click', uploadAndAnalyze);
    removeFileBtn.addEventListener('click', removeFile);
    
    // Drag and drop handlers
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-green-400', 'bg-green-50');
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-green-400', 'bg-green-50');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-green-400', 'bg-green-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    }
    
    function handleFile(file) {
        // Validate file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
        if (!allowedTypes.includes(file.type)) {
            showError('Please select a valid file type (PDF, DOC, DOCX, or TXT)');
            return;
        }
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            showError('File size must be less than 10MB');
            return;
        }
        
        selectedFile = file;
        
        // Show file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        uploadBtn.disabled = false;
        
        // Hide error if showing
        errorSection.classList.add('hidden');
    }
    
    function removeFile() {
        selectedFile = null;
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        uploadBtn.disabled = true;
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    async function uploadAndAnalyze() {
        if (!selectedFile) return;
        
        // Show progress section
        uploadSection.classList.add('hidden');
        progressSection.classList.remove('hidden');
        resultsSection.classList.add('hidden');
        errorSection.classList.add('hidden');
        
        // Simulate progress
        let progress = 0;
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 500);
        
        try {
            const formData = new FormData();
            formData.append('cv_file', selectedFile);
            
            const response = await fetch('/api/cv-analysis/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            
            setTimeout(() => {
                progressSection.classList.add('hidden');
                
                if (result.success) {
                    displayResults(result.data);
                } else {
                    showError(result.message || 'Analysis failed');
                }
            }, 1000);
            
        } catch (error) {
            clearInterval(progressInterval);
            progressSection.classList.add('hidden');
            showError('Network error: ' + error.message);
        }
    }
    
    function displayResults(data) {
        // Update summary stats
        document.getElementById('skills-count').textContent = data.analysis_summary.total_skills_found;
        document.getElementById('matches-count').textContent = data.analysis_summary.total_job_matches;
        document.getElementById('best-match').textContent = data.analysis_summary.best_match ? 
            data.analysis_summary.best_match.similarity + '%' : '0%';
        
        // Display top skills
        const topSkillsContainer = document.getElementById('top-skills');
        topSkillsContainer.innerHTML = '';
        
        data.analysis_summary.top_skills.forEach(skill => {
            const skillBadge = document.createElement('span');
            skillBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
            skillBadge.textContent = skill;
            topSkillsContainer.appendChild(skillBadge);
        });
        
        // Display job matches
        const jobMatchesContainer = document.getElementById('job-matches');
        jobMatchesContainer.innerHTML = '';
        
        if (data.job_matches.length === 0) {
            jobMatchesContainer.innerHTML = '<p class="text-gray-500 text-center py-8">No job matches found. Try uploading a more detailed CV.</p>';
        } else {
            data.job_matches.forEach(job => {
                const jobCard = createJobCard(job);
                jobMatchesContainer.appendChild(jobCard);
            });
        }
        
        resultsSection.classList.remove('hidden');
    }
    
    function createJobCard(job) {
        const card = document.createElement('div');
        card.className = 'border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-md transition-shadow duration-200';
        
        card.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">${job.job_title}</h3>
                    <p class="text-sm text-gray-600">${job.company || 'Company not specified'}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">${job.similarity_score}%</div>
                    <div class="text-sm text-gray-500">Match</div>
                </div>
            </div>
            
            <p class="text-gray-700 mb-4">${job.description}</p>
            
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Matching Skills:</h4>
                <div class="flex flex-wrap gap-2">
                    ${job.matching_skills.map(skill => 
                        `<span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            ${skill.skill} (${skill.match_strength})
                        </span>`
                    ).join('')}
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium" onclick="compareWithJob(${job.job_id})">
                    View Detailed Comparison
                </button>
                <span class="text-xs text-gray-500">Job ID: ${job.job_id}</span>
            </div>
        `;
        
        return card;
    }
    
    function showError(message) {
        document.getElementById('error-message').textContent = message;
        errorSection.classList.remove('hidden');
        uploadSection.classList.remove('hidden');
    }
});

// Global function for job comparison
function compareWithJob(jobId) {
    // This would open a modal or navigate to a detailed comparison page
    console.log('Compare with job:', jobId);
    // Implementation for detailed job comparison
}
</script>
@endpush
@endsection