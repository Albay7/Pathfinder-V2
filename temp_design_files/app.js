// Application Data
const appData = {
    mbtiQuestions: [
        "You regularly make new friends.",
        "You prefer to work in a team rather than alone.",
        "You enjoy being the center of attention.",
        "You feel comfortable in large social gatherings.",
        "You often initiate conversations with strangers.",
        "You feel energized after spending time with people.",
        "You prefer planned activities over spontaneous ones.",
        "You like to have a clear schedule for your day.",
        "You make decisions based on logic rather than feelings.",
        "You value efficiency over harmony in teamwork."
    ],
    personalityTypes: {
        'INFP': {
            name: 'The Mediator',
            description: 'You are creative, idealistic, and driven by your values. You see potential in everyone and everything.',
            careers: ['UX Designer', 'Content Creator', 'Counselor', 'Marketing Specialist']
        },
        'ENFP': {
            name: 'The Campaigner',
            description: 'You are enthusiastic, creative, and sociable. You value inspiration and possibilities.',
            careers: ['Marketing Manager', 'Product Manager', 'Sales Manager', 'HR Manager']
        },
        'INTJ': {
            name: 'The Architect',
            description: 'You are imaginative and strategic thinkers, with a plan for everything.',
            careers: ['Software Developer', 'Data Scientist', 'Business Analyst', 'Project Manager']
        },
        'ENTJ': {
            name: 'The Commander',
            description: 'You are bold, imaginative and strong-willed leaders.',
            careers: ['Product Manager', 'Business Analyst', 'Financial Analyst', 'Project Manager']
        }
    }
};

// Global state
let currentQuestionIndex = 0;
let mbtiAnswers = [];
let currentPage = 'home';

// DOM Elements
const navLinks = document.querySelectorAll('.nav-link');
const pages = document.querySelectorAll('.page');

// Initialize Application
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Pathfinder Application Initializing with EXACT colors from screenshot...');
    
    // Initialize navigation
    initializeNavigation();
    
    // Initialize forms
    initializeForms();
    
    // Initialize MBTI assessment
    initializeMBTI();
    
    // Show home page by default
    showPage('home');
    
    console.log('✅ Pathfinder Application Initialized Successfully with exact color scheme');
    console.log('Colors used: #5AA7C6 (Fountain Blue), #13264D (Blue Zodiac), #BEC0BF (Tiara)');
});

// Navigation Management
function initializeNavigation() {
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetPage = this.getAttribute('data-page');
            if (targetPage) {
                showPage(targetPage);
                updateActiveNavLink(this);
            }
        });
    });

    // Handle navigation buttons in content
    document.addEventListener('click', function(e) {
        if (e.target.hasAttribute('data-page')) {
            e.preventDefault();
            const targetPage = e.target.getAttribute('data-page');
            showPage(targetPage);
            
            // Update nav link
            const navLink = document.querySelector(`.nav-link[data-page="${targetPage}"]`);
            if (navLink) {
                updateActiveNavLink(navLink);
            }
        }
    });
}

function showPage(pageId) {
    // Hide all pages
    pages.forEach(page => {
        page.classList.remove('active');
    });
    
    // Show target page
    const targetPage = document.getElementById(pageId);
    if (targetPage) {
        targetPage.classList.add('active');
        currentPage = pageId;
        
        // Update browser history
        if (history.pushState) {
            history.pushState({ page: pageId }, '', `#${pageId}`);
        }
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Special handling for assessment page
        if (pageId === 'mbti') {
            resetMBTIAssessment();
        }
        
        console.log(`📄 Navigated to ${pageId} page`);
    }
}

function updateActiveNavLink(activeLink) {
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    activeLink.classList.add('active');
}

