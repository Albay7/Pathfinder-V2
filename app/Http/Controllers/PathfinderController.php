<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use App\Models\Tutorial;

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

        // Mock recommendation logic
        $recommendation = $this->generateRecommendation($answers, $type);

        // Save progress if user is authenticated
        if (Auth::check()) {
            UserProgress::create([
                'user_id' => Auth::id(),
                'feature_type' => 'career_guidance',
                'assessment_type' => $type,
                'questionnaire_answers' => $answers,
                'recommendation' => $recommendation,
                'completed' => true
            ]);
        }

        return view('pathfinder.recommendation', compact('recommendation', 'type'));
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

    // Helper Methods
    private function generateRecommendation($answers, $type)
    {
        if ($type === 'course') {
            return $this->generateCourseRecommendation($answers);
        } else {
            return $this->generateJobRecommendation($answers);
        }
    }

    private function generateCourseRecommendation($answers)
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

    private function generateJobRecommendation($answers)
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

        // Primary industry interest (50% weight)
        if (isset($industryJobs[$jobIndustry])) {
            foreach ($industryJobs[$jobIndustry] as $job) {
                $scores[$job] = ($scores[$job] ?? 0) + 50;
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
            'Digital Marketer' => ['Google Analytics', 'SEO', 'Social Media Marketing', 'Content Marketing', 'PPC', 'Email Marketing']
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
}
