<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CareerLevel;
use Illuminate\Support\Facades\DB;

class ArchiveOldCareerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'career:archive-old-data
                            {--dry-run : Preview what would be deleted without actually deleting}
                            {--months=12 : Archive data older than this many months (default: 12)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive and delete old career level data to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $monthsThreshold = (int) $this->option('months');
        $deleteThreshold = $monthsThreshold * 2; // Delete data older than 24 months

        $this->info('🗄️  Career Data Archival Process');
        $this->info('Archive threshold: ' . $monthsThreshold . ' months');
        $this->info('Delete threshold: ' . $deleteThreshold . ' months');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No data will be modified');
        }

        $this->newLine();

        // Calculate cutoff dates
        $archiveCutoff = now()->subMonths($monthsThreshold);
        $deleteCutoff = now()->subMonths($deleteThreshold);

        // Get statistics
        $totalRecords = CareerLevel::count();
        $currentRecords = CareerLevel::where('is_current', true)->count();
        $recordsToArchive = CareerLevel::where('is_current', true)
            ->where('created_at', '<', $archiveCutoff)
            ->count();
        $recordsToDelete = CareerLevel::where('created_at', '<', $deleteCutoff)->count();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total records', $totalRecords],
                ['Current records', $currentRecords],
                ['Records to archive (> ' . $monthsThreshold . ' months)', $recordsToArchive],
                ['Records to delete (> ' . $deleteThreshold . ' months)', $recordsToDelete],
            ]
        );

        if ($recordsToArchive === 0 && $recordsToDelete === 0) {
            $this->info('✅ No old data to archive or delete. Database is clean!');
            return Command::SUCCESS;
        }

        if (!$dryRun) {
            if (!$this->confirm('Do you want to proceed with archival/deletion?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        DB::beginTransaction();

        try {
            // Step 1: Archive old "current" records (set is_current = false)
            if ($recordsToArchive > 0) {
                $this->info('📦 Archiving old current records...');

                if (!$dryRun) {
                    $archived = CareerLevel::where('is_current', true)
                        ->where('created_at', '<', $archiveCutoff)
                        ->update(['is_current' => false]);

                    $this->info("✅ Archived {$archived} records (set is_current = false)");
                } else {
                    $this->info("Would archive {$recordsToArchive} records");
                }
            }

            // Step 2: Delete very old records
            if ($recordsToDelete > 0) {
                $this->info('🗑️  Deleting records older than ' . $deleteThreshold . ' months...');

                if (!$dryRun) {
                    $deleted = CareerLevel::where('created_at', '<', $deleteCutoff)->delete();
                    $this->info("✅ Deleted {$deleted} old records");
                } else {
                    $this->info("Would delete {$recordsToDelete} records");
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->newLine();
                $this->info('✅ Archival process completed successfully!');

                // Show updated statistics
                $remainingRecords = CareerLevel::count();
                $currentAfter = CareerLevel::where('is_current', true)->count();

                $this->table(
                    ['Metric', 'Before', 'After', 'Change'],
                    [
                        ['Total records', $totalRecords, $remainingRecords, $remainingRecords - $totalRecords],
                        ['Current records', $currentRecords, $currentAfter, $currentAfter - $currentRecords],
                    ]
                );
            } else {
                DB::rollBack();
                $this->newLine();
                $this->info('✅ Dry run completed. Run without --dry-run to execute changes.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Archival failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