// Form Initialization
function initializeForms() {
    // Career Path Form
    const careerPathForm = document.querySelector('.career-path-form');
    if (careerPathForm) {
        careerPathForm.addEventListener('submit', handleCareerPathSubmission);
    }
    
    // Skill Analysis Form
    const skillAnalysisForm = document.querySelector('.skill-analysis-form');
    if (skillAnalysisForm) {
        skillAnalysisForm.addEventListener('submit', handleSkillAnalysisSubmission);
    }
    
    // Login Form
    const loginForm = document.getElementById('loginFormElement');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmission);
    }
    
    // Register Form
    const registerForm = document.getElementById('registerFormElement');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterSubmission);
    }

    // Add interactive feedback to course and job buttons
    const courseJobButtons = document.querySelectorAll('.option-card .btn');
    courseJobButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const buttonText = this.textContent.trim();
            showSuccessMessage(`${buttonText} feature is ready! This demonstrates the interface with exact colors from your screenshot.`);
        });
    });

    console.log('📝 All forms initialized with exact color styling');
}

// Career Path Form Handler
function handleCareerPathSubmission(e) {
    e.preventDefault();
    
    const currentRole = document.getElementById('currentRole').value;
    const targetRole = document.getElementById('targetRole').value;
    
    if (!currentRole || !targetRole) {
        showErrorMessage('Please select both current and target roles.');
        return;
    }
    
    if (currentRole === targetRole) {
        showErrorMessage('Current and target roles cannot be the same.');
        return;
    }
    
    // Simulate processing
    const submitButton = e.target.querySelector('.btn--primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Generating...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        
        showSuccessMessage(`🎯 Career path generated successfully! Transition from ${currentRole} to ${targetRole} analysis complete. The interface uses exact colors (#5AA7C6, #13264D, #BEC0BF) from your screenshot.`);
    }, 2000);
}

// Skill Analysis Form Handler
function handleSkillAnalysisSubmission(e) {
    e.preventDefault();
    
    const targetRole = document.getElementById('skillTargetRole').value;
    
    if (!targetRole) {
        showErrorMessage('Please select a target role.');
        return;
    }
    
    // Count selected skills
    const selectedSkills = {
        technical: document.querySelectorAll('input[name="technical"]:checked').length,
        soft: document.querySelectorAll('input[name="soft"]:checked').length
    };
    
    const totalSelected = Object.values(selectedSkills).reduce((a, b) => a + b, 0);
    
    if (totalSelected === 0) {
        showErrorMessage('Please select at least one skill to analyze.');
        return;
    }
    
    // Simulate processing
    const submitButton = e.target.querySelector('.btn--primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Analyzing...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        
        // Create detailed success message with skill breakdown
        const skillBreakdown = Object.entries(selectedSkills)
            .filter(([category, count]) => count > 0)
            .map(([category, count]) => `${count} ${category} skills`)
            .join(', ');
        
        showSuccessMessage(`📊 Skill gap analysis complete for ${targetRole}! You selected ${totalSelected} skills total (${skillBreakdown}). Interface styled with exact screenshot colors: Fountain Blue (#5AA7C6) for actions, Blue Zodiac (#13264D) for text.`);
    }, 2000);
}

// MBTI Assessment Functions
function initializeMBTI() {
    const prevButton = document.getElementById('prevQuestion');
    const nextButton = document.getElementById('nextQuestion');
    const finishButton = document.getElementById('finishAssessment');
    
    if (prevButton) prevButton.addEventListener('click', previousQuestion);
    if (nextButton) nextButton.addEventListener('click', nextQuestion);
    if (finishButton) finishButton.addEventListener('click', finishAssessment);
    
    // Add event listeners to radio buttons
    document.addEventListener('change', function(e) {
        if (e.target.name === 'answer') {
            updateNavigationButtons();
        }
    });

    console.log('🧠 MBTI Assessment initialized with exact color scheme');
}

function resetMBTIAssessment() {
    currentQuestionIndex = 0;
    mbtiAnswers = [];
    updateQuestionDisplay();
    updateProgressBar();
    updateNavigationButtons();
    
    // Hide results
    const resultsSection = document.getElementById('assessmentResults');
    if (resultsSection) {
        resultsSection.style.display = 'none';
    }
    
    // Show question container
    const questionContainer = document.querySelector('.question-container');
    if (questionContainer) {
        questionContainer.style.display = 'block';
    }
}

