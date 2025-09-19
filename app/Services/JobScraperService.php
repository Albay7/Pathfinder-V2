<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class JobScraperService
{
    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';
    private $delay = 2; // Delay between requests in seconds
    
    /**
     * Scrape IT jobs from multiple sources
     */
    public function scrapeITJobs($limit = 50)
    {
        $jobs = [];
        
        try {
            // Scrape from different sources
            $jobs = array_merge($jobs, $this->scrapeStackOverflowJobs($limit / 2));
            $jobs = array_merge($jobs, $this->scrapeRemoteOkJobs($limit / 2));
            
            Log::info('Successfully scraped ' . count($jobs) . ' IT jobs');
            
        } catch (\Exception $e) {
            Log::error('Job scraping failed: ' . $e->getMessage());
        }
        
        return $jobs;
    }
    
    /**
     * Scrape jobs from Stack Overflow Jobs (using their RSS feed)
     */
    private function scrapeStackOverflowJobs($limit = 25)
    {
        $jobs = [];
        
        try {
            // Use Stack Overflow's RSS feed for job listings
            $response = Http::timeout(30)
                ->withHeaders(['User-Agent' => $this->userAgent])
                ->get('https://stackoverflow.com/jobs/feed');
                
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $count = 0;
                
                foreach ($xml->channel->item as $item) {
                    if ($count >= $limit) break;
                    
                    $jobs[] = [
                        'title' => (string) $item->title,
                        'company' => $this->extractCompanyFromTitle((string) $item->title),
                        'description' => strip_tags((string) $item->description),
                        'url' => (string) $item->link,
                        'source' => 'stackoverflow',
                        'scraped_at' => now(),
                        'skills' => $this->extractSkillsFromDescription(strip_tags((string) $item->description))
                    ];
                    
                    $count++;
                }
            }
            
            sleep($this->delay);
            
        } catch (\Exception $e) {
            Log::error('Stack Overflow scraping failed: ' . $e->getMessage());
        }
        
        return $jobs;
    }
    
    /**
     * Scrape jobs from Remote OK (they have a public API)
     */
    private function scrapeRemoteOkJobs($limit = 25)
    {
        $jobs = [];
        
        try {
            $response = Http::timeout(30)
                ->withHeaders(['User-Agent' => $this->userAgent])
                ->get('https://remoteok.io/api');
                
            if ($response->successful()) {
                $data = $response->json();
                $count = 0;
                
                foreach ($data as $job) {
                    if ($count >= $limit || !is_array($job)) continue;
                    
                    // Filter for IT/Tech jobs
                    if ($this->isITJob($job)) {
                        $jobs[] = [
                            'title' => $job['position'] ?? 'Unknown Position',
                            'company' => $job['company'] ?? 'Unknown Company',
                            'description' => $job['description'] ?? '',
                            'url' => 'https://remoteok.io/remote-jobs/' . ($job['id'] ?? ''),
                            'source' => 'remoteok',
                            'scraped_at' => now(),
                            'skills' => $this->extractSkillsFromTags($job['tags'] ?? [])
                        ];
                        
                        $count++;
                    }
                }
            }
            
            sleep($this->delay);
            
        } catch (\Exception $e) {
            Log::error('Remote OK scraping failed: ' . $e->getMessage());
        }
        
        return $jobs;
    }
    
    /**
     * Extract company name from job title
     */
    private function extractCompanyFromTitle($title)
    {
        // Try to extract company name from title format "Position at Company"
        if (strpos($title, ' at ') !== false) {
            return trim(substr($title, strpos($title, ' at ') + 4));
        }
        
        return 'Unknown Company';
    }
    
    /**
     * Check if job is IT/Tech related
     */
    private function isITJob($job)
    {
        $itKeywords = [
            'developer', 'engineer', 'programmer', 'software', 'web', 'mobile',
            'frontend', 'backend', 'fullstack', 'devops', 'data', 'ai', 'ml',
            'javascript', 'python', 'java', 'php', 'react', 'angular', 'vue',
            'node', 'laravel', 'django', 'spring', 'tech', 'it', 'computer'
        ];
        
        $title = strtolower($job['position'] ?? '');
        $description = strtolower($job['description'] ?? '');
        $tags = array_map('strtolower', $job['tags'] ?? []);
        
        foreach ($itKeywords as $keyword) {
            if (strpos($title, $keyword) !== false || 
                strpos($description, $keyword) !== false ||
                in_array($keyword, $tags)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Extract skills from job description text
     */
    private function extractSkillsFromDescription($description)
    {
        $skills = [];
        
        // Common IT skills to look for
        $skillKeywords = [
            // Programming Languages
            'javascript', 'python', 'java', 'php', 'c++', 'c#', 'ruby', 'go', 'rust',
            'typescript', 'swift', 'kotlin', 'scala', 'r', 'matlab', 'sql',
            
            // Frameworks & Libraries
            'react', 'angular', 'vue', 'node.js', 'express', 'laravel', 'django',
            'spring', 'flask', 'rails', 'jquery', 'bootstrap', 'tailwind',
            
            // Databases
            'mysql', 'postgresql', 'mongodb', 'redis', 'elasticsearch', 'sqlite',
            
            // Cloud & DevOps
            'aws', 'azure', 'gcp', 'docker', 'kubernetes', 'jenkins', 'git',
            'ci/cd', 'terraform', 'ansible',
            
            // Soft Skills
            'leadership', 'communication', 'teamwork', 'problem solving',
            'analytical', 'creative', 'project management'
        ];
        
        $description = strtolower($description);
        
        foreach ($skillKeywords as $skill) {
            if (strpos($description, strtolower($skill)) !== false) {
                $skills[] = $skill;
            }
        }
        
        return array_unique($skills);
    }
    
    /**
     * Extract skills from job tags
     */
    private function extractSkillsFromTags($tags)
    {
        return array_map('strtolower', $tags);
    }
    
    /**
     * Get job statistics
     */
    public function getJobStatistics($jobs)
    {
        $stats = [
            'total_jobs' => count($jobs),
            'sources' => [],
            'top_skills' => [],
            'top_companies' => []
        ];
        
        $skillCounts = [];
        $companyCounts = [];
        
        foreach ($jobs as $job) {
            // Count by source
            $source = $job['source'];
            $stats['sources'][$source] = ($stats['sources'][$source] ?? 0) + 1;
            
            // Count skills
            foreach ($job['skills'] as $skill) {
                $skillCounts[$skill] = ($skillCounts[$skill] ?? 0) + 1;
            }
            
            // Count companies
            $company = $job['company'];
            $companyCounts[$company] = ($companyCounts[$company] ?? 0) + 1;
        }
        
        // Get top 10 skills and companies
        arsort($skillCounts);
        arsort($companyCounts);
        
        $stats['top_skills'] = array_slice($skillCounts, 0, 10, true);
        $stats['top_companies'] = array_slice($companyCounts, 0, 10, true);
        
        return $stats;
    }
}