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
        // Mock recommendation based on answers
        $recommendations = [
            'course' => [
                'Web Development Bootcamp',
                'Data Science Fundamentals',
                'Digital Marketing Course',
                'UI/UX Design Program',
                'Cybersecurity Certification'
            ],
            'job' => [
                'Frontend Developer',
                'Data Analyst',
                'Digital Marketing Specialist',
                'UX Designer',
                'Cybersecurity Analyst'
            ]
        ];
        
        return $recommendations[$type][array_rand($recommendations[$type])];
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
