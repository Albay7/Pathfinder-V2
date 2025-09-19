<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobProfile;

class CheckJobProfiles extends Command
{
    protected $signature = 'check:job-profiles';
    protected $description = 'Check job profiles in the database';

    public function handle()
    {
        $total = JobProfile::count();
        $this->info("Total job profiles: {$total}");
        
        if ($total > 0) {
            $this->info('\n=== Sample Job Profiles ===');
            
            $profiles = JobProfile::take(5)->get();
            
            foreach ($profiles as $profile) {
                $this->info("Job: {$profile->job_title} at {$profile->company}");
                $this->line("Programming: {$profile->programming} | Web Dev: {$profile->web_development}");
                $this->line("Database: {$profile->database} | Cloud/DevOps: {$profile->cloud_devops}");
                $this->line("Source: {$profile->source} | Active: " . ($profile->is_active ? 'Yes' : 'No'));
                $this->newLine();
            }
            
            // Show skill vector statistics
            $this->info('=== Skill Vector Statistics ===');
            $skillCategories = JobProfile::getSkillCategories();
            
            foreach ($skillCategories as $skill) {
                $avg = JobProfile::where($skill, '>', 0)->avg($skill);
                if ($avg > 0) {
                    $this->line(ucfirst(str_replace('_', ' ', $skill)) . ": " . round($avg, 2));
                }
            }
        }
        
        return 0;
    }
}