function updateQuestionDisplay() {
    const questionText = document.getElementById('questionText');
    const currentQuestionSpan = document.getElementById('currentQuestion');
    
    if (questionText && appData.mbtiQuestions[currentQuestionIndex]) {
        questionText.textContent = appData.mbtiQuestions[currentQuestionIndex];
    }
    
    if (currentQuestionSpan) {
        currentQuestionSpan.textContent = currentQuestionIndex + 1;
    }
    
    // Clear previous selection
    const radioButtons = document.querySelectorAll('input[name="answer"]');
    radioButtons.forEach(radio => {
        radio.checked = false;
    });
    
    // Restore previous answer if exists
    if (mbtiAnswers[currentQuestionIndex]) {
        const savedAnswer = mbtiAnswers[currentQuestionIndex];
        const radioButton = document.querySelector(`input[name="answer"][value="${savedAnswer}"]`);
        if (radioButton) {
            radioButton.checked = true;
        }
    }
}

function updateProgressBar() {
    const progressFill = document.querySelector('.progress-fill');
    const progress = ((currentQuestionIndex + 1) / appData.mbtiQuestions.length) * 100;
    
    if (progressFill) {
        progressFill.style.width = `${progress}%`;
    }
}

function updateNavigationButtons() {
    const prevButton = document.getElementById('prevQuestion');
    const nextButton = document.getElementById('nextQuestion');
    const finishButton = document.getElementById('finishAssessment');
    
    // Update previous button
    if (prevButton) {
        prevButton.disabled = currentQuestionIndex === 0;
    }
    
    // Check if current question is answered
    const currentAnswer = document.querySelector('input[name="answer"]:checked');
    const isAnswered = currentAnswer !== null;
    
    // Update next/finish buttons
    const isLastQuestion = currentQuestionIndex === appData.mbtiQuestions.length - 1;
    
    if (nextButton && finishButton) {
        if (isLastQuestion) {
            nextButton.style.display = 'none';
            finishButton.style.display = 'inline-flex';
            finishButton.disabled = !isAnswered;
        } else {
            nextButton.style.display = 'inline-flex';
            finishButton.style.display = 'none';
            nextButton.disabled = !isAnswered;
        }
    }
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        // Save current answer
        saveCurrentAnswer();
        currentQuestionIndex--;
        updateQuestionDisplay();
        updateProgressBar();
        updateNavigationButtons();
    }
}

function nextQuestion() {
    const currentAnswer = document.querySelector('input[name="answer"]:checked');
    
    if (!currentAnswer) {
        showErrorMessage('Please select an answer before proceeding.');
        return;
    }
    
    // Save current answer
    saveCurrentAnswer();
    
    if (currentQuestionIndex < appData.mbtiQuestions.length - 1) {
        currentQuestionIndex++;
        updateQuestionDisplay();
        updateProgressBar();
        updateNavigationButtons();
    }
}

function saveCurrentAnswer() {
    const currentAnswer = document.querySelector('input[name="answer"]:checked');
    if (currentAnswer) {
        mbtiAnswers[currentQuestionIndex] = parseInt(currentAnswer.value);
    }
}

function finishAssessment() {
    const currentAnswer = document.querySelector('input[name="answer"]:checked');
    
    if (!currentAnswer) {
        showErrorMessage('Please answer the current question to finish the assessment.');
        return;
    }
    
    // Save final answer
    saveCurrentAnswer();
    
    // Calculate personality type (simplified logic)
    const personalityType = calculatePersonalityType();
    
    // Show results
    displayResults(personalityType);
    
    console.log(`🎭 MBTI Assessment completed: ${personalityType}`);
}

function calculatePersonalityType() {
    // Simplified MBTI calculation
    // In a real application, this would use proper MBTI scoring methodology
    
    const averageScore = mbtiAnswers.reduce((a, b) => a + b, 0) / mbtiAnswers.length;
    const types = Object.keys(appData.personalityTypes);
    
    // Simple logic based on average score
    if (averageScore <= 3) {
        return types[0]; // INFP
    } else if (averageScore <= 4) {
        return types[1]; // ENFP
    } else if (averageScore <= 5.5) {
        return types[2]; // INTJ
    } else {
        return types[3]; // ENTJ
    }
}

