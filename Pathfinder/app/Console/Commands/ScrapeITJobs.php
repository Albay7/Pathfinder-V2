<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobScraperService;
use App\Services\JobProfileGenerator;
use Illuminate\Support\Facades\Storage;

class ScrapeITJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:it-jobs {--limit=50 : Number of jobs to scrape} {--save : Save results to file} {--no-db : Skip saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape IT job postings from various job sites and generate job profiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting IT job scraping...');
        
        $limit = $this->option('limit');
        $save = $this->option('save');
        $skipDatabase = $this->option('no-db');
        
        $scraper = new JobScraperService();
        
        // Show progress bar
        $this->info("Scraping {$limit} IT jobs from multiple sources...");
        $bar = $this->output->createProgressBar(4); // 4 steps: scrape, process, profiles, save
        
        $bar->start();
        
        // Step 1: Scrape jobs
        $jobs = $scraper->scrapeITJobs($limit);
        $bar->advance();
        
        // Step 2: Get statistics
        $stats = $scraper->getJobStatistics($jobs);
        $bar->advance();
        
        // Step 3: Process jobs into profiles if not skipping database
        $profileResults = null;
        if (!$skipDatabase && !empty($jobs)) {
            $this->info("\nProcessing jobs into profiles...");
            $profileGenerator = new JobProfileGenerator(new \App\Services\SkillExtractionService());
            $profileResults = $profileGenerator->processScrapedJobs($jobs);
            
            $this->info("Generated {$profileResults['generated']} profiles, saved {$profileResults['saved']} to database");
            if ($profileResults['errors'] > 0) {
                $this->warn("Encountered {$profileResults['errors']} errors during processing");
            }
        }
        $bar->advance();
        
        // Step 4: Save if requested
        if ($save) {
            $filename = 'scraped_jobs_' . date('Y-m-d_H-i-s') . '.json';
            Storage::disk('local')->put($filename, json_encode([
                'scraped_at' => now(),
                'total_jobs' => count($jobs),
                'statistics' => $stats,
                'profile_results' => $profileResults,
                'jobs' => $jobs
            ], JSON_PRETTY_PRINT));
            
            $this->info("\nResults saved to: storage/app/{$filename}");
        }
        $bar->advance();
        $bar->finish();
        
        // Display results
        $this->newLine(2);
        $this->info('=== SCRAPING RESULTS ===');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Jobs Scraped', $stats['total_jobs']],
                ['Sources', implode(', ', array_keys($stats['sources']))],
                ['Top Skills Found', implode(', ', array_slice(array_keys($stats['top_skills']), 0, 5))]
            ]
        );
        
        // Show profile statistics if generated
        if ($profileResults) {
            $this->newLine();
            $this->info('=== PROFILE GENERATION RESULTS ===');
            $this->table(['Metric', 'Count'], [
                ['Jobs Processed', $profileResults['processed']],
                ['Profiles Generated', $profileResults['generated']],
                ['Profiles Saved', $profileResults['saved']],
                ['Errors', $profileResults['errors']]
            ]);
        }
        
        // Show source breakdown
        if (!empty($stats['sources'])) {
            $this->newLine();
            $this->info('=== SOURCE BREAKDOWN ===');
            $sourceData = [];
            foreach ($stats['sources'] as $source => $count) {
                $sourceData[] = [ucfirst($source), $count];
            }
            $this->table(['Source', 'Jobs'], $sourceData);
        }
        
        // Show top skills
        if (!empty($stats['top_skills'])) {
            $this->newLine();
            $this->info('=== TOP 10 SKILLS ===');
            $skillData = [];
            foreach (array_slice($stats['top_skills'], 0, 10, true) as $skill => $count) {
                $skillData[] = [ucfirst($skill), $count];
            }
            $this->table(['Skill', 'Frequency'], $skillData);
        }
        
        // Show sample jobs
        if (!empty($jobs)) {
            $this->newLine();
            $this->info('=== SAMPLE JOBS ===');
            $sampleJobs = array_slice($jobs, 0, 3);
            foreach ($sampleJobs as $index => $job) {
                $this->info(($index + 1) . ". {$job['title']} at {$job['company']}");
                $this->line("   Skills: " . implode(', ', array_slice($job['skills'], 0, 5)));
                $this->line("   Source: {$job['source']}");
                $this->newLine();
            }
        }
        
        $this->info('Job scraping and processing completed successfully!');
        
        return Command::SUCCESS;
    }
}