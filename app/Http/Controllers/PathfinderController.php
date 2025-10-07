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
        $allResponses = $request->get('all_responses');

        // Parse the responses if they're JSON
        if (is_string($allResponses)) {
            $allResponses = json_decode($allResponses, true);
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

        $analysis = $this->performSkillGapAnalysis($currentSkills, $targetRole);

        // Get tutorial recommendations for missing skills
        $tutorialRecommendations = Tutorial::getRecommendationsForSkills($analysis['missing_skills'], 3);
        $analysis['tutorial_recommendations'] = $tutorialRecommendations;

        // Save progress if user is authenticated
        if (Auth::check()) {
            UserProgress::create([
                'user_id' => Auth::id(),
                'feature_type' => 'skill_gap',
                'target_role' => $targetRole,
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
     * Display external learning resources and RSS feeds
     *
     * @return \Illuminate\View\View
     */
    public function externalResources()
    {
        // Mock RSS feed data
        $rssFeeds = [
            'technical' => [
                'Software Development' => [
                    ['title' => 'CSS-Tricks', 'url' => 'https://css-tricks.com/feed/'],
                    ['title' => 'Dev.to', 'url' => 'https://dev.to/feed'],
                    ['title' => 'Smashing Magazine', 'url' => 'https://www.smashingmagazine.com/feed/']
                ],
                'Data Science' => [
                    ['title' => 'Towards Data Science', 'url' => 'https://towardsdatascience.com/feed'],
                    ['title' => 'KDnuggets', 'url' => 'https://www.kdnuggets.com/feed'],
                    ['title' => 'Analytics Vidhya', 'url' => 'https://medium.com/feed/analytics-vidhya']
                ],
                'UX/UI Design' => [
                    ['title' => 'UX Collective', 'url' => 'https://uxdesign.cc/feed'],
                    ['title' => 'UX Movement', 'url' => 'https://uxmovement.com/feed/'],
                    ['title' => 'Nielsen Norman Group', 'url' => 'https://www.nngroup.com/feed/']
                ]
            ],
            'soft_skills' => [
                ['title' => 'Harvard Business Review', 'url' => 'https://hbr.org/feed'],
                ['title' => 'Mind Tools', 'url' => 'https://www.mindtools.com/blog/feed/'],
                ['title' => 'Fast Company', 'url' => 'https://www.fastcompany.com/feed']
            ]
        ];

        // Learning platforms
        $platforms = [
            ['name' => 'Udemy', 'url' => 'https://www.udemy.com', 'description' => 'Marketplace for online learning with courses on virtually any topic'],
            ['name' => 'Coursera', 'url' => 'https://www.coursera.org', 'description' => 'Online courses from top universities and companies'],
            ['name' => 'Pluralsight', 'url' => 'https://www.pluralsight.com', 'description' => 'Technology skill development platform with focus on IT and software development'],
            ['name' => 'LinkedIn Learning', 'url' => 'https://www.linkedin.com/learning', 'description' => 'Professional courses on business, technology and creative skills']
        ];

        return view('pathfinder.external-resources', ['rssFeeds' => $rssFeeds, 'platforms' => $platforms]);
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
                    'description' => 'Fresh graduate or career switcher starting your technology journey in the Philippines.',
                    'responsibilities' => [
                        'Complete company onboarding and training programs',
                        'Learn development tools and coding standards',
                        'Fix minor bugs and implement small features',
                        'Shadow senior team members on projects',
                        'Participate in code reviews as observer'
                    ]
                ],
                [
                    'level' => 'Junior-Level',
                    'duration' => '1 - 2.5 Years',
                    'salary_range' => '₱25,000 - ₱40,000/month',
                    'description' => 'Gaining practical experience and building foundational technical skills.',
                    'responsibilities' => [
                        'Develop complete features under supervision',
                        'Write and maintain technical documentation',
                        'Participate actively in team meetings and planning',
                        'Handle customer support tickets and bug reports',
                        'Learn testing frameworks and quality assurance'
                    ]
                ],
                [
                    'level' => 'Mid-Level',
                    'duration' => '2.5 - 5 Years',
                    'salary_range' => '₱40,000 - ₱70,000/month',
                    'description' => 'Independent contributor with proven ability to deliver complex projects.',
                    'responsibilities' => [
                        'Design and implement complete modules independently',
                        'Mentor junior developers and interns',
                        'Participate in technical architecture discussions',
                        'Lead small project teams (2-3 people)',
                        'Interface with QA and DevOps teams'
                    ]
                ],
                [
                    'level' => 'Senior-Level',
                    'duration' => '5 - 8 Years',
                    'salary_range' => '₱70,000 - ₱120,000/month',
                    'description' => 'Technical expert leading complex initiatives and driving best practices.',
                    'responsibilities' => [
                        'Lead end-to-end project architecture and design',
                        'Establish coding standards and development practices',
                        'Conduct technical interviews and team assessments',
                        'Interface with clients and stakeholders',
                        'Drive technical innovation and research initiatives'
                    ]
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
                    'description' => 'Expert-level professional leading initiatives and driving innovation.',
                    'responsibilities' => [
                        'Lead end-to-end project development and delivery',
                        'Define standards and best practices in field',
                        'Interface with clients and stakeholders',
                        'Drive decision-making for team and department',
                        'Conduct assessments and strategic planning'
                    ]
                ],
                [
                    'level' => 'Leadership-Level',
                    'duration' => '8+ Years',
                    'salary_range' => '₱150,000 - ₱300,000+/month',
                    'description' => 'Strategic leader managing teams and driving organizational vision.',
                    'responsibilities' => [
                        'Define strategic roadmap and organizational goals',
                        'Manage teams, budgets, and resources',
                        'Drive transformation and innovation initiatives',
                        'Partner with business leaders on strategic solutions',
                        'Represent organization at industry events'
                    ]
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

        // Combine technical and soft skills for the role
        $requiredSkills = array_merge(
            $roleSkills['technical_skills'] ?? [],
            $roleSkills['soft_skills'] ?? []
        );

        // If no specific skills found for role, use universal skills
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
            'learning_recommendations' => $this->getLearningRecommendations($missingTechnicalSkills, $targetRole)
        ];
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
