<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobScraperService;
use App\Models\CareerLadder;
use App\Models\CareerLevel;
use Illuminate\Support\Facades\DB;

class ScrapeJobMarketData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'career:scrape-market-data
                            {--role= : Specific role to scrape (optional)}
                            {--force : Force scraping even if data is recent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Philippine job market data for career levels from JobStreet and Kalibrr';

    protected $scraper;

    public function __construct(JobScraperService $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Philippine job market data scraping...');
        $this->info('Data sources: JobStreet PH, Kalibrr');
        $this->newLine();

        $dataVersion = now()->format('Y-m');
        $specificRole = $this->option('role');
        $force = $this->option('force');

        // Check if we already have current month's data
        if (!$force && !$specificRole) {
            $existingData = CareerLevel::where('data_version', $dataVersion)
                ->where('is_current', true)
                ->exists();

            if ($existingData) {
                $this->warn("⚠️  Data for {$dataVersion} already exists. Use --force to re-scrape.");

                if (!$this->confirm('Do you want to continue anyway?')) {
                    return Command::SUCCESS;
                }
            }
        }

        // Get unique roles from career_ladders
        $query = CareerLadder::select('step_role', 'level')
            ->where('is_active', true)
            ->distinct();

        if ($specificRole) {
            $query->where('step_role', 'LIKE', "%{$specificRole}%");
        }

        $roleLevels = $query->get();

        if ($roleLevels->isEmpty()) {
            $this->error('No roles found in career_ladders table. Please run seeders first.');
            return Command::FAILURE;
        }

        $this->info("Found {$roleLevels->count()} role-level combinations to scrape.");
        $this->newLine();

        $progressBar = $this->output->createProgressBar($roleLevels->count());
        $progressBar->start();

        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();

        try {
            foreach ($roleLevels as $roleLevel) {
                $roleName = $roleLevel->step_role;
                $level = $roleLevel->level;

                // Mark old data as not current for this specific role/level
                CareerLevel::where('role_name', $roleName)
                    ->where('level', $level)
                    ->update(['is_current' => false]);

                // Scrape new data
                $scrapedData = $this->scraper->scrapePhilippineJobData($roleName, $level);

                if ($scrapedData && $scrapedData['description']) {
                    // Create new record with current data
                    CareerLevel::create([
                        'role_name' => $roleName,
                        'level' => $level,
                        'description' => $scrapedData['description'],
                        'salary_min' => $scrapedData['salary_min'],
                        'salary_max' => $scrapedData['salary_max'],
                        'salary_currency' => 'PHP',
                        'responsibilities' => $scrapedData['responsibilities'],
                        'required_skills' => $scrapedData['required_skills'],
                        'data_version' => $dataVersion,
                        'is_current' => true,
                        'scraped_at' => $scrapedData['scraped_at'],
                        'data_source' => $scrapedData['data_source'],
                    ]);

                    $successCount++;
                } else {
                    // No new data found, keep old data as current
                    $restored = CareerLevel::where('role_name', $roleName)
                        ->where('level', $level)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($restored) {
                        $restored->update(['is_current' => true]);
                        $skippedCount++;
                    } else {
                        $failCount++;
                    }
                }

                $progressBar->advance();

                // Small delay to avoid overwhelming the server
                usleep(500000); // 0.5 seconds
            }

            DB::commit();

            $progressBar->finish();
            $this->newLine(2);

            // Display results
            $this->info('✅ Scraping completed!');
            $this->newLine();
            $this->table(
                ['Status', 'Count'],
                [
                    ['Successfully scraped', $successCount],
                    ['Kept previous data', $skippedCount],
                    ['Failed', $failCount],
                    ['Total processed', $roleLevels->count()],
                ]
            );

            $this->info("Data version: {$dataVersion}");
            $this->info('All data marked with is_current = true');

        } catch (\Exception $e) {
            DB::rollBack();
            $progressBar->finish();
            $this->newLine(2);
            $this->error('❌ Scraping failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
