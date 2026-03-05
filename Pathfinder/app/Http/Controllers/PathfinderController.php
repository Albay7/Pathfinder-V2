<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use App\Models\Tutorial;
use App\Models\User;

class PathfinderController extends Controller
{
    public function index()
    {
        return view('pathfinder.index');
    }

    // Career Guidance Methods
    public function careerGuidance()
    {
        return view('pathfinder.career-guidance');
    }

    public function questionnaire(Request $request)
    {
        $type = $request->get('type', 'course'); // course or job
        return view('pathfinder.questionnaire', compact('type'));
    }

    public function processQuestionnaire(Request $request)
    {
        $answers = $request->all();
        $type = $request->get('type');
        $selectedCategory = $request->get('selected_category');
        $allResponses = $request->get('all_responses') ?? $request->get('responses');
        $jobScoresInput = $request->get('job_scores');
        $topRecommendationInput = $request->get('top_recommendation');

        // Parse the responses if they're JSON
        if (is_string($allResponses)) {
            $allResponses = json_decode($allResponses, true);
        }

        // Normalize optional job score inputs
        if (is_string($jobScoresInput) && $jobScoresInput !== '') {
            $decodedJobScores = json_decode($jobScoresInput, true);
            if (is_array($decodedJobScores)) {
                $allResponses['jobScores'] = $decodedJobScores;
            }
        }
        if (is_string($topRecommendationInput) && $topRecommendationInput !== '') {
            $allResponses['topRecommendation'] = $topRecommendationInput;
        }

        // Generate recommendation based on actual questionnaire data
        $recommendation = $this->generateRecommendationFromResponses($selectedCategory, $allResponses, $type);

        // Save progress if user is authenticated
        if (Auth::check()) {
            UserProgress::create([
                'user_id' => Auth::id(),
                'feature_type' => 'career_guidance',
                'assessment_type' => $type,
                'questionnaire_answers' => $answers,
                'selected_category' => $selectedCategory,
                'calculated_scores' => $allResponses,
                'recommendation' => $recommendation,
                'completed' => true
            ]);
        }

        return view('pathfinder.recommendation', compact('recommendation', 'type', 'selectedCategory', 'allResponses'));
    }

    // Career Path Visualizer Methods
    public function careerPath()
    {
        return view('pathfinder.career-path');
    }

    public function showCareerPath(Request $request)
    {
        // Handle both GET and POST requests
        $currentRole = $request->input('current_role');
        $targetRole = $request->input('target_role');

        // If required parameters are missing, redirect back to the form
        if (!$currentRole || !$targetRole) {
            return redirect()->route('pathfinder.career-path')
                ->with('error', 'Please provide both current role and target role');
        }

        $pathSteps = $this->generateCareerPath($currentRole, $targetRole);

        // Save progress if user is authenticated
        if (Auth::check()) {
            UserProgress::create([
                'user_id' => Auth::id(),
                'feature_type' => 'career_path',
                'current_role' => $currentRole,
                'target_role' => $targetRole,
                'analysis_result' => $pathSteps,
                'completed' => true
            ]);
        }

        return view('pathfinder.career-path-result', compact('pathSteps', 'currentRole', 'targetRole'));
    }

    // Skill Gap Analyzer Methods
    public function skillGap()
    {
        return view('pathfinder.skill-gap');
    }

    public function analyzeSkillGap(Request $request)
    {
        $currentSkills = $request->get('current_skills', []);
        $targetRole = $request->get('target_role');
        $targetCategory = $request->get('target_category');

        // Store category in session for use in subsequent requests
        session(['skill_gap_category' => $targetCategory]);
        session(['skill_gap_role' => $targetRole]);
        session(['skill_gap_missing_skills' => []]);

        $analysis = $this->performSkillGapAnalysis($currentSkills, $targetRole);

        // Get tutorial recommendations for missing skills (use simple skill names if available)
        $skillNamesForTutorials = $analysis['missing_skill_names'] ?? $analysis['missing_skills'];
        $tutorialRecommendations = Tutorial::getRecommendationsForSkills($skillNamesForTutorials, 3);
        $analysis['tutorial_recommendations'] = $tutorialRecommendations;

        // Store missing skills in session for YouTube recommendation use (use simple skill names)
        session(['skill_gap_missing_skills' => $skillNamesForTutorials]);

        // Save progress if user is authenticated
        if (Auth::check()) {
            UserProgress::create([
                'user_id' => Auth::id(),
                'feature_type' => 'skill_gap',
                'target_role' => $targetRole,
                'target_category' => $targetCategory,
                'current_skills' => $currentSkills,
                'analysis_result' => $analysis,
                'match_percentage' => $analysis['match_percentage'],
                'completed' => true
            ]);
        }

        return view('pathfinder.skill-gap-result', compact('analysis', 'targetRole'));
    }

    // Career Details Page
    public function careerDetails(Request $request, $career)
    {
        // Decode the URL-encoded career name
        $career = urldecode($career);

        // Mock career details data - in a real app, this would come from a database
        $careerDetails = [
            'title' => $career,
            'description' => "This is a detailed description of the {$career} role, including responsibilities, required skills, and career outlook.",
            'skills_required' => ['Skill 1', 'Skill 2', 'Skill 3', 'Skill 4', 'Skill 5'],
            'education_requirements' => 'Bachelor\'s degree in related field',
            'salary_range' => '$50,000 - $100,000',
            'job_outlook' => 'Growing faster than average',
            'related_careers' => ['Related Career 1', 'Related Career 2', 'Related Career 3']
        ];

        return view('pathfinder.career-details', compact('careerDetails'));
    }

    /**
     * Display available courses
     *
     * @return \Illuminate\View\View
     */
    public function courses()
    {
        // Mock courses data
        $courses = [
            'technology' => [
                'Web Development', 'Data Science', 'Mobile App Development',
                'Cloud Computing', 'Cybersecurity'
            ],
            'business' => [
                'Project Management', 'Digital Marketing', 'Business Analytics',
                'Entrepreneurship', 'Finance'
            ],
            'design' => [
                'UX/UI Design', 'Graphic Design', 'Product Design',
                'Motion Graphics', '3D Modeling'
            ],
            'science' => [
                'Machine Learning', 'Bioinformatics', 'Environmental Science',
                'Physics', 'Chemistry'
            ]
        ];

        return view('pathfinder.courses', ['courses' => $courses]);
    }

