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

    public function questionnaire(Request $request, $type = 'course')
    {
        if (Auth::check()) {
            $hasProgress = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'career_guidance')
                ->where('assessment_type', $type)
                ->where('completed', true)
                ->exists();

            if ($hasProgress) {
                return redirect()->route('pathfinder.questionnaire.results', ['type' => $type]);
            }
        }

        return view('pathfinder.questionnaire', compact('type'));
    }

    public function showRecommendation(Request $request, $type)
    {
        if (!Auth::check()) {
            return redirect()->route('pathfinder.questionnaire', ['type' => $type]);
        }

        $progress = UserProgress::where('user_id', Auth::id())
            ->where('feature_type', 'career_guidance')
            ->where('assessment_type', $type)
            ->where('completed', true)
            ->latest()
            ->first();

        if (!$progress) {
            return redirect()->route('pathfinder.questionnaire', ['type' => $type]);
        }

        $recommendation = $progress->recommendation;
        $selectedCategory = $progress->selected_category;
        $allResponses = $progress->calculated_scores;

        return view('pathfinder.recommendation', compact('recommendation', 'type', 'selectedCategory', 'allResponses'));
    }

    public function retakeQuestionnaire(Request $request, $type)
    {
        if (Auth::check()) {
            UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'career_guidance')
                ->where('assessment_type', $type)
                ->delete();
        }

        return redirect()->route('pathfinder.questionnaire', ['type' => $type])
            ->with('info', 'Starting a fresh assessment. Good luck!');
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
        $career = urldecode($career);
        $careerDetails = self::getCareerData($career);

        $userMbtiType   = null;
        $mbtiCompatDesc = null;
        $mbtiIsMatch    = false;
        $skillGapResult = null;
        $userCourse     = null;

        if (Auth::check()) {
            $user = Auth::user();
            $userMbtiType = $user->mbti_type;

            if ($userMbtiType) {
                $compatMap      = $this->getMbtiCareerCompatibility();
                $mbtiIsMatch    = isset($compatMap[$career][$userMbtiType]);
                $mbtiCompatDesc = $compatMap[$career][$userMbtiType]
                    ?? "Your {$userMbtiType} personality brings unique strengths that can be applied in this role.";
            }

            $skillGapResult = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'skill_gap')
                ->where('target_role', $career)
                ->where('completed', true)
                ->latest()->first();

            $courseProgress = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'career_guidance')
                ->where('assessment_type', 'course')
                ->where('completed', true)
                ->latest()->first();

            if ($courseProgress) {
                $userCourse = $courseProgress->recommendation;
            }
        }

        return view('pathfinder.career-details', compact(
            'careerDetails', 'userMbtiType', 'mbtiCompatDesc',
            'mbtiIsMatch', 'skillGapResult', 'userCourse'
        ));
    }

    public function courseDetails(Request $request, $course)
    {
        $courseName = urldecode($course);
        $courseDetails = self::getCourseData($courseName);

        $userMbtiType   = null;
        $mbtiIsMatch    = false;

        if (Auth::check()) {
            $user = Auth::user();
            $userMbtiType = $user->mbti_type;

            if ($userMbtiType) {
                // Check if the user's MBTI is in the course's alignment array
                $alignmentArray = $courseDetails['mbti_alignment'] ?? [];
                if (in_array($userMbtiType, $alignmentArray)) {
                    $mbtiIsMatch = true;
                }
            }
        }

        return view('pathfinder.course-details', compact(
            'courseDetails', 'userMbtiType', 'mbtiIsMatch'
        ));
    }

    public static function getCareerData($career)
    {
        $careers = [
            'Software Developer' => [
                'title' => 'Software Developer',
                'tagline' => 'Build the digital infrastructure of tomorrow.',
                'description' => 'Software Developers design, code, and test software applications. They apply engineering principles to software creation, solving complex problems and building systems that power modern businesses.',
                'short_description' => 'Design, build, and maintain the applications and systems that run on computers and mobile devices.',
                'responsibilities' => [
                    'Design, write, and maintain clean, scalable code using modern programming languages and best practices.',
                    'Collaborate with cross-functional teams to test, deploy, and monitor applications in production environments.',
                    'Continuously revise, update, refactor, and debug existing codebases to improve performance and security.',
                    'Architect and implement improvements to existing software interfaces and underlying systems to enhance user experience.',
                ],
                'skills_required' => ['Programming', 'Problem Solving', 'Algorithm Design', 'Version Control', 'Agile Methodologies'],
                'certifications_required' => ['AWS Certified Developer', 'Scrum Master (CSM)', 'Oracle Certified Professional'],
                'education_requirements' => 'Bachelor\'s Degree in Computer Science, IT, or equivalent experience.',
                'salary_range' => '₱400k - ₱1.2M Per year',
                'job_outlook' => 'Very High Growth',
                'related_careers' => ['Web Developer', 'Computer Engineer', 'Systems Administrator', 'Data Analyst'],
                'recommended_courses' => [
                    ['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#'],
                    ['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#'],
                    ['title' => 'Bachelor of Science in Computer Engineering', 'platform' => 'Degree Program', 'url' => '#']
                ]
            ],
            'Data Analyst' => [
                'title' => 'Data Analyst',
                'tagline' => 'Transform numbers into strategic business insights.',
                'description' => 'Data Analysts gather, clean, and analyze datasets to help organizations make better business decisions. They use statistical tools to interpret data and create visual reports.',
                'short_description' => 'Collect, clean, and interpret complex datasets to help organizations make strategic business decisions.',
                'responsibilities' => [
                    'Collect, clean, and preprocess large datasets from diverse sources to ensure data integrity and usability.',
                    'Identify complex trends, correlations, and actionable patterns hidden within raw business data.',
                    'Design and maintain interactive dashboards and comprehensive data visualizations for ongoing monitoring.',
                    'Translate technical findings into clear, strategic presentations for stakeholders and management teams.',
                ],
                'skills_required' => ['SQL', 'Python/R', 'Data Visualization', 'Statistics', 'Critical Thinking'],
                'certifications_required' => ['Google Data Analytics Certificate', 'Microsoft Certified: Power BI Data Analyst', 'CAP'],
                'education_requirements' => 'Bachelor\'s in Math, Statistics, Computer Science, or Economics.',
                'salary_range' => '₱400k - ₱1.1M Per year',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Data Scientist', 'Financial Analyst', 'Market Research Analyst'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Statistics', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Mathematics', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Cybersecurity Analyst' => [
                'title' => 'Cybersecurity Analyst',
                'tagline' => 'Defend digital boundaries against modern threats.',
                'description' => 'Cybersecurity Analysts protect an organization\'s computer networks and systems. They monitor for security breaches and investigate violations when they occur.',
                'responsibilities' => [
                    'Continuously monitor network traffic and system logs to detect anomalies and potential security incidents.',
                    'Conduct thorough investigations into security breaches and implement rapid incident response protocols.',
                    'Install, configure, and operate advanced security software, firewalls, and data encryption programs.',
                    'Perform regular vulnerability assessments and penetration testing to preemptively secure infrastructure.',
                ],
                'skills_required' => ['Network Security', 'Threat Analysis', 'Linux', 'Ethical Hacking', 'Attention to Detail'],
                'certifications_required' => ['CISSP', 'CompTIA Security+', 'Certified Ethical Hacker (CEH)'],
                'education_requirements' => 'Bachelor\'s in Cybersecurity, IT, or related certifications (e.g., Security+, CISSP).',
                'salary_range' => '₱450k - ₱1.4M Per year',
                'job_outlook' => 'Very High Demand',
                'related_careers' => ['Systems Administrator', 'Network Administrator'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Financial Analyst' => [
                'title' => 'Financial Analyst',
                'tagline' => 'Guide business decisions through financial modeling and market trends.',
                'description' => 'Financial Analysts examine financial data to guide business decisions. They analyze macroeconomic trends and assess the financial health of companies.',
                'short_description' => 'Guide investment decisions for businesses and individuals by evaluating financial data and economic trends.',
                'responsibilities' => [
                    'Analyze complex financial data, market trends, and macroeconomic indicators to guide corporate strategy.',
                    'Develop robust financial models to simulate business scenarios and support major investment decisions.',
                    'Prepare detailed financial reports, revenue forecasts, and budget variance analyses for executive review.',
                    'Recommend optimal investment strategies and portfolio allocations to maximize returns and mitigate risk.',
                ],
                'skills_required' => ['Financial Modeling', 'Excel', 'Accounting', 'Analytical Skills', 'Data Analysis'],
                'certifications_required' => ['Chartered Financial Analyst (CFA)', 'Certified Public Accountant (CPA)', 'Certified Financial Planner (CFP)'],
                'education_requirements' => 'Bachelor\'s in Finance, Accounting, Economics, or Business.',
                'salary_range' => '₱350k - ₱900k Per year',
                'job_outlook' => 'Stable Growth',
                'related_careers' => ['Data Analyst', 'Accountant', 'Investment Banker'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Accountancy', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Database Administrator' => [
                'title' => 'Database Administrator',
                'tagline' => 'Organize, secure, and manage the world\'s data.',
                'description' => 'Database Administrators (DBAs) use specialized software to store and organize data. They ensure that data is available to users and secure from unauthorized access.',
                'responsibilities' => [
                    'Design, build, and optimize complex database architectures to ensure fast, reliable data retrieval.',
                    'Implement rigorous data security protocols and manage automated backup systems to guarantee data integrity.',
                    'Proactively troubleshoot database errors, resolve performance bottlenecks, and monitor server health.',
                    'Manage user access levels, permissions, and authentication to prevent unauthorized data exposure.',
                ],
                'skills_required' => ['SQL', 'Oracle/MySQL', 'Database Tuning', 'System Administration'],
                'certifications_required' => ['Oracle Database Administrator Certified Professional', 'Microsoft Certified: Azure DB Administrator'],
                'education_requirements' => 'Bachelor\'s in Computer Science or IT.',
                'salary_range' => '₱400k - ₱1.0M Per year',
                'job_outlook' => 'Steady Demand',
                'related_careers' => ['Data Analyst', 'Systems Administrator', 'Software Developer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Systems Administrator' => [
                'title' => 'Systems Administrator',
                'tagline' => 'Ensure the reliable operation of critical IT infrastructure.',
                'description' => 'Systems Administrators are responsible for the upkeep, configuration, and reliable operation of computer systems, especially multi-user computers such as servers.',
                'short_description' => 'Maintain, configure, and ensure the reliable operation of computer systems and corporate servers.',
                'responsibilities' => [
                    'Install, configure, and manage both hardware and software systems across on-premise and cloud environments.',
                    'Maintain the health of network facilities in individual machines, ensuring maximum uptime and connectivity.',
                    'Regularly audit system logs, apply critical security patches, and perform routine maintenance tasks.',
                    'Provide tier-2 and tier-3 technical support to internal staff, resolving complex infrastructure issues.',
                ],
                'skills_required' => ['Linux/Windows ServerOps', 'Scripting', 'Networking', 'Troubleshooting'],
                'certifications_required' => ['CompTIA Server+', 'Red Hat Certified System Administrator (RHCSA)', 'CCNA'],
                'education_requirements' => 'Bachelor\'s in IT, Computer Engineering, or related field.',
                'salary_range' => '₱350k - ₱900k Per year',
                'job_outlook' => 'Consistent Demand',
                'related_careers' => ['Network Administrator', 'IT Support Specialist', 'Cybersecurity Analyst'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Engineering', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Network Administrator' => [
                'title' => 'Network Administrator',
                'tagline' => 'Keep organizations connected with robust network infrastructure.',
                'description' => 'Network Administrators organize, install, and support an organization\'s computer systems, including local area networks (LANs), wide area networks (WANs), and intranets.',
                'responsibilities' => [
                    'Design, deploy, and maintain robust local area (LAN) and wide area (WAN) network infrastructures.',
                    'Proactively monitor network performance, bandwidth usage, and latency to ensure optimal operations.',
                    'Configure and secure core networking equipment including enterprize routers, switches, and load balancers.',
                    'Implement and enforce strict network security protocols, firewalls, and VPN access policies.',
                ],
                'skills_required' => ['Routing & Switching', 'Firewall Administration', 'Cisco/Juniper', 'Troubleshooting'],
                'certifications_required' => ['Cisco Certified Network Associate (CCNA)', 'CompTIA Network+', 'JNCIA'],
                'education_requirements' => 'Bachelor\'s in IT or related certifications (e.g., CCNA).',
                'salary_range' => '₱320k - ₱950k Per year',
                'job_outlook' => 'Strong Demand',
                'related_careers' => ['Systems Administrator', 'Cybersecurity Analyst'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Engineering', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Web Developer' => [
                'title' => 'Web Developer',
                'tagline' => 'Bring visual concepts to life on the web.',
                'description' => 'Web Developers create and maintain websites. They are responsible for the site\'s technical aspects, such as its performance and capacity.',
                'responsibilities' => [
                    'Write well-designed, highly testable, and efficient code using HTML, CSS, JavaScript, and modern frameworks.',
                    'Create responsive website layouts and interactive user interfaces that function seamlessly across all devices.',
                    'Integrate frontend designs with robust back-end services, APIs, and complex database architectures.',
                    'Collaborate closely with UI/UX designers and stakeholders to gather and refine technical specifications.',
                ],
                'skills_required' => ['HTML/CSS', 'JavaScript', 'React/Vue', 'Responsive Design'],
                'certifications_required' => ['AWS Certified Developer', 'Zend Certified PHP Engineer', 'Google Developers Certification'],
                'education_requirements' => 'Bachelor\'s in Computer Science, or relevant coding bootcamp experience.',
                'salary_range' => '₱300k - ₱850k Per year',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Software Developer', 'UX/UI Designer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'IT Support Specialist' => [
                'title' => 'IT Support Specialist',
                'tagline' => 'The essential front line of technical problem-solving.',
                'description' => 'IT Support Specialists provide help and advice to computer users and organizations. They offer technical assistance directly to users.',
                'short_description' => 'Diagnose and rapidly resolve technical hardware and software issues to keep employees working efficiently.',
                'responsibilities' => [
                    'Diagnose, troubleshoot, and resolve a wide variety of complex hardware and software technical issues.',
                    'Effectively triage, prioritize, and resolve help desk tickets within established service level agreements.',
                    'Set up, configure, and deploy computers, mobile devices, and peripherals for new and existing employees.',
                    'Document technical knowledge, formulate FAQs, and maintain an internal knowledge base for end-users.',
                ],
                'skills_required' => ['Customer Service', 'Windows/macOS Support', 'Hardware Troubleshooting', 'Patience'],
                'certifications_required' => ['CompTIA A+', 'Google IT Support Professional Certificate', 'Microsoft 365 Certified'],
                'education_requirements' => 'Associate\'s or Bachelor\'s Degree in IT, or relevant certifications (e.g., CompTIA A+).',
                'salary_range' => '₱250k - ₱500k Per year',
                'job_outlook' => 'High Turnover / High Demand',
                'related_careers' => ['Systems Administrator', 'Network Administrator'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Information Technology', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Staff Nurse' => [
                'title' => 'Staff Nurse',
                'tagline' => 'Provide compassionate, frontline medical care.',
                'description' => 'Staff Nurses provide care to patients, coordinate with doctors, administer medications, and ensure patient comfort and recovery.',
                'short_description' => 'Provide direct medical care, monitor patient recovery, and assist doctors in critical medical procedures.',
                'responsibilities' => [
                    'Comprehensively assess patient conditions, take vital signs, and compile accurate medical histories.',
                    'Safely administer medications, perform prescribed treatments, and assist physicians during medical procedures.',
                    'Continuously monitor patient progress, document recovery milestones, and adjust care plans as necessary.',
                    'Educate patients and their families regarding medical conditions, post-discharge care, and wellness strategies.',
                ],
                'skills_required' => ['Clinical Knowledge', 'Patient Care', 'Empathy', 'Communication', 'Attention to Detail'],
                'certifications_required' => ['Philippine Nursing Licensure Examination (PNLE)', 'Basic Life Support (BLS)', 'Advance Cardiac Life Support (ACLS)'],
                'education_requirements' => 'Bachelor of Science in Nursing (BSN) and passing the licensure exam.',
                'salary_range' => '₱300k - ₱540k Per year',
                'job_outlook' => 'Very High Demand (Local & Abroad)',
                'related_careers' => ['Physical Therapist', 'Occupational Therapist'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Nursing', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Human Resources Specialist' => [
                'title' => 'Human Resources Specialist',
                'tagline' => 'Build and support a thriving workforce.',
                'description' => 'HR Specialists recruit, screen, interview, and place workers. They often handle employee relations, compensation, and training.',
                'short_description' => 'Recruit top talent, administer employee benefits, and foster a positive, supportive company culture.',
                'responsibilities' => [
                    'Manage the full-cycle recruitment process, from sourcing and interviewing candidates to onboarding new hires.',
                    'Administer employee compensation packages, health benefits, and coordinate regular performance evaluations.',
                    'Mediate complex workplace conflicts, address employee grievances, and foster a positive organizational culture.',
                    'Ensure company policies remain strictly compliant with local and national labor laws and employment regulations.',
                ],
                'skills_required' => ['Interpersonal Skills', 'Conflict Resolution', 'Employment Law', 'Communication'],
                'certifications_required' => ['Certified Human Resources Professional (CHRP)', 'SHRM Certified Professional'],
                'education_requirements' => 'Bachelor\'s in Human Resources, Business, or Psychology.',
                'salary_range' => '₱300k - ₱650k Per year',
                'job_outlook' => 'Stable',
                'related_careers' => ['Administrative Officer', 'Operations Manager'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Psychology', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Educational Coordinator' => [
                'title' => 'Educational Coordinator',
                'tagline' => 'Design engaging and effective learning experiences.',
                'description' => 'Educational Coordinators develop instructional material, coordinate educational content, and incorporate current technology into specialized fields.',
                'short_description' => 'Design engaging school curricula and provide critical training for instructional staff and teachers.',
                'responsibilities' => [
                    'Develop comprehensive educational programs, training materials, and curricula tailored to specific learning goals.',
                    'Rigorously evaluate program effectiveness through assessments, surveys, and qualitative feedback metrics.',
                    'Organize and conduct workshops to train educators, administrators, and staff on new teaching methodologies.',
                    'Oversee the successful implementation of new curricula across diverse classrooms and educational settings.',
                ],
                'skills_required' => ['Curriculum Design', 'Leadership', 'Communication', 'Instructional Strategy'],
                'certifications_required' => ['Licensure Examination for Teachers (LET)', 'Educational Leadership & Administration Certificate'],
                'education_requirements' => 'Master\'s Degree in Education or Educational Leadership.',
                'salary_range' => '₱300k - ₱650k Per year',
                'job_outlook' => 'Moderate',
                'related_careers' => ['Curriculum Developer', 'Elementary School Teacher'],
                'recommended_courses' => [['title' => 'Bachelor of Elementary Education', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Secondary Education', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Content Writer' => [
                'title' => 'Content Writer',
                'tagline' => 'Craft compelling narratives that engage and inform.',
                'description' => 'Content Writers produce engaging and relevant written content for websites, blogs, articles, and marketing materials.',
                'short_description' => 'Craft compelling, well-researched written content for blogs, marketing materials, and digital platforms.',
                'responsibilities' => [
                    'Conduct in-depth research on industry-related topics to produce accurate, engaging, and authoritative content.',
                    'Draft, edit, and proofread high-quality copy for blogs, websites, social media, and marketing collateral.',
                    'Optimize all digital content using SEO best practices to maximize web traffic and search engine rankings.',
                    'Collaborate closely with marketing and design teams to ensure content aligns with overarching brand strategies.',
                ],
                'skills_required' => ['Writing/Editing', 'SEO Basics', 'Creativity', 'Research Skills', 'Time Management'],
                'certifications_required' => ['HubSpot Content Marketing Certification', 'Google Digital Garage Certificate', 'SEO Certification'],
                'education_requirements' => 'Bachelor\'s in English, Journalism, Communications, or Marketing.',
                'salary_range' => '₱280k - ₱700k Per year',
                'job_outlook' => 'Fast Growing (Freelance/BPO)',
                'related_careers' => ['Communications Specialist', 'Public Relations Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Journalism', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Social Worker' => [
                'title' => 'Social Worker',
                'tagline' => 'Advocate for vulnerable individuals and communities.',
                'description' => 'Social Workers help people solve and cope with problems in their everyday lives. Clinical social workers also diagnose and treat mental, behavioral, and emotional issues.',
                'short_description' => 'Advocate for vulnerable individuals, connect them with vital resources, and provide crisis intervention strategies.',
                'responsibilities' => [
                    'Actively identify and connect with individuals and communities who are vulnerable or in urgent need of assistance.',
                    'Conduct thorough assessments of clients\' emotional needs, living situations, and available support networks.',
                    'Develop and implement holistic intervention plans tailored to improve clients\' overall well-being and safety.',
                    'Provide direct crisis intervention and respond rapidly to emergency situations involving abuse or mental health.',
                ],
                'skills_required' => ['Empathy', 'Active Listening', 'Crisis Intervention', 'Advocacy'],
                'certifications_required' => ['Licensure Exam for Social Workers', 'Certified Clinical Social Worker'],
                'education_requirements' => 'Bachelor\'s or Master\'s Degree in Social Work (BSW/MSW) and licensure.',
                'salary_range' => '₱240k - ₱480k Per year',
                'job_outlook' => 'Steady Demand',
                'related_careers' => ['Occupational Therapist', 'Human Resources Specialist'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Social Work', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Psychology', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Compliance Officer' => [
                'title' => 'Compliance Officer',
                'tagline' => 'Ensure ethical practices and adherence to regulations.',
                'description' => 'Compliance Officers examine, evaluate, and investigate eligibility for or conformity with laws and regulations governing contract compliance of licenses.',
                'short_description' => 'Audit internal operations to ensure strict company adherence to complex legal and regulatory standards.',
                'responsibilities' => [
                    'Meticulously review corporate operations to ensure they meet all legal standards and industry regulations.',
                    'Plan and execute regular compliance audits, risk assessments, and internal investigations across all departments.',
                    'Develop, update, and actively enforce internal policies that safeguard organizational ethics and data privacy.',
                    'Conduct training sessions to educate employees on regulatory changes and the importance of compliance adherence.',
                ],
                'skills_required' => ['Regulatory Knowledge', 'Attention to Detail', 'Analytical Thinking', 'Integrity'],
                'certifications_required' => ['Certified Compliance & Ethics Professional (CCEP)', 'Certified Regulatory Compliance Manager (CRCM)'],
                'education_requirements' => 'Bachelor\'s Degree in Business, Finance, or Law.',
                'salary_range' => '₱400k - ₱1.0M Per year',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Financial Analyst', 'Administrative Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Laws (LLB)', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Administrative Officer' => [
                'title' => 'Administrative Officer',
                'tagline' => 'The backbone of smooth organizational operations.',
                'description' => 'Administrative Officers provide administrative and clerical support to departments or management, ensuring smooth day-to-day operations.',
                'short_description' => 'Manage daily office operations, execute scheduling, and maintain complex organizational records.',
                'responsibilities' => [
                    'Expertly handle office scheduling, executive calendars, and direct communications to ensure fluid operations.',
                    'Organize and securely maintain complex electronic datasets, physical files, and confidential corporate records.',
                    'Draft comprehensive reports, prepare compelling presentations, and record detailed minutes during key meetings.',
                    'Coordinate multifaceted office activities, manage essential vendor relationships, and oversee facility maintenance.',
                ],
                'skills_required' => ['Organization', 'Communication', 'Office Software Proficiency', 'Time Management'],
                'certifications_required' => ['Certified Administrative Professional (CAP)', 'Civil Service Eligibility (Professional)'],
                'education_requirements' => 'Bachelor\'s Degree in Business Administration or related field.',
                'salary_range' => '₱240k - ₱500k Per year',
                'job_outlook' => 'Stable',
                'related_careers' => ['Human Resources Specialist', 'Operations Manager'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Operations Manager' => [
                'title' => 'Operations Manager',
                'tagline' => 'Optimize processes to maximize efficiency and output.',
                'description' => 'Operations Managers formulate strategy, improve performance, procure material and resources, and secure compliance. They mentor teams and find ways to increase quality of customer service and implement best practices.',
                'short_description' => 'Optimize daily business procedures to maximize efficiency, cut costs, and increase company output.',
                'responsibilities' => [
                    'Oversee and optimize daily operational activities to ensure maximum efficiency and high organizational output.',
                    'Formulate, iterate, and strictly enforce operational policies, quality control standards, and best practices.',
                    'Manage end-to-end supply chains, negotiate with key vendors, and ensure seamless inventory logistics.',
                    'Continuously monitor financial data, analyze operational metrics, and optimize departmental budgets.',
                ],
                'skills_required' => ['Leadership', 'Strategic Planning', 'Process Optimization', 'Financial Acumen'],
                'certifications_required' => ['Project Management Professional (PMP)', 'Six Sigma Green Belt', 'Certified Supply Chain Professional'],
                'education_requirements' => 'Bachelor\'s or Master\'s Degree in Business Administration or Operations Management.',
                'salary_range' => '₱600k - ₱1.8M Per year',
                'job_outlook' => 'Very Strong Demand',
                'related_careers' => ['Business Development Manager', 'Administrative Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Business Development Manager' => [
                'title' => 'Business Development Manager',
                'tagline' => 'Drive growth, forge partnerships, and expand markets.',
                'description' => 'Business Development Managers identify new corporate sales and partnership opportunities, pitching products/services and maintaining mutually beneficial relationships.',
                'short_description' => 'Identify lucrative new market opportunities and aggressively negotiate high-value corporate partnerships.',
                'responsibilities' => [
                    'Proactively research and identify potential new geographic markets, enterprise clients, and strategic partnerships.',
                    'Expertly negotiate terms, close high-value business deals, and facilitate complex contract agreements.',
                    'Collaborate extensively with marketing and product teams to perfectly align offerings with shifting market demands.',
                    'Develop and execute long-term strategic business plans focused on sustainable growth and revenue generation.',
                ],
                'skills_required' => ['Negotiation', 'Sales Strategy', 'Networking', 'Communication', 'Market Research'],
                'certifications_required' => ['Certified Business Development Professional', 'Salesforce Certified Administrator'],
                'education_requirements' => 'Bachelor\'s Degree in Business Administration, Marketing, or Finance.',
                'salary_range' => '₱500k - ₱1.5M Per year',
                'job_outlook' => 'High Growth',
                'related_careers' => ['Operations Manager', 'Sales Representative'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Marketing Coordinator' => [
                'title' => 'Marketing Coordinator',
                'tagline' => 'Execute creative campaigns that capture audience attention.',
                'description' => 'Marketing Coordinators organize marketing campaigns, create promotional materials, and analyze target audience engagement to boost a brand\'s presence.',
                'responsibilities' => [
                    'Brainstorm, develop, and meticulously execute multi-channel marketing campaigns tailored to target audiences.',
                    'Continuously analyze campaign performance metrics and adjust active strategies to maximize return on investment.',
                    'Coordinate seamlessly with diverse sales and creative teams to produce highly effective promotional materials.',
                    'Manage corporate social media channels, foster community engagement, and monitor brand reputation online.',
                ],
                'skills_required' => ['Digital Marketing', 'Copywriting', 'Analytics', 'Creativity'],
                'certifications_required' => ['Google Analytics Individual Qualification', 'Facebook Blueprint Certification', 'HubSpot Certification'],
                'education_requirements' => 'Bachelor\'s Degree in Marketing, Communications, or Business.',
                'salary_range' => '₱300k - ₱750k Per year',
                'job_outlook' => 'Rapidly Growing',
                'related_careers' => ['Public Relations Officer', 'Market Research Analyst'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Sales Representative' => [
                'title' => 'Sales Representative',
                'tagline' => 'Connect customers with solutions that meet their needs.',
                'description' => 'Sales Representatives sell retail products, goods, and services to customers. They work with customers to find what they want, create solutions, and ensure a smooth sales process.',
                'short_description' => 'Pitch products directly to clients, negotiate contracts, and consistently close high-value business deals.',
                'responsibilities' => [
                    'Proactively prospect, identify, and consistently initiate contact with qualified leads and potential new clients.',
                    'Deliver highly persuasive product demonstrations and tailor value propositions to meet specific customer pain points.',
                    'Skillfully negotiate contract terms, overcome buyer objections, and successfully close lucrative sales deals.',
                    'Maintain deep, long-lasting client relationships to drive recurring revenue and generate referral business.',
                ],
                'skills_required' => ['Persuasion', 'Active Listening', 'Resilience', 'Communication'],
                'certifications_required' => ['Certified Sales Professional (CSP)', 'HubSpot Sales Software Certification'],
                'education_requirements' => 'High School Diploma to Bachelor\'s Degree, depending on the industry.',
                'salary_range' => '₱240k - ₱600k Per year + commission',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Customer Service Representative', 'Business Development Manager'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Customer Service Representative' => [
                'title' => 'Customer Service Representative',
                'tagline' => 'Provide support, solve problems, and delight customers.',
                'description' => 'Customer Service Representatives interact with customers to handle complaints, process orders, and provide information about an organization’s products and services.',
                'short_description' => 'Interact directly with customers to resolve complaints, answer inquiries, and ensure total satisfaction.',
                'responsibilities' => [
                    'Swiftly and professionally resolve complex customer inquiries via telephone, email, and live chat platforms.',
                    'Maintain highly accurate customer interaction records, update internal databases, and document complex issues.',
                    'Efficiently process incoming orders, facilitate returns, and manage financial refunds with a focus on accuracy.',
                    'Expertly de-escalate stressful or confrontational situations by utilizing empathy and proven conflict resolution tactics.',
                ],
                'skills_required' => ['Empathy', 'Patience', 'Verbal Communication', 'Problem Solving'],
                'certifications_required' => ['Certified Customer Service Professional (CCSP)', 'Call Center Fundamentals Certificate'],
                'education_requirements' => 'High School Diploma, or Bachelor\'s Degree.',
                'salary_range' => '₱280k - ₱500k Per year',
                'job_outlook' => 'Massive Demand (BPO)',
                'related_careers' => ['Sales Representative', 'Administrative Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Event Coordinator' => [
                'title' => 'Event Coordinator',
                'tagline' => 'Create memorable gatherings through meticulous planning.',
                'description' => 'Event Coordinators organize and manage events, such as conferences, trade shows, weddings, parties, and corporate meetings.',
                'short_description' => 'Meticulously plan and execute large-scale gatherings, managing vendors, budgets, and on-site logistics.',
                'responsibilities' => [
                    'Meticulously plan comprehensive event details, encompassing thematic design, guest lists, and precise timeline logistics.',
                    'Liaise effectively with external vendors, caterers, and venues to negotiate contracts and ensure seamless delivery.',
                    'Strictly manage overarching event budgets, track all expenses, and ensure maximum value without compromising quality.',
                    'Oversee critical on-site event execution, swiftly resolving unexpected issues to guarantee a flawless attendee experience.',
                ],
                'skills_required' => ['Organization', 'Negotiation', 'Time Management', 'Crisis Management'],
                'certifications_required' => ['Certified Special Events Professional (CSEP)', 'Certified Meeting Professional (CMP)'],
                'education_requirements' => 'Bachelor\'s Degree in Hospitality Management, Public Relations, or Business.',
                'salary_range' => '₱220k - ₱600k / year',
                'job_outlook' => 'Strong Recovery',
                'related_careers' => ['Marketing Coordinator', 'Public Relations Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Tourism Management', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Hotel Front Desk Agent' => [
                'title' => 'Hotel Front Desk Agent',
                'tagline' => 'Be the welcoming face and first point of contact for guests.',
                'description' => 'Hotel Front Desk Agents accommodate hotel patrons by registering and assigning rooms to guests, issuing room keys or cards, transmitting and receiving messages, and keeping records of occupied rooms and guests\' accounts.',
                'responsibilities' => [
                    'Warmly greet guests, orchestrate smooth check-in/check-out processes, and assign rooms according to preferences.',
                    'Accurately process guest payments, manage financial folios, and ensure the integrity of cash drawer transactions.',
                    'Promptly handle intricate guest requests, resolve complaints with grace, and provide insightful local recommendations.',
                    'Maintain real-time records of room occupancy, communicate with housekeeping, and manage daily reservation platforms.',
                ],
                'skills_required' => ['Customer Service', 'Multitasking', 'Professionalism', 'Communication'],
                'certifications_required' => ['Certified Front Desk Representative', 'Hospitality Management Diploma'],
                'education_requirements' => 'High School Diploma or degree in Hospitality Management.',
                'salary_range' => '₱200k - ₱400k Per year',
                'job_outlook' => 'Recovering/Growing',
                'related_careers' => ['Customer Service Representative', 'Administrative Officer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Tourism Management', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Hospitality Management', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Public Relations Officer' => [
                'title' => 'Public Relations Officer',
                'tagline' => 'Shape public perception and manage organizational reputation.',
                'description' => 'Public Relations Officers create and maintain a favorable public image for the organization they represent. They design media releases to shape public perception.',
                'short_description' => 'Shape a positive public image for organizations through strategic media releases and relationship management.',
                'responsibilities' => [
                    'Draft exceptional press releases, compelling media pitches, and dynamic public statements that capture attention.',
                    'Cultivate and manage robust relationships with essential journalists, influential media outlets, and key stakeholders.',
                    'Strategically develop and execute comprehensive PR campaigns designed to proactively shape a favorable public image.',
                    'Assume a leadership role during crisis communication scenarios, rapidly mitigating reputational damage.',
                ],
                'skills_required' => ['Communication', 'Media Relations', 'Crisis Management', 'Writing'],
                'certifications_required' => ['Accreditation in Public Relations (APR)', 'Crisis Communication Certificate'],
                'education_requirements' => 'Bachelor\'s Degree in Public Relations, Communications, or Journalism.',
                'salary_range' => '₱300k - ₱800k Per year',
                'job_outlook' => 'Stable Growth',
                'related_careers' => ['Communications Specialist', 'Marketing Coordinator'],
                'recommended_courses' => [['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Journalism', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Communications Specialist' => [
                'title' => 'Communications Specialist',
                'tagline' => 'Facilitate clear and effective internal and external messaging.',
                'description' => 'Communications Specialists manage external and internal communications for an organization. They write news releases, prepare presentations, and craft newsletters.',
                'short_description' => 'Develop clear, unified messaging for both internal staff communications and external public engagement.',
                'responsibilities' => [
                    'Strategically develop and refine both internal enterprise and external public-facing communication frameworks.',
                    'Expertly write and curate engaging organizational newsletters, internal memos, and executive leadership announcements.',
                    'Manage diverse multidimensional corporate communications channels, evaluating engagement and message penetration.',
                    'Act as a diligent brand guardian, ensuring strict consistency in tone, messaging, and visual identity across all platforms.',
                ],
                'skills_required' => ['Writing/Editing', 'Presentation Skills', 'Strategic Planning', 'Interpersonal Skills'],
                'certifications_required' => ['Accredited Business Communicator (ABC)', 'Digital Marketing Certificate'],
                'education_requirements' => 'Bachelor\'s Degree in Communications, Journalism, or English.',
                'salary_range' => '₱300k - ₱800k Per year',
                'job_outlook' => 'Steady Demand',
                'related_careers' => ['Public Relations Officer', 'Content Writer'],
                'recommended_courses' => [['title' => 'Bachelor of Arts in Communication', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Arts in Journalism', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Market Research Analyst' => [
                'title' => 'Market Research Analyst',
                'tagline' => 'Analyze market conditions to pinpoint opportunities.',
                'description' => 'Market Research Analysts study market conditions to examine potential sales of a product or service. They help companies understand what products people want, who will buy them, and at what price.',
                'short_description' => 'Analyze shifting market trends and complex consumer data to guide strategic corporate decision-making.',
                'responsibilities' => [
                    'Scientifically design and conduct comprehensive surveys, insightful focus groups, and structured market questionnaires.',
                    'Deeply analyze qualitative and quantitative data using advanced statistical software to uncover critical market insights.',
                    'Accurately forecast emerging market trends, evaluate shifting consumer behaviors, and assess competitor strategies.',
                    'Distill complex analytical findings into highly actionable, visually compelling reports for senior management.',
                ],
                'skills_required' => ['Data Analysis', 'Critical Thinking', 'Reporting', 'Statistical Software'],
                'certifications_required' => ['Professional Researcher Certification (PRC)', 'Google Analytics Certification'],
                'education_requirements' => 'Bachelor\'s in Market Research, Statistics, Math, or Computer Science.',
                'salary_range' => '₱300k - ₱700k Per year',
                'job_outlook' => 'Growing',
                'related_careers' => ['Data Analyst', 'Marketing Coordinator'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Statistics', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Business Administration', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Curriculum Developer' => [
                'title' => 'Curriculum Developer',
                'tagline' => 'Design the blueprints for engaging educational programs.',
                'description' => 'Curriculum Developers create instructional materials and develop teaching models for schools and educational organizations.',
                'short_description' => 'Create structured, engaging educational materials and instructional models for diverse learning environments.',
                'responsibilities' => [
                    'Systematically design comprehensive syllabi, innovative lesson plans, and diverse evaluation metrics for students.',
                    'Critically evaluate and continuously update educational materials to ensure they remain accurate and pedagogically sound.',
                    'Conduct rigorous training seminars to instruct educators on implementing new curricula and best teaching practices.',
                    'Pioneer the integration of emerging educational technologies and digital learning tools into standard classroom environments.',
                ],
                'skills_required' => ['Instructional Design', 'Research Skills', 'Communication', 'Attention to Detail'],
                'certifications_required' => ['Licensure Examination for Teachers (LET)', 'Instructional Design Certificate'],
                'education_requirements' => 'Master\'s Degree in Education or Curriculum and Instruction.',
                'salary_range' => '₱300k - ₱700k Per year',
                'job_outlook' => 'Moderate',
                'related_careers' => ['Educational Coordinator', 'Elementary School Teacher'],
                'recommended_courses' => [['title' => 'Bachelor of Elementary Education', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Secondary Education', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Computer Engineer' => [
                'title' => 'Computer Engineer',
                'tagline' => 'Bridge the gap between hardware architecture and software design.',
                'description' => 'Computer Engineers research, design, develop, and test computer systems and components such as processors, circuit boards, memory devices, and networks.',
                'short_description' => 'Design, build, and rigorously test cutting-edge computer hardware, processors, and circuit boards.',
                'responsibilities' => [
                    'Meticulously design and architecture complex computer hardware components, microprocessors, and custom circuit boards.',
                    'Rigorously test and analyze hardware performance under extreme conditions to identify and resolve critical bottlenecks.',
                    'Spearhead the continuous improvement and technological update of existing enterprise-grade computer equipment.',
                    'Write highly optimized low-level software, critical firmware, and driver interactions connecting software to physical systems.',
                ],
                'skills_required' => ['Electronics', 'C/C++', 'Hardware Design', 'Problem Solving'],
                'certifications_required' => ['Licensure Exam for Electronics/Computer Engineers', 'Cisco Certified Network Professional (CCNP)'],
                'education_requirements' => 'Bachelor\'s Degree in Computer Engineering or Electrical Engineering.',
                'salary_range' => '₱350k - ₱1.0M Per year',
                'job_outlook' => 'Strong Growth',
                'related_careers' => ['Software Developer', 'Electronics Engineer'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Computer Engineering', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Computer Science', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Electronics Engineer' => [
                'title' => 'Electronics Engineer',
                'tagline' => 'Design the electronic components powering modern devices.',
                'description' => 'Electronics Engineers design, develop, test, and supervise the manufacturing of electronic equipment such as broadcast and communications systems.',
                'short_description' => 'Develop sophisticated electronic components and circuitry utilized in commercial and industrial devices.',
                'responsibilities' => [
                    'Pioneer the design of sophisticated electronic systems and micro-components tailored for specialized industrial applications.',
                    'Rigorously evaluate proposed electronic designs for strict adherence to safety standards and maximum energy efficiency.',
                    'Develop comprehensive, step-by-step maintenance procedures and operational protocols for complex electronic equipment.',
                    'Diagnose and troubleshoot intricate systemic failures in broadcast, communication, and high-level electronic infrastructures.',
                ],
                'skills_required' => ['Circuit Design', 'AutoCAD/CAD Software', 'Analytical Skills', 'Math'],
                'certifications_required' => ['Electronics Engineer Licensure Examination', 'Certified Electronics Technician (CET)'],
                'education_requirements' => 'Bachelor\'s Degree in Electronics Engineering.',
                'salary_range' => '₱300k - ₱850k Per year',
                'job_outlook' => 'Stable',
                'related_careers' => ['Computer Engineer', 'Systems Administrator'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Electronics Engineering', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Electrical Engineering', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Physical Therapist' => [
                'title' => 'Physical Therapist',
                'tagline' => 'Help patients regain movement, manage pain, and improve their lives.',
                'description' => 'Physical Therapists help injured or ill people improve their movement and manage their pain. They are an important part of the rehabilitation, treatment, and prevention of patients with chronic conditions.',
                'short_description' => 'Help injured or ill people improve physical movement, manage pain, and recover through guided exercise.',
                'responsibilities' => [
                    'Expertly diagnose patients\' functional impairments, range of motion limitations, and underlying neurological mobility issues.',
                    'Develop highly customized, measurable therapeutic care plans tailored exactly to the patient\'s specific injury or chronic condition.',
                    'Actively teach and physically guide patients through specific rehabilitative exercises, strength training, and stretching routines.',
                    'Regularly evaluate patient recovery progress, modify treatment strategies dynamically, and meticulously document clinical outcomes.',
                ],
                'skills_required' => ['Compassion', 'Physical Stamina', 'Anatomy/Physiology', 'Communication'],
                'certifications_required' => ['Physical and Occupational Therapy Licensure Examination', 'Basic Life Support (BLS)'],
                'education_requirements' => 'Doctor of Physical Therapy (DPT) degree and passing the licensure exam.',
                'salary_range' => '₱300k - ₱700k Per year',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Occupational Therapist', 'Staff Nurse'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Physical Therapy', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Occupational Therapist' => [
                'title' => 'Occupational Therapist',
                'tagline' => 'Empower individuals to overcome physical and emotional barriers.',
                'description' => 'Occupational Therapists treat injured, ill, or disabled patients through the therapeutic use of everyday activities. They help these patients develop, recover, improve, as well as maintain the skills needed for daily living and working.',
                'short_description' => 'Help patients regain the crucial physical skills required for daily living and working independence.',
                'responsibilities' => [
                    'Holistically assess patients\' combined physical, mental, and cognitive conditions to determine their overall functional baseline.',
                    'Develop innovative treatment plans focused entirely on restoring the patient\'s ability to perform vital daily living and work tasks.',
                    'Carefully recommend and train patients in the use of specialized adaptive equipment and personalized ergonomic modifications.',
                    'Extensively educate and train patients\' family members and caregivers to ensure safe and continuous support in the home environment.',
                ],
                'skills_required' => ['Patience', 'Empathy', 'Problem Solving', 'Adaptability'],
                'certifications_required' => ['Physical and Occupational Therapy Licensure Examination', 'NBCOT Certification'],
                'education_requirements' => 'Master\'s or Doctoral degree in Occupational Therapy and licensure.',
                'salary_range' => '₱300k - ₱700k Per year',
                'job_outlook' => 'High Demand',
                'related_careers' => ['Physical Therapist', 'Social Worker'],
                'recommended_courses' => [['title' => 'Bachelor of Science in Occupational Therapy', 'platform' => 'Degree Program', 'url' => '#'], ['title' => 'Bachelor of Science in Psychology', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            'Elementary School Teacher' => [
                'title' => 'Elementary School Teacher',
                'tagline' => 'Shape the minds and futures of young learners.',
                'description' => 'Elementary School Teachers instruct young students in basic subjects, such as math and reading, in order to prepare them for future schooling.',
                'short_description' => 'Instruct young children in foundational subjects, fostering both their emotional and academic development.',
                'responsibilities' => [
                    'Creatively conceptualize and adhere to engaging daily lesson plans that solidly cover foundational subjects like math and reading.',
                    'Continuously evaluate and document individual student academic progress, identifying areas requiring targeted intervention.',
                    'Skillfully manage classroom behavior utilizing positive reinforcement frameworks to maintain a safe, highly productive learning environment.',
                    'Communicate consistently and effectively with parents or guardians to align on student development, behavioral goals, and academic milestones.',
                ],
                'skills_required' => ['Patience', 'Communication', 'Creativity', 'Classroom Management'],
                'certifications_required' => ['Licensure Examination for Teachers (LET)', 'TEFL/TESOL Certification'],
                'education_requirements' => 'Bachelor\'s Degree in Elementary Education and teaching license.',
                'salary_range' => '₱360k - ₱600k Per year',
                'job_outlook' => 'Consistent Demand',
                'related_careers' => ['Educational Coordinator', 'Curriculum Developer'],
                'recommended_courses' => [['title' => 'Bachelor of Elementary Education', 'platform' => 'Degree Program', 'url' => '#']]
            ],
            // Default fallback for other unlisted roles (Now used ONLY if AI fails)
            'default' => [
                'title' => $career,
                'tagline' => 'Discover your path and excel in your career.',
                'description' => "Detailed information for the {$career} role is currently unavailable. Please verify your AI integration or check back later.",
                'responsibilities' => [
                    'Contact support for detailed role responsibilities.',
                    'Verify system configuration for dynamic content.',
                ],
                'skills_required' => ['N/A'],
                'education_requirements' => 'N/A',
                'salary_range' => 'N/A',
                'job_outlook' => 'N/A',
                'related_careers' => [],
                'recommended_courses' => []
            ]
        ];

        // Check if we have dynamic AI-generated data cached
        $cacheKey = 'career_data_' . str_replace(' ', '_', strtolower($career));
        
        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        // 1. Static Match
        if (isset($careers[$career])) {
            return $careers[$career];
        }

        // 2. AI Generation Fallback
        $groqKey = config('services.groq.key');
        \Log::debug('PathfinderController AI Check', [
            'has_groq_key' => !empty($groqKey),
            'career' => $career
        ]);

        $groqAiService = app(\App\Services\GroqAiService::class);
        $dynamicData = $groqAiService->generateCareerData($career);

        if ($dynamicData) {
            // Only cache successful AI results
            \Illuminate\Support\Facades\Cache::put($cacheKey, $dynamicData, now()->addDays(30));
            return $dynamicData;
        }

        // 3. Final Default Fallback (Do NOT cache this)
        return $careers['default'];
    }

    /**
     * Get real-world data for courses in the Philippines.
     */
    public static function getCourseData($course)
    {
        $defaultCourse = [
            'title' => $course,
            'tagline' => 'Unlock your potential in this specialized field.',
            'description' => 'This program offers exploring specialized concepts and practices that prepare you for a professional career. You will gain industry-relevant skills and join a network of experts.',
            'short_description' => 'A specialized program designed to build core competencies in your chosen field.',
            'curriculum_highlights' => ['Core Principles', 'Industry Best Practices', 'Practical Applications', 'Capstone Research'],
            'skills_gained' => ['Critical Thinking', 'Technical Proficiency', 'Communication', 'Collaborative Problem Solving'],
            'career_opportunities' => ['Industry Specialist', 'Professional Consultant', 'Researcher', 'Technical Lead'],
            'duration' => '4 Years',
            'difficulty' => 'Moderate',
            'tuition' => '₱40,000 - ₱100,000 / Sem',
            'mbti_alignment' => ['INTJ', 'ENFJ', 'ISTJ', 'ENTP'],
            'top_universities' => []
        ];

        // Map long-form degree names → short-name keys used in courses_data.json
        $nameMap = [
            'Bachelor of Science in Accountancy'                           => 'BS Accountancy',
            'Bachelor of Science in Business Administration'               => 'BS Business Administration',
            'Bachelor of Science in Marketing Management'                  => 'BS Marketing Management',
            'Bachelor of Science in Financial Management'                  => 'BS Financial Management',
            'Bachelor of Science in Entrepreneurship'                      => 'BS Entrepreneurship',
            'Bachelor of Science in Human Resource Management'             => 'BS Human Resource Management',
            'Bachelor of Science in Operations Management'                 => 'BS Operations Management',
            'Bachelor of Science in Management Accounting'                 => 'BS Management Accounting',
            'Bachelor of Science in International Relations'               => 'BS International Relations',
            'Bachelor of Science in Civil Engineering'                     => 'BS Civil Engineering',
            'Bachelor of Science in Electrical Engineering'                => 'BS Electrical Engineering',
            'Bachelor of Science in Mechanical Engineering'                => 'BS Mechanical Engineering',
            'Bachelor of Science in Chemical Engineering'                  => 'BS Chemical Engineering',
            'Bachelor of Science in Industrial Engineering'                => 'BS Industrial Engineering',
            'Bachelor of Science in Computer Engineering'                  => 'BS Computer Engineering',
            'Bachelor of Science in Electronics Engineering'               => 'BS Electronics Engineering',
            'Bachelor of Science in Geodetic Engineering'                  => 'BS Geodetic Engineering',
            'Bachelor of Science in Computer Science'                      => 'BS Computer Science',
            'Bachelor of Science in Information Technology'                => 'BS Information Technology',
            'Bachelor of Science in Information Systems'                   => 'BS Information Systems',
            'Bachelor of Science in Data Science'                          => 'BS Data Science',
            'Bachelor of Science in Cybersecurity'                         => 'BS Cybersecurity',
            'Bachelor of Science in Network Administration'                => 'BS Network Administration',
            'Bachelor of Science in Entertainment and Multimedia Computing'=> 'BS Entertainment and Multimedia Computing',
            'Bachelor of Science in Nursing'                               => 'BS Nursing',
            'Bachelor of Science in Medical Technology'                    => 'BS Medical Technology',
            'Bachelor of Science in Pharmacy'                              => 'BS Pharmacy',
            'Bachelor of Science in Physical Therapy'                      => 'BS Physical Therapy',
            'Bachelor of Science in Occupational Therapy'                  => 'BS Occupational Therapy',
            'Bachelor of Science in Radiologic Technology'                 => 'BS Radiologic Technology',
            'Bachelor of Science in Respiratory Therapy'                   => 'BS Respiratory Therapy',
            'Bachelor of Science in Public Health'                         => 'BS Public Health',
            'Bachelor of Science in Psychology'                            => 'BS Psychology',
            'Bachelor of Science in Criminology'                           => 'BS Criminology',
            'Bachelor of Science in Forensic Science'                      => 'BS Forensic Science',
            'Bachelor of Science in Tourism Management'                    => 'BS Tourism Management',
            'Bachelor of Science in Hotel and Restaurant Management'       => 'BS Hotel and Restaurant Management',
            'Bachelor of Science in Hospitality Management'                => 'BS Hotel and Restaurant Management',
            'Bachelor of Science in International Hospitality Management'  => 'BS International Hospitality Management',
            'Bachelor of Science in Event Management'                      => 'BS Event Management',
            'Bachelor of Science in Cruise Ship Management'                => 'BS Cruise Ship Management',
            'Bachelor of Science in Travel Management'                     => 'BS Travel Management',
            'Bachelor of Science in Food Service Management'               => 'BS Food Service Management',
            'Bachelor of Science in Culinary Arts Management'              => 'BS Culinary Arts Management',
            'Bachelor of Science in Customs Administration'                => 'BS Customs Administration',
            'Bachelor of Arts in Communication'                            => 'BA Communication',
            'Bachelor of Arts in Psychology'                               => 'BA Psychology',
            'Bachelor of Arts in English'                                  => 'BA Communication',
            'Bachelor of Arts in Political Science'                        => 'BA Political Science',
            'Bachelor of Arts in History'                                  => 'BA History',
            'Bachelor of Arts in Sociology'                                => 'BA Sociology',
            'Bachelor of Arts in Public Administration'                    => 'BA Public Administration',
            'Bachelor of Arts in Legal Management'                         => 'BA Legal Management',
            'Bachelor of Arts in International Studies'                    => 'BA International Studies',
            'Bachelor of Arts in Development Studies'                      => 'BA Development Studies',
            'Bachelor of Elementary Education'                             => 'Bachelor of Elementary Education (BEEd)',
            'Bachelor of Elementary Education (BEEd)'                     => 'Bachelor of Elementary Education (BEEd)',
            'Bachelor of Early Childhood Education'                        => 'Bachelor of Early Childhood Education (BECEd)',
            'Bachelor of Early Childhood Education (BECEd)'               => 'Bachelor of Early Childhood Education (BECEd)',
            'Bachelor of Secondary Education'                              => 'Bachelor of Secondary Education (BSEd) major in English',
            'Bachelor of Physical Education (BPEd)'                       => 'Bachelor of Physical Education (BPEd)',
            'Bachelor of Technology and Livelihood Education (BTLEd)'     => 'Bachelor of Technology and Livelihood Education (BTLEd)',
            'Bachelor of Laws (LLB)'                                       => 'BA Legal Management',
        ];

        // Resolve short key: use map or try exact key directly
        $lookupKey = $nameMap[$course] ?? $course;

        try {
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists('courses_data.json')) {
                $json = \Illuminate\Support\Facades\Storage::disk('local')->get('courses_data.json');
                $courses = json_decode($json, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($courses)) {
                    // Try short key first, then original name
                    $data = $courses[$lookupKey] ?? $courses[$course] ?? null;
                    if ($data) {
                        // Always show the original full title in the UI
                        $data['title'] = $course;
                        return $data;
                    }
                    return $defaultCourse;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to load courses data: ' . $e->getMessage());
        }

        return $defaultCourse;
    }

    private function getMbtiCareerCompatibility()
    {
        return [
            'Software Developer' => [
                'ISTJ' => 'ISTJs possess a strong Introverted Sensing (Si) function, which makes them exceptional at retaining complex technical information and adhering to established coding standards. In software development, their systematic and highly organized approach ensures code is meticulously structured, thoroughly tested, and highly reliable. They thrive in environments where they can apply logical, step-by-step methods (Te) to resolve intricate bugs and build secure, stable systems.',
                'INFJ' => 'INFJs combine their dominant Introverted Intuition (Ni) with Extroverted Feeling (Fe) to build software that not only functions well but serves a meaningful human purpose. They excel at seeing the "big picture" architecture of a project while remaining deeply attuned to the end-user\'s experience. As developers, they are often the ones advocating for accessibility, intuitive design, and creating products that genuinely improve people\'s daily lives.',
                'INTJ' => 'INTJs are visionaries driven by Introverted Intuition (Ni) and Extroverted Thinking (Te), making them natural-born software architects. They excel at holding massive, complex systems in their minds and designing elegant, highly efficient solutions to engineering challenges. Their independent and deeply analytical nature allows them to thrive when given the autonomy to rethink legacy systems and engineer highly scalable, future-proof platforms.',
                'INTP' => 'INTPs are driven by Introverted Thinking (Ti), giving them an insatiable desire to understand exactly how underlying systems work. In software development, they are the ultimate problem-solvers, naturally deconstructing complex issues until they find edge cases others miss. Their Extroverted Intuition (Ne) allows them to brainstorm highly unconventional, elegant code solutions, making them invaluable when a team needs to invent a completely new technical approach.',
                'ENTP' => 'ENTPs leverage their dominant Extroverted Intuition (Ne) to rapidly connect disparate technical concepts, making them highly innovative developers. They love the challenge of building something that hasn\'t been done before and are quick to prototype new, out-of-the-box solutions. Their logical framework (Ti) ensures their creative ideas remain technically sound, allowing them to thrive in fast-paced startup environments or R&D roles.',
                'ENTJ' => 'ENTJs use their dominant Extroverted Thinking (Te) to drive efficiency, structure, and decisive action in the software development lifecycle. While they are highly capable coders, they often naturally gravitate toward technical leadership or systems architecture. They excel at mapping out the strategic direction of a codebase, organizing development workflows, and leading engineering teams to deliver high-quality products on aggressive timelines.'
            ],
            'Data Analyst' => [
                'INFJ' => 'INFJs possess highly developed Introverted Intuition (Ni), giving them an uncanny ability to spot hidden trends and underlying narratives within massive datasets. Unlike purely logical types, their Extroverted Feeling (Fe) drives them to translate these data points into compelling, human-centric stories. They excel in this role because they don\'t just analyze numbers—they interpret what the data means for the people, customers, or patients behind it.',
                'INTJ' => 'INTJs are master strategists (Ni) who utilize Extroverted Thinking (Te) to turn complex data into actionable business directives. They thrive in Data Analyst roles because they can effortlessly zoom out to see the overarching patterns in a dataset while designing highly efficient, automated models to track them. Their analysis is rarely just observational; it is always tied to optimizing systems and driving long-term organizational strategy.',
                'INTP' => 'INTPs utilize Introverted Thinking (Ti) to rigorously tear apart datasets, ruthlessly seeking the precise truth hidden within the numbers. They are not satisfied with surface-level reports; they want to build complex queries and experimental models (Ne) to understand the fundamental logic driving the data. This deep, meticulous curiosity makes them exceptional at uncovering critical insights that more conventional analysts might overlook.'
            ],
            'Cybersecurity Analyst' => [
                'ISTJ' => 'ISTJs are naturally vigilant and highly observant, relying on Introverted Sensing (Si) to meticulously track anomalies and deviations from normal system behavior. In cybersecurity, their deep respect for procedure and unmatched attention to detail makes them the ultimate defenders of digital infrastructure. They excel at writing robust security protocols, monitoring logs with unwavering consistency, and ensuring that compliance standards are flawlessly exacted.',
                'INTJ' => 'INTJs apply their dominant Introverted Intuition (Ni) to anticipate security threats long before they materialize. They approach cybersecurity like a high-stakes game of chess, designing complex, multi-layered defensive architectures (Te) to outsmart sophisticated adversaries. Their ability to foresee potential attack vectors and systematically eliminate vulnerabilities makes them highly effective at proactive threat hunting and strategic risk management.',
                'ISTP' => 'ISTPs possess dominant Introverted Thinking (Ti) paired with highly reactive Extroverted Sensing (Se), making them unmatched in crisis situations. When a system is actively under attack, they remain calm, immediately diagnosing the technical breach and taking decisive, hands-on action to neutralize the threat. They thrive in the high-adrenaline, problem-solving environment of incident response and penetration testing.'
            ],
            'Financial Analyst' => [
                'ISTJ' => 'ISTJs rely on Introverted Sensing (Si) to maintain incredibly accurate, organized, and reliable financial records. They excel in financial analysis because they have the patience and discipline to pore over years of historical data, ensuring every budget is balanced and every forecast is rooted in hard reality. Their strong factual memory and methodical approach make them highly trusted custodians of an organization\'s financial health.',
                'INTJ' => 'INTJs use their Introverted Intuition (Ni) to see the long-term economic trajectories that others miss. They excel as Financial Analysts because they view finance not just as accounting, but as a complex machine that can be optimized (Te) for maximum future yield. They are highly skilled at building sophisticated financial models that accurately forecast market shifts and guide high-level corporate investments.',
                'ENTP' => 'ENTPs bring a highly dynamic, Extroverted Intuition (Ne) driven approach to finance, constantly identifying emerging market trends and unconventional investment opportunities. They love debating economic theory and utilizing logical frameworks (Ti) to assess risk versus reward in fluid markets. They thrive in fast-paced financial environments like trading, venture capital, or market strategy where agility and out-of-the-box thinking are highly rewarded.',
                'ESTJ' => 'ESTJs use Extroverted Thinking (Te) to bring aggressive order, efficiency, and clear metrics to financial operations. They are highly effective Financial Analysts because they do not just report the numbers—they use them to enforce budgets, streamline spending, and drive tangible business growth. Their practical, decisive nature ensures that financial insights are translated immediately into measurable corporate action.',
                'ENTJ' => 'ENTJs are natural financial strategists, utilizing Extroverted Thinking (Te) and Introverted Intuition (Ni) to orchestrate large-scale economic growth. They view financial analysis as the ultimate tool for corporate dominance and expansion. They excel at diagnosing financial inefficiencies, executing aggressive growth models, and confidently presenting complex financial directives to executive boards.'
            ],
            'Database Administrator' => [
                'ISTJ' => 'ISTJs have a natural affinity for classifying information, driven by Introverted Sensing (Si). As Database Administrators, they take immense pride in ensuring that massive data stores are highly structured, perfectly backed up, and utterly secure. Their methodical persistence (Te) ensures that data integrity is maintained flawlessly, making them the most reliable safeguard an organization\'s critical information could have.',
                'INTP' => 'INTPs are driven by Introverted Thinking (Ti) to build sophisticated, logically perfect frameworks. In database administration, this translates to designing elegant, highly normalized relational schemas that eliminate redundancy and process queries with maximum efficiency. They view the database as a massive logical puzzle that can always be optimized, making them exceptional at performance tuning and complex architecture design.'
            ],
            'Systems Administrator' => [
                'INTJ' => 'INTJs utilize Introverted Intuition (Ni) to design highly resilient, future-proof IT infrastructures. They excel as Systems Administrators by anticipating capacity bottlenecks and hardware failures long before they occur, systematically automating operations (Te) to remove human error. They don\'t just maintain servers; they architect long-term, self-sustaining technological ecosystems.',
                'ISTP' => 'ISTPs are hands-on troubleshooters who thrive when interacting directly with complex machinery and systems via their Extroverted Sensing (Se). When a critical server crashes, their Introverted Thinking (Ti) allows them to remain completely detached and analytical, rapidly identifying the root cause and deploying a practical fix. They excel in the unpredictable, dynamic reality of daily system operations and hardware maintenance.',
                'ESTJ' => 'ESTJs bring rigorous, standard-operating-procedure discipline (Te) to IT environments. As Systems Administrators, they ensure that every server update, user permission, and network policy is executed perfectly to spec. They are highly reliable managers of technology who enforce security standards and guarantee maximum uptime through meticulous, routine maintenance (Si).',
                'ENTJ' => 'ENTJs view enterprise IT systems as the central nervous system of a business that must operate with maximum, uncompromising efficiency (Te). They excel as Systems Administrators because they quickly identify operational bottlenecks and decisively implement sweeping technological upgrades. They are natural technical leaders who align a company\'s infrastructure directly with its aggressive growth objectives.'
            ],
            'Network Administrator' => [
                'ISTP' => 'ISTPs possess a highly technical, hands-on learning style driven by Extroverted Sensing (Se) and Introverted Thinking (Ti). They don\'t just want to know that a network works; they want to physically understand how the packets are routed. This makes them exceptional Network Administrators who can mentally map out complex physical topologies, rapidly diagnose hardware failures, and execute practical, real-world solutions under intense pressure.',
                'INTP' => 'INTPs are drawn to the pure underlying logic (Ti) of complex systems. As Network Administrators, they view network architecture as a massive puzzle of interconnecting protocols. Their Extroverted Intuition (Ne) allows them to foresee how a single routing change will impact the entire ecosystem, making them exceptional at designing highly efficient, elegant network topologies from the ground up.'
            ],
            'Web Developer' => [
                'ISFP' => 'ISFPs possess a strong aesthetic awareness (Se) combined with deeply held personal values (Fi). They excel as Front-End Web Developers because they view coding not just as logic, but as a medium for artistic expression. They have a natural eye for user interface design and are driven to create beautiful, accessible web experiences that feel genuinely authentic and engaging to the end-user.',
                'INTP' => 'INTPs use their Introverted Thinking (Ti) to endlessly experiment with new frameworks and back-end logic. They thrive as Web Developers because the internet is a constantly evolving sandbox for their Extroverted Intuition (Ne). They love to tear down existing codebases to understand how they work, ultimately rebuilding them into more elegant, modular, and highly efficient web applications.'
            ],
            'IT Support Specialist' => [
                'ISFJ' => 'ISFJs are naturally empathetic and highly dependable, driven by Extroverted Feeling (Fe) and Introverted Sensing (Si). In IT Support, they excel not just at resolving the technical issue, but at making the frustrated user feel heard and cared for. They remember individual preferences, meticulously document recurring issues, and bring a much-needed sense of calm and order to chaotic technical environments.',
                'ISFP' => 'ISFPs are highly observant (Se) and deeply empathetic (Fi). As IT Support Specialists, they are excellent at reading a user\'s stress level and responding with quiet, practical assistance. They do not over-complicate solutions with technical jargon; instead, they focus on hands-on, immediate fixes that get people back to work, always maintaining a gentle and accommodating demeanor.',
                'ESTP' => 'ESTPs are energetic troubleshooters who thrive on immediate, tactical action (Se). In IT support, they are the ones who jump straight into a crisis, quickly physically diagnosing the hardware or software fault, and deploying a rapid fix. They enjoy the fast-paced, unpredictable nature of helpdesk environments where they can interact with different people and solve tangible problems every single day.'
            ],
            'Staff Nurse' => [
                'ISFJ' => 'ISFJs are the quintessential caregivers, utilizing Introverted Sensing (Si) to meticulously track a patient\'s medical history, exact dosages, and daily routines. Their Extroverted Feeling (Fe) makes them deeply attuned to the emotional needs of both patients and their families. They excel as Staff Nurses because they provide an environment of absolute reliability, warmth, and unshakeable dedication to their patients\' daily comfort.',
                'ISFP' => 'ISFPs bring a deeply compassionate, hands-on approach (Se) to patient care. They are acutely aware of their physical environment and use Introverted Feeling (Fi) to connect with patients on a profoundly personal level. As Staff Nurses, they are often the most gentle and attentive caregivers, quickly noticing subtle changes in a patient\'s physical comfort and responding with immediate, practical care.',
                'ESFJ' => 'ESFJs are the ultimate coordinators of care, using Extroverted Feeling (Fe) to ensure everyone on the ward feels supported, informed, and valued. They excel as Staff Nurses because they seamlessly manage the complex social dynamics between doctors, patients, and families. Their strong organizational skills (Si) ensure that ward operations run smoothly and that no patient is ever overlooked.'
            ],
            'Human Resources Specialist' => [
                'ISFJ' => 'ISFJs are the bedrock of any supportive organization. Utilizing Extroverted Feeling (Fe), they possess a natural instinct to mediate conflict and ensure that every employee feels valued and secure. In HR, their Introverted Sensing (Si) allows them to flawlessly manage complex payrolls, benefits structures, and compliance policies, ensuring the workplace is both legally sound and emotionally supportive.',
                'INFJ' => 'INFJs use their Introverted Intuition (Ni) to foresee long-term organizational health and deeply understand the underlying cultural dynamics of a workplace. They excel in Human Resources as counselors and strategic planners, recognizing the untapped potential in employees and advocating for policies that promote genuine mental well-being, diversity, and long-term talent development.',
                'ENFP' => 'ENFPs bring boundless energy and Extroverted Intuition (Ne) to Human Resources. They are the ultimate champions of company culture, constantly devising creative new ways to boost morale, foster team bonding, and recruit innovative talent. Their deep empathy (Fi) ensures that they view employees as unique individuals to be inspired, rather than just "human capital" to be managed.',
                'ESFJ' => 'ESFJs are naturally community-minded organizers who thrive when creating harmony within a group (Fe). They are highly effective HR Specialists because they take immense pride in onboarding new hires, organizing company events, and clearly communicating expectations. They ensure that the workplace feels like a cohesive, welcoming community where established rules (Si) are followed for everyone\'s benefit.',
                'ENFJ' => 'ENFJs are charismatic, visionary leaders of people (Fe/Ni). In Human Resources, they excel as transformational leaders who inspire entire organizations to align around a shared mission and set of values. They are exceptionally skilled at talent acquisition and leadership development, instinctively knowing how to place people in roles where they will achieve their absolute highest potential.'
            ],
            'Educational Coordinator' => [
                'ISFJ' => 'ISFJs are meticulous organizers (Si) dedicated to serving their community (Fe). As Educational Coordinators, they excel at managing the complex, day-to-day scheduling required to keep a school or program running flawlessly. They ensure that teachers have the resources they need and that students are supported by a stable, predictable, and deeply caring educational environment.',
                'INFJ' => 'INFJs view education as the ultimate tool for human transformation. Utilizing Introverted Intuition (Ni), they design long-term educational programs that don\'t just impart facts, but fundamentally shape a student\'s character and worldview. They excel as Coordinators because they advocate fiercely for holistic, inclusive curricula that addresses the emotional and intellectual needs of every learner.',
                'INFP' => 'INFPs are deeply ideological (Fi) and believe that education should inspire an individual\'s unique authentic path. As Educational Coordinators, they excel at designing specialized, creative programs that cater to diverse learning styles rather than a "one-size-fits-all" approach. They advocate tirelessly for educational structures that protect student mental health and foster genuine creative expression.',
                'ENFP' => 'ENFPs view the educational environment as a massive playground of ideas (Ne). They are highly effective Educational Coordinators because they bring infectious enthusiasm to curriculum design, constantly seeking out innovative, interactive, and unconventional teaching methods. They excel at rallying teachers around exciting new initiatives and creating an atmosphere where learning is genuinely fun.',
                'ENFJ' => 'ENFJs are naturally charismatic mentors driven by Extroverted Feeling (Fe) and Introverted Intuition (Ni). As Educational Coordinators, they are unparalleled at inspiring faculty, leading teacher training programs, and uniting a school around a powerful educational vision. They instinctively understand how to motivate both educators and students to strive for academic and personal excellence.'
            ],
            'Content Writer' => [
                'INFJ' => 'INFJs utilize Introverted Intuition (Ni) to grasp profound, universal human truths, and Extroverted Feeling (Fe) to express them with deep resonance. As Content Writers, they excel at crafting authentic, values-driven narratives that connect with readers on an emotional level. They are often the most persuasive writers because they genuinely believe in the stories they are telling.',
                'INFP' => 'INFPs are deeply imaginative (Ne) and fiercely authentic (Fi). They excel as Content Writers because writing offers them a pure, unfiltered medium to express their complex internal worlds and ideological convictions. They are masters of tone and voice, uniquely capable of producing original, compelling prose that feels incredibly personal and intensely creative.',
                'ENFP' => 'ENFPs possess endless curiosity and an explosive Extroverted Intuition (Ne) that allows them to find fascinating angles on almost any topic. As Content Writers, they thrive when given a wide variety of subjects to explore, constantly inventing new, engaging ways to hook an audience. Their natural enthusiasm (Fi) shines through their writing, making their content highly persuasive and highly readable.'
            ],
            'Social Worker' => [
                'INFP' => 'INFPs are idealistic and deeply empathetic (Fi), driven by a powerful internal moral compass to protect the vulnerable. As Social Workers, they provide a profound level of emotional safety for their clients, listening without judgment and advocating fiercely for those who cannot advocate for themselves. They view social work not just as a job, but as a calling to heal a fractured world.',
                'ENFJ' => 'ENFJs are natural advocates who utilize Extroverted Feeling (Fe) to mobilize communities and uplift individuals in crisis. They are highly effective Social Workers because they do not just offer sympathy; they proactively design interventions and build support networks (Ni) that drive tangible, positive change in people\'s lives. They are charismatic, endlessly supportive, and fiercely protective of their clients.'
            ],
            'Compliance Officer' => [
                'ISTJ' => 'ISTJs rely on a dominant Introverted Sensing (Si) function, making them unmatched in their ability to retain, interpret, and strictly apply complex rules. In Compliance, they are the ultimate guardians of organizational integrity, ensuring that every legal statute and internal policy is followed to the letter. Their objective, logical nature (Te) ensures that rules are enforced fairly and without emotional bias.',
                'ESTJ' => 'ESTJs are driven by Extroverted Thinking (Te) to bring absolute order and accountability to their environment. As Compliance Officers, they are highly proactive, explicitly defining standards, auditing organizational behavior, and swiftly correcting deviations from policy. They do not shy away from conflict, ensuring that a company remains secure, legally compliant, and systematically organized at all times.'
            ],
            'Administrative Officer' => [
                'ISFJ' => 'ISFJs combine meticulous organization (Si) with a deep desire to support their community (Fe). As Administrative Officers, they are the invisible glue that holds an office together, quietly anticipating the needs of the team and ensuring that daily operations run without a single flaw. They take immense pride in creating a harmonious, perfectly functioning work environment for their colleagues.',
                'ESTJ' => 'ESTJs are natural administrators who use Extroverted Thinking (Te) to turn chaotic offices into highly-tuned machines. They excel in this role by establishing clear operating procedures, driving out inefficiencies, and ensuring that every resource is utilized perfectly. They are highly dependable and take swift, decisive action to ensure organizational goals are met without delay.',
                'ESFJ' => 'ESFJs are the ultimate coordinators, using Extroverted Feeling (Fe) to ensure an office remains both highly organized and emotionally supportive. As Administrative Officers, they excel at managing not just the paperwork, but the people—organizing events, resolving interpersonal friction, and ensuring everyone feels valued. Their strong attention to routine (Si) guarantees that operational details are never missed.'
            ],
            'Operations Manager' => [
                'ESTP' => 'ESTPs are pragmatic action-takers who thrive in high-stakes, fast-moving environments (Se). As Operations Managers, they excel at crisis management, rapidly diagnosing logistical bottlenecks (Ti) and deploying immediate, on-the-ground solutions. They lead by example, maintaining total composure under pressure and mobilizing teams with their energetic, results-driven leadership style.',
                'ENTP' => 'ENTPs view operations as a massive puzzle to be solved and optimized. Utilizing Extroverted Intuition (Ne) and Introverted Thinking (Ti), they are never satisfied with "the way things have always been done." They excel at identifying systemic inefficiencies and architecting brilliant, unconventional new workflows that drastically improve a company\'s operational throughput.',
                'ESTJ' => 'ESTJs are the epitome of structured leadership (Te). As Operations Managers, they excel at designing rigid, highly efficient operating procedures and holding their teams strictly accountable to those standards. They possess a commanding presence and an incredible capacity to organize people and resources (Si) to achieve maximum operational efficiency.',
                'ENTJ' => 'ENTJs are strategic visionaries (Ni) who execute their plans with ruthless efficiency (Te). In operations management, they are unparalleled at diagnosing large-scale organizational flaws and driving sweeping, systemic change to fix them. They do not just manage current operations; they aggressively scale them to support massive future corporate growth.'
            ],
            'Business Development Manager' => [
                'ESTP' => 'ESTPs rely on Extroverted Sensing (Se) to read a room perfectly, adapting their pitch on the fly to close complex deals. Their fearless, action-oriented nature makes them exceptional at networking, cold-calling, and aggressively pursuing new market opportunities. They thrive on the thrill of the negotiation and the tangible reward of securing new business.',
                'ENTP' => 'ENTPs use Extroverted Intuition (Ne) to spot lucrative market opportunities that competitors completely miss. As Business Development Managers, they are highly persuasive debaters (Ti), excellent at pitching innovative, unconventional partnerships to high-level stakeholders. They excel in the initial, creative phases of deal-making where vision and adaptability are paramount.',
                'ENTJ' => 'ENTJs approach Business Development as a strategic campaign for corporate expansion. Using Extroverted Thinking (Te) and Introverted Intuition (Ni), they systematically analyze market trends, identify high-value acquisition targets, and execute complex, long-term partnership strategies. They are formidable negotiators who inspire confidence through their sheer competence and unshakeable ambition.'
            ],
            'Marketing Coordinator' => [
                'ESTP' => 'ESTPs have their finger firmly on the pulse of current trends (Se) and know exactly what will grab the public\'s attention. As Marketing Coordinators, they operate with speed and tactical precision, quickly identifying viral opportunities and executing high-impact campaigns before the competition can react. Their practical logic (Ti) ensures that every marketing dollar spent translates to immediate, tangible results.',
                'ESFP' => 'ESFPs are natural entertainers (Se) who understand the emotional triggers of an audience (Fi) better than almost anyone. They excel in marketing because they know how to make a brand feel genuinely exciting, authentic, and culturally relevant. They are highly creative, deeply attuned to aesthetics, and brilliant at crafting campaigns that generate massive organic engagement.',
                'ENFP' => 'ENFPs possess an explosive imagination (Ne) that allows them to generate wildly creative, out-of-the-box marketing concepts. As Marketing Coordinators, they are unparalleled at crafting compelling brand narratives that inspire and captivate their target demographic. Their genuine enthusiasm (Fi) is infectious, allowing them to build marketing campaigns that feel more like energetic movements than traditional advertisements.'
            ],
            'Sales Representative' => [
                'ESTP' => 'ESTPs are born negotiators driven by Extroverted Sensing (Se) and quick, tactical logic (Ti). They thrive in the high-stakes, competitive environment of sales because they can read a client\'s hesitation in real-time and effortlessly pivot their pitch to close the deal. They are thick-skinned, highly persuasive, and energized by the immediate thrill of winning a contract.',
                'ESFP' => 'ESFPs utilize their immense charm (Se) and emotional intelligence (Fi) to build deep, authentic rapport with their clients. They excel in sales because they don\'t come across as aggressive or "salesy"; instead, they feel like a trusted friend recommending a genuinely good product. Their ability to make the purchasing experience genuinely enjoyable results in fierce, long-term client loyalty.'
            ],
            'Customer Service Representative' => [
                'ESFP' => 'ESFPs possess boundless energy and a genuine love for interacting with people (Se/Fi). As Customer Service Representatives, they excel because they can diffuse high-stress situations with their natural warmth, humor, and empathy. They thrive in dynamic environments where they can turn a frustrated customer into a fiercely loyal brand advocate through sheer force of positive personality.',
                'ESFJ' => 'ESFJs are deeply motivated by Extroverted Feeling (Fe) to help others and restore harmony. In Customer Service, they are highly attentive, infinitely patient, and dedicated to resolving every single issue completely so the customer leaves satisfied. Their strong organizational skills (Si) ensure they follow all company protocols while delivering exceptional, deeply caring service.'
            ],
            'Event Coordinator' => [
                'ESFP' => 'ESFPs are the ultimate hosts, driven by Extroverted Sensing (Se) to create stunning, highly memorable physical experiences. As Event Coordinators, they excel at managing the fast-paced, chaotic reality of live events, improvising brilliant solutions on the fly when things invariably go wrong. Their natural charisma (Fi) ensures that vendors, staff, and guests all remain happy and energized throughout the entire production.'
            ],
            'Hotel Front Desk Agent' => [
                'ESFP' => 'ESFPs define the concept of hospitality, using their Extroverted Sensing (Se) to remain highly attentive to their immediate environment and the needs of their guests. Their warm, spontaneous nature (Fi) allows them to provide uniquely personalized service, turning a standard hotel check-in into a genuinely welcoming and memorable first impression for weary travelers.'
            ],
            'Public Relations Officer' => [
                'ENFP' => 'ENFPs are master communicators who utilize Extroverted Intuition (Ne) to shape incredibly compelling public narratives. As PR Officers, they excel at spinning complex situations into positive, forward-looking stories that captivate the media and the public. Their deep empathy (Fi) ensures that an organization\'s messaging remains authentic, human-centric, and intensely relatable.',
                'ENFJ' => 'ENFJs are natural diplomats guided by Extroverted Feeling (Fe) and strategic foresight (Ni). In Public Relations, they are unparalleled at managing a brand\'s reputation, anticipating public reactions, and cultivating deep, trusting relationships with the press and the community. During a crisis, they step up as the calm, charismatic, and reassuring voice of the organization.'
            ],
            'Communications Specialist' => [
                'INFP' => 'INFPs use Introverted Feeling (Fi) to deeply understand the emotional core of an audience, allowing them to craft highly resonant, empathetic messaging. As Communications Specialists, they excel at ensuring an organization\'s internal and external voice remains fiercely authentic and aligned with its core values. They possess an incredible talent for writing communications that feel genuinely heartfelt and inspiring.',
                'ENFJ' => 'ENFJs are visionary communicators (Ni/Fe) who know exactly how to tailor a message to inspire action from diverse groups of people. As Communications Specialists, they excel at defining an organization\'s public voice and ensuring that every memo, speech, and press release resonates with deep emotional intelligence. They are the ultimate unifiers, using language to build consensus and drive organizational momentum.'
            ],
            'Market Research Analyst' => [
                'ENTP' => 'ENTPs are driven by Extroverted Intuition (Ne) to constantly question the status quo and uncover hidden market dynamics. As Market Research Analysts, they excel because they utilize rigorous logic (Ti) to build unconventional models that accurately predict shifts in consumer behavior before they happen. They love the intellectual challenge of finding the "signal in the noise" and presenting game-changing strategic insights to leadership.'
            ],
            'Curriculum Developer' => [
                'INFP' => 'INFPs view learning as an intensely personal, transformative journey (Fi). As Curriculum Developers, they excel at designing holistic, creative educational modules (Ne) that engage a student\'s imagination and moral compass, not just their rote memory. They design curricula that inspire genuine passion and cater beautifully to diverse learning styles.'
            ],
            'Computer Engineer' => [
                'ISTP' => 'ISTPs are the ultimate mechanical and technical pragmatists, driven by a powerful combination of Introverted Thinking (Ti) and Extroverted Sensing (Se). As Computer Engineers, they excel because they possess an innate understanding of how hardware and software must physical integrate. They love the hands-on challenge of prototyping circuitry, diagnosing complex hardware faults, and building elegant, highly efficient physical systems.'
            ],
            'Electronics Engineer' => [
                'ISTP' => 'ISTPs possess a natural, deeply intuitive understanding of physical mechanics and logical systems (Ti/Se). As Electronics Engineers, they are highly skilled at designing complex circuits, troubleshooting erratic electrical behavior, and physically prototyping new hardware. They remain incredibly calm and analytical when diagnosing complex physical faults, making them exceptional at hands-on engineering.'
            ],
            'Physical Therapist' => [
                'ISFP' => 'ISFPs are intimately attuned to the physical world (Se) and possess a deep, quiet empathy for the suffering of others (Fi). As Physical Therapists, they excel at reading the subtle physical cues of a patient in pain, adjusting their hands-on treatments with incredible sensitivity. They provide a calm, highly supportive environment that encourages patients through the arduous process of physical rehabilitation.'
            ],
            'Occupational Therapist' => [
                'ISFP' => 'ISFPs combine a deep desire to help individuals (Fi) with a practical, hands-on awareness of the physical environment (Se). In Occupational Therapy, they excel at designing highly creative, personalized interventions that help patients regain their independence in daily life tasks. They are immensely patient, celebrating small physical victories and fiercely advocating for their patients\' quality of life.'
            ],
            'Elementary School Teacher' => [
                'ESFJ' => 'ESFJs are the quintessential, nurturing guardians of their community (Fe). As Elementary School Teachers, they excel at creating highly organized, predictable, and deeply loving classroom environments (Si) where young children feel incredibly safe to learn and grow. They are exceptionally attentive to the emotional and developmental needs of every single student, making them profoundly impactful early-childhood educators.'
            ]
        ];
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
