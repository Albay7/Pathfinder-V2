<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobProfile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'job_title',
        'company',
        'description',
        'source',
        'url',
        'programming',
        'web_development',
        'database',
        'cloud_devops',
        'mobile_development',
        'data_science',
        'ui_ux',
        'project_management',
        'communication',
        'leadership',
        'analytical_thinking',
        'problem_solving',
        'technical_skills',
        'soft_skills',
        'frameworks_libraries',
        'tools',
        'skill_scores',
        'scraped_at',
        'is_active'
    ];
    
    protected $casts = [
        'technical_skills' => 'array',
        'soft_skills' => 'array',
        'frameworks_libraries' => 'array',
        'tools' => 'array',
        'skill_scores' => 'array',
        'scraped_at' => 'datetime',
        'is_active' => 'boolean',
        'programming' => 'decimal:2',
        'web_development' => 'decimal:2',
        'database' => 'decimal:2',
        'cloud_devops' => 'decimal:2',
        'mobile_development' => 'decimal:2',
        'data_science' => 'decimal:2',
        'ui_ux' => 'decimal:2',
        'project_management' => 'decimal:2',
        'communication' => 'decimal:2',
        'leadership' => 'decimal:2',
        'analytical_thinking' => 'decimal:2',
        'problem_solving' => 'decimal:2'
    ];
    
    /**
     * Get skill vector as array
     */
    public function getSkillVector()
    {
        return [
            'programming' => (float) $this->programming,
            'web_development' => (float) $this->web_development,
            'database' => (float) $this->database,
            'cloud_devops' => (float) $this->cloud_devops,
            'mobile_development' => (float) $this->mobile_development,
            'data_science' => (float) $this->data_science,
            'ui_ux' => (float) $this->ui_ux,
            'project_management' => (float) $this->project_management,
            'communication' => (float) $this->communication,
            'leadership' => (float) $this->leadership,
            'analytical_thinking' => (float) $this->analytical_thinking,
            'problem_solving' => (float) $this->problem_solving
        ];
    }
    
    /**
     * Set skill vector from array
     */
    public function setSkillVector(array $vector)
    {
        $this->programming = $vector['programming'] ?? 0;
        $this->web_development = $vector['web_development'] ?? 0;
        $this->database = $vector['database'] ?? 0;
        $this->cloud_devops = $vector['cloud_devops'] ?? 0;
        $this->mobile_development = $vector['mobile_development'] ?? 0;
        $this->data_science = $vector['data_science'] ?? 0;
        $this->ui_ux = $vector['ui_ux'] ?? 0;
        $this->project_management = $vector['project_management'] ?? 0;
        $this->communication = $vector['communication'] ?? 0;
        $this->leadership = $vector['leadership'] ?? 0;
        $this->analytical_thinking = $vector['analytical_thinking'] ?? 0;
        $this->problem_solving = $vector['problem_solving'] ?? 0;
    }
    
    /**
     * Calculate cosine similarity with another vector
     */
    public function calculateSimilarity(array $userVector)
    {
        $jobVector = $this->getSkillVector();
        
        // Calculate dot product
        $dotProduct = 0;
        foreach ($jobVector as $key => $value) {
            $dotProduct += $value * ($userVector[$key] ?? 0);
        }
        
        // Calculate magnitudes
        $jobMagnitude = sqrt(array_sum(array_map(function($x) { return $x * $x; }, $jobVector)));
        $userMagnitude = sqrt(array_sum(array_map(function($x) { return $x * $x; }, $userVector)));
        
        // Avoid division by zero
        if ($jobMagnitude == 0 || $userMagnitude == 0) {
            return 0;
        }
        
        return $dotProduct / ($jobMagnitude * $userMagnitude);
    }
    
    /**
     * Scope for active job profiles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope for specific job sources
     */
    public function scopeFromSource($query, $source)
    {
        return $query->where('source', $source);
    }
    
    /**
     * Get all skill categories
     */
    public static function getSkillCategories()
    {
        return [
            'programming',
            'web_development',
            'database',
            'cloud_devops',
            'mobile_development',
            'data_science',
            'ui_ux',
            'project_management',
            'communication',
            'leadership',
            'analytical_thinking',
            'problem_solving'
        ];
    }
}
