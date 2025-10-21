<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionnaireResponse;
use App\Models\Course;
use App\Models\User;

class QuestionnaireController extends Controller
{
    /**
     * Get all active questionnaires
     */
    public function index(): JsonResponse
    {
        try {
            $questionnaires = Questionnaire::active()
                ->with(['questions' => function ($query) {
                    $query->ordered();
                }])
                ->ordered()
                ->get()
                ->map(function ($questionnaire) {
                    return [
                        'id' => $questionnaire->id,
                        'title' => $questionnaire->title,
                        'description' => $questionnaire->description,
                        'course_category' => $questionnaire->course_category,
                        'target_audience' => $questionnaire->target_audience,
                        'estimated_duration_minutes' => $questionnaire->estimated_duration_minutes,
                        'skills_assessed' => $questionnaire->skills_assessed,
                        'career_paths' => $questionnaire->career_paths,
                        'total_questions' => $questionnaire->total_questions,
                        'average_completion_time' => $questionnaire->average_completion_time
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $questionnaires
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching questionnaires: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch questionnaires'
            ], 500);
        }
    }

    /**
     * Get a specific questionnaire with its questions
     */
    public function show($id): JsonResponse
    {
        try {
            $questionnaire = Questionnaire::active()
                ->with(['questions' => function ($query) {
                    $query->ordered();
                }])
                ->findOrFail($id);

            $questions = $questionnaire->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'options' => $question->formatted_options,
                    'skill_category' => $question->skill_category,
                    'order' => $question->order,
                    'is_required' => $question->is_required,
                    'help_text' => $question->help_text
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'questionnaire' => [
                        'id' => $questionnaire->id,
                        'title' => $questionnaire->title,
                        'description' => $questionnaire->description,
                        'course_category' => $questionnaire->course_category,
                        'target_audience' => $questionnaire->target_audience,
                        'estimated_duration_minutes' => $questionnaire->estimated_duration_minutes,
                        'skills_assessed' => $questionnaire->skills_assessed,
                        'career_paths' => $questionnaire->career_paths
                    ],
                    'questions' => $questions
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching questionnaire: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Questionnaire not found'
            ], 404);
        }
    }

    /**
     * Submit questionnaire responses and get course recommendations
     */
    public function submitResponse(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string',
                'responses' => 'required|array',
                'selected_category' => 'required|string'
            ]);

            $user = Auth::user();
            $responses = $validated['responses'];
            $sessionId = $validated['session_id'];
            $selectedCategory = $validated['selected_category'];

            // Calculate scores based on responses and selected category
            $skillScores = $this->calculateCategoryBasedSkillScores($responses, $selectedCategory);

            // Get course recommendations based on category and scores
            $recommendedCourses = $this->getCategoryRecommendedCourses($selectedCategory, $skillScores);

            // Save response to database
            $questionnaireResponse = QuestionnaireResponse::create([
                'user_id' => $user ? $user->id : null,
                'questionnaire_id' => 1, // Default hardcoded questionnaire ID
                'session_id' => $sessionId,
                'responses' => $responses,
                'calculated_scores' => $skillScores,
                'recommended_courses' => $recommendedCourses,
                'completion_percentage' => 100,
                'started_at' => now(),
                'completed_at' => now()
            ]);

            // If user is logged in, also save to user_course_recommendations table
            if ($user && !empty($recommendedCourses)) {
                $this->saveUserCourseRecommendations($user, $recommendedCourses);
            }

            return response()->json([
                'success' => true,
                'skill_scores' => $skillScores,
                'recommended_courses' => $recommendedCourses,
                'response_id' => $questionnaireResponse->id,
                'session_id' => $sessionId
            ]);

        } catch (\Exception $e) {
            Log::error('Error submitting questionnaire response: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process questionnaire response'
            ], 500);
        }
    }

    /**
     * Calculate skill scores based on category-specific responses
     */
    private function calculateCategoryBasedSkillScores(array $responses, string $selectedCategory): array
    {
        $skillScores = [
            'analytical_thinking' => 0,
            'creativity' => 0,
            'leadership' => 0,
            'technical_skills' => 0,
            'communication' => 0,
            'problem_solving' => 0,
            'teamwork' => 0,
            'adaptability' => 0
        ];

        // Category-specific scoring logic
        switch ($selectedCategory) {
            case 'business_administration':
                $skillScores = $this->calculateBusinessAdministrationScores($responses);
                break;
            case 'engineering_technology':
                $skillScores = $this->calculateEngineeringTechnologyScores($responses);
                break;
            case 'health_sciences':
                $skillScores = $this->calculateHealthSciencesScores($responses);
                break;
            case 'education_social_sciences':
                $skillScores = $this->calculateEducationSocialSciencesScores($responses);
                break;
            case 'arts_creative':
                $skillScores = $this->calculateArtsCreativeScores($responses);
                break;
            case 'law_legal':
                $skillScores = $this->calculateLawLegalScores($responses);
                break;
            case 'agriculture_environmental':
                $skillScores = $this->calculateAgricultureEnvironmentalScores($responses);
                break;
            case 'communication_media':
                $skillScores = $this->calculateCommunicationMediaScores($responses);
                break;
            default:
                $skillScores = $this->calculateGenericCategoryScores($responses);
                break;
        }

        return $skillScores;
    }

    /**
     * Calculate scores for Business Administration category (16 comprehensive questions)
     */
    private function calculateBusinessAdministrationScores(array $responses): array
    {
        // Initialize course scores based on the comprehensive weight mapping from script.py
        $courseScores = [
            'Business Administration' => 0,
            'Accounting' => 0,
            'Finance' => 0,
            'Marketing' => 0,
            'Human Resource Management' => 0,
            'Operations Management' => 0,
            'International Business' => 0,
            'Entrepreneurship' => 0,
            'Business Analytics' => 0,
            'Project Management' => 0,
            'Supply Chain Management' => 0,
            'Business Law' => 0,
            'Economics' => 0,
            'Management Information Systems' => 0,
            'Organizational Behavior' => 0
        ];

        // Weight mapping for each question (from script.py)
        $questionWeights = [
            2 => [ // Mathematical calculations and numerical analysis
                'Business Administration' => 0.7, 'Accounting' => 0.9, 'Finance' => 0.9, 'Marketing' => 0.6,
                'Human Resource Management' => 0.4, 'Operations Management' => 0.8, 'International Business' => 0.6,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.9, 'Project Management' => 0.7,
                'Supply Chain Management' => 0.8, 'Business Law' => 0.5, 'Economics' => 0.9,
                'Management Information Systems' => 0.8, 'Organizational Behavior' => 0.5
            ],
            3 => [ // Financial statements and business performance metrics
                'Business Administration' => 0.8, 'Accounting' => 0.9, 'Finance' => 0.9, 'Marketing' => 0.6,
                'Human Resource Management' => 0.5, 'Operations Management' => 0.7, 'International Business' => 0.7,
                'Entrepreneurship' => 0.8, 'Business Analytics' => 0.8, 'Project Management' => 0.6,
                'Supply Chain Management' => 0.7, 'Business Law' => 0.6, 'Economics' => 0.8,
                'Management Information Systems' => 0.7, 'Organizational Behavior' => 0.4
            ],
            4 => [ // Communication skills
                'Business Administration' => 0.8, 'Accounting' => 0.6, 'Finance' => 0.7, 'Marketing' => 0.9,
                'Human Resource Management' => 0.9, 'Operations Management' => 0.7, 'International Business' => 0.9,
                'Entrepreneurship' => 0.8, 'Business Analytics' => 0.6, 'Project Management' => 0.8,
                'Supply Chain Management' => 0.7, 'Business Law' => 0.8, 'Economics' => 0.6,
                'Management Information Systems' => 0.7, 'Organizational Behavior' => 0.9
            ],
            5 => [ // Leadership and managing people
                'Business Administration' => 0.9, 'Accounting' => 0.5, 'Finance' => 0.6, 'Marketing' => 0.7,
                'Human Resource Management' => 0.9, 'Operations Management' => 0.8, 'International Business' => 0.8,
                'Entrepreneurship' => 0.9, 'Business Analytics' => 0.5, 'Project Management' => 0.9,
                'Supply Chain Management' => 0.7, 'Business Law' => 0.6, 'Economics' => 0.5,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.9
            ],
            6 => [ // Technology and business software
                'Business Administration' => 0.6, 'Accounting' => 0.8, 'Finance' => 0.7, 'Marketing' => 0.7,
                'Human Resource Management' => 0.6, 'Operations Management' => 0.8, 'International Business' => 0.6,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.9, 'Project Management' => 0.7,
                'Supply Chain Management' => 0.8, 'Business Law' => 0.5, 'Economics' => 0.6,
                'Management Information Systems' => 0.9, 'Organizational Behavior' => 0.5
            ],
            7 => [ // Innovation and entrepreneurship
                'Business Administration' => 0.7, 'Accounting' => 0.4, 'Finance' => 0.6, 'Marketing' => 0.8,
                'Human Resource Management' => 0.5, 'Operations Management' => 0.7, 'International Business' => 0.7,
                'Entrepreneurship' => 0.9, 'Business Analytics' => 0.6, 'Project Management' => 0.7,
                'Supply Chain Management' => 0.6, 'Business Law' => 0.5, 'Economics' => 0.6,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.6
            ],
            8 => [ // Accounting principles and bookkeeping
                'Business Administration' => 0.7, 'Accounting' => 0.9, 'Finance' => 0.8, 'Marketing' => 0.4,
                'Human Resource Management' => 0.5, 'Operations Management' => 0.6, 'International Business' => 0.6,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.6, 'Project Management' => 0.5,
                'Supply Chain Management' => 0.6, 'Business Law' => 0.6, 'Economics' => 0.7,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.4
            ],
            9 => [ // Consumer behavior and market trends
                'Business Administration' => 0.7, 'Accounting' => 0.4, 'Finance' => 0.5, 'Marketing' => 0.9,
                'Human Resource Management' => 0.5, 'Operations Management' => 0.6, 'International Business' => 0.8,
                'Entrepreneurship' => 0.8, 'Business Analytics' => 0.8, 'Project Management' => 0.5,
                'Supply Chain Management' => 0.6, 'Business Law' => 0.4, 'Economics' => 0.8,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.7
            ],
            10 => [ // Decision making under pressure
                'Business Administration' => 0.8, 'Accounting' => 0.6, 'Finance' => 0.8, 'Marketing' => 0.7,
                'Human Resource Management' => 0.7, 'Operations Management' => 0.8, 'International Business' => 0.8,
                'Entrepreneurship' => 0.9, 'Business Analytics' => 0.7, 'Project Management' => 0.8,
                'Supply Chain Management' => 0.8, 'Business Law' => 0.7, 'Economics' => 0.7,
                'Management Information Systems' => 0.7, 'Organizational Behavior' => 0.7
            ],
            11 => [ // Economic principles and business applications
                'Business Administration' => 0.8, 'Accounting' => 0.6, 'Finance' => 0.8, 'Marketing' => 0.7,
                'Human Resource Management' => 0.5, 'Operations Management' => 0.7, 'International Business' => 0.9,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.7, 'Project Management' => 0.6,
                'Supply Chain Management' => 0.7, 'Business Law' => 0.6, 'Economics' => 0.9,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.6
            ],
            12 => [ // Budget management and financial planning
                'Business Administration' => 0.8, 'Accounting' => 0.9, 'Finance' => 0.9, 'Marketing' => 0.6,
                'Human Resource Management' => 0.6, 'Operations Management' => 0.7, 'International Business' => 0.7,
                'Entrepreneurship' => 0.8, 'Business Analytics' => 0.7, 'Project Management' => 0.8,
                'Supply Chain Management' => 0.7, 'Business Law' => 0.5, 'Economics' => 0.8,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.5
            ],
            13 => [ // Organization and time management
                'Business Administration' => 0.8, 'Accounting' => 0.7, 'Finance' => 0.7, 'Marketing' => 0.7,
                'Human Resource Management' => 0.8, 'Operations Management' => 0.9, 'International Business' => 0.7,
                'Entrepreneurship' => 0.8, 'Business Analytics' => 0.7, 'Project Management' => 0.9,
                'Supply Chain Management' => 0.8, 'Business Law' => 0.7, 'Economics' => 0.6,
                'Management Information Systems' => 0.7, 'Organizational Behavior' => 0.8
            ],
            14 => [ // Workplace policies and employee relations
                'Business Administration' => 0.7, 'Accounting' => 0.5, 'Finance' => 0.5, 'Marketing' => 0.6,
                'Human Resource Management' => 0.9, 'Operations Management' => 0.6, 'International Business' => 0.6,
                'Entrepreneurship' => 0.6, 'Business Analytics' => 0.4, 'Project Management' => 0.6,
                'Supply Chain Management' => 0.5, 'Business Law' => 0.8, 'Economics' => 0.5,
                'Management Information Systems' => 0.5, 'Organizational Behavior' => 0.9
            ],
            15 => [ // Data analysis and business patterns
                'Business Administration' => 0.7, 'Accounting' => 0.7, 'Finance' => 0.8, 'Marketing' => 0.8,
                'Human Resource Management' => 0.6, 'Operations Management' => 0.8, 'International Business' => 0.7,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.9, 'Project Management' => 0.7,
                'Supply Chain Management' => 0.8, 'Business Law' => 0.5, 'Economics' => 0.8,
                'Management Information Systems' => 0.9, 'Organizational Behavior' => 0.6
            ],
            16 => [ // Legal and regulatory aspects
                'Business Administration' => 0.7, 'Accounting' => 0.7, 'Finance' => 0.7, 'Marketing' => 0.6,
                'Human Resource Management' => 0.7, 'Operations Management' => 0.6, 'International Business' => 0.8,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.5, 'Project Management' => 0.6,
                'Supply Chain Management' => 0.6, 'Business Law' => 0.9, 'Economics' => 0.7,
                'Management Information Systems' => 0.6, 'Organizational Behavior' => 0.6
            ],
            17 => [ // Operations management and efficiency
                'Business Administration' => 0.8, 'Accounting' => 0.6, 'Finance' => 0.6, 'Marketing' => 0.6,
                'Human Resource Management' => 0.6, 'Operations Management' => 0.9, 'International Business' => 0.7,
                'Entrepreneurship' => 0.7, 'Business Analytics' => 0.8, 'Project Management' => 0.8,
                'Supply Chain Management' => 0.9, 'Business Law' => 0.5, 'Economics' => 0.7,
                'Management Information Systems' => 0.8, 'Organizational Behavior' => 0.6
            ]
        ];

        // Calculate weighted scores for each course
        foreach ($responses as $questionIndex => $response) {
            $value = is_array($response) ? (int)($response['value'] ?? 0) : (int)$response;
            $questionId = $questionIndex + 1; // Adjust for 0-based indexing

            if (isset($questionWeights[$questionId])) {
                foreach ($questionWeights[$questionId] as $course => $weight) {
                    $courseScores[$course] += $value * $weight;
                }
            }
        }

        // Convert course scores to skill scores for compatibility with existing system
        $skillScores = [
            'analytical_thinking' => ($courseScores['Business Analytics'] + $courseScores['Economics'] + $courseScores['Accounting']) / 3,
            'creativity' => ($courseScores['Marketing'] + $courseScores['Entrepreneurship']) / 2,
            'leadership' => ($courseScores['Business Administration'] + $courseScores['Human Resource Management'] + $courseScores['Project Management']) / 3,
            'technical_skills' => ($courseScores['Management Information Systems'] + $courseScores['Business Analytics']) / 2,
            'communication' => ($courseScores['Marketing'] + $courseScores['International Business'] + $courseScores['Human Resource Management']) / 3,
            'problem_solving' => ($courseScores['Operations Management'] + $courseScores['Project Management'] + $courseScores['Entrepreneurship']) / 3,
            'teamwork' => ($courseScores['Organizational Behavior'] + $courseScores['Human Resource Management']) / 2,
            'adaptability' => ($courseScores['International Business'] + $courseScores['Entrepreneurship']) / 2
        ];

        // Normalize scores to 0-100 range
        foreach ($skillScores as $skill => $score) {
            $skillScores[$skill] = min(100, max(0, ($score / 16) * 10)); // Normalize based on 16 questions with max score 10
        }

        return $skillScores;
    }

    /**
     * Calculate scores for other categories (generic scoring)
     */
    private function calculateGenericCategoryScores(array $responses): array
    {
        $skillScores = [
            'analytical_thinking' => 0,
            'creativity' => 0,
            'leadership' => 0,
            'technical_skills' => 0,
            'communication' => 0,
            'problem_solving' => 0,
            'teamwork' => 0,
            'adaptability' => 0
        ];

        // Generic scoring for other categories
        foreach ($responses as $questionIndex => $response) {
            $value = is_array($response) ? (int)($response['value'] ?? 0) : (int)$response;

            // Distribute scores evenly across all skills for generic categories
            foreach ($skillScores as $skill => $score) {
                $skillScores[$skill] += $value * 1.25; // Base multiplier
            }
        }

        // Normalize scores to 0-100 range
        foreach ($skillScores as $skill => $score) {
            $skillScores[$skill] = min(100, max(0, $score));
        }

        return $skillScores;
    }

    // Placeholder methods for other categories (can be expanded later)
    private function calculateEngineeringTechnologyScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateHealthSciencesScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateEducationSocialSciencesScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateArtsCreativeScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateLawLegalScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateAgricultureEnvironmentalScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    private function calculateCommunicationMediaScores(array $responses): array
    {
        return $this->calculateGenericCategoryScores($responses);
    }

    /**
     * Get course recommendations based on category and skill scores
     */
    private function getCategoryRecommendedCourses(string $selectedCategory, array $skillScores): array
    {
        try {
            $query = Course::where('is_active', true);

            // Apply category filter based on selected category
            $query->where('category', $selectedCategory);

            $courses = $query->get();

            if ($courses->isEmpty()) {
                return [];
            }

            $recommendations = [];
            foreach ($courses as $course) {
                $compatibility = $this->calculateCourseCompatibility($course, $skillScores);

                if ($compatibility >= 30) { // Minimum compatibility threshold
                    $recommendations[] = [
                        'course_id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'provider' => $course->provider,
                        'category' => $course->category,
                        'level' => $course->level,
                        'url' => $course->url,
                        'price' => $course->price,
                        'duration' => $course->duration,
                        'skills_taught' => $course->skills_taught,
                        'prerequisites' => $course->prerequisites,
                        'compatibility_score' => $compatibility,
                        'recommended_reason' => $this->generateRecommendationReason($course, $skillScores)
                    ];
                }
            }

            // Sort by compatibility score (highest first)
            usort($recommendations, function($a, $b) {
                return $b['compatibility_score'] <=> $a['compatibility_score'];
            });

            // Return top 10 recommendations
            return array_slice($recommendations, 0, 10);

        } catch (\Exception $e) {
            Log::error('Error getting category course recommendations: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate skill scores based on questionnaire responses
     */
    private function calculateSkillScores(Questionnaire $questionnaire, array $responses): array
    {
        $skillScores = [];
        $skillCounts = [];

        foreach ($responses as $response) {
            $question = $questionnaire->questions->firstWhere('id', $response['question_id']);
            if (!$question) continue;

            $skillCategory = $question->skill_category;
            $answer = $response['answer'];

            // Calculate score based on question type
            $score = $question->calculateScore($answer);

            // Accumulate scores by skill category
            if (!isset($skillScores[$skillCategory])) {
                $skillScores[$skillCategory] = 0;
                $skillCounts[$skillCategory] = 0;
            }

            $skillScores[$skillCategory] += $score;
            $skillCounts[$skillCategory]++;
        }

        // Calculate average scores and normalize to 0-100 scale
        $normalizedScores = [];
        foreach ($skillScores as $skill => $totalScore) {
            $averageScore = $totalScore / $skillCounts[$skill];
            $normalizedScores[$skill] = min(100, max(0, ($averageScore / 5) * 100)); // Assuming max score is 5
        }

        // Sort by score descending
        arsort($normalizedScores);

        return $normalizedScores;
    }

    /**
     * Get recommended courses based on skill scores and category
     */
    private function getRecommendedCourses(string $category, array $skillScores): array
    {
        try {
            // Get courses that match the category or are general
            $courses = Course::where(function ($query) use ($category) {
                $query->where('category', $category)
                      ->orWhere('category', 'general')
                      ->orWhereJsonContains('skills_taught', $category);
            })
            ->where('is_active', true)
            ->get();

            if ($courses->isEmpty()) {
                // Fallback to any courses if no category match
                $courses = Course::where('is_active', true)->limit(5)->get();
            }

            $recommendations = [];

            foreach ($courses as $course) {
                $compatibilityScore = $this->calculateCourseCompatibility($course, $skillScores);

                $recommendations[] = [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'provider' => $course->provider,
                    'category' => $course->category,
                    'level' => $course->level,
                    'skills_taught' => $course->skills_taught,
                    'prerequisites' => $course->prerequisites,
                    'compatibility_score' => $compatibilityScore,
                    'url' => $course->url,
                    'duration' => $course->duration,
                    'price' => $course->price
                ];
            }

            // Sort by compatibility score and return top 5
            usort($recommendations, function ($a, $b) {
                return $b['compatibility_score'] <=> $a['compatibility_score'];
            });

            return array_slice($recommendations, 0, 5);

        } catch (\Exception $e) {
            Log::error('Error getting course recommendations: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate compatibility score between course and user skill scores
     */
    private function calculateCourseCompatibility(Course $course, array $skillScores): float
    {
        $compatibility = 0;
        $skillsCount = 0;

        // Get course skills (assuming skills_taught is a JSON array or comma-separated string)
        $courseSkills = [];
        if (is_string($course->skills_taught)) {
            $courseSkills = json_decode($course->skills_taught, true) ?? explode(',', $course->skills_taught);
        } elseif (is_array($course->skills_taught)) {
            $courseSkills = $course->skills_taught;
        }

        // Map course skills to our skill categories
        $skillMapping = [
            'analytical' => 'analytical_thinking',
            'analytical_thinking' => 'analytical_thinking',
            'creative' => 'creativity',
            'creativity' => 'creativity',
            'leadership' => 'leadership',
            'technical' => 'technical_skills',
            'technical_skills' => 'technical_skills',
            'communication' => 'communication',
            'problem_solving' => 'problem_solving',
            'teamwork' => 'teamwork',
            'adaptability' => 'adaptability',
            'management' => 'leadership',
            'innovation' => 'creativity',
            'programming' => 'technical_skills',
            'data_analysis' => 'analytical_thinking',
            'project_management' => 'leadership'
        ];

        foreach ($courseSkills as $skill) {
            $skill = trim(strtolower($skill));
            if (isset($skillMapping[$skill])) {
                $mappedSkill = $skillMapping[$skill];
                if (isset($skillScores[$mappedSkill])) {
                    $compatibility += $skillScores[$mappedSkill];
                    $skillsCount++;
                }
            }
        }

        // If no skills matched, use average of all user skills
        if ($skillsCount === 0) {
            $compatibility = array_sum($skillScores) / count($skillScores);
        } else {
            $compatibility = $compatibility / $skillsCount;
        }

        // Apply level-based adjustment
        $levelMultiplier = match($course->level) {
            'beginner' => 1.0,
            'intermediate' => 0.9,
            'advanced' => 0.8,
            default => 1.0
        };

        return round($compatibility * $levelMultiplier, 2);
    }

    /**
     * Generate recommendation reason based on course and skill scores
     */
    private function generateRecommendationReason($course, array $skillScores): string
    {
        $topSkills = arsort($skillScores);
        $topSkillNames = array_keys(array_slice($skillScores, 0, 2, true));

        $skillLabels = [
            'analytical_thinking' => 'analytical thinking',
            'creativity' => 'creativity',
            'leadership' => 'leadership',
            'technical_skills' => 'technical skills',
            'communication' => 'communication',
            'problem_solving' => 'problem solving',
            'teamwork' => 'teamwork',
            'adaptability' => 'adaptability'
        ];

        $topSkillLabels = array_map(function($skill) use ($skillLabels) {
            return $skillLabels[$skill] ?? $skill;
        }, $topSkillNames);

        return "Recommended based on your strong " . implode(' and ', $topSkillLabels) . " abilities, matching this " . $course->level . " level course.";
    }

    /**
     * Save user course recommendations to the pivot table
     */
    private function saveUserCourseRecommendations(User $user, array $recommendations): void
    {
        try {
            foreach ($recommendations as $recommendation) {
                $user->courseRecommendations()->updateOrCreate(
                    ['course_id' => $recommendation['course_id']],
                    [
                        'compatibility_score' => $recommendation['compatibility_score'],
                        'recommended_at' => now()
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Error saving user course recommendations: ' . $e->getMessage());
        }
    }

    /**
     * Get user's questionnaire history
     */
    public function getUserHistory(): JsonResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $responses = QuestionnaireResponse::with('questionnaire')
                ->where('user_id', $user->id)
                ->orderBy('completed_at', 'desc')
                ->get()
                ->map(function ($response) {
                    return [
                        'id' => $response->id,
                        'questionnaire_title' => $response->questionnaire->title,
                        'course_category' => $response->questionnaire->course_category,
                        'completed_at' => $response->completed_at,
                        'skill_scores' => $response->calculated_scores,
                        'top_skills' => $response->top_skill_categories,
                        'completion_time' => $response->completion_duration,
                        'recommended_courses_count' => count($response->recommended_courses ?? [])
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $responses
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user questionnaire history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch questionnaire history'
            ], 500);
        }
    }

    /**
     * Get detailed results for a specific questionnaire response
     */
    public function getResponseDetails($responseId): JsonResponse
    {
        try {
            $user = Auth::user();

            $response = QuestionnaireResponse::with('questionnaire')
                ->where('id', $responseId)
                ->when($user, function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                })
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $response->id,
                    'questionnaire' => [
                        'title' => $response->questionnaire->title,
                        'description' => $response->questionnaire->description,
                        'course_category' => $response->questionnaire->course_category
                    ],
                    'responses' => $response->responses,
                    'skill_scores' => $response->calculated_scores,
                    'recommended_courses' => $response->recommended_courses,
                    'completed_at' => $response->completed_at,
                    'completion_time' => $response->completion_duration,
                    'top_skills' => $response->top_skill_categories
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching response details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Response not found'
            ], 404);
        }
    }
}