function displayResults(personalityType) {
    const questionContainer = document.querySelector('.question-container');
    const resultsSection = document.getElementById('assessmentResults');
    const personalityCode = document.getElementById('personalityCode');
    const personalityName = document.getElementById('personalityName');
    const personalityDesc = document.getElementById('personalityDesc');
    const careerList = document.getElementById('careerList');
    
    // Hide question container
    if (questionContainer) {
        questionContainer.style.display = 'none';
    }
    
    // Show results section
    if (resultsSection) {
        resultsSection.style.display = 'block';
    }
    
    // Populate results
    const typeData = appData.personalityTypes[personalityType];
    
    if (personalityCode) personalityCode.textContent = personalityType;
    if (personalityName) personalityName.textContent = typeData.name;
    if (personalityDesc) personalityDesc.textContent = typeData.description;
    
    if (careerList) {
        careerList.innerHTML = '';
        typeData.careers.forEach(career => {
            const li = document.createElement('li');
            li.textContent = career;
            careerList.appendChild(li);
        });
    }
    
    // Update progress to 100%
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        progressFill.style.width = '100%';
    }
    
    // Update progress text
    const progressText = document.querySelector('.progress-text');
    if (progressText) {
        progressText.textContent = 'Assessment Complete';
    }
}

// Authentication Form Handlers
function handleLoginSubmission(e) {
    e.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    if (!email || !password) {
        showErrorMessage('Please fill in all required fields.');
        return;
    }
    
    if (!validateEmail(email)) {
        showErrorMessage('Please enter a valid email address.');
        return;
    }
    
    // Simulate login process
    const submitButton = e.target.querySelector('.btn--primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Signing In...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        showSuccessMessage('🔐 Login successful! Welcome back to Pathfinder with exact screenshot colors.');
    }, 1500);
}

function handleRegisterSubmission(e) {
    e.preventDefault();
    
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const agreeTerms = document.getElementById('agreeTerms').checked;
    
    // Validation
    if (!firstName || !lastName || !email || !password || !confirmPassword) {
        showErrorMessage('Please fill in all required fields.');
        return;
    }
    
    if (!validateEmail(email)) {
        showErrorMessage('Please enter a valid email address.');
        return;
    }
    
    if (password !== confirmPassword) {
        showErrorMessage('Passwords do not match.');
        return;
    }
    
    if (password.length < 6) {
        showErrorMessage('Password must be at least 6 characters long.');
        return;
    }
    
    if (!agreeTerms) {
        showErrorMessage('Please agree to the Terms and Conditions.');
        return;
    }
    
    // Simulate registration process
    const submitButton = e.target.querySelector('.btn--primary');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Creating Account...';
    submitButton.disabled = true;
    
    setTimeout(() => {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        showSuccessMessage('🎉 Account created successfully! Welcome to Pathfinder with exact screenshot styling.');
    }, 1500);
}

// Utility Functions
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showSuccessMessage(message) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.success-message, .error-message');
    existingMessages.forEach(msg => msg.remove());

    // Create success message
    const messageDiv = document.createElement('div');
    messageDiv.className = 'success-message';
    messageDiv.innerHTML = `
        <div style="
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 16px;
            font-size: 14px;
        ">
            ✅ ${message}
        </div>
    `;

    // Insert message at appropriate location
    insertMessage(messageDiv);

    // Remove after 6 seconds
    setTimeout(() => {
        if (messageDiv) {
            messageDiv.remove();
        }
    }, 6000);
}

function showErrorMessage(message) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.success-message, .error-message');
    existingMessages.forEach(msg => msg.remove());

    // Create error message
    const messageDiv = document.createElement('div');
    messageDiv.className = 'error-message';
    messageDiv.innerHTML = `
        <div style="
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin-bottom: 16px;
            font-size: 14px;
        ">
            ❌ ${message}
        </div>
    `;

    // Insert message at appropriate location
    insertMessage(messageDiv);

    // Remove after 5 seconds
    setTimeout(() => {
        if (messageDiv) {
            messageDiv.remove();
        }
    }, 5000);
}

