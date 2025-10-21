<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questionnaire;
use App\Models\Question;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionnaires = [
            [
                'title' => 'Programming & Software Development Assessment',
                'description' => 'Discover your aptitude for programming and software development careers',
                'course_category' => 'programming',
                'target_audience' => 'beginners to intermediate',
                'estimated_duration_minutes' => 8,
                'skills_assessed' => ['logical_thinking', 'problem_solving', 'technical_aptitude', 'attention_to_detail'],
                'career_paths' => ['Software Developer', 'Web Developer', 'Mobile App Developer', 'DevOps Engineer'],
                'questions' => [
                    [
                        'question_text' => 'How do you approach solving complex problems?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'break_down' => 'Break them down into smaller, manageable parts',
                            'research' => 'Research similar problems and solutions online',
                            'trial_error' => 'Try different approaches through trial and error',
                            'ask_help' => 'Ask for help from others immediately'
                        ],
                        'scoring_weights' => ['break_down' => 5, 'research' => 4, 'trial_error' => 3, 'ask_help' => 2],
                        'skill_category' => 'problem_solving'
                    ],
                    [
                        'question_text' => 'How comfortable are you with learning new technologies?',
                        'question_type' => 'scale',
                        'skill_category' => 'technical_aptitude'
                    ],
                    [
                        'question_text' => 'Do you enjoy working with logical sequences and patterns?',
                        'question_type' => 'scale',
                        'skill_category' => 'logical_thinking'
                    ],
                    [
                        'question_text' => 'Which programming concept interests you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'web_dev' => 'Building websites and web applications',
                            'mobile_dev' => 'Creating mobile apps',
                            'data_analysis' => 'Analyzing data and creating insights',
                            'game_dev' => 'Game development and interactive media'
                        ],
                        'scoring_weights' => ['web_dev' => 4, 'mobile_dev' => 4, 'data_analysis' => 3, 'game_dev' => 3],
                        'skill_category' => 'technical_aptitude'
                    ],
                    [
                        'question_text' => 'How do you handle debugging and finding errors in your work?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'systematic' => 'Systematically check each part step by step',
                            'intuitive' => 'Use intuition to guess where the problem might be',
                            'frustrated' => 'Get frustrated and take breaks frequently',
                            'enjoy_challenge' => 'Enjoy it as a puzzle-solving challenge'
                        ],
                        'scoring_weights' => ['systematic' => 5, 'enjoy_challenge' => 5, 'intuitive' => 3, 'frustrated' => 1],
                        'skill_category' => 'problem_solving'
                    ],
                    [
                        'question_text' => 'How important is attention to detail in your work style?',
                        'question_type' => 'scale',
                        'skill_category' => 'attention_to_detail'
                    ]
                ]
            ],
            [
                'title' => 'Business & Management Assessment',
                'description' => 'Evaluate your potential for business leadership and management roles',
                'course_category' => 'business',
                'target_audience' => 'professionals and aspiring managers',
                'estimated_duration_minutes' => 10,
                'skills_assessed' => ['leadership', 'communication', 'strategic_thinking', 'decision_making'],
                'career_paths' => ['Business Manager', 'Project Manager', 'Entrepreneur', 'Business Analyst'],
                'questions' => [
                    [
                        'question_text' => 'In group projects, what role do you naturally take?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'leader' => 'The leader who organizes and delegates tasks',
                            'contributor' => 'An active contributor with good ideas',
                            'supporter' => 'A supportive team member who helps others',
                            'specialist' => 'The specialist who focuses on specific tasks'
                        ],
                        'scoring_weights' => ['leader' => 5, 'contributor' => 4, 'supporter' => 3, 'specialist' => 2],
                        'skill_category' => 'leadership'
                    ],
                    [
                        'question_text' => 'How comfortable are you with public speaking and presentations?',
                        'question_type' => 'scale',
                        'skill_category' => 'communication'
                    ],
                    [
                        'question_text' => 'When making important decisions, you prefer to:',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'data_driven' => 'Analyze all available data and metrics',
                            'intuition' => 'Trust your gut feeling and experience',
                            'consensus' => 'Seek input from team members and build consensus',
                            'quick_decisive' => 'Make quick decisions and adjust as needed'
                        ],
                        'scoring_weights' => ['data_driven' => 5, 'consensus' => 4, 'quick_decisive' => 3, 'intuition' => 3],
                        'skill_category' => 'decision_making'
                    ],
                    [
                        'question_text' => 'How do you approach long-term planning?',
                        'question_type' => 'scale',
                        'skill_category' => 'strategic_thinking'
                    ],
                    [
                        'question_text' => 'What motivates you most in a work environment?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'achievement' => 'Achieving targets and measurable results',
                            'innovation' => 'Creating innovative solutions and processes',
                            'team_success' => 'Helping team members succeed and grow',
                            'recognition' => 'Receiving recognition and advancement opportunities'
                        ],
                        'scoring_weights' => ['achievement' => 4, 'innovation' => 4, 'team_success' => 5, 'recognition' => 3],
                        'skill_category' => 'leadership'
                    ],
                    [
                        'question_text' => 'How effectively do you communicate complex ideas to others?',
                        'question_type' => 'scale',
                        'skill_category' => 'communication'
                    ]
                ]
            ],
            [
                'title' => 'Data Science & Analytics Assessment',
                'description' => 'Assess your aptitude for data analysis and machine learning careers',
                'course_category' => 'data_science',
                'target_audience' => 'analytical minds and math enthusiasts',
                'estimated_duration_minutes' => 9,
                'skills_assessed' => ['analytical_thinking', 'mathematical_aptitude', 'pattern_recognition', 'research_skills'],
                'career_paths' => ['Data Scientist', 'Data Analyst', 'Machine Learning Engineer', 'Business Intelligence Analyst'],
                'questions' => [
                    [
                        'question_text' => 'How do you feel about working with large datasets and numbers?',
                        'question_type' => 'scale',
                        'skill_category' => 'analytical_thinking'
                    ],
                    [
                        'question_text' => 'Which type of analysis interests you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'predictive' => 'Predicting future trends and outcomes',
                            'descriptive' => 'Understanding what happened and why',
                            'prescriptive' => 'Recommending actions based on data',
                            'exploratory' => 'Discovering hidden patterns and insights'
                        ],
                        'scoring_weights' => ['predictive' => 5, 'exploratory' => 5, 'prescriptive' => 4, 'descriptive' => 3],
                        'skill_category' => 'analytical_thinking'
                    ],
                    [
                        'question_text' => 'How comfortable are you with statistics and mathematics?',
                        'question_type' => 'scale',
                        'skill_category' => 'mathematical_aptitude'
                    ],
                    [
                        'question_text' => 'When presented with data, what do you do first?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'visualize' => 'Create charts and visualizations to understand it',
                            'clean' => 'Clean and organize the data for analysis',
                            'explore' => 'Explore relationships and correlations',
                            'hypothesis' => 'Form hypotheses about what the data might show'
                        ],
                        'scoring_weights' => ['clean' => 5, 'explore' => 4, 'visualize' => 4, 'hypothesis' => 3],
                        'skill_category' => 'analytical_thinking'
                    ],
                    [
                        'question_text' => 'How good are you at identifying patterns in complex information?',
                        'question_type' => 'scale',
                        'skill_category' => 'pattern_recognition'
                    ],
                    [
                        'question_text' => 'How do you approach learning new analytical tools and techniques?',
                        'question_type' => 'scale',
                        'skill_category' => 'research_skills'
                    ]
                ]
            ],
            [
                'title' => 'Digital Marketing Assessment',
                'description' => 'Discover your potential in digital marketing and social media careers',
                'course_category' => 'marketing',
                'target_audience' => 'creative communicators and trend followers',
                'estimated_duration_minutes' => 8,
                'skills_assessed' => ['creativity', 'communication', 'trend_awareness', 'analytical_marketing'],
                'career_paths' => ['Digital Marketer', 'Social Media Manager', 'Content Creator', 'SEO Specialist'],
                'questions' => [
                    [
                        'question_text' => 'How do you stay updated with current trends and news?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'social_media' => 'Through social media platforms and influencers',
                            'news_sites' => 'Reading news websites and industry publications',
                            'networking' => 'Talking to people and networking events',
                            'multiple_sources' => 'Using multiple sources and cross-referencing'
                        ],
                        'scoring_weights' => ['multiple_sources' => 5, 'social_media' => 4, 'news_sites' => 3, 'networking' => 3],
                        'skill_category' => 'trend_awareness'
                    ],
                    [
                        'question_text' => 'How creative do you consider yourself in generating new ideas?',
                        'question_type' => 'scale',
                        'skill_category' => 'creativity'
                    ],
                    [
                        'question_text' => 'Which aspect of marketing appeals to you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'content_creation' => 'Creating engaging content and campaigns',
                            'data_analysis' => 'Analyzing campaign performance and metrics',
                            'strategy' => 'Developing marketing strategies and plans',
                            'community' => 'Building communities and engaging with audiences'
                        ],
                        'scoring_weights' => ['content_creation' => 4, 'strategy' => 4, 'community' => 4, 'data_analysis' => 3],
                        'skill_category' => 'creativity'
                    ],
                    [
                        'question_text' => 'How comfortable are you with analyzing marketing metrics and ROI?',
                        'question_type' => 'scale',
                        'skill_category' => 'analytical_marketing'
                    ],
                    [
                        'question_text' => 'How well do you adapt your communication style for different audiences?',
                        'question_type' => 'scale',
                        'skill_category' => 'communication'
                    ],
                    [
                        'question_text' => 'How quickly do you notice and adapt to new social media trends?',
                        'question_type' => 'scale',
                        'skill_category' => 'trend_awareness'
                    ]
                ]
            ],
            [
                'title' => 'UX/UI Design Assessment',
                'description' => 'Evaluate your potential for user experience and interface design careers',
                'course_category' => 'design',
                'target_audience' => 'creative problem solvers and visual thinkers',
                'estimated_duration_minutes' => 9,
                'skills_assessed' => ['visual_design', 'user_empathy', 'problem_solving', 'attention_to_detail'],
                'career_paths' => ['UX Designer', 'UI Designer', 'Product Designer', 'Design Researcher'],
                'questions' => [
                    [
                        'question_text' => 'When using a poorly designed app or website, what bothers you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'confusing_navigation' => 'Confusing navigation and layout',
                            'ugly_design' => 'Unattractive visual design and colors',
                            'slow_performance' => 'Slow loading times and performance issues',
                            'missing_features' => 'Missing features or functionality'
                        ],
                        'scoring_weights' => ['confusing_navigation' => 5, 'ugly_design' => 4, 'missing_features' => 3, 'slow_performance' => 2],
                        'skill_category' => 'user_empathy'
                    ],
                    [
                        'question_text' => 'How strong is your visual and aesthetic sense?',
                        'question_type' => 'scale',
                        'skill_category' => 'visual_design'
                    ],
                    [
                        'question_text' => 'How do you approach understanding user needs?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'observe' => 'Observe how people actually use products',
                            'ask_directly' => 'Ask users directly about their preferences',
                            'research' => 'Research industry best practices and standards',
                            'test_iterate' => 'Create prototypes and test with users'
                        ],
                        'scoring_weights' => ['test_iterate' => 5, 'observe' => 4, 'ask_directly' => 3, 'research' => 3],
                        'skill_category' => 'user_empathy'
                    ],
                    [
                        'question_text' => 'How detail-oriented are you when it comes to visual elements?',
                        'question_type' => 'scale',
                        'skill_category' => 'attention_to_detail'
                    ],
                    [
                        'question_text' => 'Which design challenge excites you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'complex_workflows' => 'Simplifying complex workflows and processes',
                            'visual_identity' => 'Creating beautiful visual identities and interfaces',
                            'user_research' => 'Understanding user behavior and psychology',
                            'accessibility' => 'Making designs accessible to all users'
                        ],
                        'scoring_weights' => ['complex_workflows' => 5, 'user_research' => 4, 'accessibility' => 4, 'visual_identity' => 3],
                        'skill_category' => 'problem_solving'
                    ],
                    [
                        'question_text' => 'How well do you balance creativity with practical constraints?',
                        'question_type' => 'scale',
                        'skill_category' => 'problem_solving'
                    ]
                ]
            ],
            [
                'title' => 'Cybersecurity Assessment',
                'description' => 'Assess your aptitude for cybersecurity and information security careers',
                'course_category' => 'cybersecurity',
                'target_audience' => 'security-minded and detail-oriented individuals',
                'estimated_duration_minutes' => 10,
                'skills_assessed' => ['security_mindset', 'technical_aptitude', 'attention_to_detail', 'continuous_learning'],
                'career_paths' => ['Cybersecurity Analyst', 'Ethical Hacker', 'Security Engineer', 'Compliance Officer'],
                'questions' => [
                    [
                        'question_text' => 'How do you approach online security in your personal life?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'very_careful' => 'Very careful with strong passwords and 2FA everywhere',
                            'somewhat_careful' => 'Somewhat careful with basic security measures',
                            'minimal_effort' => 'Minimal effort, use simple passwords',
                            'not_concerned' => 'Not very concerned about online security'
                        ],
                        'scoring_weights' => ['very_careful' => 5, 'somewhat_careful' => 3, 'minimal_effort' => 1, 'not_concerned' => 0],
                        'skill_category' => 'security_mindset'
                    ],
                    [
                        'question_text' => 'How interested are you in understanding how systems can be compromised?',
                        'question_type' => 'scale',
                        'skill_category' => 'security_mindset'
                    ],
                    [
                        'question_text' => 'Which cybersecurity area interests you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'ethical_hacking' => 'Ethical hacking and penetration testing',
                            'incident_response' => 'Incident response and forensics',
                            'compliance' => 'Compliance and risk management',
                            'security_architecture' => 'Security architecture and system design'
                        ],
                        'scoring_weights' => ['ethical_hacking' => 4, 'incident_response' => 4, 'security_architecture' => 4, 'compliance' => 3],
                        'skill_category' => 'technical_aptitude'
                    ],
                    [
                        'question_text' => 'How comfortable are you with constantly learning new technologies?',
                        'question_type' => 'scale',
                        'skill_category' => 'continuous_learning'
                    ],
                    [
                        'question_text' => 'How do you handle high-pressure situations?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'calm_systematic' => 'Stay calm and work systematically',
                            'focused_determined' => 'Become more focused and determined',
                            'stressed_but_manage' => 'Feel stressed but manage to work through it',
                            'overwhelmed' => 'Often feel overwhelmed and need help'
                        ],
                        'scoring_weights' => ['calm_systematic' => 5, 'focused_determined' => 4, 'stressed_but_manage' => 2, 'overwhelmed' => 1],
                        'skill_category' => 'security_mindset'
                    ],
                    [
                        'question_text' => 'How meticulous are you in following procedures and documentation?',
                        'question_type' => 'scale',
                        'skill_category' => 'attention_to_detail'
                    ]
                ]
            ],
            [
                'title' => 'Healthcare & Medicine Assessment',
                'description' => 'Evaluate your potential for healthcare and medical careers',
                'course_category' => 'healthcare',
                'target_audience' => 'caring individuals interested in helping others',
                'estimated_duration_minutes' => 11,
                'skills_assessed' => ['empathy', 'attention_to_detail', 'stress_management', 'scientific_aptitude'],
                'career_paths' => ['Nurse', 'Medical Technician', 'Healthcare Administrator', 'Medical Researcher'],
                'questions' => [
                    [
                        'question_text' => 'What motivates you most about healthcare work?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'helping_people' => 'Directly helping people feel better and recover',
                            'scientific_challenge' => 'The scientific challenge of diagnosis and treatment',
                            'making_difference' => 'Making a meaningful difference in people\'s lives',
                            'job_security' => 'Job security and career stability'
                        ],
                        'scoring_weights' => ['helping_people' => 5, 'making_difference' => 5, 'scientific_challenge' => 4, 'job_security' => 2],
                        'skill_category' => 'empathy'
                    ],
                    [
                        'question_text' => 'How well do you handle seeing people in pain or distress?',
                        'question_type' => 'scale',
                        'skill_category' => 'empathy'
                    ],
                    [
                        'question_text' => 'How do you perform under time pressure and stress?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'thrive' => 'I thrive and perform better under pressure',
                            'maintain_quality' => 'I maintain quality while working efficiently',
                            'manage_okay' => 'I manage okay but prefer less pressure',
                            'struggle' => 'I struggle and make more mistakes'
                        ],
                        'scoring_weights' => ['thrive' => 5, 'maintain_quality' => 4, 'manage_okay' => 2, 'struggle' => 1],
                        'skill_category' => 'stress_management'
                    ],
                    [
                        'question_text' => 'How interested are you in biological sciences and human anatomy?',
                        'question_type' => 'scale',
                        'skill_category' => 'scientific_aptitude'
                    ],
                    [
                        'question_text' => 'How precise are you in following medical procedures and protocols?',
                        'question_type' => 'scale',
                        'skill_category' => 'attention_to_detail'
                    ],
                    [
                        'question_text' => 'Which healthcare setting appeals to you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'hospital' => 'Fast-paced hospital environment',
                            'clinic' => 'Outpatient clinic with regular patients',
                            'research' => 'Research laboratory or academic setting',
                            'community' => 'Community health and preventive care'
                        ],
                        'scoring_weights' => ['hospital' => 4, 'clinic' => 4, 'community' => 4, 'research' => 3],
                        'skill_category' => 'empathy'
                    ]
                ]
            ],
            [
                'title' => 'Engineering Assessment',
                'description' => 'Assess your aptitude for engineering and technical problem-solving careers',
                'course_category' => 'engineering',
                'target_audience' => 'analytical problem solvers and technical minds',
                'estimated_duration_minutes' => 10,
                'skills_assessed' => ['mathematical_aptitude', 'problem_solving', 'technical_thinking', 'attention_to_detail'],
                'career_paths' => ['Mechanical Engineer', 'Electrical Engineer', 'Civil Engineer', 'Software Engineer'],
                'questions' => [
                    [
                        'question_text' => 'How do you approach complex technical problems?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'systematic_analysis' => 'Systematic analysis and breaking down into components',
                            'research_solutions' => 'Research existing solutions and adapt them',
                            'trial_experimentation' => 'Trial and experimentation with different approaches',
                            'collaborate_team' => 'Collaborate with team members to brainstorm'
                        ],
                        'scoring_weights' => ['systematic_analysis' => 5, 'research_solutions' => 4, 'trial_experimentation' => 3, 'collaborate_team' => 3],
                        'skill_category' => 'problem_solving'
                    ],
                    [
                        'question_text' => 'How comfortable are you with advanced mathematics and physics?',
                        'question_type' => 'scale',
                        'skill_category' => 'mathematical_aptitude'
                    ],
                    [
                        'question_text' => 'Which engineering discipline interests you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'mechanical' => 'Mechanical systems and machinery',
                            'electrical' => 'Electrical systems and electronics',
                            'civil' => 'Infrastructure and construction projects',
                            'software' => 'Software systems and programming'
                        ],
                        'scoring_weights' => ['mechanical' => 4, 'electrical' => 4, 'civil' => 4, 'software' => 4],
                        'skill_category' => 'technical_thinking'
                    ],
                    [
                        'question_text' => 'How important is precision and accuracy in your work?',
                        'question_type' => 'scale',
                        'skill_category' => 'attention_to_detail'
                    ],
                    [
                        'question_text' => 'How do you feel about working with technical specifications and blueprints?',
                        'question_type' => 'scale',
                        'skill_category' => 'technical_thinking'
                    ],
                    [
                        'question_text' => 'What excites you most about engineering work?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'innovation' => 'Creating innovative solutions to real problems',
                            'optimization' => 'Optimizing systems for better performance',
                            'building' => 'Building and creating tangible products',
                            'analysis' => 'Analyzing and understanding how things work'
                        ],
                        'scoring_weights' => ['innovation' => 5, 'optimization' => 4, 'building' => 4, 'analysis' => 4],
                        'skill_category' => 'technical_thinking'
                    ]
                ]
            ],
            [
                'title' => 'Creative Arts & Media Assessment',
                'description' => 'Discover your potential in creative arts, media, and content creation careers',
                'course_category' => 'creative_arts',
                'target_audience' => 'creative individuals and artistic minds',
                'estimated_duration_minutes' => 9,
                'skills_assessed' => ['creativity', 'artistic_vision', 'storytelling', 'technical_creativity'],
                'career_paths' => ['Graphic Designer', 'Video Editor', 'Content Creator', 'Art Director'],
                'questions' => [
                    [
                        'question_text' => 'Which creative medium appeals to you most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'visual_design' => 'Visual design and graphics',
                            'video_animation' => 'Video production and animation',
                            'writing_content' => 'Writing and content creation',
                            'photography' => 'Photography and visual storytelling'
                        ],
                        'scoring_weights' => ['visual_design' => 4, 'video_animation' => 4, 'writing_content' => 4, 'photography' => 4],
                        'skill_category' => 'artistic_vision'
                    ],
                    [
                        'question_text' => 'How original and innovative do you consider your creative ideas?',
                        'question_type' => 'scale',
                        'skill_category' => 'creativity'
                    ],
                    [
                        'question_text' => 'How do you approach creative projects?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'inspiration_driven' => 'Wait for inspiration and creative bursts',
                            'systematic_process' => 'Follow a systematic creative process',
                            'research_trends' => 'Research trends and build upon them',
                            'collaborate_feedback' => 'Collaborate and iterate based on feedback'
                        ],
                        'scoring_weights' => ['systematic_process' => 5, 'collaborate_feedback' => 4, 'research_trends' => 3, 'inspiration_driven' => 3],
                        'skill_category' => 'creativity'
                    ],
                    [
                        'question_text' => 'How skilled are you at telling compelling stories through your work?',
                        'question_type' => 'scale',
                        'skill_category' => 'storytelling'
                    ],
                    [
                        'question_text' => 'How comfortable are you learning creative software and tools?',
                        'question_type' => 'scale',
                        'skill_category' => 'technical_creativity'
                    ],
                    [
                        'question_text' => 'What motivates your creative work most?',
                        'question_type' => 'multiple_choice',
                        'options' => [
                            'self_expression' => 'Personal self-expression and artistic vision',
                            'audience_impact' => 'Creating impact and emotional connection with audience',
                            'problem_solving' => 'Solving visual or communication problems',
                            'commercial_success' => 'Commercial success and client satisfaction'
                        ],
                        'scoring_weights' => ['audience_impact' => 5, 'problem_solving' => 4, 'self_expression' => 4, 'commercial_success' => 3],
                        'skill_category' => 'artistic_vision'
                    ]
                ]
            ]
        ];

        foreach ($questionnaires as $index => $questionnaireData) {
            $questionnaire = Questionnaire::create([
                'title' => $questionnaireData['title'],
                'description' => $questionnaireData['description'],
                'course_category' => $questionnaireData['course_category'],
                'target_audience' => $questionnaireData['target_audience'],
                'estimated_duration_minutes' => $questionnaireData['estimated_duration_minutes'],
                'skills_assessed' => $questionnaireData['skills_assessed'],
                'career_paths' => $questionnaireData['career_paths'],
                'is_active' => true,
                'sort_order' => $index + 1
            ]);

            foreach ($questionnaireData['questions'] as $questionIndex => $questionData) {
                Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'options' => $questionData['options'] ?? null,
                    'scoring_weights' => $questionData['scoring_weights'] ?? null,
                    'skill_category' => $questionData['skill_category'],
                    'order' => $questionIndex + 1,
                    'is_required' => true
                ]);
            }
        }
    }
}