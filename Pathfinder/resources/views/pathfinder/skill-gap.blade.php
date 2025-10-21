@extends('pathfinder.layout')

@section('title', 'Skill Gap Analyzer - Pathfinder')

@php
use App\Services\SkillMappingService;
@endphp

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Skill Gap Analyzer
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
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
                <div class="flex items-center justify-center w-16 h-16 rounded-full mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-4" style="color: #13264D;">
                    Analyze Your Skill Gap
                </h2>
                <p class="text-lg text-gray-600">
                    Tell us about your current skills and target role to get a detailed analysis of what you need to improve.
                </p>
            </div>

            <form action="{{ route('pathfinder.skill-gap.analyze') }}" method="POST" class="space-y-8" id="skillGapForm">
                @csrf

                <!-- Target Role Section -->
                <div>
                    <label for="target_role" class="block text-lg font-semibold text-gray-900 mb-4">
                        What is your target role?
                    </label>
                    <select name="target_role" id="target_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900" style="transition: all 0.3s; border-color: #BEC0BF;" onfocus="this.style.borderColor='#5AA7C6'; this.style.boxShadow='0 0 0 3px rgba(90, 167, 198, 0.1)';" onblur="this.style.borderColor='#BEC0BF'; this.style.boxShadow='none';" required>
                        <option value="">Select your target role...</option>
                        @php
                            $rolesByCategory = SkillMappingService::getRolesByCategory();
                        @endphp
                        @foreach($rolesByCategory as $category => $roles)
                            <optgroup label="{{ $category }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Skills Selection Notice -->
                <div id="skills-notice" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 hidden">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">
                            <strong>Skills customized for your target role!</strong> The skills below are specifically relevant to <span id="selected-role-name" class="font-semibold"></span>.
                        </p>
                    </div>
                </div>

                <!-- Pro Tip -->
                <div id="pro-tip" class="rounded-lg p-6 hidden" style="background-color: #EFF6FF; border: 1px solid #5AA7C6;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #13264D;">
                        💡 Pro Tip for <span id="tip-role-name"></span>
                    </h3>
                    <p style="color: #13264D;">
                        Be honest about your current skill level. Our analysis will help you identify exactly what you need to focus on to reach your career goals. The skills shown above are specifically selected for your target role to give you the most accurate assessment possible.
                    </p>
                </div>

                <!-- Current Skills Section -->
                <div id="skills-section" class="hidden">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        What are your current skills?
                    </label>
                    <p class="text-sm text-gray-600 mb-6">
                        Select all skills that you currently possess. We're showing skills specifically relevant to your target role plus essential soft skills. Be honest about your skill level to get accurate recommendations.
                    </p>

                    <!-- Skills Container -->
                    <div class="space-y-6" id="skills-container">
                        <!-- Technical Skills - Will be populated dynamically -->
                        <div class="skill-category rounded-lg p-6" style="background-color: #EFF6FF; border: 1px solid #5AA7C6;" id="technical-skills">
                            <h3 class="text-lg font-semibold mb-4" style="color: #13264D;">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                Technical Skills for <span id="role-name-tech"></span>
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="technical-skills-grid">
                                <!-- Technical skills will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Soft Skills - Always shown -->
                        <div class="skill-category rounded-lg p-6" style="background-color: #F0F9FF; border: 1px solid #0EA5E9;">
                            <h3 class="text-lg font-semibold text-sky-800 mb-4">
                                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Essential Soft Skills
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $universalSoftSkills = SkillMappingService::getUniversalSoftSkills();
                                @endphp
                                @foreach($universalSoftSkills as $skill)
                                    <label class="flex items-center p-2 border border-sky-200 rounded-lg cursor-pointer transition-colors duration-200 skill-checkbox" style="background-color: white;" onmouseover="this.style.backgroundColor='#F0F9FF';" onmouseout="this.style.backgroundColor='white';">
                                        <input type="checkbox" name="current_skills[]" value="{{ $skill }}" class="mr-2" style="color: #0EA5E9;">
                                        <span class="text-sm text-gray-700">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center" id="submit-section" style="display: none;">
                    <button type="submit" class="inline-flex items-center px-8 py-4 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg" style="background-color: #5AA7C6;" onmouseover="this.style.backgroundColor='#13264D';" onmouseout="this.style.backgroundColor='#5AA7C6';">
                        Analyze My Skills
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Role Skill Mapping Data for JavaScript -->
            <script id="skill-mapping-data" type="application/json">
                @php
                    $skillMapping = SkillMappingService::getRoleSkillMapping();
                    echo json_encode($skillMapping);
                @endphp
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('Skill Gap Analyzer JavaScript loaded');

                    const targetRoleSelect = document.getElementById('target_role');
                    const skillsSection = document.getElementById('skills-section');
                    const skillsNotice = document.getElementById('skills-notice');
                    const selectedRoleName = document.getElementById('selected-role-name');
                    const technicalSkillsGrid = document.getElementById('technical-skills-grid');
                    const roleNameTech = document.getElementById('role-name-tech');
                    const proTip = document.getElementById('pro-tip');
                    const tipRoleName = document.getElementById('tip-role-name');
                    const submitSection = document.getElementById('submit-section');

                    // Check if all elements exist
                    console.log('Elements found:', {
                        targetRoleSelect: !!targetRoleSelect,
                        skillsSection: !!skillsSection,
                        skillsNotice: !!skillsNotice,
                        technicalSkillsGrid: !!technicalSkillsGrid,
                        proTip: !!proTip
                    });

                    // Get skill mapping data
                    try {
                        const skillMappingData = JSON.parse(document.getElementById('skill-mapping-data').textContent);
                        console.log('Skill mapping data loaded:', Object.keys(skillMappingData).length + ' roles');

                        targetRoleSelect.addEventListener('change', function() {
                            const selectedRole = this.value;
                            console.log('Role selected:', selectedRole);

                            if (selectedRole) {
                                // Show sections
                                if (skillsSection) skillsSection.classList.remove('hidden');
                                if (skillsNotice) skillsNotice.classList.remove('hidden');
                                if (proTip) proTip.classList.remove('hidden');
                                if (submitSection) submitSection.style.display = 'block';

                                // Update role names
                                if (selectedRoleName) selectedRoleName.textContent = selectedRole;
                                if (roleNameTech) roleNameTech.textContent = selectedRole;
                                if (tipRoleName) tipRoleName.textContent = selectedRole;

                                // Get skills for selected role
                                const roleSkills = skillMappingData[selectedRole];
                                console.log('Role skills found:', !!roleSkills, roleSkills ? Object.keys(roleSkills) : 'none');

                                if (roleSkills && roleSkills.technical_skills) {
                                    console.log('Technical skills count:', roleSkills.technical_skills.length);
                                    // Clear existing technical skills
                                    technicalSkillsGrid.innerHTML = '';

                                    // Add technical skills for this role
                                    roleSkills.technical_skills.forEach(function(skill, index) {
                                        console.log('Adding skill ' + (index + 1) + ':', skill);
                                    const label = document.createElement('label');
                                    label.className = 'flex items-center p-2 border rounded-lg cursor-pointer transition-colors duration-200 skill-checkbox';
                                    label.style.backgroundColor = 'white';
                                    label.style.borderColor = '#5AA7C6';
                                    label.onmouseover = function() { this.style.backgroundColor = '#EFF6FF'; };
                                    label.onmouseout = function() { this.style.backgroundColor = 'white'; };

                                    const checkbox = document.createElement('input');
                                    checkbox.type = 'checkbox';
                                    checkbox.name = 'current_skills[]';
                                    checkbox.value = skill;
                                    checkbox.className = 'mr-2';
                                    checkbox.style.color = '#5AA7C6';

                                    const span = document.createElement('span');
                                    span.className = 'text-sm text-gray-700';
                                    span.textContent = skill;

                                    label.appendChild(checkbox);
                                    label.appendChild(span);
                                    technicalSkillsGrid.appendChild(label);
                                    });
                                    console.log('All technical skills added successfully');
                                } else {
                                    console.warn('No technical skills found for role:', selectedRole);
                                }
                            } else {
                                // Hide sections if no role selected
                                if (skillsSection) skillsSection.classList.add('hidden');
                                if (skillsNotice) skillsNotice.classList.add('hidden');
                                if (proTip) proTip.classList.add('hidden');
                                if (submitSection) submitSection.style.display = 'none';
                            }
                        });
                    } catch (error) {
                        console.error('Error loading skill mapping data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Loading Error',
                            text: 'Error loading skill data. Please refresh the page and try again.',
                            confirmButtonColor: '#5AA7C6'
                        });
                    }
                });
            </script>
        </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4" style="color: #13264D;">
                What You'll Discover
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Our skill gap analysis provides detailed insights to help you focus your learning efforts effectively.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Skills You Have</h3>
                <p class="text-gray-600">See which skills you already possess that are relevant to your target role and build confidence.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Skills You Need</h3>
                <p class="text-gray-600">Identify the specific skills you need to develop to qualify for your target role.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mx-auto mb-4" style="background-color: #EFF6FF;">
                    <svg class="h-8 w-8" style="color: #5AA7C6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <h2 class="text-3xl font-bold mb-4" style="color: #13264D;">
                How Our Analysis Works
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #13264D;">
                    1
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Select Target Role</h3>
                <p class="text-gray-600">Choose the role you want to pursue from our comprehensive list.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #5AA7C6;">
                    2
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mark Your Skills</h3>
                <p class="text-gray-600">Honestly assess and select your current skill set across different categories.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #13264D;">
                    3
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Analysis</h3>
                <p class="text-gray-600">Receive detailed comparison between your skills and role requirements.</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 text-white rounded-full mx-auto mb-4 text-xl font-bold" style="background-color: #5AA7C6;">
                    4
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Plan Learning</h3>
                <p class="text-gray-600">Use insights to create a focused learning plan and track your progress.</p>
            </div>
        </div>
    </div>
</div>
@endsection