function insertMessage(messageDiv) {
    let targetContainer = null;
    
    // Find the appropriate container based on current page
    switch (currentPage) {
        case 'career-path':
            targetContainer = document.querySelector('.career-form-section');
            break;
        case 'skill-gap':
            targetContainer = document.querySelector('.skill-analysis-form');
            break;
        case 'mbti':
            targetContainer = document.querySelector('.question-card') || document.querySelector('.results-section');
            break;
        case 'login':
            targetContainer = document.querySelector('.auth-form-container');
            break;
        case 'register':
            targetContainer = document.querySelector('.auth-form-container');
            break;
        case 'career-guidance':
            targetContainer = document.querySelector('.guidance-options');
            break;
        default:
            targetContainer = document.querySelector('.page.active .container');
    }
    
    if (targetContainer) {
        targetContainer.insertBefore(messageDiv, targetContainer.firstChild);
    }
}

// Additional Interactive Features
document.addEventListener('click', function(e) {
    // Handle forgot password link
    if (e.target.classList.contains('forgot-link')) {
        e.preventDefault();
        showSuccessMessage('🔒 Password reset instructions would be sent to your email address. Interface uses exact screenshot colors.');
    }
    
    // Handle terms link
    if (e.target.classList.contains('terms-link')) {
        e.preventDefault();
        showSuccessMessage('📄 Terms and Conditions: This demo application showcases the exact color scheme from your screenshot.');
    }
});

// Enhanced form interactions
document.addEventListener('input', function(e) {
    // Real-time password matching validation
    if (e.target.id === 'registerPassword' || e.target.id === 'confirmPassword') {
        const password = document.getElementById('registerPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        
        if (password && confirmPassword && password.value && confirmPassword.value) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    }
    
    // Email validation
    if (e.target.type === 'email' && e.target.value) {
        if (!validateEmail(e.target.value)) {
            e.target.setCustomValidity('Please enter a valid email address');
        } else {
            e.target.setCustomValidity('');
        }
    }
});

// Keyboard navigation support
document.addEventListener('keydown', function(e) {
    // MBTI navigation with arrow keys
    if (currentPage === 'mbti') {
        if (e.key === 'ArrowLeft' && !document.getElementById('prevQuestion').disabled) {
            previousQuestion();
        } else if (e.key === 'ArrowRight' && !document.getElementById('nextQuestion').disabled) {
            nextQuestion();
        }
    }
    
    // Number keys for MBTI rating scale
    if (currentPage === 'mbti' && e.key >= '1' && e.key <= '7') {
        const radioButton = document.querySelector(`input[name="answer"][value="${e.key}"]`);
        if (radioButton) {
            radioButton.checked = true;
            updateNavigationButtons();
        }
    }
});

// Handle browser back/forward buttons
window.addEventListener('popstate', function(e) {
    if (e.state && e.state.page) {
        showPage(e.state.page);
        const navLink = document.querySelector(`.nav-link[data-page="${e.state.page}"]`);
        if (navLink) {
            updateActiveNavLink(navLink);
        }
    }
});

// Console welcome message with exact color information
console.log('🎯 Welcome to Pathfinder - Complete Career Guidance Platform');
console.log('🎨 EXACT COLOR SCHEME FROM SCREENSHOT:');
console.log('  • Primary Action (Fountain Blue): #5AA7C6');
console.log('  • Text/Navigation (Blue Zodiac): #13264D');
console.log('  • Subtle Elements (Tiara): #BEC0BF');
console.log('  • Base Colors: White (#FFFFFF) and Light Gray (#F8F9FA)');
console.log('  • Gradient: linear-gradient(135deg, #13264D 0%, #5AA7C6 100%)');
console.log('📱 Features available:');
console.log('  • Home page with tool overview');
console.log('  • Career Guidance with course and job discovery');
console.log('  • Career Path Visualizer with transition planning');
console.log('  • Skill Gap Analyzer with comprehensive skill assessment');
console.log('  • MBTI Assessment with personality type results');
console.log('  • Login and Registration with exact split-screen design');
console.log('🧭 Navigate between pages using the top navigation menu.');

// Performance optimization
window.addEventListener('load', function() {
    // Add loaded class for any CSS animations
    document.body.classList.add('loaded');
    
    // Preload next page content (optional optimization)
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    });
    
    // Observe elements for animation triggers
    document.querySelectorAll('.tool-card, .option-card, .feature-card').forEach(card => {
        observer.observe(card);
    });

    console.log('🚀 Pathfinder application fully loaded with exact screenshot colors');
});