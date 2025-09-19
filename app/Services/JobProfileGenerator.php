<?php

namespace App\Services;

use App\Models\JobProfile;
use App\Services\SkillExtractionService;

class JobProfileGenerator
{
    private $skillExtractor;
    
    public function __construct(SkillExtractionService $skillExtractor)
    {
        $this->skillExtractor = $skillExtractor;
    }
    
    /**
     * Generate job profile from scraped job data
     */
    public function generateProfile(array $jobData)
    {
        // Extract skills from job description
        $extractedSkills = $this->skillExtractor->extractSkills($jobData['description']);
        
        // Generate skill vector
        $skillVector = $this->skillExtractor->generateSkillVector($extractedSkills);
        
        // Create job profile
        $profile = new JobProfile();
        $profile->job_title = $jobData['title'];
        $profile->company = $jobData['company'] ?? 'Unknown';
        $profile->description = $jobData['description'];
        $profile->source = $jobData['source'];
        $profile->url = $jobData['url'] ?? null;
        
        // Set skill vectors
        $profile->setSkillVector($skillVector);
        
        // Set raw skill data
        $profile->technical_skills = $extractedSkills['technical'] ?? [];
        $profile->soft_skills = $extractedSkills['soft'] ?? [];
        $profile->frameworks_libraries = $extractedSkills['frameworks'] ?? [];
        $profile->tools = $extractedSkills['tools'] ?? [];
        $profile->skill_scores = $extractedSkills['scores'] ?? [];
        
        // Set metadata
        $profile->scraped_at = now();
        $profile->is_active = true;
        
        return $profile;
    }
    
    /**
     * Generate multiple profiles from job data array
     */
    public function generateProfiles(array $jobsData)
    {
        $profiles = [];
        
        foreach ($jobsData as $jobData) {
            try {
                $profile = $this->generateProfile($jobData);
                $profiles[] = $profile;
            } catch (\Exception $e) {
                // Log error and continue with next job
                \Log::error('Failed to generate profile for job: ' . $jobData['title'] ?? 'Unknown', [
                    'error' => $e->getMessage(),
                    'job_data' => $jobData
                ]);
            }
        }
        
        return $profiles;
    }
    
    /**
     * Save profiles to database
     */
    public function saveProfiles(array $profiles)
    {
        $saved = 0;
        $errors = 0;
        
        foreach ($profiles as $profile) {
            try {
                // Check if profile already exists
                $existing = JobProfile::where('job_title', $profile->job_title)
                    ->where('company', $profile->company)
                    ->where('source', $profile->source)
                    ->first();
                
                if ($existing) {
                    // Update existing profile
                    $existing->update($profile->toArray());
                    $saved++;
                } else {
                    // Create new profile
                    $profile->save();
                    $saved++;
                }
            } catch (\Exception $e) {
                $errors++;
                \Log::error('Failed to save job profile', [
                    'error' => $e->getMessage(),
                    'profile' => $profile->toArray()
                ]);
            }
        }
        
        return [
            'saved' => $saved,
            'errors' => $errors
        ];
    }
    
    /**
     * Process and save scraped jobs in one go
     */
    public function processScrapedJobs(array $jobsData)
    {
        $profiles = $this->generateProfiles($jobsData);
        $result = $this->saveProfiles($profiles);
        
        return [
            'processed' => count($jobsData),
            'generated' => count($profiles),
            'saved' => $result['saved'],
            'errors' => $result['errors']
        ];
    }
    
    /**
     * Find similar job profiles using cosine similarity
     */
    public function findSimilarJobs(array $userSkillVector, int $limit = 10, float $minSimilarity = 0.1)
    {
        $jobProfiles = JobProfile::active()->get();
        $similarities = [];
        
        foreach ($jobProfiles as $profile) {
            $similarity = $profile->calculateSimilarity($userSkillVector);
            
            if ($similarity >= $minSimilarity) {
                $similarities[] = [
                    'profile' => $profile,
                    'similarity' => $similarity
                ];
            }
        }
        
        // Sort by similarity (highest first)
        usort($similarities, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        return array_slice($similarities, 0, $limit);
    }
    
    /**
     * Get job profile statistics
     */
    public function getStatistics()
    {
        $total = JobProfile::count();
        $active = JobProfile::active()->count();
        $sources = JobProfile::select('source')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('source')
            ->get()
            ->pluck('count', 'source')
            ->toArray();
        
        $topSkills = $this->getTopSkills();
        
        return [
            'total_profiles' => $total,
            'active_profiles' => $active,
            'sources' => $sources,
            'top_skills' => $topSkills
        ];
    }
    
    /**
     * Get top skills across all job profiles
     */
    private function getTopSkills(int $limit = 10)
    {
        $skillCategories = JobProfile::getSkillCategories();
        $skillAverages = [];
        
        foreach ($skillCategories as $skill) {
            $average = JobProfile::active()
                ->where($skill, '>', 0)
                ->avg($skill);
            
            if ($average > 0) {
                $skillAverages[$skill] = round($average, 2);
            }
        }
        
        arsort($skillAverages);
        
        return array_slice($skillAverages, 0, $limit, true);
    }
    
    /**
     * Clean up old inactive profiles
     */
    public function cleanupOldProfiles(int $daysOld = 30)
    {
        $cutoffDate = now()->subDays($daysOld);
        
        $deleted = JobProfile::where('is_active', false)
            ->where('updated_at', '<', $cutoffDate)
            ->delete();
        
        return $deleted;
    }
}