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
        $currentRole = $request->get('current_role');
        $targetRole = $request->get('target_role');

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
        // Mock career path generation
        return [
            ['step' => 1, 'title' => 'Assess Current Skills', 'description' => 'Evaluate your current skill set and experience level.', 'duration' => '1 week'],
            ['step' => 2, 'title' => 'Learn Core Technologies', 'description' => 'Master the fundamental technologies required for your target role.', 'duration' => '3-6 months'],
            ['step' => 3, 'title' => 'Build Portfolio Projects', 'description' => 'Create 3-5 projects that demonstrate your new skills.', 'duration' => '2-3 months'],
            ['step' => 4, 'title' => 'Gain Practical Experience', 'description' => 'Apply for internships or entry-level positions.', 'duration' => '6-12 months'],
            ['step' => 5, 'title' => 'Network and Apply', 'description' => 'Build professional network and apply for target positions.', 'duration' => '1-3 months'],
            ['step' => 6, 'title' => 'Achieve Target Role', 'description' => 'Successfully transition to your desired career path.', 'duration' => 'Ongoing']
        ];
    }

    private function performSkillGapAnalysis($currentSkills, $targetRole)
    {
        // Mock skill requirements for different roles
        $roleRequirements = [
            'Frontend Developer' => ['HTML', 'CSS', 'JavaScript', 'React', 'Vue.js', 'Git', 'Responsive Design'],
            'Backend Developer' => ['PHP', 'Python', 'Node.js', 'SQL', 'API Development', 'Git', 'Docker'],
            'Data Scientist' => ['Python', 'R', 'SQL', 'Machine Learning', 'Statistics', 'Pandas', 'Numpy'],
            'UX Designer' => ['Figma', 'Adobe XD', 'User Research', 'Wireframing', 'Prototyping', 'Design Thinking'],
            'Digital Marketer' => ['Google Analytics', 'SEO', 'Social Media Marketing', 'Content Marketing', 'PPC', 'Email Marketing'],
            'Project Manager' => ['Project Planning', 'Agile Methodologies', 'Risk Management', 'Stakeholder Communication', 'Budgeting', 'Team Leadership'],
            'Business Analyst' => ['Requirements Gathering', 'Process Modeling', 'Data Analysis', 'SQL', 'Documentation', 'Stakeholder Management'],
            'Financial Analyst' => ['Financial Modeling', 'Excel', 'Data Analysis', 'Accounting Principles', 'Forecasting', 'Business Intelligence'],
            'Human Resources Manager' => ['Recruitment', 'Employee Relations', 'Performance Management', 'Compensation & Benefits', 'HR Policies', 'Conflict Resolution'],
            'Marketing Coordinator' => ['Social Media', 'Content Creation', 'Campaign Management', 'Analytics', 'Brand Management', 'Market Research']
        ];

        $requiredSkills = $roleRequirements[$targetRole] ?? ['Communication', 'Problem Solving', 'Teamwork'];
        $missingSkills = array_diff($requiredSkills, $currentSkills);
        $matchingSkills = array_intersect($requiredSkills, $currentSkills);

        return [
            'required_skills' => $requiredSkills,
            'current_skills' => $currentSkills,
            'matching_skills' => $matchingSkills,
            'missing_skills' => $missingSkills,
            'match_percentage' => round((count($matchingSkills) / count($requiredSkills)) * 100, 1)
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
