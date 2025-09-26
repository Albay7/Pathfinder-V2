<?php

namespace App\Http\Controllers;

use App\Services\CVAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserProgress;

class CVAnalysisController extends Controller
{
    private $cvAnalysisService;
    
    public function __construct(CVAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }
    
    /**
     * Show CV upload form
     */
    public function showUploadForm()
    {
        return view('pathfinder.cv-upload');
    }
    
    /**
     * Handle CV upload and analysis
     */
    public function uploadAndAnalyze(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'cv_file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Get user ID if authenticated, otherwise use null for anonymous users
            $userId = Auth::check() ? Auth::id() : null;
            $file = $request->file('cv_file');
            
            // Analyze the CV
            $analysisResult = $this->cvAnalysisService->analyzeCVFile($file, $userId);
            
            return response()->json([
                'success' => true,
                'message' => 'CV analyzed successfully',
                'data' => $analysisResult
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user's CV analysis history
     */
    public function getAnalysisHistory(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            $analyses = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'cv_analysis')
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($analysis) {
                    return [
                        'id' => $analysis->id,
                        'analyzed_at' => $analysis->created_at->format('Y-m-d H:i:s'),
                        'match_percentage' => $analysis->match_percentage,
                        'summary' => $analysis->analysis_result['analysis_summary'] ?? null,
                        'top_job_match' => $analysis->analysis_result['job_matches'][0] ?? null
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $analyses
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analysis history: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get detailed analysis results by ID
     */
    public function getAnalysisDetails(int $analysisId): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            $analysis = UserProgress::where('id', $analysisId)
                ->where('user_id', Auth::id())
                ->where('feature_type', 'cv_analysis')
                ->first();
            
            if (!$analysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Analysis not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $analysis->id,
                    'analyzed_at' => $analysis->created_at->format('Y-m-d H:i:s'),
                    'extracted_skills' => $analysis->analysis_result['extracted_skills'] ?? [],
                    'skill_vector' => $analysis->analysis_result['skill_vector'] ?? [],
                    'job_matches' => $analysis->analysis_result['job_matches'] ?? [],
                    'analysis_summary' => $analysis->analysis_result['analysis_summary'] ?? null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analysis details: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get job recommendations based on latest CV analysis
     */
    public function getJobRecommendations(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            // Get the latest CV analysis
            $latestAnalysis = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'cv_analysis')
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$latestAnalysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'No CV analysis found. Please upload your CV first.'
                ], 404);
            }
            
            $jobMatches = $latestAnalysis->analysis_result['job_matches'] ?? [];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'analysis_date' => $latestAnalysis->created_at->format('Y-m-d H:i:s'),
                    'job_recommendations' => $jobMatches,
                    'total_matches' => count($jobMatches)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job recommendations: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Compare user skills with a specific job
     */
    public function compareWithJob(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|integer|exists:job_profiles,id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid job ID',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }
            
            // Get the latest CV analysis
            $latestAnalysis = UserProgress::where('user_id', Auth::id())
                ->where('feature_type', 'cv_analysis')
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$latestAnalysis) {
                return response()->json([
                    'success' => false,
                    'message' => 'No CV analysis found. Please upload your CV first.'
                ], 404);
            }
            
            $userSkillVector = $latestAnalysis->analysis_result['skill_vector'] ?? [];
            $jobId = $request->input('job_id');
            
            // Get job details and calculate detailed comparison
            $jobProfile = \App\Models\JobProfile::find($jobId);
            $jobSkillVector = $jobProfile->getSkillVector();
            
            // Calculate similarity and detailed comparison
            $similarity = $this->calculateCosineSimilarity($userSkillVector, $jobSkillVector);
            $skillGaps = $this->identifySkillGaps($userSkillVector, $jobSkillVector);
            $strengths = $this->identifyStrengths($userSkillVector, $jobSkillVector);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'job_title' => $jobProfile->job_title,
                    'company' => $jobProfile->company,
                    'overall_match' => round($similarity * 100, 2),
                    'strengths' => $strengths,
                    'skill_gaps' => $skillGaps,
                    'recommendations' => $this->generateImprovementRecommendations($skillGaps)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Comparison failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Calculate cosine similarity between two skill vectors
     */
    private function calculateCosineSimilarity(array $vectorA, array $vectorB): float
    {
        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;
        
        foreach ($vectorA as $key => $valueA) {
            $valueB = $vectorB[$key] ?? 0;
            
            $dotProduct += $valueA * $valueB;
            $magnitudeA += $valueA * $valueA;
            $magnitudeB += $valueB * $valueB;
        }
        
        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);
        
        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }
        
        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
    
    /**
     * Identify skill gaps between user and job requirements
     */
    private function identifySkillGaps(array $userVector, array $jobVector): array
    {
        $gaps = [];
        
        foreach ($jobVector as $skill => $jobScore) {
            $userScore = $userVector[$skill] ?? 0;
            $gap = $jobScore - $userScore;
            
            if ($gap > 0.2) { // Significant gap threshold
                $gaps[] = [
                    'skill' => ucfirst(str_replace('_', ' ', $skill)),
                    'required_level' => round($jobScore, 2),
                    'current_level' => round($userScore, 2),
                    'gap_size' => round($gap, 2),
                    'priority' => $gap > 0.5 ? 'high' : ($gap > 0.3 ? 'medium' : 'low')
                ];
            }
        }
        
        // Sort by gap size (descending)
        usort($gaps, function($a, $b) {
            return $b['gap_size'] <=> $a['gap_size'];
        });
        
        return $gaps;
    }
    
    /**
     * Identify user strengths compared to job requirements
     */
    private function identifyStrengths(array $userVector, array $jobVector): array
    {
        $strengths = [];
        
        foreach ($userVector as $skill => $userScore) {
            $jobScore = $jobVector[$skill] ?? 0;
            
            if ($userScore > 0.3 && $userScore >= $jobScore) {
                $strengths[] = [
                    'skill' => ucfirst(str_replace('_', ' ', $skill)),
                    'user_level' => round($userScore, 2),
                    'required_level' => round($jobScore, 2),
                    'advantage' => round($userScore - $jobScore, 2)
                ];
            }
        }
        
        // Sort by user score (descending)
        usort($strengths, function($a, $b) {
            return $b['user_level'] <=> $a['user_level'];
        });
        
        return $strengths;
    }
    
    /**
     * Generate improvement recommendations based on skill gaps
     */
    private function generateImprovementRecommendations(array $skillGaps): array
    {
        $recommendations = [];
        
        foreach (array_slice($skillGaps, 0, 5) as $gap) { // Top 5 gaps
            $skill = strtolower(str_replace(' ', '_', $gap['skill']));
            
            $recommendations[] = [
                'skill' => $gap['skill'],
                'priority' => $gap['priority'],
                'suggestion' => $this->getSkillImprovementSuggestion($skill),
                'estimated_time' => $this->getEstimatedLearningTime($gap['gap_size'])
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Get skill improvement suggestions
     */
    private function getSkillImprovementSuggestion(string $skill): string
    {
        $suggestions = [
            'programming' => 'Practice coding challenges on platforms like LeetCode, HackerRank, or Codewars. Build personal projects.',
            'web_development' => 'Create responsive websites, learn modern frameworks, and contribute to open-source projects.',
            'database' => 'Practice SQL queries, learn database design principles, and work with different database systems.',
            'cloud_devops' => 'Get cloud certifications (AWS, Azure, GCP), practice with containerization and CI/CD pipelines.',
            'mobile_development' => 'Build mobile apps using React Native or Flutter, publish apps to app stores.',
            'data_science' => 'Complete online courses, work on Kaggle competitions, and build data analysis projects.',
            'ui_ux' => 'Study design principles, create a portfolio, and practice with design tools like Figma.',
            'project_management' => 'Get certified in Agile/Scrum, lead small projects, and learn project management tools.',
            'communication' => 'Practice public speaking, write technical blogs, and participate in team presentations.',
            'leadership' => 'Take on team lead roles, mentor junior developers, and study leadership principles.',
            'analytical_thinking' => 'Solve complex problems, learn data analysis techniques, and practice logical reasoning.',
            'problem_solving' => 'Practice algorithmic thinking, debug complex issues, and learn systematic problem-solving approaches.'
        ];
        
        return $suggestions[$skill] ?? 'Focus on practical experience and continuous learning in this area.';
    }
    
    /**
     * Get estimated learning time based on skill gap size
     */
    private function getEstimatedLearningTime(float $gapSize): string
    {
        if ($gapSize > 0.7) {
            return '6-12 months';
        } elseif ($gapSize > 0.5) {
            return '3-6 months';
        } elseif ($gapSize > 0.3) {
            return '1-3 months';
        } else {
            return '2-4 weeks';
        }
    }
}