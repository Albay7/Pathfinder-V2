<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobProfileGenerator;
use App\Services\SkillExtractionService;
use App\Models\JobProfile;

class TestCosineSimilarity extends Command
{
    protected $signature = 'test:cosine-similarity {--user-skills= : Comma-separated user skills}';
    protected $description = 'Test cosine similarity matching between user skills and job profiles';

    public function handle()
    {
        $userSkillsInput = $this->option('user-skills');
        
        // Create sample user skill vector
        if ($userSkillsInput) {
            $userSkills = explode(',', $userSkillsInput);
            $userSkills = array_map('trim', $userSkills);
        } else {
            // Default sample user skills
            $userSkills = ['javascript', 'react', 'node.js', 'css', 'html', 'git'];
            $this->info('Using default user skills: ' . implode(', ', $userSkills));
        }
        
        // Generate user skill vector using SkillExtractionService
        $skillExtractor = new SkillExtractionService();
        $extractedSkills = $skillExtractor->extractSkills(implode(' ', $userSkills));
        $userVector = $skillExtractor->generateSkillVector($extractedSkills);
        
        $this->info('\n=== User Skill Vector ===');
        foreach ($userVector as $skill => $score) {
            if ($score > 0) {
                $this->line(ucfirst(str_replace('_', ' ', $skill)) . ": " . round($score, 2));
            }
        }
        
        // Find similar jobs
        $profileGenerator = new JobProfileGenerator($skillExtractor);
        $similarJobs = $profileGenerator->findSimilarJobs($userVector, 10, 0.0);
        
        if (empty($similarJobs)) {
            $this->warn('No job profiles found in database. Run scrape:it-jobs first.');
            return 1;
        }
        
        $this->info('\n=== Job Matches (Cosine Similarity) ===');
        $this->table(
            ['Job Title', 'Company', 'Similarity Score', 'Top Skills'],
            array_map(function($match) {
                $profile = $match['profile'];
                $similarity = round($match['similarity'], 3);
                
                // Get top skills for this job
                $jobVector = $profile->getSkillVector();
                arsort($jobVector);
                $topSkills = array_slice(array_keys(array_filter($jobVector, function($v) { return $v > 0; })), 0, 3);
                $topSkillsStr = implode(', ', array_map(function($s) { return str_replace('_', ' ', $s); }, $topSkills));
                
                return [
                    $profile->job_title,
                    $profile->company,
                    $similarity,
                    $topSkillsStr ?: 'None'
                ];
            }, $similarJobs)
        );
        
        // Show detailed comparison for top match
        if (!empty($similarJobs)) {
            $topMatch = $similarJobs[0];
            $this->info('\n=== Detailed Comparison (Top Match) ===');
            $this->info("Job: {$topMatch['profile']->job_title} at {$topMatch['profile']->company}");
            $this->info("Similarity Score: " . round($topMatch['similarity'], 3));
            
            $jobVector = $topMatch['profile']->getSkillVector();
            
            $this->info('\n=== Skill Comparison ===');
            $comparisonData = [];
            foreach ($userVector as $skill => $userScore) {
                $jobScore = $jobVector[$skill] ?? 0;
                if ($userScore > 0 || $jobScore > 0) {
                    $comparisonData[] = [
                        ucfirst(str_replace('_', ' ', $skill)),
                        round($userScore, 2),
                        round($jobScore, 2),
                        round(abs($userScore - $jobScore), 2)
                    ];
                }
            }
            
            $this->table(
                ['Skill', 'User Score', 'Job Score', 'Difference'],
                $comparisonData
            );
        }
        
        // Show statistics
        $this->info('\n=== Statistics ===');
        $stats = $profileGenerator->getStatistics();
        $this->info("Total job profiles: {$stats['total_profiles']}");
        $this->info("Active profiles: {$stats['active_profiles']}");
        $this->info("Matches found: " . count($similarJobs));
        
        if (!empty($stats['top_skills'])) {
            $this->info('\nTop skills in job market:');
            foreach (array_slice($stats['top_skills'], 0, 5) as $skill => $avg) {
                $this->line("  " . ucfirst(str_replace('_', ' ', $skill)) . ": " . $avg);
            }
        }
        
        return 0;
    }
}