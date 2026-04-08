<?php

namespace App\Services;

use App\Models\CVAnalysis;
use App\Models\JobProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class CVAnalysisService
{
    public function __construct()
    {
        Settings::setOutputEscapingEnabled(true);
    }

    /**
     * Analyze uploaded CV file
     */
    public function analyzeCVFile(UploadedFile $file, ?int $userId = null, ?string $sessionId = null): CVAnalysis
    {
        $startTime = microtime(true);

        $analysis = CVAnalysis::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'file_name' => $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => '',
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'extracted_text' => '',
            'skills_extracted' => [],
            'skill_vector' => [],
            'analysis_summary' => [],
            'status' => 'processing',
        ]);

        try {
            $filePath = $file->store('cv-uploads', 'local');
            $analysis->update(['file_path' => $filePath]);

            $extractedText = $this->extractTextFromFile($file);
            $analysis->update(['extracted_text' => $extractedText]);

            $processedText = $this->preprocessText($extractedText);
            
            // Send to FastAPI ML Microservice
            $apiUrl = env('ML_API_URL', 'http://127.0.0.1:8000') . '/api/analyze';
            $response = Http::timeout(30)->post($apiUrl, [
                'text' => $processedText
            ]);
            
            if (!$response->successful()) {
                throw new \Exception("ML API failed: " . $response->body());
            }

            $mlData = $response->json();
            $topCategory = $mlData['top_category'] ?? 'OTHER';
            $extractedSkills = $mlData['skills'] ?? [];
            $skillVector = $mlData['skill_vector'] ?? [];
            
            // Format extracted skills structure for legacy views
            $formattedSkills = [];
            foreach ($extractedSkills as $skill) {
                $formattedSkills[$skill] = [
                    'category' => $this->formatCategoryName($topCategory),
                    'score' => 1.0,
                    'frequency' => 1
                ];
            }

            // Perform Hybrid Matching against actual JobProfiles database
            $jobMatches = $this->findMatchingJobsProfile($topCategory, $skillVector, $extractedSkills);
            $analysisSummary = $this->createAnalysisSummary($formattedSkills, $jobMatches, $topCategory);

            $processingTime = microtime(true) - $startTime;

            $analysis->update([
                'skills_extracted' => $formattedSkills,
                'skill_vector' => $skillVector,
                'job_matches' => $jobMatches,
                'analysis_summary' => $analysisSummary,
                'processing_time' => $processingTime,
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            $analysis->markAsFailed($e->getMessage());
            Log::error('CV Analysis Failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $analysis;
    }

    /**
     * Analyze CV and return results as array (no database dependency)
     */
    public function analyzeCV(UploadedFile $file): array
    {
        $extractedText = $this->extractTextFromFile($file);
        $processedText = $this->preprocessText($extractedText);
        
        $apiUrl = env('ML_API_URL', 'http://127.0.0.1:8000') . '/api/analyze';
        $response = Http::timeout(30)->post($apiUrl, [
            'text' => $processedText
        ]);
        
        if (!$response->successful()) {
            throw new \Exception("ML API failed: " . $response->body());
        }

        $mlData = $response->json();
        $topCategory = $mlData['top_category'] ?? 'OTHER';
        $extractedSkills = $mlData['skills'] ?? [];
        $skillVector = $mlData['skill_vector'] ?? [];
        
        // Format extracted skills structure
        $formattedSkills = [];
        foreach ($extractedSkills as $skill) {
            $formattedSkills[$skill] = [
                'category' => $this->formatCategoryName($topCategory),
                'score' => 1.0,
                'frequency' => 1
            ];
        }

        $jobMatches = $this->findMatchingJobsProfile($topCategory, $skillVector, $extractedSkills);
        $analysisSummary = $this->createAnalysisSummary($formattedSkills, $jobMatches, $topCategory);

        return [
            'extracted_skills' => $formattedSkills,
            'skill_vector' => $skillVector,
            'job_matches' => $jobMatches,
            'analysis_summary' => $analysisSummary,
            'file_name' => $file->getClientOriginalName(),
        ];
    }
    
    /**
     * Hybrid Matching Approach with JobProfile DB.
     * Uses ML Category grouping if possible, then calculates local cosine similarity.
     */
    private function findMatchingJobsProfile(string $topCategory, array $skillVector, array $extractedSkills): array
    {
        $jobProfiles = JobProfile::active()->get();
        if ($jobProfiles->isEmpty()) {
            return []; // No jobs in DB
        }
        
        $matches = [];
        foreach ($jobProfiles as $job) {
            $jobVector = $job->getSkillVector();
            
            // Calculate similarity using exact 12-dimensional vector provided by API
            $similarity = $job->calculateSimilarity($skillVector);
            
            // Apply bonus for dynamically matched exact skills
            $skillBonus = 0;
            $techSkills = is_array($job->technical_skills) ? $job->technical_skills : [];
            $softSkills = is_array($job->soft_skills) ? $job->soft_skills : [];
            $allJobSkills = array_merge($techSkills, $softSkills);
            
            foreach ($extractedSkills as $skill) {
                if (in_array(strtolower($skill), array_map('strtolower', $allJobSkills))) {
                    $skillBonus += 0.05;
                }
            }
            
            // Hybrid Category filtering mappings (Boost jobs that logically map to the ML category)
            $catBonus = 0;
            $catLower = strtolower($topCategory);
            $jobTitleLower = strtolower($job->job_title);
            
            if (($catLower === 'information-technology' || $catLower === 'engineering') && 
                (str_contains($jobTitleLower, 'developer') || str_contains($jobTitleLower, 'engineer'))) {
                $catBonus += 0.2;
            } elseif ($catLower === 'sales' && str_contains($jobTitleLower, 'sales')) {
                $catBonus += 0.2;
            } elseif ($catLower === 'finance' && str_contains($jobTitleLower, 'financial')) {
                $catBonus += 0.2;
            }
            
            $finalScore = min($similarity + $skillBonus + $catBonus, 1.0);
            
            if ($finalScore > 0.1) {
                // Calculate dimensions correctly 
                $matchingDimensions = [];
                foreach ($skillVector as $dim => $val) {
                    $jobVal = $jobVector[$dim] ?? 0;
                    if ($val > 0.1 && $jobVal > 0.1) {
                        $matchingDimensions[] = [
                            'dimension' => $dim,
                            'user_score' => $val,
                            'job_score' => $jobVal,
                            'contribution' => $val * $jobVal
                        ];
                    }
                }
                
                usort($matchingDimensions, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
                
                $matches[] = [
                    'job_id' => $job->id,
                    'job_title' => $job->job_title,
                    'category' => $this->formatCategoryName($topCategory),
                    'company' => $job->company ?? null,
                    'description' => \Illuminate\Support\Str::limit($job->description, 200),
                    'similarity_score' => round($finalScore * 100, 1),
                    'matching_dimensions' => array_slice($matchingDimensions, 0, 4),
                    'required_skills' => $allJobSkills
                ];
            }
        }
        
        usort($matches, fn($a, $b) => $b['similarity_score'] <=> $a['similarity_score']);
        return array_slice($matches, 0, 10);
    }
    
    private function createAnalysisSummary(array $skillsExtracted, array $jobMatches, string $topCategory): array
    {
        $topSkills = array_slice(array_keys($skillsExtracted), 0, 10);
        $bestMatch = !empty($jobMatches) ? $jobMatches[0] : null;

        return [
            'total_skills_found' => count($skillsExtracted),
            'total_job_matches' => count($jobMatches),
            'top_skills' => $topSkills,
            'ml_detected_category' => $this->formatCategoryName($topCategory),
            'best_match' => $bestMatch ? [
                'job_title' => $bestMatch['job_title'],
                'similarity' => $bestMatch['similarity_score'],
            ] : null,
            'skill_categories' => $this->categorizeSkills($skillsExtracted),
        ];
    }
    
    public function categorizeSkills(array $skills): array
    {
        // Dynamic basic categorizer since ML doesn't return sub-clusters directly per word
        $categories = [
            'technical' => array_keys($skills),
            'soft' => [],
            'tools' => [],
            'languages' => [],
            'other' => [],
        ];
        return $categories;
    }

    public function extractTextFromFile(UploadedFile $file): string
    {
        $mimeType = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        $tempPath = $file->store('temp_cv');
        $fullPath = Storage::path($tempPath);

        try {
            switch ($mimeType) {
                case 'application/pdf':
                    $parser = new PdfParser();
                    return $parser->parseFile($fullPath)->getText();
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                case 'application/msword':
                    $phpWord = IOFactory::load($fullPath);
                    $text = '';
                    foreach ($phpWord->getSections() as $section) {
                        foreach ($section->getElements() as $element) {
                            if (method_exists($element, 'getText')) {
                                $text .= $element->getText() . ' ';
                            } elseif (method_exists($element, 'getElements')) {
                                foreach ($element->getElements() as $child) {
                                    if (method_exists($child, 'getText')) {
                                        $text .= $child->getText() . ' ';
                                    }
                                }
                            }
                        }
                    }
                    return $text;
                default:
                    return file_get_contents($fullPath) ?: '';
            }
        } catch (\Exception $e) {
            Log::error('CV text extraction failed', ['error' => $e->getMessage()]);
            return '';
        } finally {
            Storage::delete($tempPath);
        }
    }

    public function preprocessText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function formatCategoryName(string $category): string
    {
        return ucwords(strtolower(str_replace('-', ' ', $category)));
    }
}