    /**
     * Display external learning resources with YouTube playlists from skill gap analysis
     *
     * @return \Illuminate\View\View
     */
    public function externalResources()
    {
        // Get skill gap data from session
        $missingSkills = session('skill_gap_missing_skills', []);
        $targetRole = session('skill_gap_role');
        $targetCategory = session('skill_gap_category');

        // Database fallback for authenticated users when session is empty
        if (empty($missingSkills) && Auth::check()) {
            $latestSkillGap = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'skill_gap')
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestSkillGap) {
                $targetRole = $latestSkillGap->target_role;
                $targetCategory = $latestSkillGap->target_category;
                $analysisResult = $latestSkillGap->analysis_result;
                $missingSkills = $analysisResult['missing_skill_names']
                    ?? array_column($analysisResult['missing_skills'] ?? [], 'name')
                    ?? [];

                // Restore session so subsequent requests skip DB
                if (!empty($missingSkills)) {
                    session([
                        'skill_gap_missing_skills' => $missingSkills,
                        'skill_gap_role' => $targetRole,
                        'skill_gap_category' => $targetCategory,
                    ]);
                }
            }
        }

        $youtubeRecommendations = [];

        // If we have missing skills from skill gap analysis, fetch YouTube playlists
        if (!empty($missingSkills) && !empty($targetRole)) {
            $youtubeService = app(\App\Services\YouTubeService::class);
            $youtubeRecommendations = $youtubeService->searchPlaylistsForSkills(
                $missingSkills,
                perSkill: 2,
                maxSkills: 8,
                roleContext: $targetRole,
                categoryContext: $targetCategory
            );
        }

        // Fetch personalized articles from NewsAPI
        $newsService = app(\App\Services\NewsApiService::class);
        $articles = $newsService->fetchArticlesForRole($targetRole, 9);

        // Learning platforms
        $platforms = [
            ['name' => 'Udemy', 'url' => 'https://www.udemy.com', 'description' => 'Marketplace for online learning with courses on virtually any topic'],
            ['name' => 'Coursera', 'url' => 'https://www.coursera.org', 'description' => 'Online courses from top universities and companies'],
            ['name' => 'Pluralsight', 'url' => 'https://www.pluralsight.com', 'description' => 'Technology skill development platform with focus on IT and software development'],
            ['name' => 'LinkedIn Learning', 'url' => 'https://www.linkedin.com/learning', 'description' => 'Professional courses on business, technology and creative skills']
        ];

        return view('pathfinder.external-resources', [
            'articles' => $articles,
            'platforms' => $platforms,
            'youtubeRecommendations' => $youtubeRecommendations,
            'targetRole' => $targetRole,
            'targetCategory' => $targetCategory,
            'missingSkills' => $missingSkills
        ]);
    }

    // Helper Methods
    private function generateRecommendationFromResponses($selectedCategory, $allResponses, $type)
    {
        if ($type === 'course') {
            return $this->generateCourseRecommendationFromScores($selectedCategory, $allResponses);
        } else {
            return $this->generateJobRecommendationFromScores($selectedCategory, $allResponses);
        }
    }

    private function generateCourseRecommendationFromScores($selectedCategory, $allResponses)
    {
        // Get course scores from the responses
        $courseScores = $allResponses['courseScores'] ?? [];

        if (empty($courseScores)) {
            // Fallback to category-based recommendation
            return $this->getCategoryFallbackCourse($selectedCategory);
        }

        // Find the highest scoring course
        $topCourse = array_keys($courseScores, max($courseScores))[0];
        return $topCourse;
    }

    private function generateJobRecommendationFromScores($selectedCategory, $allResponses)
    {
        // Get job scores from the responses
        $jobScores = $allResponses['jobScores'] ?? [];
        $topRecommendation = $allResponses['topRecommendation'] ?? null;

        if (!empty($topRecommendation)) {
            return $topRecommendation;
        }

        if (empty($jobScores)) {
            // Fallback to category-based recommendation
            return $this->getCategoryFallbackJob($selectedCategory);
        }

        // Find the highest scoring job
        $topJob = array_keys($jobScores, max($jobScores))[0];
        return $topJob;
    }

    private function getCategoryFallbackCourse($category)
    {
        $categoryDefaults = [
            'business' => 'Bachelor of Science in Business Administration',
            'healthcare' => 'Bachelor of Science in Nursing',
            'technology' => 'Bachelor of Science in Computer Science',
            'creative' => 'Bachelor of Arts in Communication',
            'education' => 'Bachelor of Elementary Education',
            'engineering' => 'Bachelor of Science in Civil Engineering',
            'law' => 'Bachelor of Laws (LLB)',
            'tourism' => 'Bachelor of Science in Tourism Management'
        ];

        return $categoryDefaults[$category] ?? 'Bachelor of Science in Business Administration';
    }

    private function getCategoryFallbackJob($category)
    {
        $categoryDefaults = [
            'business' => 'Business Analyst',
            'healthcare' => 'Registered Nurse',
            'technology' => 'Software Developer',
            'creative' => 'Graphic Designer',
            'education' => 'Elementary Teacher',
            'engineering' => 'Civil Engineer',
            'law' => 'Legal Assistant',
            'tourism' => 'Tourism Coordinator'
        ];

        return $categoryDefaults[$category] ?? 'Business Analyst';
    }

    private function generateCourseRecommendation($answers, $mbtiType = null, $mbtiWeight = 0.2)
    {
        // DLSU Dasmariñas course recommendations based on assessment
        $dlsuCourses = [
            // Engineering & Technology
            'engineering' => [
                'Bachelor of Science in Civil Engineering',
                'Bachelor of Science in Electrical Engineering',
                'Bachelor of Science in Computer Engineering',
                'Bachelor of Science in Mechanical Engineering',
                'Bachelor of Science in Industrial Engineering'
            ],
            // Computer Science & IT
            'computer_science' => [
                'Bachelor of Science in Computer Science',
                'Bachelor of Science in Information Technology',
                'Bachelor of Science in Information Systems'
            ],
            // Business & Management
            'business' => [
                'Bachelor of Science in Business Administration',
                'Bachelor of Science in Marketing Management',
                'Bachelor of Science in Financial Management',
                'Bachelor of Science in Entrepreneurship'
            ],
            // Education
            'education' => [
                'Bachelor of Elementary Education',
                'Bachelor of Secondary Education',
                'Bachelor of Early Childhood Education'
            ],
            // Accounting & Finance
            'accounting' => [
                'Bachelor of Science in Accountancy',
                'Bachelor of Science in Financial Management'
            ],
            // Liberal Arts
            'liberal_arts' => [
                'Bachelor of Arts in Communication',
                'Bachelor of Arts in Psychology',
                'Bachelor of Arts in English'
            ],
            // Tourism & Management
            'tourism' => [
                'Bachelor of Science in Tourism Management',
                'Bachelor of Science in Hospitality Management',
                'Bachelor of Science in Hotel and Restaurant Management'
            ],
            // Science & Research
            'science' => [
                'Bachelor of Science in Biology',
                'Bachelor of Science in Chemistry',
                'Bachelor of Science in Physics',
                'Bachelor of Science in Environmental Science',
                'Bachelor of Science in Mathematics'
            ],
            // Criminal Justice Education
            'criminal_justice' => [
                'Bachelor of Science in Criminology',
                'Bachelor of Science in Criminal Justice',
                'Bachelor of Science in Forensic Science'
            ]
        ];

        // Determine best course based on answers
        $fieldInterest = $answers['field_interest'] ?? 'business';
        $strongSubjects = $answers['strong_subjects'] ?? 'business_economics';
        $careerVision = $answers['career_vision'] ?? 'business_leader';
        $dlsuProgramInterest = $answers['dlsu_program_interest'] ?? 'cbaa';

        // Score-based recommendation system
        $scores = [];

        // Primary field interest (40% weight)
        if (isset($dlsuCourses[$fieldInterest])) {
            foreach ($dlsuCourses[$fieldInterest] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 40;
            }
        }

        // Strong subjects alignment (30% weight)
        if ($strongSubjects === 'math_science') {
            foreach ($dlsuCourses['engineering'] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 30;
            }
        } elseif ($strongSubjects === 'computer_tech') {
            foreach ($dlsuCourses['computer_science'] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 30;
            }
        } elseif ($strongSubjects === 'business_economics') {
            foreach (array_merge($dlsuCourses['business'], $dlsuCourses['accounting']) as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 30;
            }
        } elseif ($strongSubjects === 'languages_social') {
            foreach (array_merge($dlsuCourses['education'], $dlsuCourses['liberal_arts'], $dlsuCourses['criminal_justice']) as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 30;
            }
        } elseif ($strongSubjects === 'arts_humanities') {
            foreach (array_merge($dlsuCourses['liberal_arts'], $dlsuCourses['tourism']) as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 30;
            }
        }

        // Additional scoring for science fields
        if ($strongSubjects === 'math_science') {
            foreach ($dlsuCourses['science'] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 25;
            }
        }

        // Career vision alignment (20% weight)
        if ($careerVision === 'technical_specialist') {
            foreach (array_merge($dlsuCourses['engineering'], $dlsuCourses['computer_science']) as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 20;
            }
        } elseif ($careerVision === 'business_leader') {
            foreach ($dlsuCourses['business'] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 20;
            }
        } elseif ($careerVision === 'educator_trainer') {
            foreach ($dlsuCourses['education'] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 20;
            }
        }

        // DLSU program interest (10% weight)
        $programMapping = [
            'ceat' => array_merge($dlsuCourses['engineering']),
            'ccs' => $dlsuCourses['computer_science'],
            'cbaa' => array_merge($dlsuCourses['business'], $dlsuCourses['accounting']),
            'coed' => $dlsuCourses['education'],
            'cla' => $dlsuCourses['liberal_arts']
        ];

        if (isset($programMapping[$dlsuProgramInterest])) {
            foreach ($programMapping[$dlsuProgramInterest] as $course) {
                $scores[$course] = ($scores[$course] ?? 0) + 10;
            }
        }

        // Return highest scoring course
        if (!empty($scores)) {
            arsort($scores);
            return array_key_first($scores);
        }

        // Fallback recommendation
        return 'Bachelor of Science in Business Administration';
    }

    private function generateJobRecommendation($answers, $mbtiType = null, $mbtiWeight = 0.2)
    {
        // Industry-specific job recommendations
        $industryJobs = [
            // Technology
            'technology' => [
                'Frontend Developer',
                'Backend Developer',
                'Full-Stack Developer',
                'Mobile App Developer',
                'Data Scientist',
                'Cybersecurity Specialist',
                'DevOps Engineer',
                'UI/UX Designer',
                'Software Engineer',
                'Data Analyst'
            ],
            // Business
            'business' => [
                'Business Analyst',
                'Project Manager',
                'Operations Manager',
                'Human Resources Manager',
                'Sales Representative',
                'Business Consultant',
                'Account Manager',
                'Marketing Coordinator',
                'Administrative Assistant',
                'Customer Success Manager'
            ],
            // Finance
            'finance' => [
                'Financial Analyst',
                'Investment Banker',
                'Accountant',
                'Financial Planner',
                'Insurance Agent',
                'Credit Analyst',
                'Tax Specialist',
                'Auditor',
                'Risk Manager',
                'Banking Associate'
            ],
            // Healthcare
            'healthcare' => [
                'Registered Nurse',
                'Medical Technologist',
                'Healthcare Administrator',
                'Pharmacy Technician',
                'Physical Therapist',
                'Medical Assistant',
                'Health Information Technician',
                'Clinical Research Coordinator',
                'Healthcare Social Worker',
                'Medical Receptionist'
            ],
            // Education
            'education' => [
                'Elementary Teacher',
                'High School Teacher',
                'School Administrator',
                'School Counselor',
                'Corporate Trainer',
                'Curriculum Developer',
                'Educational Coordinator',
                'Instructional Designer',
                'Academic Advisor',
                'Special Education Teacher'
            ],
            // Marketing
            'marketing' => [
                'Digital Marketing Specialist',
                'Content Marketing Manager',
                'Brand Manager',
                'Advertising Coordinator',
                'Market Research Analyst',
                'Social Media Manager',
                'SEO Specialist',
                'Public Relations Specialist',
                'Marketing Coordinator',
                'Creative Director'
            ],
            // Engineering
            'engineering' => [
                'Civil Engineer',
                'Mechanical Engineer',
                'Electrical Engineer',
                'Project Manager',
                'Quality Control Engineer',
                'Construction Manager',
                'Design Engineer',
                'Site Engineer',
                'Safety Engineer',
                'Engineering Technician'
            ],
            // Government
            'government' => [
                'Civil Service Officer',
                'Police Officer',
                'Social Worker',
                'Regulatory Affairs Specialist',
                'Public Health Officer',
                'Government Analyst',
                'Administrative Officer',
                'Policy Advisor',
                'Immigration Officer',
                'Tax Examiner'
            ],
            // Tourism
            'tourism' => [
                'Hotel Manager',
                'Tour Guide',
                'Travel Agent',
                'Event Coordinator',
                'Restaurant Manager',
                'Front Desk Agent',
                'Concierge',
                'Tourism Marketing Specialist',
                'Resort Activities Coordinator',
                'Hospitality Supervisor'
            ]
        ];

        // Determine best job based on answers
        $jobIndustry = $answers['job_industry'] ?? 'technology';
        $careerGoal = $answers['career_goal'] ?? 'entry_level';
        $workSchedule = $answers['work_schedule'] ?? 'standard';
        $jobResponsibilities = $answers['job_responsibilities'] ?? 'technical_execution';
        $jobMotivation = $answers['job_motivation'] ?? 'growth';

        // Score-based recommendation system
        $scores = [];

        // Adjust weights if MBTI is available
        $industryWeight = $mbtiType ? 40 : 50; // Reduce from 50% to 40% if MBTI is available

        // Primary industry interest (40-50% weight)
        if (isset($industryJobs[$jobIndustry])) {
            foreach ($industryJobs[$jobIndustry] as $job) {
                $scores[$job] = ($scores[$job] ?? 0) + $industryWeight;
            }
        }

        // MBTI-based job recommendations (20% weight if available)
        if ($mbtiType) {
            $mbtiRecommendations = $this->getMbtiJobRecommendations($mbtiType);
            foreach ($mbtiRecommendations as $job) {
                // Check if this job exists in our job list (any industry)
                $jobExists = false;
                foreach ($industryJobs as $industry => $jobs) {
                    if (in_array($job, $jobs)) {
                        $jobExists = true;
                        break;
                    }
                }

                if ($jobExists) {
                    $scores[$job] = ($scores[$job] ?? 0) + (20 * $mbtiWeight);
                }
            }
        }

        // Career goal adjustments (20% weight)
        if ($careerGoal === 'entry_level') {
            // Boost entry-level friendly jobs
            $entryLevelJobs = [
                'Frontend Developer', 'Marketing Coordinator', 'Administrative Assistant',
                'Medical Assistant', 'Sales Representative', 'Customer Success Manager'
            ];
            foreach ($entryLevelJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 20;
                }
            }
        } elseif ($careerGoal === 'career_advancement') {
            // Boost management and senior-level jobs
            $advancementJobs = [
                'Project Manager', 'Operations Manager', 'Business Consultant',
                'Team Lead', 'Healthcare Administrator', 'School Administrator'
            ];
            foreach ($advancementJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 20;
                }
            }
        } elseif ($careerGoal === 'entrepreneurship') {
            // Boost business and consulting roles
            $entrepreneurialJobs = [
                'Business Consultant', 'Marketing Coordinator', 'Sales Representative',
                'Creative Director', 'Project Manager'
            ];
            foreach ($entrepreneurialJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 20;
                }
            }
        }

        // Job responsibilities preferences (15% weight)
        if ($jobResponsibilities === 'people_management') {
            $managementJobs = [
                'Project Manager', 'Operations Manager', 'Human Resources Manager',
                'School Administrator', 'Healthcare Administrator'
            ];
            foreach ($managementJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 15;
                }
            }
        } elseif ($jobResponsibilities === 'technical_execution') {
            $technicalJobs = [
                'Frontend Developer', 'Backend Developer', 'Data Scientist',
                'Medical Technologist', 'Engineering Technician'
            ];
            foreach ($technicalJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 15;
                }
            }
        } elseif ($jobResponsibilities === 'client_interaction') {
            $clientFacingJobs = [
                'Sales Representative', 'Customer Success Manager', 'Account Manager',
                'Travel Agent', 'Financial Planner'
            ];
            foreach ($clientFacingJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 15;
                }
            }
        }

        // Motivation-based adjustments (15% weight)
        if ($jobMotivation === 'financial') {
            $highPayingJobs = [
                'Investment Banker', 'Software Engineer', 'Data Scientist',
                'Civil Engineer', 'Financial Analyst'
            ];
            foreach ($highPayingJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 15;
                }
            }
        } elseif ($jobMotivation === 'impact') {
            $impactJobs = [
                'Teacher', 'Social Worker', 'Public Health Officer',
                'Registered Nurse', 'School Counselor'
            ];
            foreach ($impactJobs as $job) {
                if (isset($scores[$job])) {
                    $scores[$job] += 15;
                }
            }
        }

        // Get top recommendation
        if (!empty($scores)) {
            arsort($scores);
            return array_key_first($scores);
        }

        // Fallback to industry default
        if (isset($industryJobs[$jobIndustry])) {
            return $industryJobs[$jobIndustry][array_rand($industryJobs[$jobIndustry])];
        }

        // Final fallback
        return 'Software Developer';
    }

    private function generateCareerPath($currentRole, $targetRole)
    {
        // Generate career ladder based on target role
        $careerLadder = $this->generateCareerLadder($targetRole);

        return $careerLadder;
    }

    private function generateCareerLadder($targetRole)
    {
        // Check if it's a technology role to provide expanded career path
        $techRoles = ['Software Developer', 'Web Developer', 'IT Support Specialist', 'Data Analyst',
                      'Cybersecurity Analyst', 'Systems Administrator', 'Database Administrator',
                      'Network Administrator', 'Computer Engineer'];

        $isTechRole = in_array($targetRole, $techRoles);

        if ($isTechRole) {
            // Expanded 6-level career progression for technology roles
            $careerLevels = [
                [
                    'level' => 'Entry-Level',
                    'duration' => '0 - 1 Year',
                    'salary_range' => '₱18,000 - ₱25,000/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Entry-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Entry-Level')
                ],
                [
                    'level' => 'Junior-Level',
                    'duration' => '1 - 2.5 Years',
                    'salary_range' => '₱25,000 - ₱40,000/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Junior-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Junior-Level')
                ],
                [
                    'level' => 'Mid-Level',
                    'duration' => '2.5 - 5 Years',
                    'salary_range' => '₱40,000 - ₱70,000/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Mid-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Mid-Level')
                ],
                [
                    'level' => 'Senior-Level',
                    'duration' => '5 - 8 Years',
                    'salary_range' => '₱70,000 - ₱120,000/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Senior-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Senior-Level')
                ],
                [
                    'level' => 'Principal-Level',
                    'duration' => '8 - 12 Years',
                    'salary_range' => '₱120,000 - ₱200,000/month',
                    'description' => 'Distinguished technologist shaping organizational technology strategy.',
                    'responsibilities' => [
                        'Define long-term technical vision and roadmap',
                        'Lead cross-functional technical initiatives',
                        'Mentor senior engineers and technical leaders',
                        'Represent company in technical conferences and forums',
                        'Drive digital transformation and innovation projects'
                    ]
                ],
                [
                    'level' => 'Executive-Level',
                    'duration' => '12+ Years',
                    'salary_range' => '₱200,000 - ₱500,000+/month',
                    'description' => 'Executive leader driving enterprise-wide technology strategy and business growth.',
                    'responsibilities' => [
                        'Set enterprise technology strategy and budget',
                        'Build and manage large technical organizations',
                        'Partner with C-suite on business technology alignment',
                        'Drive mergers, acquisitions, and strategic partnerships',
                        'Represent company as industry thought leader'
                    ]
                ]
            ];
        } else {
            // Standard 4-level progression for other roles
            $careerLevels = [
                [
                    'level' => 'Entry-Level',
                    'duration' => '0 - 2 Years',
                    'salary_range' => '₱20,000 - ₱35,000/month',
                    'description' => 'Starting your career in the Philippines with foundational skills and on-the-job training.',
                    'responsibilities' => [
                        'Learn company-specific tools and methodologies',
                        'Work under supervision of senior team members',
                        'Participate in team meetings and training sessions',
                        'Complete assigned tasks and learning objectives',
                        'Build professional relationships and network'
                    ]
                ],
                [
                    'level' => 'Mid-Level',
                    'duration' => '2 - 5 Years',
                    'salary_range' => '₱35,000 - ₱80,000/month',
                    'description' => 'Established professional with proven skills, working on significant projects independently.',
                    'responsibilities' => [
                        'Lead complete projects and initiatives',
                        'Collaborate with cross-functional teams',
                        'Mentor junior staff and new hires',
                        'Participate in strategic planning and decisions',
                        'Handle complex challenges and problem-solving'
                    ]
                ],
                [
                    'level' => 'Senior-Level',
                    'duration' => '5 - 8 Years',
                    'salary_range' => '₱80,000 - ₱150,000/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Senior-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Senior-Level')
                ],
                [
                    'level' => 'Leadership-Level',
                    'duration' => '8+ Years',
                    'salary_range' => '₱150,000 - ₱300,000+/month',
                    'description' => $this->getDescriptionForRole($targetRole, 'Leadership-Level'),
                    'responsibilities' => $this->getResponsibilitiesForRole($targetRole, 'Leadership-Level')
                ]
            ];
        }

        // Customize based on target role and reverse order for proper ascending display
        $totalLevels = count($careerLevels);
        foreach ($careerLevels as $index => $level) {
            $careerLevels[$index]['title'] = $this->getCustomizedTitle($targetRole, $level['level']);
            // Make step numbering ascend from 1 (bottom) to max (top)
            $careerLevels[$index]['step'] = $totalLevels - $index;
        }

        // Reverse the array so highest step number (leadership) appears first in layout
        return array_reverse($careerLevels);
    }

    /**
     * Get role-specific descriptions for each career level
     */
    private function getDescriptionForRole($targetRole, $level)
    {
        $descriptions = [
            'Software Developer' => [
                'Entry-Level' => 'Fresh graduate developing basic software features under guidance from senior engineers. Learning industry practices and development tools.',
                'Junior-Level' => 'Building competence in full development lifecycle with increasing responsibility for feature delivery. Contributing to team projects independently.',
                'Mid-Level' => 'Independent contributor designing and delivering complex features with minimal supervision. Mentoring junior developers and driving code quality.',
                'Senior-Level' => 'Leading architectural decisions and technical excellence while mentoring junior developers. Setting standards and driving innovation.',
                'Principal-Level' => 'Shaping company-wide engineering practices and driving strategic technology initiatives. Leading major platform decisions.',
                'Executive-Level' => 'Setting enterprise engineering vision and leading large-scale technology transformation. Building high-performing teams.'
            ],
            'Web Developer' => [
                'Entry-Level' => 'Learning web development fundamentals and building simple static and dynamic web pages. Working under guidance and building foundational skills.',
                'Junior-Level' => 'Developing responsive web applications with both front-end and back-end technologies. Contributing to team projects with growing independence.',
                'Mid-Level' => 'Architecting scalable web solutions and leading development of complete web platforms. Mentoring junior developers.',
                'Senior-Level' => 'Defining web technology strategy and leading high-performing web development teams. Driving innovation and best practices.',
                'Principal-Level' => 'Shaping digital transformation strategy and establishing web development excellence across organization. Leading enterprise initiatives.',
                'Executive-Level' => 'Leading digital innovation and aligning web technology with business objectives. Building and managing large teams.'
            ],
            'Data Analyst' => [
                'Entry-Level' => 'Learning data analysis tools and supporting analysts on basic reporting and data queries. Building foundational analytical skills.',
                'Junior-Level' => 'Creating analytical reports and dashboards with increasing complexity and business impact. Contributing to data-driven insights.',
                'Mid-Level' => 'Leading analytical projects and providing strategic insights that drive business decisions. Mentoring junior analysts.',
                'Senior-Level' => 'Designing data strategies and influencing major decisions through advanced analytics and insights. Driving data culture.',
                'Principal-Level' => 'Shaping data-driven culture and establishing enterprise analytics standards and practices. Leading transformation initiatives.',
                'Executive-Level' => 'Leading data transformation initiatives and aligning analytics with organizational strategy. Building analytics leadership.'
            ],
            'Systems Administrator' => [
                'Entry-Level' => 'Supporting infrastructure operations and learning system administration under supervision. Building technical foundation.',
                'Junior-Level' => 'Managing IT infrastructure and handling system administration tasks independently. Growing operational expertise.',
                'Mid-Level' => 'Leading infrastructure initiatives and optimizing systems for performance and reliability. Mentoring junior administrators.',
                'Senior-Level' => 'Defining infrastructure strategy and driving adoption of cloud and modern DevOps practices. Setting standards.',
                'Principal-Level' => 'Shaping organizational infrastructure vision and establishing enterprise IT excellence standards. Leading transformation.',
                'Executive-Level' => 'Leading IT operations transformation and aligning infrastructure with business strategy. Building and managing teams.'
            ],
            'IT Support Specialist' => [
                'Entry-Level' => 'Providing helpdesk support and learning IT troubleshooting fundamentals. Supporting user success.',
                'Junior-Level' => 'Handling tier-2 support issues and managing IT systems independently. Growing technical expertise.',
                'Mid-Level' => 'Leading IT support operations and implementing process improvements. Mentoring junior support staff.',
                'Senior-Level' => 'Defining IT support strategy and ensuring organizational technology enablement. Driving service excellence.',
                'Principal-Level' => 'Shaping IT service delivery and driving customer-centric support excellence. Leading operational transformation.',
                'Executive-Level' => 'Leading IT operations and aligning support services with business objectives. Building support leadership.'
            ],
            'Cybersecurity Analyst' => [
                'Entry-Level' => 'Learning security fundamentals and supporting security operations under supervision. Building security knowledge.',
                'Junior-Level' => 'Conducting vulnerability assessments and implementing security controls independently. Growing security expertise.',
                'Mid-Level' => 'Leading security initiatives and managing comprehensive security programs. Mentoring junior analysts.',
                'Senior-Level' => 'Defining organizational cybersecurity strategy and driving enterprise security transformation. Setting standards.',
                'Principal-Level' => 'Shaping security culture and establishing industry-leading security practices. Leading transformation.',
                'Executive-Level' => 'Leading enterprise security transformation and aligning security with business strategy. Building security leadership.'
            ],
            'Administrative Officer' => [
                'Entry-Level' => 'Supporting office operations, managing schedules, and learning administrative systems and procedures.',
                'Mid-Level' => 'Managing office administration independently and implementing process improvements. Mentoring junior staff.',
                'Senior-Level' => 'Leading administrative functions and optimizing organizational processes and systems. Driving efficiency.',
                'Leadership-Level' => 'Directing administrative operations and driving operational excellence across organization. Building operations teams.'
            ],
            'Accountant' => [
                'Entry-Level' => 'Learning accounting procedures and supporting senior accountants on financial transactions and records.',
                'Mid-Level' => 'Managing accounting functions independently and ensuring financial accuracy and compliance. Mentoring junior accountants.',
                'Senior-Level' => 'Leading accounting operations and providing strategic financial insights to management. Driving financial excellence.',
                'Leadership-Level' => 'Directing accounting department and aligning financial management with business strategy. Building accounting leadership.'
            ],
            'Sales Representative' => [
                'Entry-Level' => 'Learning sales techniques and supporting team on customer prospecting and account management.',
                'Mid-Level' => 'Managing client relationships and closing sales independently with strong revenue impact. Mentoring junior sales staff.',
                'Senior-Level' => 'Leading sales initiatives and mentoring team while managing key strategic accounts. Driving revenue growth.',
                'Leadership-Level' => 'Directing sales operations and driving revenue growth through team development and strategy. Building sales leadership.'
            ],
            'Marketing Coordinator' => [
                'Entry-Level' => 'Supporting marketing campaigns and learning marketing fundamentals and tools. Building marketing knowledge.',
                'Mid-Level' => 'Managing marketing projects and campaigns with increasing strategic responsibility. Mentoring junior marketers.',
                'Senior-Level' => 'Leading marketing initiatives and developing strategies that drive brand growth. Driving brand excellence.',
                'Leadership-Level' => 'Directing marketing operations and aligning marketing strategy with business objectives. Building marketing leadership.'
            ],
            'Human Resources Specialist' => [
                'Entry-Level' => 'Supporting HR functions and learning human resources processes and policies. Building HR foundation.',
                'Mid-Level' => 'Managing HR programs and initiatives independently with focus on employee engagement. Mentoring junior HR staff.',
                'Senior-Level' => 'Leading HR operations and implementing strategic people programs that drive organizational success. Driving talent excellence.',
                'Leadership-Level' => 'Directing HR functions and aligning people strategy with business growth objectives. Building HR leadership.'
            ],
            'Customer Service Representative' => [
                'Entry-Level' => 'Providing customer support through various channels and learning service best practices. Supporting customer success.',
                'Mid-Level' => 'Managing customer relationships and resolving complex issues with minimal escalation. Mentoring junior service staff.',
                'Senior-Level' => 'Leading customer service operations and implementing improvements that enhance satisfaction. Driving service excellence.',
                'Leadership-Level' => 'Directing customer service functions and ensuring exceptional service delivery organizational-wide. Building service leadership.'
            ],
            'Business Development Manager' => [
                'Entry-Level' => 'Supporting business development activities and learning market analysis and client development.',
                'Mid-Level' => 'Identifying growth opportunities and developing business relationships independently. Mentoring junior BD staff.',
                'Senior-Level' => 'Leading business development initiatives and driving significant revenue expansion. Driving growth strategy.',
                'Leadership-Level' => 'Directing business development strategy and establishing new market opportunities. Building BD leadership.'
            ],
            'Operations Manager' => [
                'Entry-Level' => 'Supporting operations and learning process management and operational systems. Building operations foundation.',
                'Mid-Level' => 'Managing operations independently and implementing efficiency improvements. Mentoring junior operations staff.',
                'Senior-Level' => 'Leading operational transformation and optimizing organizational processes and systems. Driving operational excellence.',
                'Leadership-Level' => 'Directing operations strategy and ensuring operational excellence across organization. Building operations leadership.'
            ]
        ];

        // Generic fallback
        $generic = [
            'Entry-Level' => 'Starting your career with foundational skills and on-the-job training in the Philippines.',
            'Junior-Level' => 'Gaining practical experience and building competence in your chosen field.',
            'Mid-Level' => 'Established professional with proven skills managing significant responsibilities independently.',
            'Senior-Level' => 'Expert professional leading initiatives and driving innovation in your organization.',
            'Principal-Level' => 'Distinguished professional shaping organizational strategy and thought leadership.',
            'Leadership-Level' => 'Strategic leader managing teams and driving organizational success and vision.',
            'Executive-Level' => 'Executive leader setting enterprise vision and driving organizational transformation.'
        ];

        return $descriptions[$targetRole][$level] ?? $generic[$level] ?? 'Professional development opportunity.';
    }

    /**
     * Get role-specific responsibilities for each career level
     */
    private function getResponsibilitiesForRole($targetRole, $level)
    {
        $responsibilities = [
            'Software Developer' => [
                'Entry-Level' => [
                    'Complete company onboarding and training programs',
                    'Learn development tools and coding standards',
                    'Fix minor bugs and implement small features',
                    'Shadow senior team members on projects',
                    'Participate in code reviews as observer'
                ],
                'Junior-Level' => [
                    'Develop complete features under supervision',
                    'Write and maintain technical documentation',
                    'Participate actively in team meetings and planning',
                    'Handle customer support tickets and bug reports',
                    'Learn testing frameworks and quality assurance',
                    'Contribute to code optimization and refactoring'
                ],
                'Mid-Level' => [
                    'Design and implement complete modules independently',
                    'Mentor junior developers and interns',
                    'Participate in technical architecture discussions',
                    'Lead small project teams (2-3 people)',
                    'Interface with QA and DevOps teams',
                    'Drive code quality improvements and standards',
                    'Evaluate and recommend new technologies'
                ],
                'Senior-Level' => [
                    'Lead end-to-end project architecture and design',
                    'Establish coding standards and development practices',
                    'Conduct technical interviews and team assessments',
                    'Interface with clients and stakeholders',
                    'Drive technical innovation and research initiatives',
                    'Mentor and develop senior team members',
                    'Define system architecture for complex projects',
                    'Evaluate enterprise tools and frameworks'
                ],
                'Principal-Level' => [
                    'Define long-term technical vision and roadmap',
                    'Lead cross-functional technical initiatives',
                    'Mentor senior engineers and technical leaders',
                    'Represent company in technical conferences and forums',
                    'Drive digital transformation and innovation projects',
                    'Shape organizational technology stack decisions',
                    'Architect major platform-wide systems',
                    'Build partnerships with external technical organizations'
                ],
                'Executive-Level' => [
                    'Set enterprise technology strategy and budget',
                    'Build and manage large technical organizations',
                    'Partner with C-suite on business technology alignment',
                    'Drive mergers, acquisitions, and strategic partnerships',
                    'Represent company as industry thought leader',
                    'Manage technology investments and ROI',
                    'Lead organizational transformation initiatives',
                    'Shape company culture around technical excellence'
                ]
            ],
            'Web Developer' => [
                'Entry-Level' => [
                    'Build static and dynamic web pages using HTML, CSS, JavaScript',
                    'Work under supervision implementing design mockups',
                    'Test web applications for functionality and usability',
                    'Fix bugs identified in code review',
                    'Learn web development frameworks and libraries'
                ],
                'Junior-Level' => [
                    'Develop responsive web applications',
                    'Implement frontend features from design specifications',
                    'Write clean and maintainable code',
                    'Collaborate with backend developers on APIs',
                    'Test applications across browsers and devices',
                    'Learn performance optimization techniques'
                ],
                'Mid-Level' => [
                    'Design and implement complex web applications',
                    'Lead frontend architecture decisions',
                    'Mentor junior web developers',
                    'Optimize website performance and user experience',
                    'Interface with product and design teams',
                    'Lead small development teams',
                    'Implement responsive design patterns'
                ],
                'Senior-Level' => [
                    'Lead end-to-end web application design and development',
                    'Establish web development standards and best practices',
                    'Mentor and develop junior and mid-level developers',
                    'Interface with clients and stakeholders',
                    'Drive innovation in web technologies',
                    'Architect scalable web applications',
                    'Conduct technical interviews',
                    'Establish website performance and security standards'
                ],
                'Principal-Level' => [
                    'Define long-term web technology vision and strategy',
                    'Lead organization-wide web transformation',
                    'Mentor senior engineers and web architects',
                    'Represent company in web development forums',
                    'Drive digital experience innovation',
                    'Shape organizational web platform decisions',
                    'Lead major web infrastructure initiatives',
                    'Establish enterprise web standards'
                ],
                'Executive-Level' => [
                    'Set web and digital strategy for the organization',
                    'Build and manage large web development teams',
                    'Partner with business leadership on digital transformation',
                    'Drive web innovation and competitive advantage',
                    'Manage web development investments and budgets',
                    'Lead organization-wide digital initiatives',
                    'Represent company on digital transformation',
                    'Drive business growth through digital platforms'
                ]
            ],
            'Data Analyst' => [
                'Entry-Level' => [
                    'Extract and clean data from various sources',
                    'Create basic SQL queries to retrieve data',
                    'Build simple dashboards and reports',
                    'Assist with data analysis under supervision',
                    'Learn business intelligence tools'
                ],
                'Junior-Level' => [
                    'Analyze datasets to identify trends and patterns',
                    'Write moderate complexity SQL queries',
                    'Create interactive dashboards for various departments',
                    'Support business decision-making with data',
                    'Learn statistical analysis techniques',
                    'Collaborate with stakeholders on data needs'
                ],
                'Mid-Level' => [
                    'Lead data analysis projects independently',
                    'Design data models and analytical frameworks',
                    'Mentor junior data analysts',
                    'Create advanced visualizations and reports',
                    'Analyze business problems and recommend solutions',
                    'Work with data engineers on data pipelines',
                    'Present findings to leadership'
                ],
                'Senior-Level' => [
                    'Drive organizational data strategy and initiatives',
                    'Lead complex analytical projects',
                    'Mentor and develop analytics teams',
                    'Interface with C-suite on strategic insights',
                    'Design and oversee data governance',
                    'Architect analytics solutions',
                    'Conduct predictive and statistical analysis',
                    'Lead cross-functional analytics initiatives'
                ],
                'Principal-Level' => [
                    'Define organizational data and analytics vision',
                    'Lead enterprise-wide analytics transformation',
                    'Mentor senior analytics professionals',
                    'Represent company in data science forums',
                    'Drive advanced analytics and AI initiatives',
                    'Shape organizational data strategy',
                    'Build high-performing analytics teams',
                    'Drive business transformation through data'
                ],
                'Executive-Level' => [
                    'Set enterprise data and analytics strategy',
                    'Build and manage large analytics organizations',
                    'Partner with C-suite on data-driven decisions',
                    'Drive business transformation through data',
                    'Manage analytics investments and ROI',
                    'Shape data culture across organization',
                    'Lead organizational data governance',
                    'Establish data as strategic business asset'
                ]
            ],
            'Systems Administrator' => [
                'Entry-Level' => [
                    'Assist with server and system maintenance',
                    'Monitor system performance and health',
                    'Help users with technical issues',
                    'Learn system administration best practices',
                    'Assist with system backups and recovery'
                ],
                'Junior-Level' => [
                    'Manage server and system operations',
                    'Handle user access and permissions',
                    'Perform system updates and patches',
                    'Troubleshoot infrastructure issues',
                    'Maintain system documentation',
                    'Assist with disaster recovery planning'
                ],
                'Mid-Level' => [
                    'Design and implement system solutions',
                    'Mentor junior system administrators',
                    'Manage complex infrastructure',
                    'Plan and execute system upgrades',
                    'Establish system management policies',
                    'Lead infrastructure projects',
                    'Optimize system performance'
                ],
                'Senior-Level' => [
                    'Lead infrastructure strategy and planning',
                    'Design enterprise systems architecture',
                    'Mentor and develop administration teams',
                    'Interface with business leaders on IT needs',
                    'Establish infrastructure standards',
                    'Drive infrastructure modernization',
                    'Conduct capacity planning and forecasting',
                    'Lead disaster recovery and continuity planning'
                ],
                'Principal-Level' => [
                    'Define long-term infrastructure vision',
                    'Lead organization-wide infrastructure initiatives',
                    'Mentor infrastructure leaders',
                    'Represent company in infrastructure forums',
                    'Drive infrastructure innovation',
                    'Shape organizational technology platform',
                    'Build high-performing infrastructure teams',
                    'Lead cloud and hybrid infrastructure transformation'
                ],
                'Executive-Level' => [
                    'Set enterprise IT operations strategy',
                    'Build and manage large IT operations teams',
                    'Partner with business on IT strategy',
                    'Drive IT transformation and modernization',
                    'Manage IT operations budget and investments',
                    'Shape IT culture in organization',
                    'Lead organizational digital infrastructure',
                    'Establish IT as strategic business enabler'
                ]
            ],
            'IT Support Specialist' => [
                'Entry-Level' => [
                    'Respond to user technical support requests',
                    'Troubleshoot basic hardware and software issues',
                    'Document issues and solutions',
                    'Assist with IT onboarding of new employees',
                    'Learn IT support tools and processes'
                ],
                'Junior-Level' => [
                    'Handle complex user support issues',
                    'Provide technical training to users',
                    'Maintain IT infrastructure components',
                    'Assist with hardware installations',
                    'Document and share knowledge base articles',
                    'Support system deployments and migrations'
                ],
                'Mid-Level' => [
                    'Lead IT support operations independently',
                    'Mentor junior support staff',
                    'Design support processes and procedures',
                    'Manage hardware and software inventory',
                    'Interface with vendors on technical issues',
                    'Lead infrastructure projects',
                    'Conduct technical training for users'
                ],
                'Senior-Level' => [
                    'Lead IT operations and support strategy',
                    'Mentor and develop support teams',
                    'Interface with business leaders on IT support',
                    'Design support solutions for complex problems',
                    'Establish IT service management standards',
                    'Lead infrastructure and system upgrades',
                    'Conduct vendor negotiations and management',
                    'Lead disaster recovery planning'
                ],
                'Principal-Level' => [
                    'Define organizational IT support vision',
                    'Lead enterprise IT operations transformation',
                    'Mentor IT operations leaders',
                    'Represent company in IT forums',
                    'Drive IT operations innovation',
                    'Shape organizational IT infrastructure',
                    'Build high-performing IT operations teams',
                    'Lead enterprise-wide system initiatives'
                ],
                'Executive-Level' => [
                    'Set enterprise IT support and operations strategy',
                    'Build and manage large IT operations teams',
                    'Partner with C-suite on IT strategy',
                    'Drive IT transformation and modernization',
                    'Manage IT operations budget and investments',
                    'Shape IT culture and service delivery',
                    'Lead organizational digital infrastructure',
                    'Position IT as strategic business partner'
                ]
            ],
            'Cybersecurity Analyst' => [
                'Entry-Level' => [
                    'Monitor network and system security',
                    'Assist with vulnerability assessments',
                    'Learn security tools and frameworks',
                    'Document security incidents and responses',
                    'Support security compliance efforts'
                ],
                'Junior-Level' => [
                    'Conduct vulnerability assessments and scans',
                    'Respond to security incidents',
                    'Analyze security logs and alerts',
                    'Implement basic security controls',
                    'Document security procedures',
                    'Participate in security testing'
                ],
                'Mid-Level' => [
                    'Lead security analysis and incident response',
                    'Design and implement security solutions',
                    'Mentor junior security analysts',
                    'Conduct security assessments and audits',
                    'Establish security best practices',
                    'Lead security projects and initiatives',
                    'Interface with vendors on security tools'
                ],
                'Senior-Level' => [
                    'Lead organizational security strategy',
                    'Design enterprise security architecture',
                    'Mentor and develop security teams',
                    'Interface with C-suite on security risks',
                    'Establish organization-wide security standards',
                    'Lead major security initiatives',
                    'Conduct security risk assessments',
                    'Lead incident response and forensics'
                ],
                'Principal-Level' => [
                    'Define long-term organizational security vision',
                    'Lead enterprise-wide security transformation',
                    'Mentor security leaders and practitioners',
                    'Represent company in security forums',
                    'Drive security innovation and research',
                    'Shape organizational security culture',
                    'Build high-performing security teams',
                    'Lead organization-wide security initiatives'
                ],
                'Executive-Level' => [
                    'Set enterprise cybersecurity strategy',
                    'Build and manage large security teams',
                    'Partner with board and C-suite on security',
                    'Drive organizational security transformation',
                    'Manage security budget and investments',
                    'Shape security culture across organization',
                    'Lead security governance and risk management',
                    'Position security as business enabler'
                ]
            ],
            'Administrative Officer' => [
                'Entry-Level' => [
                    'Assist with office administration tasks',
                    'Manage office supplies and inventory',
                    'Support executive and team scheduling',
                    'Learn administrative processes and procedures',
                    'Help organize office events and meetings'
                ],
                'Mid-Level' => [
                    'Manage office operations independently',
                    'Coordinate team meetings and events',
                    'Mentor junior administrative staff',
                    'Establish office procedures and standards',
                    'Interface with vendors and service providers',
                    'Manage office facilities and resources',
                    'Support multiple departments and teams'
                ],
                'Senior-Level' => [
                    'Lead administrative and operations functions',
                    'Mentor and develop administrative teams',
                    'Interface with leadership on operations',
                    'Design operational processes and systems',
                    'Establish administrative standards',
                    'Lead office relocation or renovation projects',
                    'Manage contracts and vendor relationships',
                    'Drive operational efficiency improvements'
                ],
                'Leadership-Level' => [
                    'Direct organizational operations strategy',
                    'Build and manage large operations teams',
                    'Partner with leadership on operational needs',
                    'Develop and implement operations systems',
                    'Manage operations budget and investments',
                    'Lead organizational transformation initiatives',
                    'Establish organizational operational excellence',
                    'Drive business efficiency and effectiveness'
                ]
            ],
            'Accountant' => [
                'Entry-Level' => [
                    'Process accounting transactions',
                    'Assist with month-end closing procedures',
                    'Learn accounting principles and standards',
                    'Support accounts payable and receivable',
                    'Maintain accounting records and files'
                ],
                'Mid-Level' => [
                    'Manage accounting functions independently',
                    'Prepare financial statements and reports',
                    'Mentor junior accounting staff',
                    'Conduct account reconciliations',
                    'Establish accounting procedures and controls',
                    'Interface with external auditors',
                    'Support financial planning and analysis'
                ],
                'Senior-Level' => [
                    'Lead accounting operations and strategy',
                    'Mentor and develop accounting teams',
                    'Interface with leadership on financial matters',
                    'Design accounting systems and controls',
                    'Establish accounting standards',
                    'Lead financial audits and reviews',
                    'Support strategic financial decisions',
                    'Drive accounting efficiency'
                ],
                'Leadership-Level' => [
                    'Direct organizational accounting and finance',
                    'Build and manage large accounting teams',
                    'Partner with C-suite on financial strategy',
                    'Develop accounting policies and systems',
                    'Manage accounting budget and investments',
                    'Lead financial planning and forecasting',
                    'Drive organizational financial health',
                    'Establish financial controls and governance'
                ]
            ],
            'Sales Representative' => [
                'Entry-Level' => [
                    'Contact potential customers and prospects',
                    'Learn sales techniques and processes',
                    'Support account management for existing clients',
                    'Prepare sales presentations and proposals',
                    'Assist with sales pipeline management'
                ],
                'Mid-Level' => [
                    'Manage assigned sales territory independently',
                    'Build client relationships and nurture accounts',
                    'Meet and exceed sales targets',
                    'Mentor junior sales representatives',
                    'Develop sales strategies for key accounts',
                    'Interface with clients on needs and solutions',
                    'Participate in sales planning and forecasting'
                ],
                'Senior-Level' => [
                    'Lead sales initiatives and major accounts',
                    'Mentor and develop sales teams',
                    'Interface with C-suite and key customers',
                    'Develop sales strategies and approaches',
                    'Manage large complex deals and negotiations',
                    'Drive sales team performance',
                    'Build strategic partnerships',
                    'Support business development initiatives'
                ],
                'Leadership-Level' => [
                    'Direct organizational sales strategy',
                    'Build and manage large sales teams',
                    'Partner with business leadership on growth',
                    'Develop sales systems and processes',
                    'Manage sales budget and resources',
                    'Lead sales transformation and growth',
                    'Drive organizational revenue growth',
                    'Drive competitive market positioning'
                ]
            ],
            'Marketing Coordinator' => [
                'Entry-Level' => [
                    'Assist with marketing campaign execution',
                    'Learn marketing tools and platforms',
                    'Support content creation and distribution',
                    'Help organize marketing events',
                    'Maintain marketing materials and collateral'
                ],
                'Mid-Level' => [
                    'Coordinate marketing campaigns independently',
                    'Create and manage marketing content',
                    'Mentor junior marketing staff',
                    'Analyze campaign performance and results',
                    'Develop marketing strategies for initiatives',
                    'Interface with customers and stakeholders',
                    'Manage social media and digital marketing'
                ],
                'Senior-Level' => [
                    'Lead marketing strategy and initiatives',
                    'Mentor and develop marketing teams',
                    'Interface with business leaders on marketing',
                    'Design marketing systems and approaches',
                    'Develop go-to-market strategies',
                    'Manage marketing budget and resources',
                    'Drive marketing innovation and growth',
                    'Lead marketing transformation'
                ],
                'Leadership-Level' => [
                    'Direct organizational marketing strategy',
                    'Build and manage large marketing teams',
                    'Partner with C-suite on brand strategy',
                    'Develop marketing systems and budgets',
                    'Manage marketing investments and ROI',
                    'Lead brand development and positioning',
                    'Drive organizational market presence',
                    'Drive business growth through marketing'
                ]
            ],
            'Human Resources Specialist' => [
                'Entry-Level' => [
                    'Assist with recruitment and hiring',
                    'Support employee onboarding process',
                    'Learn HR processes and systems',
                    'Help maintain HR records and files',
                    'Assist with HR events and programs'
                ],
                'Mid-Level' => [
                    'Manage recruitment and hiring independently',
                    'Develop employee programs and initiatives',
                    'Mentor junior HR staff',
                    'Handle employee relations and concerns',
                    'Establish HR procedures and processes',
                    'Interface with leadership on people matters',
                    'Support compensation and benefits'
                ],
                'Senior-Level' => [
                    'Lead HR strategy and initiatives',
                    'Mentor and develop HR teams',
                    'Interface with C-suite on talent strategy',
                    'Design HR systems and processes',
                    'Develop organizational talent strategies',
                    'Lead organizational culture initiatives',
                    'Manage organizational development programs',
                    'Lead HR transformation'
                ],
                'Leadership-Level' => [
                    'Direct organizational HR and people strategy',
                    'Build and manage large HR organizations',
                    'Partner with C-suite on talent and culture',
                    'Develop HR systems and strategies',
                    'Manage HR budget and investments',
                    'Lead organizational transformation',
                    'Build high-performing organizational culture',
                    'Drive organizational effectiveness and growth'
                ]
            ],
            'Customer Service Representative' => [
                'Entry-Level' => [
                    'Respond to customer inquiries and requests',
                    'Learn customer service best practices',
                    'Resolve customer issues under guidance',
                    'Document customer interactions',
                    'Help with customer onboarding'
                ],
                'Mid-Level' => [
                    'Handle complex customer issues independently',
                    'Mentor junior service representatives',
                    'Develop customer solutions and strategies',
                    'Interface with customers on needs',
                    'Improve customer satisfaction and experience',
                    'Analyze customer feedback and trends',
                    'Participate in service improvement projects'
                ],
                'Senior-Level' => [
                    'Lead customer service strategy and operations',
                    'Mentor and develop service teams',
                    'Interface with leadership on customer needs',
                    'Design customer service systems',
                    'Develop customer retention and growth strategies',
                    'Lead customer experience improvements',
                    'Build strategic customer partnerships',
                    'Lead service transformation'
                ],
                'Leadership-Level' => [
                    'Direct organizational customer service strategy',
                    'Build and manage large service organizations',
                    'Partner with C-suite on customer strategy',
                    'Develop customer service systems',
                    'Manage service budget and resources',
                    'Lead customer experience transformation',
                    'Drive organizational customer focus',
                    'Drive business growth through customer experience'
                ]
            ],
            'Business Development Manager' => [
                'Entry-Level' => [
                    'Assist with business opportunity identification',
                    'Support partnership development efforts',
                    'Learn business development processes',
                    'Help with market research and analysis',
                    'Assist with business planning'
                ],
                'Mid-Level' => [
                    'Identify and develop business opportunities',
                    'Build and nurture business partnerships',
                    'Mentor junior BD staff',
                    'Conduct market research and analysis',
                    'Develop business strategies for growth',
                    'Interface with potential partners',
                    'Support strategic initiatives'
                ],
                'Senior-Level' => [
                    'Lead business development strategy',
                    'Mentor and develop BD teams',
                    'Interface with C-suite on growth opportunities',
                    'Develop partnership strategies',
                    'Build major strategic partnerships',
                    'Lead market entry and expansion',
                    'Drive organizational growth initiatives',
                    'Lead BD transformation'
                ],
                'Leadership-Level' => [
                    'Direct organizational growth strategy',
                    'Build and manage large BD organizations',
                    'Partner with C-suite on expansion strategy',
                    'Develop growth systems and approaches',
                    'Manage growth investments and resources',
                    'Lead organizational expansion and transformation',
                    'Drive business growth and market position',
                    'Shape organizational strategic direction'
                ]
            ],
            'Operations Manager' => [
                'Entry-Level' => [
                    'Assist with operational tasks and processes',
                    'Learn operations procedures and systems',
                    'Support team coordination and planning',
                    'Help with operational improvements',
                    'Maintain operational records'
                ],
                'Mid-Level' => [
                    'Manage operations functions independently',
                    'Mentor junior operations staff',
                    'Develop operational processes and systems',
                    'Coordinate multiple teams and functions',
                    'Establish operations standards and procedures',
                    'Interface with customers and vendors',
                    'Drive operational efficiency'
                ],
                'Senior-Level' => [
                    'Lead operations strategy and planning',
                    'Mentor and develop operations teams',
                    'Interface with business leadership',
                    'Design operations systems and processes',
                    'Lead major operational initiatives',
                    'Manage operations budget and resources',
                    'Drive operations excellence and efficiency',
                    'Support business growth'
                ],
                'Leadership-Level' => [
                    'Direct organizational operations strategy',
                    'Build and manage large operations organizations',
                    'Partner with C-suite on operational needs',
                    'Develop operations systems and strategies',
                    'Manage operations budget and investments',
                    'Lead operational transformation',
                    'Drive organizational operational excellence',
                    'Enable business growth through operations'
                ]
            ]
        ];

        // Generic fallback
        $generic = [
            'Entry-Level' => [
                'Complete company onboarding and foundational training',
                'Learn role-specific tools and methodologies',
                'Support senior team members on assigned tasks',
                'Participate in team meetings and training sessions',
                'Build professional relationships and understand company culture'
            ],
            'Junior-Level' => [
                'Develop competence in core job responsibilities',
                'Contribute independently to team projects',
                'Document processes and maintain knowledge base',
                'Seek feedback and continuously improve skills',
                'Participate in professional development opportunities'
            ],
            'Mid-Level' => [
                'Lead complete projects and deliverables',
                'Mentor junior staff and new hires',
                'Participate in strategic planning and decisions',
                'Establish best practices and standards',
                'Drive improvements and process optimization'
            ],
            'Senior-Level' => [
                'Define strategic direction and vision',
                'Lead major initiatives and transformation',
                'Mentor and develop senior team members',
                'Interface with executive leadership',
                'Drive innovation and emerging opportunities'
            ],
            'Leadership-Level' => [
                'Define organizational strategy and goals',
                'Build and manage large teams and organizations',
                'Drive transformation and innovation initiatives',
                'Interface with C-level executives and board',
                'Establish organizational culture and values'
            ],
            'Principal-Level' => [
                'Shape organizational strategy and direction',
                'Lead transformative initiatives across organization',
                'Mentor leaders and build high-performing teams',
                'Drive innovation and thought leadership',
                'Establish best practices and standards'
            ],
            'Executive-Level' => [
                'Set enterprise strategy and vision',
                'Lead organizational transformation',
                'Build and manage large organizations',
                'Drive business growth and profitability',
                'Establish company as industry leader'
            ]
        ];

        return $responsibilities[$targetRole][$level] ?? $generic[$level] ?? [];
    }

    private function getCustomizedTitle($targetRole, $level)
    {
        // Philippines-based realistic job titles across major industries
        $titles = [
            'Entry-Level' => [
                // Technology Roles - Entry Level (Philippines)
                'Software Developer' => 'Associate Software Developer',
                'Web Developer' => 'Junior Web Developer',
                'IT Support Specialist' => 'IT Help Desk Associate',
                'Data Analyst' => 'Business Data Associate',
                'Cybersecurity Analyst' => 'Information Security Associate',
                'Systems Administrator' => 'Junior Systems Administrator',
                'Database Administrator' => 'Database Support Associate',
                'Network Administrator' => 'Network Support Technician',

                // Business & Finance Roles - Entry Level (Philippines)
                'Accountant' => 'Junior Accountant',
                'Financial Analyst' => 'Financial Associate',
                'Sales Representative' => 'Sales Associate',
                'Marketing Coordinator' => 'Marketing Assistant',
                'Human Resources Specialist' => 'HR Associate',
                'Administrative Officer' => 'Administrative Assistant',
                'Customer Service Representative' => 'Customer Service Associate',
                'Business Development Manager' => 'Business Development Associate',
                'Operations Manager' => 'Operations Coordinator',

                // Education Roles - Entry Level (Philippines)
                'Elementary School Teacher' => 'Elementary Teacher I',
                'High School English Teacher' => 'High School Teacher I (English)',
                'High School Math Teacher' => 'High School Teacher I (Mathematics)',
                'High School Science Teacher' => 'High School Teacher I (Science)',
                'Preschool Teacher' => 'Preschool Teacher',
                'Special Education Teacher' => 'Special Education Teacher I',
                'Educational Coordinator' => 'Education Assistant',
                'Curriculum Developer' => 'Curriculum Assistant',

                // Engineering Roles - Entry Level (Philippines)
                'Civil Engineer' => 'Junior Civil Engineer',
                'Mechanical Engineer' => 'Junior Mechanical Engineer',
                'Electrical Engineer' => 'Junior Electrical Engineer',
                'Electronics Engineer' => 'Junior Electronics Engineer',
                'Industrial Engineer' => 'Junior Industrial Engineer',
                'Computer Engineer' => 'Junior Computer Engineer',
                'Chemical Engineer' => 'Junior Chemical Engineer',
                'Project Engineer' => 'Project Engineering Assistant',

                // Healthcare Roles - Entry Level (Philippines)
                'Staff Nurse' => 'Staff Nurse I',
                'Medical Technologist' => 'Medical Technologist I',
                'Pharmacist' => 'Staff Pharmacist',
                'Physical Therapist' => 'Physical Therapist I',
                'Radiologic Technologist' => 'Radiologic Technologist I',
                'Respiratory Therapist' => 'Respiratory Therapist I',
                'Occupational Therapist' => 'Occupational Therapist I',
                'Public Health Officer' => 'Public Health Associate',

                // Legal & Government Roles - Entry Level (Philippines)
                'Police Officer' => 'Police Officer I (PO1)',
                'Government Administrative Officer' => 'Administrative Aide',
                'Compliance Officer' => 'Compliance Associate',
                'Court Personnel' => 'Court Aide',

                'default' => 'Associate ' . $targetRole
            ],
            'Junior-Level' => [
                // Technology Roles - Junior Level (Philippines)
                'Software Developer' => 'Junior Software Developer',
                'Web Developer' => 'Web Developer',
                'IT Support Specialist' => 'IT Support Specialist',
                'Data Analyst' => 'Junior Data Analyst',
                'Cybersecurity Analyst' => 'Junior Information Security Analyst',
                'Systems Administrator' => 'Systems Administrator',
                'Database Administrator' => 'Junior Database Administrator',
                'Network Administrator' => 'Junior Network Administrator',
                'Computer Engineer' => 'Junior Computer Engineer',

                'default' => 'Junior ' . $targetRole
            ],
            'Mid-Level' => [
                // Technology Roles - Mid Level (Philippines)
                'Software Developer' => 'Software Developer',
                'Web Developer' => 'Full Stack Web Developer',
                'IT Support Specialist' => 'IT Systems Specialist',
                'Data Analyst' => 'Business Intelligence Analyst',
                'Cybersecurity Analyst' => 'Information Security Analyst',
                'Systems Administrator' => 'Systems Administrator',
                'Database Administrator' => 'Database Administrator',
                'Network Administrator' => 'Network Administrator',

                // Business & Finance Roles - Mid Level (Philippines)
                'Accountant' => 'Staff Accountant',
                'Financial Analyst' => 'Financial Analyst',
                'Sales Representative' => 'Sales Specialist',
                'Marketing Coordinator' => 'Marketing Specialist',
                'Human Resources Specialist' => 'HR Specialist',
                'Administrative Officer' => 'Administrative Officer',
                'Customer Service Representative' => 'Customer Service Specialist',
                'Business Development Manager' => 'Business Development Specialist',
                'Operations Manager' => 'Operations Supervisor',

                // Education Roles - Mid Level (Philippines)
                'Elementary School Teacher' => 'Elementary Teacher II',
                'High School English Teacher' => 'High School Teacher II (English)',
                'High School Math Teacher' => 'High School Teacher II (Mathematics)',
                'High School Science Teacher' => 'High School Teacher II (Science)',
                'Preschool Teacher' => 'Senior Preschool Teacher',
                'Special Education Teacher' => 'Special Education Teacher II',
                'Educational Coordinator' => 'Education Program Coordinator',
                'Curriculum Developer' => 'Curriculum Specialist',

                // Engineering Roles - Mid Level (Philippines)
                'Civil Engineer' => 'Civil Engineer',
                'Mechanical Engineer' => 'Mechanical Engineer',
                'Electrical Engineer' => 'Electrical Engineer',
                'Electronics Engineer' => 'Electronics Engineer',
                'Industrial Engineer' => 'Industrial Engineer',
                'Computer Engineer' => 'Computer Engineer',
                'Chemical Engineer' => 'Chemical Engineer',
                'Project Engineer' => 'Project Engineer',

                // Healthcare Roles - Mid Level (Philippines)
                'Staff Nurse' => 'Staff Nurse II',
                'Medical Technologist' => 'Medical Technologist II',
                'Pharmacist' => 'Hospital Pharmacist',
                'Physical Therapist' => 'Physical Therapist II',
                'Radiologic Technologist' => 'Radiologic Technologist II',
                'Respiratory Therapist' => 'Respiratory Therapist II',
                'Occupational Therapist' => 'Occupational Therapist II',
                'Public Health Officer' => 'Public Health Officer',

                // Legal & Government Roles - Mid Level (Philippines)
                'Police Officer' => 'Police Officer II (PO2)',
                'Government Administrative Officer' => 'Administrative Officer II',
                'Compliance Officer' => 'Compliance Officer',
                'Court Personnel' => 'Court Stenographer',

                'default' => $targetRole
            ],
            'Senior-Level' => [
                // Technology Roles - Senior Level (Philippines)
                'Software Developer' => 'Senior Software Engineer',
                'Web Developer' => 'Senior Full Stack Engineer',
                'IT Support Specialist' => 'Senior IT Operations Specialist',
                'Data Analyst' => 'Senior Business Intelligence Consultant',
                'Cybersecurity Analyst' => 'Senior Information Security Specialist',
                'Systems Administrator' => 'Senior Systems Engineer',
                'Database Administrator' => 'Senior Database Engineer',
                'Network Administrator' => 'Senior Network Engineer',

                // Business & Finance Roles - Senior Level (Philippines)
                'Accountant' => 'Senior Accountant / CPA',
                'Financial Analyst' => 'Senior Financial Analyst',
                'Sales Representative' => 'Senior Sales Consultant',
                'Marketing Coordinator' => 'Senior Marketing Consultant',
                'Human Resources Specialist' => 'Senior HR Business Partner',
                'Administrative Officer' => 'Senior Administrative Officer',
                'Customer Service Representative' => 'Customer Experience Consultant',
                'Business Development Manager' => 'Senior Business Development Consultant',
                'Operations Manager' => 'Operations Manager',

                // Education Roles - Senior Level (Philippines)
                'Elementary School Teacher' => 'Elementary Master Teacher I',
                'High School English Teacher' => 'High School Master Teacher I (English)',
                'High School Math Teacher' => 'High School Master Teacher I (Mathematics)',
                'High School Science Teacher' => 'High School Master Teacher I (Science)',
                'Preschool Teacher' => 'Lead Preschool Teacher',
                'Special Education Teacher' => 'Senior Special Education Teacher',
                'Educational Coordinator' => 'Senior Education Program Manager',
                'Curriculum Developer' => 'Senior Curriculum Specialist',

                // Engineering Roles - Senior Level (Philippines)
                'Civil Engineer' => 'Senior Civil Engineer',
                'Mechanical Engineer' => 'Senior Mechanical Engineer',
                'Electrical Engineer' => 'Senior Electrical Engineer',
                'Electronics Engineer' => 'Senior Electronics Engineer',
                'Industrial Engineer' => 'Senior Industrial Engineer',
                'Computer Engineer' => 'Senior Computer Engineer',
                'Chemical Engineer' => 'Senior Chemical Engineer',
                'Project Engineer' => 'Senior Project Engineer',

                // Healthcare Roles - Senior Level (Philippines)
                'Staff Nurse' => 'Charge Nurse / Senior Staff Nurse',
                'Medical Technologist' => 'Senior Medical Technologist',
                'Pharmacist' => 'Clinical Pharmacist / Senior Pharmacist',
                'Physical Therapist' => 'Senior Physical Therapist',
                'Radiologic Technologist' => 'Senior Radiologic Technologist',
                'Respiratory Therapist' => 'Senior Respiratory Therapist',
                'Occupational Therapist' => 'Senior Occupational Therapist',
                'Public Health Officer' => 'Senior Public Health Officer',

                // Legal & Government Roles - Senior Level (Philippines)
                'Police Officer' => 'Police Senior Inspector (PSI)',
                'Government Administrative Officer' => 'Administrative Officer IV',
                'Compliance Officer' => 'Senior Compliance Officer',
                'Court Personnel' => 'Court Attorney / Legal Researcher',

                'default' => 'Senior ' . str_replace(['Analyst', 'Administrator', 'Specialist'], ['Consultant', 'Engineer', 'Specialist'], $targetRole)
            ],
            'Leadership-Level' => [
                // Technology Roles - Leadership Level (Philippines)
                'Software Developer' => 'Technical Team Lead / Software Architect',
                'Web Developer' => 'Lead Frontend Engineer / Solutions Architect',
                'IT Support Specialist' => 'IT Operations Manager / Infrastructure Lead',
                'Data Analyst' => 'Analytics Manager / Business Intelligence Lead',
                'Cybersecurity Analyst' => 'Information Security Manager / CISO',
                'Systems Administrator' => 'Infrastructure Manager / DevOps Lead',
                'Database Administrator' => 'Database Manager / Data Architect',
                'Network Administrator' => 'Network Operations Manager / Infrastructure Architect',

                // Business & Finance Roles - Leadership Level (Philippines)
                'Accountant' => 'Accounting Manager / Finance Director',
                'Financial Analyst' => 'Finance Manager / CFO',
                'Sales Representative' => 'Sales Manager / Regional Sales Director',
                'Marketing Coordinator' => 'Marketing Manager / Brand Director',
                'Human Resources Specialist' => 'HR Manager / People Operations Director',
                'Administrative Officer' => 'Administrative Manager / Operations Director',
                'Customer Service Representative' => 'Customer Success Manager / Service Director',
                'Business Development Manager' => 'Business Development Director',
                'Operations Manager' => 'Operations Director / COO',

                // Education Roles - Leadership Level (Philippines)
                'Elementary School Teacher' => 'Elementary School Principal / Education Supervisor',
                'High School English Teacher' => 'English Department Head / Academic Director',
                'High School Math Teacher' => 'Mathematics Department Head / Academic Coordinator',
                'High School Science Teacher' => 'Science Department Head / Research Director',
                'Preschool Teacher' => 'Preschool Principal / Early Childhood Director',
                'Special Education Teacher' => 'Special Education Coordinator / Inclusion Director',
                'Educational Coordinator' => 'Education Director / Academic Affairs Director',
                'Curriculum Developer' => 'Curriculum Director / Academic Standards Manager',

                // Engineering Roles - Leadership Level (Philippines)
                'Civil Engineer' => 'Chief Civil Engineer / Project Director',
                'Mechanical Engineer' => 'Chief Mechanical Engineer / Engineering Manager',
                'Electrical Engineer' => 'Chief Electrical Engineer / Power Systems Manager',
                'Electronics Engineer' => 'Chief Electronics Engineer / R&D Manager',
                'Industrial Engineer' => 'Industrial Engineering Manager / Process Director',
                'Computer Engineer' => 'Chief Technology Officer / Engineering Director',
                'Chemical Engineer' => 'Chief Chemical Engineer / Plant Manager',
                'Project Engineer' => 'Project Manager / Engineering Director',

                // Healthcare Roles - Leadership Level (Philippines)
                'Staff Nurse' => 'Nurse Manager / Director of Nursing',
                'Medical Technologist' => 'Laboratory Manager / Pathology Director',
                'Pharmacist' => 'Pharmacy Director / Chief Pharmacist',
                'Physical Therapist' => 'Rehabilitation Manager / PT Director',
                'Radiologic Technologist' => 'Radiology Manager / Imaging Director',
                'Respiratory Therapist' => 'Respiratory Care Manager / Department Head',
                'Occupational Therapist' => 'OT Department Manager / Rehabilitation Director',
                'Public Health Officer' => 'Public Health Director / Health Program Manager',

                // Legal & Government Roles - Leadership Level (Philippines)
                'Police Officer' => 'Police Superintendent / Regional Director',
                'Government Administrative Officer' => 'Division Chief / Department Director',
                'Compliance Officer' => 'Compliance Manager / Risk Management Director',
                'Court Personnel' => 'Court Administrator / Judicial Administrator',

                'default' => 'Technical Lead / ' . str_replace(['Analyst', 'Administrator', 'Specialist', 'Developer'], ['Manager', 'Manager', 'Manager', 'Architect'], $targetRole)
            ],
            'Principal-Level' => [
                // Technology Roles - Principal Level (Philippines)
                'Software Developer' => 'Principal Software Engineer / Solutions Architect',
                'Web Developer' => 'Principal Frontend Architect / Digital Solutions Lead',
                'IT Support Specialist' => 'Principal Infrastructure Architect / IT Director',
                'Data Analyst' => 'Principal Data Scientist / Chief Analytics Officer',
                'Cybersecurity Analyst' => 'Principal Security Architect / CISO',
                'Systems Administrator' => 'Principal Infrastructure Architect / Cloud Director',
                'Database Administrator' => 'Principal Data Architect / Database Engineering Director',
                'Network Administrator' => 'Principal Network Architect / Infrastructure Director',
                'Computer Engineer' => 'Principal Computer Engineer / Chief Technology Officer',

                'default' => 'Principal ' . str_replace(['Analyst', 'Administrator', 'Specialist', 'Developer'], ['Architect', 'Architect', 'Architect', 'Engineer'], $targetRole)
            ],
            'Executive-Level' => [
                // Technology Roles - Executive Level (Philippines)
                'Software Developer' => 'Chief Technology Officer / VP of Engineering',
                'Web Developer' => 'VP of Digital Innovation / Chief Digital Officer',
                'IT Support Specialist' => 'Chief Information Officer / VP of IT Operations',
                'Data Analyst' => 'Chief Data Officer / VP of Analytics',
                'Cybersecurity Analyst' => 'Chief Information Security Officer / VP of Cybersecurity',
                'Systems Administrator' => 'Chief Infrastructure Officer / VP of Cloud Operations',
                'Database Administrator' => 'Chief Data Officer / VP of Data Engineering',
                'Network Administrator' => 'Chief Network Officer / VP of Infrastructure',
                'Computer Engineer' => 'Chief Technology Officer / VP of Product Engineering',

                'default' => 'Chief ' . str_replace(['Analyst', 'Administrator', 'Specialist', 'Developer'], ['Data Officer', 'Information Officer', 'Technology Officer', 'Technology Officer'], $targetRole)
            ]
        ];

        return $titles[$level][$targetRole] ?? $titles[$level]['default'];
    }

    private function performSkillGapAnalysis($currentSkills, $targetRole)
    {
        // Use SkillMappingService to get accurate role requirements
        $roleSkills = \App\Services\SkillMappingService::getSkillsForRole($targetRole);

        // Check if role uses new categorized structure
        $usesNewStructure = isset($roleSkills['fundamental_skills']);

        if ($usesNewStructure) {
            // NEW: Use weighted matching for IT/CS roles with categorized skills
            $allSkills = \App\Services\SkillMappingService::getAllSkillsForRole($targetRole);
            $matchingSkillNames = array_intersect($allSkills, $currentSkills);
            $missingSkillNames = array_diff($allSkills, $currentSkills);

            // Enrich both matching and missing skills with category information for priority display
            $matchingSkills = $this->enrichSkillsWithCategory($matchingSkillNames, $targetRole);
            $missingSkills = $this->enrichSkillsWithCategory($missingSkillNames, $targetRole);

            // Calculate weighted match percentage
            $matchPercentage = $this->calculateWeightedRoleMatch($targetRole, $currentSkills);

            return [
                'target_role' => $targetRole,
                'required_skills' => $allSkills,
                'current_skills' => $currentSkills,
                'matching_skills' => $matchingSkills,
                'matching_skill_names' => array_values($matchingSkillNames), // Simple array for compatibility
                'missing_skills' => $missingSkills,
                'missing_skill_names' => array_values($missingSkillNames), // Simple array for Tutorial compatibility
                'match_percentage' => $matchPercentage,
                'skill_readiness_level' => $this->getSkillReadinessLevel($matchPercentage),
                'learning_recommendations' => $this->getLearningRecommendations($missingSkillNames, $targetRole),
                'uses_weighted_scoring' => true
            ];
        } else {
            // OLD: Keep existing simple matching for non-IT roles
            $requiredSkills = array_merge(
                $roleSkills['technical_skills'] ?? [],
                $roleSkills['soft_skills'] ?? []
            );

            if (empty($requiredSkills)) {
                $requiredSkills = \App\Services\SkillMappingService::getUniversalSoftSkills();
            }

            $missingSkills = array_diff($requiredSkills, $currentSkills);
            $matchingSkills = array_intersect($requiredSkills, $currentSkills);

            // Separate technical and soft skills in the analysis
            $missingTechnicalSkills = array_intersect($missingSkills, $roleSkills['technical_skills'] ?? []);
            $missingSoftSkills = array_intersect($missingSkills, $roleSkills['soft_skills'] ?? []);

            $matchingTechnicalSkills = array_intersect($matchingSkills, $roleSkills['technical_skills'] ?? []);
            $matchingSoftSkills = array_intersect($matchingSkills, $roleSkills['soft_skills'] ?? []);

            // Calculate match percentage
            $totalRequiredSkills = count($requiredSkills);
            $totalMatchingSkills = count($matchingSkills);
            $matchPercentage = $totalRequiredSkills > 0 ? round(($totalMatchingSkills / $totalRequiredSkills) * 100, 1) : 0;

            // Calculate skill priority levels
            $prioritySkills = $this->calculateSkillPriority($missingTechnicalSkills, $targetRole);

            return [
                'target_role' => $targetRole,
                'required_skills' => $requiredSkills,
                'required_technical_skills' => $roleSkills['technical_skills'] ?? [],
                'required_soft_skills' => $roleSkills['soft_skills'] ?? [],
                'current_skills' => $currentSkills,
                'matching_skills' => $matchingSkills,
                'matching_technical_skills' => $matchingTechnicalSkills,
                'matching_soft_skills' => $matchingSoftSkills,
                'missing_skills' => $missingSkills,
                'missing_technical_skills' => $missingTechnicalSkills,
                'missing_soft_skills' => $missingSoftSkills,
                'match_percentage' => $matchPercentage,
                'priority_skills' => $prioritySkills,
                'skill_readiness_level' => $this->getSkillReadinessLevel($matchPercentage),
                'learning_recommendations' => $this->getLearningRecommendations($missingTechnicalSkills, $targetRole),
                'uses_weighted_scoring' => false
            ];
        }
    }

    /**
     * Calculate weighted role match percentage using skill importance and level multipliers
     *
     * Formula: Σ(User Proficiency × Skill Weight × Level Multiplier) / Σ(Max Proficiency × Skill Weight × Level Multiplier) × 100
     *
     * @param string $role The target role
     * @param array $currentSkills Array of skill names the user possesses
     * @return float Match percentage (0-100)
     */
    private function calculateWeightedRoleMatch($role, $currentSkills): float
    {
        $allSkills = \App\Services\SkillMappingService::getAllSkillsForRole($role);

        if (empty($allSkills)) {
            return 0;
        }

        $userScore = 0;
        $maxScore = 0;

        foreach ($allSkills as $skillName) {
            // Get skill importance weight (1-5 scale)
            $weight = \App\Services\SkillMappingService::getSkillImportanceWeight($role, $skillName);

            // Get skill level multiplier (fundamental/medium/advanced/soft)
            $multiplier = $this->getSkillLevelMultiplier($role, $skillName);

            // User proficiency: 5 if they have the skill, 0 if they don't
            // (Binary for now until questionnaire adds proficiency ratings)
            $userProficiency = in_array($skillName, $currentSkills) ? 5 : 0;

            // Calculate weighted scores
            $userScore += $userProficiency * $weight * $multiplier;
            $maxScore += 5 * $weight * $multiplier; // 5 is max proficiency
        }

        return $maxScore > 0 ? round(($userScore / $maxScore) * 100, 1) : 0;
    }

    /**
     * Get skill level multiplier based on skill category
     *
     * Multipliers prioritize fundamental skills over advanced skills
     * because fundamentals are critical for role entry
     *
     * @param string $role The target role
     * @param string $skillName The skill name
     * @return float Multiplier value
     */
    private function getSkillLevelMultiplier($role, $skillName): float
    {
        $roleData = \App\Services\SkillMappingService::getSkillsForRole($role);

        // Check which category the skill belongs to
        if (in_array($skillName, $roleData['fundamental_skills'] ?? [])) {
            return 1.5; // Fundamental skills are most critical
        }

        if (in_array($skillName, $roleData['medium_skills'] ?? [])) {
            return 1.2; // Medium skills are important for competency
        }

        if (in_array($skillName, $roleData['soft_skills'] ?? [])) {
            return 1.3; // Soft skills are crucial for success
        }

        if (in_array($skillName, $roleData['advanced_skills'] ?? [])) {
            return 1.0; // Advanced skills are nice-to-have, not required for entry
        }

        return 1.0; // Default multiplier
    }

    /**
     * Enrich skill names with category information for priority-based display
     * Skills are sorted by priority: advanced first, then medium, then fundamental, then soft
     *
     * @param array $skillNames Array of skill name strings
     * @param string $role The target role
     * @return array Array of skills with category info: ['name' => 'Skill Name', 'category' => 'advanced']
     */
    private function enrichSkillsWithCategory($skillNames, $role): array
    {
        $roleData = \App\Services\SkillMappingService::getSkillsForRole($role);
        $enrichedSkills = [];

        foreach ($skillNames as $skillName) {
            $category = 'unknown';

            // Determine which category this skill belongs to
            if (in_array($skillName, $roleData['fundamental_skills'] ?? [])) {
                $category = 'fundamental';
            } elseif (in_array($skillName, $roleData['medium_skills'] ?? [])) {
                $category = 'medium';
            } elseif (in_array($skillName, $roleData['advanced_skills'] ?? [])) {
                $category = 'advanced';
            } elseif (in_array($skillName, $roleData['soft_skills'] ?? [])) {
                $category = 'soft';
            }

            $enrichedSkills[] = [
                'name' => $skillName,
                'category' => $category
            ];
        }

        // Sort skills by priority category
        // Priority order: advanced (high) → medium → fundamental (low) → soft
        usort($enrichedSkills, function($a, $b) {
            $priorityOrder = [
                'advanced' => 1,
                'medium' => 2,
                'fundamental' => 3,
                'soft' => 4,
                'unknown' => 5
            ];

            return $priorityOrder[$a['category']] <=> $priorityOrder[$b['category']];
        });

        return $enrichedSkills;
    }

    /**
     * Calculate skill priority based on role importance
     */
    private function calculateSkillPriority($missingSkills, $targetRole)
    {
        // Define high-priority skills for different role categories
        $highPriorityByRole = [
            'Frontend Developer' => ['JavaScript', 'HTML', 'CSS', 'React'],
            'Backend Developer' => ['Python', 'PHP', 'SQL', 'API Development'],
            'Full Stack Developer' => ['JavaScript', 'Python', 'HTML', 'CSS', 'SQL'],
            'Data Scientist' => ['Python', 'SQL', 'Machine Learning', 'Statistics'],
            'Data Analyst' => ['SQL', 'Excel', 'Python', 'Data Visualization'],
            'UX Designer' => ['User Research', 'Wireframing', 'Prototyping', 'Figma'],
            'Digital Marketer' => ['Google Analytics', 'SEO', 'Social Media Marketing'],
        ];

        $highPriority = array_intersect($missingSkills, $highPriorityByRole[$targetRole] ?? []);
        $mediumPriority = array_diff($missingSkills, $highPriority);

        return [
            'high' => array_values($highPriority),
            'medium' => array_values($mediumPriority)
        ];
    }

    /**
     * Get skill readiness level description
     */
    private function getSkillReadinessLevel($matchPercentage)
    {
        if ($matchPercentage >= 80) {
            return [
                'level' => 'Ready',
                'description' => 'You have most of the skills needed for this role!',
                'color' => 'green'
            ];
        } elseif ($matchPercentage >= 60) {
            return [
                'level' => 'Nearly Ready',
                'description' => 'You\'re on the right track - focus on a few key skills.',
                'color' => 'blue'
            ];
        } elseif ($matchPercentage >= 40) {
            return [
                'level' => 'Developing',
                'description' => 'Good foundation - you need to build more skills.',
                'color' => 'yellow'
            ];
        } else {
            return [
                'level' => 'Starting Out',
                'description' => 'Great choice! Time to start your learning journey.',
                'color' => 'red'
            ];
        }
    }

    /**
     * Get learning recommendations based on missing skills
     */
    private function getLearningRecommendations($missingSkills, $targetRole)
    {
        // Generate learning path recommendations
        $recommendations = [];

        foreach (array_slice($missingSkills, 0, 5) as $index => $skill) {
            $recommendations[] = [
                'skill' => $skill,
                'priority' => $index < 2 ? 'High' : 'Medium',
                'estimated_time' => $this->getEstimatedLearningTime($skill),
                'resources' => $this->getSkillResources($skill)
            ];
        }

        return $recommendations;
    }

    /**
     * Get estimated learning time for a skill
     */
    private function getEstimatedLearningTime($skill)
    {
        $timeMapping = [
            'HTML' => '2-4 weeks',
            'CSS' => '3-6 weeks',
            'JavaScript' => '2-4 months',
            'Python' => '2-3 months',
            'SQL' => '1-2 months',
            'React' => '1-2 months',
            'Node.js' => '1-2 months',
            'Machine Learning' => '3-6 months',
            'Data Analysis' => '2-3 months',
            'User Research' => '1-2 months',
            'Figma' => '2-4 weeks',
            'SEO' => '1-2 months',
            'Google Analytics' => '2-4 weeks',
        ];

        return $timeMapping[$skill] ?? '1-3 months';
    }

    /**
     * Get learning resources for a skill
     */
    private function getSkillResources($skill)
    {
        return [
            'Free Resources',
            'Online Courses',
            'Practice Projects',
            'Community Forums'
        ];
    }

    /**
     * Get job recommendations based on MBTI personality type
     *
     * @param string $mbtiType
     * @return array
     */
    private function getMbtiJobRecommendations($mbtiType)
    {
        $recommendations = [
            // Analysts
            'INTJ' => ['Data Scientist', 'Software Engineer', 'Systems Analyst', 'Financial Analyst', 'Business Consultant'],
            'INTP' => ['Backend Developer', 'Data Scientist', 'Systems Architect', 'Research Scientist', 'Business Analyst'],
            'ENTJ' => ['Project Manager', 'Business Consultant', 'Operations Manager', 'Financial Analyst', 'Investment Banker'],
            'ENTP' => ['Business Consultant', 'Marketing Coordinator', 'Creative Director', 'Entrepreneur', 'Product Manager'],

            // Diplomats
            'INFJ' => ['School Counselor', 'Healthcare Administrator', 'Content Marketing Manager', 'Human Resources Manager', 'UX Designer'],
            'INFP' => ['UX Designer', 'Content Marketing Manager', 'School Counselor', 'Social Worker', 'Instructional Designer'],
            'ENFJ' => ['Human Resources Manager', 'School Administrator', 'Corporate Trainer', 'Public Relations Specialist', 'Healthcare Administrator'],
            'ENFP' => ['Marketing Coordinator', 'Public Relations Specialist', 'Event Coordinator', 'Corporate Trainer', 'Sales Representative'],

            // Sentinels
            'ISTJ' => ['Financial Analyst', 'Accountant', 'Quality Control Engineer', 'Systems Analyst', 'Auditor'],
            'ISFJ' => ['Administrative Assistant', 'Medical Assistant', 'Registered Nurse', 'Elementary Teacher', 'Accountant'],
            'ESTJ' => ['Operations Manager', 'Project Manager', 'Financial Planner', 'School Administrator', 'Business Analyst'],
            'ESFJ' => ['Customer Success Manager', 'Human Resources Manager', 'Healthcare Administrator', 'Elementary Teacher', 'Sales Representative'],

            // Explorers
            'ISTP' => ['Civil Engineer', 'Mechanical Engineer', 'Site Engineer', 'Frontend Developer', 'Backend Developer'],
            'ISFP' => ['UX Designer', 'Medical Assistant', 'Tour Guide', 'Administrative Assistant', 'Social Media Manager'],
            'ESTP' => ['Sales Representative', 'Marketing Coordinator', 'Project Manager', 'Event Coordinator', 'Tour Guide'],
            'ESFP' => ['Event Coordinator', 'Sales Representative', 'Tour Guide', 'Customer Success Manager', 'Social Media Manager']
        ];

        return $recommendations[$mbtiType] ?? [];
    }

    public function submitQuestionnaire(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string',
                'responses' => 'required|array',
                'selected_category' => 'required|string'
            ]);

            // For now, return a simple success response
            // This will be enhanced with the sectioned questionnaire logic
            return response()->json([
                'success' => true,
                'message' => 'Questionnaire submitted successfully',
                'skill_scores' => [],
                'recommended_courses' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error processing your questionnaire. Please try again.'
            ], 500);
        }
    }
}
