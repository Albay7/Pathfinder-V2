<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MbtiTestSession;
use App\Models\MbtiPersonalityType;
use App\Models\UserProgress;
use App\Models\Course;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class MbtiController extends Controller
{
    /**
     * Display the MBTI questionnaire form
     *
     * @return \Illuminate\View\View
     */
    public function showQuestionnaire()
    {
        return view('pathfinder.mbti-questionnaire');
    }

    /**
     * Process the MBTI questionnaire submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processQuestionnaire(Request $request)
    {
        // Validate the request - expecting JSON responses
        $request->validate([
            'responses' => 'required|string',
            'current_page' => 'required|integer|min:1|max:10'
        ]);

        // Decode the JSON responses
        $responses = json_decode($request->input('responses'), true);
        
        // Validate that we have all 60 questions with valid values
        if (!$responses || count($responses) !== 60) {
            return back()->withErrors(['error' => 'Please complete all questions before submitting.']);
        }
        
        // Convert responses to the expected format and validate values
        $answers = [];
        for ($i = 1; $i <= 60; $i++) {
            $questionKey = "q{$i}";
            if (!isset($responses[$questionKey]) || !is_numeric($responses[$questionKey]) || 
                $responses[$questionKey] < 1 || $responses[$questionKey] > 7) {
                return back()->withErrors(['error' => "Invalid response for question {$i}."]);
            }
            $answers[$questionKey] = (int) $responses[$questionKey];
        }

        // Calculate MBTI dimension scores using improved algorithm
        // Each dimension has 15 questions (questions 1-15: E/I, 16-30: S/N, 31-45: T/F, 46-60: J/P)
        
        // Extraversion vs Introversion (Questions 1-15)
        $eScore = 0;
        $iScore = 0;
        $eiQuestions = [
            // Direct E questions (higher score = more extraverted)
            1, 3, 5, 7, 9, 11, 13, 15,
            // Reverse I questions (higher score = more introverted, so reverse for E)
            2, 4, 6, 8, 10, 12, 14
        ];
        
        foreach ($eiQuestions as $qNum) {
            if (in_array($qNum, [2, 4, 6, 8, 10, 12, 14])) {
                // Reverse scoring for introverted questions
                $eScore += (8 - $answers["q{$qNum}"]);
                $iScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for extraverted questions
                $eScore += $answers["q{$qNum}"];
                $iScore += (8 - $answers["q{$qNum}"]);
            }
        }
        
        // Sensing vs Intuition (Questions 16-30)
        $sScore = 0;
        $nScore = 0;
        $snQuestions = [
            // Direct S questions (higher score = more sensing)
            16, 18, 20, 22, 24, 26, 28, 30,
            // Reverse N questions (higher score = more intuitive, so reverse for S)
            17, 19, 21, 23, 25, 27, 29
        ];
        
        foreach ($snQuestions as $qNum) {
            if (in_array($qNum, [17, 19, 21, 23, 25, 27, 29])) {
                // Reverse scoring for intuitive questions
                $sScore += (8 - $answers["q{$qNum}"]);
                $nScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for sensing questions
                $sScore += $answers["q{$qNum}"];
                $nScore += (8 - $answers["q{$qNum}"]);
            }
        }
        
        // Thinking vs Feeling (Questions 31-45)
        $tScore = 0;
        $fScore = 0;
        $tfQuestions = [
            // Direct T questions (higher score = more thinking)
            31, 33, 35, 37, 39, 41, 43, 45,
            // Reverse F questions (higher score = more feeling, so reverse for T)
            32, 34, 36, 38, 40, 42, 44
        ];
        
        foreach ($tfQuestions as $qNum) {
            if (in_array($qNum, [32, 34, 36, 38, 40, 42, 44])) {
                // Reverse scoring for feeling questions
                $tScore += (8 - $answers["q{$qNum}"]);
                $fScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for thinking questions
                $tScore += $answers["q{$qNum}"];
                $fScore += (8 - $answers["q{$qNum}"]);
            }
        }
        
        // Judging vs Perceiving (Questions 46-60)
        $jScore = 0;
        $pScore = 0;
        $jpQuestions = [
            // Direct J questions (higher score = more judging)
            46, 48, 50, 52, 54, 56, 58, 60,
            // Reverse P questions (higher score = more perceiving, so reverse for J)
            47, 49, 51, 53, 55, 57, 59
        ];
        
        foreach ($jpQuestions as $qNum) {
            if (in_array($qNum, [47, 49, 51, 53, 55, 57, 59])) {
                // Reverse scoring for perceiving questions
                $jScore += (8 - $answers["q{$qNum}"]);
                $pScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for judging questions
                $jScore += $answers["q{$qNum}"];
                $pScore += (8 - $answers["q{$qNum}"]);
            }
        }

        // Determine MBTI type
        $mbtiType = '';
        $mbtiType .= $eScore > $iScore ? 'E' : 'I';
        $mbtiType .= $sScore > $nScore ? 'S' : 'N';
        $mbtiType .= $tScore > $fScore ? 'T' : 'F';
        $mbtiType .= $jScore > $pScore ? 'J' : 'P';

        // Find the personality type from database
        $personalityType = MbtiPersonalityType::where('type_code', $mbtiType)->first();

        if (!$personalityType) {
            return redirect()->back()->with('error', 'Unable to determine personality type. Please try again.');
        }

        // Calculate percentages for display
        $mbtiScores = [
            'E_I' => [
                'E' => round(($eScore / ($eScore + $iScore)) * 100),
                'I' => round(($iScore / ($eScore + $iScore)) * 100)
            ],
            'S_N' => [
                'S' => round(($sScore / ($sScore + $nScore)) * 100),
                'N' => round(($nScore / ($sScore + $nScore)) * 100)
            ],
            'T_F' => [
                'T' => round(($tScore / ($tScore + $fScore)) * 100),
                'F' => round(($fScore / ($tScore + $fScore)) * 100)
            ],
            'J_P' => [
                'J' => round(($jScore / ($jScore + $pScore)) * 100),
                'P' => round(($pScore / ($jScore + $pScore)) * 100)
            ]
        ];

        // Create test session record
        $testSession = MbtiTestSession::create([
            'user_id' => Auth::id(),
            'responses' => $answers,
            'e_score' => $eScore,
            'i_score' => $iScore,
            's_score' => $sScore,
            'n_score' => $nScore,
            't_score' => $tScore,
            'f_score' => $fScore,
            'j_score' => $jScore,
            'p_score' => $pScore,
            'result_type' => $mbtiType,
            'personality_type_id' => $personalityType->id,
            'completed' => true,
            'completed_at' => now()
        ]);

        // Save to user profile if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'mbti_type' => $mbtiType,
                'mbti_scores' => $mbtiScores,
                'mbti_description' => $personalityType->description
            ]);
            
            // Store career recommendations in user_progress
            $this->storeCareerRecommendations($user->id, $mbtiType, $personalityType);
        } else {
            // Store in session for guest users
            session([
                'mbti_type' => $mbtiType,
                'mbti_scores' => $mbtiScores,
                'mbti_description' => $personalityType->description,
                'personality_type' => $personalityType
            ]);
        }

        return redirect()->route('pathfinder.mbti.results')
            ->with('success', 'Your MBTI assessment has been completed!');
    }

    /**
     * Display the MBTI results
     *
     * @return \Illuminate\View\View
     */
    public function showResults()
    {
        $personalityType = null;

        if (Auth::check()) {
            $user = Auth::user();
            $mbtiType = $user->mbti_type;
            $mbtiScores = $user->mbti_scores;
            $mbtiDescription = $user->mbti_description;

            // Get the full personality type from database
            if ($mbtiType) {
                $personalityType = MbtiPersonalityType::where('type_code', $mbtiType)->first();
            }
        } else {
            // For guest users, get from session
            $mbtiType = session('mbti_type');
            $mbtiScores = session('mbti_scores');
            $mbtiDescription = session('mbti_description');
            $personalityType = session('personality_type');

            if (!$mbtiType) {
                return redirect()->route('pathfinder.mbti.questionnaire')
                    ->with('error', 'Please complete the MBTI questionnaire first.');
            }
        }

        // If we have personality type from database, use its data
        if ($personalityType) {
            $mbtiDescription = $personalityType->description;
        }

        $careerRecommendations = $this->getMbtiCareerRecommendations($mbtiType);
        $learningStyle = $this->getMbtiLearningStyle($mbtiType);
        
        // Get course and job recommendations with compatibility scores
        $courseRecommendations = [];
        $jobRecommendations = [];
        
        if (Auth::check()) {
            // Get top 6 course recommendations with compatibility scores
            $courseRecommendations = Course::with('recommendedUsers')
                ->whereHas('recommendedUsers', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get()
                ->map(function($course) {
                    $pivot = $course->recommendedUsers->where('id', Auth::id())->first()->pivot ?? null;
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'provider' => $course->provider,
                        'url' => $course->url,
                        'compatibility_score' => $pivot ? $pivot->compatibility_score : 0,
                        'compatibility_explanation' => $pivot ? $pivot->compatibility_explanation : ''
                    ];
                })
                ->sortByDesc('compatibility_score')
                ->take(6)
                ->values();
                
            // Get top 6 job recommendations with compatibility scores
            $jobRecommendations = Job::with('recommendedUsers')
                ->whereHas('recommendedUsers', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get()
                ->map(function($job) {
                    $pivot = $job->recommendedUsers->where('id', Auth::id())->first()->pivot ?? null;
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'description' => $job->description,
                        'company' => $job->company,
                        'location' => $job->location,
                        'salary_range' => $job->salary_range,
                        'url' => $job->url,
                        'compatibility_score' => $pivot ? $pivot->compatibility_score : 0,
                        'compatibility_explanation' => $pivot ? $pivot->compatibility_explanation : ''
                    ];
                })
                ->sortByDesc('compatibility_score')
                ->take(6)
                ->values();
        }

        return view('pathfinder.mbti-results', compact(
            'mbtiType',
            'mbtiScores',
            'mbtiDescription',
            'careerRecommendations',
            'learningStyle',
            'personalityType',
            'courseRecommendations',
            'jobRecommendations'
        ));
    }

    /**
     * Get MBTI type description
     *
     * @param string $mbtiType
     * @return string
     */
    private function getMbtiDescription($mbtiType)
    {
        $descriptions = [
            'ISTJ' => 'Quiet, serious, earn success by thoroughness and dependability. Practical, matter-of-fact, realistic, and responsible. Decide logically what should be done and work toward it steadily, regardless of distractions. Take pleasure in making everything orderly and organized - their work, their home, their life. Value traditions and loyalty.',
            'ISFJ' => 'Quiet, friendly, responsible, and conscientious. Committed and steady in meeting their obligations. Thorough, painstaking, and accurate. Loyal, considerate, notice and remember specifics about people who are important to them, concerned with how others feel. Strive to create an orderly and harmonious environment at work and at home.',
            'INFJ' => 'Seek meaning and connection in ideas, relationships, and material possessions. Want to understand what motivates people and are insightful about others. Conscientious and committed to their firm values. Develop a clear vision about how best to serve the common good. Organized and decisive in implementing their vision.',
            'INTJ' => 'Have original minds and great drive for implementing their ideas and achieving their goals. Quickly see patterns in external events and develop long-range explanatory perspectives. When committed, organize a job and carry it through. Skeptical and independent, have high standards of competence and performance - for themselves and others.',
            'ISTP' => 'Tolerant and flexible, quiet observers until a problem appears, then act quickly to find workable solutions. Analyze what makes things work and readily get through large amounts of data to isolate the core of practical problems. Interested in cause and effect, organize facts using logical principles, value efficiency.',
            'ISFP' => 'Quiet, friendly, sensitive, and kind. Enjoy the present moment, what\'s going on around them. Like to have their own space and to work within their own time frame. Loyal and committed to their values and to people who are important to them. Dislike disagreements and conflicts, do not force their opinions or values on others.',
            'INFP' => 'Idealistic, loyal to their values and to people who are important to them. Want an external life that is congruent with their values. Curious, quick to see possibilities, can be catalysts for implementing ideas. Seek to understand people and to help them fulfill their potential. Adaptable, flexible, and accepting unless a value is threatened.',
            'INTP' => 'Seek to develop logical explanations for everything that interests them. Theoretical and abstract, interested more in ideas than in social interaction. Quiet, contained, flexible, and adaptable. Have unusual ability to focus in depth to solve problems in their area of interest. Skeptical, sometimes critical, always analytical.',
            'ESTP' => 'Flexible and tolerant, they take a pragmatic approach focused on immediate results. Theories and conceptual explanations bore them - they want to act energetically to solve the problem. Focus on the here-and-now, spontaneous, enjoy each moment that they can be active with others. Enjoy material comforts and style. Learn best through doing.',
            'ESFP' => 'Outgoing, friendly, and accepting. Exuberant lovers of life, people, and material comforts. Enjoy working with others to make things happen. Bring common sense and a realistic approach to their work, and make work fun. Flexible and spontaneous, adapt readily to new people and environments.',
            'ENFP' => 'Warmly enthusiastic and imaginative. See life as full of possibilities. Make connections between events and information very quickly, and confidently proceed based on the patterns they see. Want a lot of affirmation from others, and readily give appreciation and support. Spontaneous and flexible, often rely on their ability to improvise and their verbal fluency.',
            'ENTP' => 'Quick, ingenious, stimulating, alert, and outspoken. Resourceful in solving new and challenging problems. Adept at generating conceptual possibilities and then analyzing them strategically. Good at reading other people. Bored by routine, will seldom do the same thing the same way, apt to turn to one new interest after another.',
            'ESTJ' => 'Practical, realistic, matter-of-fact. Decisive, quickly move to implement decisions. Organize projects and people to get things done, focus on getting results in the most efficient way possible. Take care of routine details. Have a clear set of logical standards, systematically follow them and want others to also. Forceful in implementing their plans.',
            'ESFJ' => 'Warmhearted, conscientious, and cooperative. Want harmony in their environment, work with determination to establish it. Like to work with others to complete tasks accurately and on time. Loyal, follow through even in small matters. Notice what others need in their day-by-day lives and try to provide it. Want to be appreciated for who they are and for what they contribute.',
            'ENFJ' => 'Warm, empathetic, responsive, and responsible. Highly attuned to the emotions, needs, and motivations of others. Find potential in everyone, want to help others fulfill their potential. May act as catalysts for individual and group growth. Loyal, responsive to praise and criticism. Sociable, facilitate others in a group, and provide inspiring leadership.',
            'ENTJ' => 'Frank, decisive, assume leadership readily. Quickly see illogical and inefficient procedures and policies, develop and implement comprehensive systems to solve organizational problems. Enjoy long-term planning and goal setting. Usually well informed, well read, enjoy expanding their knowledge and passing it on to others. Forceful in presenting their ideas.'
        ];

        return $descriptions[$mbtiType] ?? 'Description not available for this personality type.';
    }

    /**
     * Get career recommendations based on MBTI type
     *
     * @param string $mbtiType
     * @return array
     */
    private function getMbtiCareerRecommendations($mbtiType)
    {
        $recommendations = [
            'ISTJ' => [
                'Software Engineer',
                'Systems Analyst',
                'Database Administrator',
                'Project Manager',
                'Quality Assurance Specialist'
            ],
            'ISFJ' => [
                'Technical Support Specialist',
                'UX Researcher',
                'IT Trainer',
                'Web Content Manager',
                'Database Administrator'
            ],
            'INFJ' => [
                'UX Designer',
                'Technical Writer',
                'AI Ethics Specialist',
                'Digital Strategist',
                'Information Architecture Specialist'
            ],
            'INTJ' => [
                'Software Architect',
                'Data Scientist',
                'AI/ML Engineer',
                'IT Strategist',
                'Systems Architect'
            ],
            'ISTP' => [
                'Cybersecurity Specialist',
                'Network Engineer',
                'DevOps Engineer',
                'Systems Administrator',
                'Mobile App Developer'
            ],
            'ISFP' => [
                'UI Designer',
                'Digital Artist',
                'Web Designer',
                'Multimedia Specialist',
                'Game Designer'
            ],
            'INFP' => [
                'Content Strategist',
                'User Experience Researcher',
                'Game Writer/Narrative Designer',
                'Digital Marketing Specialist',
                'E-learning Developer'
            ],
            'INTP' => [
                'Data Scientist',
                'Algorithm Developer',
                'Research Scientist',
                'Software Developer',
                'Systems Analyst'
            ],
            'ESTP' => [
                'IT Sales Representative',
                'Technical Project Manager',
                'IT Consultant',
                'Startup Founder',
                'Digital Marketing Specialist'
            ],
            'ESFP' => [
                'Social Media Manager',
                'Customer Success Manager',
                'IT Trainer',
                'Digital Media Producer',
                'Technology Sales Representative'
            ],
            'ENFP' => [
                'Creative Director',
                'User Experience Designer',
                'Digital Marketing Manager',
                'Innovation Consultant',
                'Technology Evangelist'
            ],
            'ENTP' => [
                'Technology Entrepreneur',
                'Product Manager',
                'Business Analyst',
                'Innovation Consultant',
                'Solutions Architect'
            ],
            'ESTJ' => [
                'IT Project Manager',
                'Information Systems Manager',
                'Chief Technology Officer',
                'IT Director',
                'Business Systems Analyst'
            ],
            'ESFJ' => [
                'IT Account Manager',
                'Technical Trainer',
                'Customer Support Manager',
                'IT Recruiter',
                'Scrum Master'
            ],
            'ENFJ' => [
                'Technology Team Leader',
                'IT Training Manager',
                'User Research Lead',
                'Product Marketing Manager',
                'Community Manager'
            ],
            'ENTJ' => [
                'Chief Information Officer',
                'IT Director',
                'Technology Consultant',
                'Enterprise Architect',
                'Technology Program Manager'
            ]
        ];

        return $recommendations[$mbtiType] ?? ['Career recommendations not available for this personality type.'];
    }

    /**
     * Get learning style recommendations based on MBTI type
     *
     * @param string $mbtiType
     * @return array
     */
    private function getMbtiLearningStyle($mbtiType)
    {
        $styles = [
            // Sensing-Thinking types
            'ISTJ' => [
                'style' => 'Structured and Sequential Learning',
                'description' => 'You learn best with clear, step-by-step instructions and practical applications. You prefer organized learning environments with concrete examples and detailed explanations.',
                'recommendations' => [
                    'Use detailed checklists and schedules',
                    'Take thorough notes and organize them systematically',
                    'Practice with real-world examples and case studies',
                    'Break complex concepts into logical steps',
                    'Seek out structured courses with clear objectives'
                ]
            ],
            'ESTJ' => [
                'style' => 'Practical and Organized Learning',
                'description' => 'You learn best in structured environments with clear objectives and practical applications. You prefer logical organization and efficiency in learning.',
                'recommendations' => [
                    'Create structured study plans with deadlines',
                    'Participate in group discussions and debates',
                    'Apply concepts to real-world scenarios',
                    'Use traditional learning methods with clear metrics',
                    'Take leadership roles in group projects'
                ]
            ],
            'ISTP' => [
                'style' => 'Hands-on Technical Learning',
                'description' => 'You learn best through direct experience and hands-on problem-solving. You prefer practical applications and understanding how things work.',
                'recommendations' => [
                    'Engage in hands-on labs and experiments',
                    'Take apart and rebuild systems to understand them',
                    'Focus on practical skills with immediate application',
                    'Learn through troubleshooting real problems',
                    'Use simulations and interactive tutorials'
                ]
            ],
            'ESTP' => [
                'style' => 'Active and Experiential Learning',
                'description' => 'You learn best through active engagement and real-time problem-solving. You prefer dynamic, interactive learning environments with immediate results.',
                'recommendations' => [
                    'Participate in interactive workshops and hackathons',
                    'Learn through competitive challenges',
                    'Engage in role-playing and simulations',
                    'Seek opportunities for immediate application',
                    'Use trial and error with quick feedback loops'
                ]
            ],

            // Sensing-Feeling types
            'ISFJ' => [
                'style' => 'Supportive and Practical Learning',
                'description' => 'You learn best in harmonious environments with clear, practical applications. You prefer structured learning with personal relevance.',
                'recommendations' => [
                    'Connect learning to helping others',
                    'Use study groups with supportive peers',
                    'Follow clear, step-by-step tutorials',
                    'Relate concepts to personal experiences',
                    'Maintain organized notes and resources'
                ]
            ],
            'ESFJ' => [
                'style' => 'Collaborative and Structured Learning',
                'description' => 'You learn best in social, cooperative environments with clear guidelines. You prefer learning that has practical applications and helps others.',
                'recommendations' => [
                    'Participate in study groups and team projects',
                    'Teach concepts to others to reinforce learning',
                    'Connect learning to real-world impact',
                    'Use structured courses with clear milestones',
                    'Seek feedback and validation from instructors'
                ]
            ],
            'ISFP' => [
                'style' => 'Artistic and Hands-on Learning',
                'description' => 'You learn best through creative expression and personal experience. You prefer learning environments that allow for individual exploration and aesthetic appreciation.',
                'recommendations' => [
                    'Incorporate visual and artistic elements',
                    'Learn at your own pace in low-pressure environments',
                    'Connect concepts to personal values',
                    'Use hands-on, creative projects',
                    'Apply skills in personally meaningful ways'
                ]
            ],
            'ESFP' => [
                'style' => 'Interactive and Social Learning',
                'description' => 'You learn best through engaging, social experiences with immediate application. You prefer dynamic, fun learning environments with practical outcomes.',
                'recommendations' => [
                    'Participate in interactive, group-based learning',
                    'Use gamification and competitive elements',
                    'Learn through role-playing and simulations',
                    'Seek courses with social components',
                    'Apply concepts in creative, practical ways'
                ]
            ],

            // Intuitive-Thinking types
            'INTJ' => [
                'style' => 'Strategic and Conceptual Learning',
                'description' => 'You learn best through conceptual understanding and strategic thinking. You prefer independent study with focus on theoretical frameworks and systems thinking.',
                'recommendations' => [
                    'Focus on the big picture and underlying principles',
                    'Develop your own learning strategies and systems',
                    'Seek out challenging, complex material',
                    'Connect new concepts to existing knowledge',
                    'Engage in independent research and analysis'
                ]
            ],
            'ENTJ' => [
                'style' => 'Strategic and Efficient Learning',
                'description' => 'You learn best through organized, efficient study with clear objectives. You prefer conceptual understanding with practical applications and strategic relevance.',
                'recommendations' => [
                    'Create comprehensive learning plans with goals',
                    'Focus on concepts with strategic applications',
                    'Engage in debates and intellectual discussions',
                    'Take leadership in group learning situations',
                    'Seek mentorship from respected experts'
                ]
            ],
            'INTP' => [
                'style' => 'Analytical and Conceptual Learning',
                'description' => 'You learn best through logical analysis and theoretical understanding. You prefer independent exploration of complex systems and abstract concepts.',
                'recommendations' => [
                    'Focus on understanding underlying principles',
                    'Explore connections between different concepts',
                    'Engage in theoretical problem-solving',
                    'Question assumptions and explore alternatives',
                    'Allow time for independent analysis and reflection'
                ]
            ],
            'ENTP' => [
                'style' => 'Innovative and Conceptual Learning',
                'description' => 'You learn best through exploring possibilities and connecting diverse ideas. You prefer dynamic, intellectually stimulating environments with room for debate and innovation.',
                'recommendations' => [
                    'Engage in brainstorming and idea generation',
                    'Explore multiple perspectives and approaches',
                    'Participate in debates and intellectual discussions',
                    'Connect concepts across different domains',
                    'Experiment with new learning methods and tools'
                ]
            ],

            // Intuitive-Feeling types
            'INFJ' => [
                'style' => 'Insightful and Meaningful Learning',
                'description' => 'You learn best when material connects to values and long-term vision. You prefer quiet, focused environments with conceptual depth and personal meaning.',
                'recommendations' => [
                    'Connect learning to personal values and goals',
                    'Seek out meaningful patterns and insights',
                    'Allow time for reflection and integration',
                    'Focus on how concepts impact people',
                    'Use visualization and conceptual mapping'
                ]
            ],
            'ENFJ' => [
                'style' => 'Collaborative and Meaningful Learning',
                'description' => 'You learn best in harmonious environments focused on personal growth and helping others. You prefer learning with clear purpose and positive social impact.',
                'recommendations' => [
                    'Participate in collaborative learning communities',
                    'Connect concepts to human potential and growth',
                    'Teach and mentor others to reinforce learning',
                    'Seek learning with meaningful real-world impact',
                    'Use discussion and dialogue to explore ideas'
                ]
            ],
            'INFP' => [
                'style' => 'Reflective and Values-Based Learning',
                'description' => 'You learn best when material aligns with personal values and allows for creative expression. You prefer independent exploration guided by personal meaning.',
                'recommendations' => [
                    'Connect learning to personal values and ideals',
                    'Allow time for reflection and creative processing',
                    'Seek authentic, meaningful applications',
                    'Use journaling and personal expression',
                    'Explore concepts through metaphor and storytelling'
                ]
            ],
            'ENFP' => [
                'style' => 'Enthusiastic and Innovative Learning',
                'description' => 'You learn best through creative exploration and connecting diverse ideas. You prefer dynamic, interactive environments that encourage innovation and personal meaning.',
                'recommendations' => [
                    'Explore connections between diverse concepts',
                    'Engage in collaborative brainstorming',
                    'Use creative projects and presentations',
                    'Seek variety and novelty in learning approaches',
                    'Connect learning to personal growth and values'
                ]
            ]
        ];

        return $styles[$mbtiType] ?? [
            'style' => 'Personalized Learning',
            'description' => 'Learning style information not available for this personality type.',
            'recommendations' => ['Try different learning approaches to find what works best for you.']
        ];
    }
    
    /**
     * Store career recommendations in user_progress table
     *
     * @param int $userId
     * @param string $mbtiType
     * @param MbtiPersonalityType $personalityType
     * @return void
     */
    private function storeCareerRecommendations($userId, $mbtiType, $personalityType)
    {
        // Get career recommendations for this MBTI type
        $careerRecommendations = $this->getMbtiCareerRecommendations($mbtiType);
        
        // Store MBTI results with career recommendations in user_progress
        UserProgress::create([
            'user_id' => $userId,
            'feature_type' => 'mbti_assessment',
            'assessment_type' => 'personality',
            'questionnaire_answers' => null, // MBTI responses are stored in mbti_test_sessions
            'recommendation' => $mbtiType,
            'analysis_result' => [
                'mbti_type' => $mbtiType,
                'personality_description' => $personalityType->description,
                'career_recommendations' => $careerRecommendations,
                'recommended_at' => now()->toISOString()
            ],
            'completed' => true
        ]);
        
        // Generate course and job recommendations with compatibility scores
        $this->generateAndStoreRecommendations($userId, $mbtiType);
    }
    
    /**
     * Generate and store course/job recommendations with compatibility scores
     *
     * @param int $userId
     * @param string $mbtiType
     * @return void
     */
    private function generateAndStoreRecommendations($userId, $mbtiType)
    {
        // Get all active courses and calculate compatibility
        $courses = Course::active()->get();
        foreach ($courses as $course) {
            $compatibilityScore = $this->calculateCourseCompatibility($course, $mbtiType);
            
            if ($compatibilityScore >= 60) { // Only store high compatibility recommendations
                $course->recommendedUsers()->attach($userId, [
                    'compatibility_score' => $compatibilityScore,
                    'recommended_at' => now()
                ]);
            }
        }
        
        // Get all active jobs and calculate compatibility
        $jobs = Job::active()->get();
        foreach ($jobs as $job) {
            $compatibilityScore = $this->calculateJobCompatibility($job, $mbtiType);
            
            if ($compatibilityScore >= 60) { // Only store high compatibility recommendations
                $job->recommendedUsers()->attach($userId, [
                    'compatibility_score' => $compatibilityScore,
                    'recommended_at' => now()
                ]);
            }
        }
    }
    
    /**
     * Calculate compatibility score between a course and MBTI type
     *
     * @param Course $course
     * @param string $mbtiType
     * @return int
     */
    private function calculateCourseCompatibility($course, $mbtiType)
    {
        if (!$course->mbti_compatibility || !is_array($course->mbti_compatibility)) {
            return 50; // Default neutral score
        }
        
        return $course->mbti_compatibility[$mbtiType] ?? 50;
    }
    
    /**
     * Calculate compatibility score between a job and MBTI type
     *
     * @param Job $job
     * @param string $mbtiType
     * @return int
     */
    private function calculateJobCompatibility($job, $mbtiType)
    {
        if (!$job->mbti_compatibility || !is_array($job->mbti_compatibility)) {
            return 50; // Default neutral score
        }
        
        return $job->mbti_compatibility[$mbtiType] ?? 50;
    }
}
