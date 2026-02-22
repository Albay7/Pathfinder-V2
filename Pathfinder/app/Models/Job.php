<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'career_jobs';

    protected $fillable = [
        'title',
        'description',
        'industry',
        'level',
        'employment_type',
        'required_skills',
        'preferred_skills',
        'responsibilities',
        'mbti_compatibility',
        'mbti_explanation',
        'salary_min',
        'salary_max',
        'salary_currency',
        'education_requirements',
        'experience_years_min',
        'experience_years_max',
        'growth_opportunities',
        'work_environment',
        'remote_available',
        'is_active'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'preferred_skills' => 'array',
        'responsibilities' => 'array',
        'mbti_compatibility' => 'array',
        'education_requirements' => 'array',
        'growth_opportunities' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'remote_available' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Users who have been recommended this job
     */
    public function recommendedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_job_recommendations')
                    ->withPivot('compatibility_score', 'recommended_at')
                    ->withTimestamps();
    }

    /**
     * Get users who have this job in their recommendations
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_job_recommendations')
                    ->withPivot('compatibility_score', 'recommended_at')
                    ->withTimestamps();
    }

    /**
     * Calculate compatibility score with given MBTI type
     */
    public function getCompatibilityScore(string $mbtiType): int
    {
        if (!$this->mbti_compatibility || !isset($this->mbti_compatibility[$mbtiType])) {
            return $this->calculateDefaultCompatibility($mbtiType);
        }

        return $this->mbti_compatibility[$mbtiType];
    }

    /**
     * Get compatibility explanation for given MBTI type
     */
    public function getCompatibilityExplanation(string $mbtiType): string
    {
        $score = $this->getCompatibilityScore($mbtiType);
        $baseExplanation = $this->mbti_explanation ?? 'This career aligns with your personality traits.';
        
        $workStyleMap = [
            'E' => 'team collaboration and client interaction',
            'I' => 'independent work and focused concentration',
            'S' => 'practical implementation and attention to detail',
            'N' => 'strategic thinking and innovation',
            'T' => 'analytical decision-making and objective problem-solving',
            'F' => 'people-focused solutions and value-driven work',
            'J' => 'structured processes and organized workflows',
            'P' => 'adaptability and diverse project opportunities'
        ];

        $strengths = [];
        for ($i = 0; $i < 4; $i++) {
            $trait = $mbtiType[$i];
            if (isset($workStyleMap[$trait])) {
                $strengths[] = $workStyleMap[$trait];
            }
        }

        $explanation = $baseExplanation;
        if (!empty($strengths)) {
            $explanation .= ' Your ' . $mbtiType . ' personality excels in roles requiring ' . implode(' and ', array_slice($strengths, 0, 2)) . '.';
        }

        // Add work environment insights
        $environmentInsights = $this->getEnvironmentInsights($mbtiType);
        if ($environmentInsights) {
            $explanation .= ' ' . $environmentInsights;
        }

        if ($score >= 85) {
            $explanation .= ' This is an exceptional career match for your personality type.';
        } elseif ($score >= 70) {
            $explanation .= ' This career offers strong alignment with your natural work style.';
        } elseif ($score >= 55) {
            $explanation .= ' This role provides good opportunities to leverage your strengths.';
        } else {
            $explanation .= ' While this role may stretch your comfort zone, it offers valuable growth opportunities.';
        }

        return $explanation;
    }

    /**
     * Get job description with fallback for common titles
     */
    public function getJobDescription(): string
    {
        if ($this->description && !empty(trim($this->description))) {
            return $this->description;
        }

        $jobTitleDescriptions = [
            'Software Developer' => 'Design, build, and maintain software applications across various platforms and languages.',
            'Research Scientist' => 'Conduct original research to advance knowledge in specialized scientific fields and publish findings.',
            'Systems Analyst' => 'Evaluate and improve IT systems, bridging the gap between business needs and technical solutions.',
            'Mathematician' => 'Develop mathematical theories, conduct research, and solve complex problems using mathematical principles.',
            'University Professor' => 'Teach students, conduct research, and contribute to academic knowledge in your field of expertise.',
            'Data Scientist' => 'Analyze complex data sets to extract insights and build predictive models for business decisions.',
            'Systems Architect' => 'Design and plan the structure of complex IT systems and infrastructure solutions.',
            'Engineer' => 'Apply scientific and mathematical principles to design and develop practical solutions and products.',
            'Consultant' => 'Provide expert advice to organizations on strategic business and operational improvements.'
        ];

        return $jobTitleDescriptions[$this->title] ?? $this->description ?? 'Job description details available upon application.';
    }

    /**
     * Get work environment insights based on MBTI type
     */
    private function getEnvironmentInsights(string $mbtiType): string
    {
        $insights = [
            'E' => 'The collaborative nature of this role suits your preference for external interaction.',
            'I' => 'This position offers the focused work environment you prefer.',
            'S' => 'The practical, hands-on aspects of this job align with your detail-oriented approach.',
            'N' => 'This role provides the big-picture thinking and innovation opportunities you enjoy.',
            'T' => 'The logical, analytical requirements match your objective decision-making style.',
            'F' => 'This career allows you to make a meaningful impact on people and organizations.',
            'J' => 'The structured nature of this role suits your preference for organization and planning.',
            'P' => 'This position offers the flexibility and variety you thrive on.'
        ];

        // Return insight for the dominant trait
        $dominantTrait = $this->getDominantTrait($mbtiType);
        return $insights[$dominantTrait] ?? '';
    }

    /**
     * Determine dominant trait for insights
     */
    private function getDominantTrait(string $mbtiType): string
    {
        // Simple logic: return the first trait that has strong industry alignment
        $industryTraits = [
            'technology' => ['N', 'T'],
            'business' => ['E', 'J'],
            'healthcare' => ['F', 'S'],
            'education' => ['F', 'E'],
            'finance' => ['T', 'J']
        ];

        $relevantTraits = $industryTraits[$this->industry] ?? ['T'];
        
        foreach ($relevantTraits as $trait) {
            if (strpos($mbtiType, $trait) !== false) {
                return $trait;
            }
        }

        return $mbtiType[0]; // Fallback to first trait
    }

    /**
     * Calculate default compatibility based on MBTI traits and job industry
     */
    private function calculateDefaultCompatibility(string $mbtiType): int
    {
        $baseScore = 55;
        $industryBonus = $this->getIndustryCompatibilityBonus($mbtiType);
        $levelBonus = $this->getLevelCompatibilityBonus($mbtiType);
        
        return min(95, max(35, $baseScore + $industryBonus + $levelBonus));
    }

    /**
     * Get industry-specific compatibility bonus
     */
    private function getIndustryCompatibilityBonus(string $mbtiType): int
    {
        $industryCompatibility = [
            'technology' => [
                'INTJ' => 30, 'INTP' => 25, 'ENTJ' => 20, 'ENTP' => 20,
                'ISTJ' => 15, 'ISTP' => 20, 'ESTJ' => 15, 'ESTP' => 10
            ],
            'business' => [
                'ENTJ' => 30, 'ESTJ' => 25, 'ENFJ' => 20, 'ESFJ' => 20,
                'INTJ' => 15, 'ISTJ' => 20, 'INFJ' => 15, 'ISFJ' => 15
            ],
            'finance' => [
                'ISTJ' => 30, 'ESTJ' => 25, 'INTJ' => 20, 'ENTJ' => 20,
                'ISFJ' => 15, 'ESFJ' => 15, 'INTP' => 10, 'ENTP' => 10
            ],
            'healthcare' => [
                'ISFJ' => 30, 'ESFJ' => 25, 'INFJ' => 20, 'ENFJ' => 20,
                'ISFP' => 15, 'ESFP' => 15, 'ISTJ' => 15, 'ESTJ' => 10
            ],
            'education' => [
                'ENFJ' => 30, 'ESFJ' => 25, 'INFJ' => 20, 'ISFJ' => 20,
                'ENFP' => 15, 'ESFP' => 15, 'INFP' => 15, 'ISFP' => 10
            ],
            'marketing' => [
                'ENFP' => 30, 'ESFP' => 25, 'ENTP' => 20, 'ESTP' => 20,
                'ENFJ' => 15, 'ESFJ' => 15, 'INFP' => 10, 'ISFP' => 10
            ]
        ];

        return $industryCompatibility[$this->industry][$mbtiType] ?? 0;
    }

    /**
     * Get level-specific compatibility bonus
     */
    private function getLevelCompatibilityBonus(string $mbtiType): int
    {
        $levelCompatibility = [
            'entry' => ['E' => 5, 'S' => 5], // Extroverts and Sensors often start well
            'mid' => ['J' => 5, 'T' => 5], // Judgers and Thinkers excel in mid-level
            'senior' => ['N' => 10, 'T' => 5], // Intuitives and Thinkers in senior roles
            'executive' => ['E' => 10, 'N' => 10, 'T' => 5, 'J' => 5] // Leadership traits
        ];

        $bonus = 0;
        $levelTraits = $levelCompatibility[$this->level] ?? [];
        
        for ($i = 0; $i < 4; $i++) {
            $trait = $mbtiType[$i];
            $bonus += $levelTraits[$trait] ?? 0;
        }

        return $bonus;
    }

    /**
     * Get formatted salary range
     */
    public function getSalaryRangeAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Salary not specified';
        }

        $currency = $this->salary_currency;
        $min = $this->salary_min ? number_format($this->salary_min, 0) : null;
        $max = $this->salary_max ? number_format($this->salary_max, 0) : null;

        if ($min && $max) {
            return "{$currency} {$min} - {$max}";
        } elseif ($min) {
            return "{$currency} {$min}+";
        } elseif ($max) {
            return "Up to {$currency} {$max}";
        }

        return 'Salary not specified';
    }

    /**
     * Scope for active jobs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for jobs by industry
     */
    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }

    /**
     * Scope for jobs by level
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Get recommended jobs for MBTI type
     */
    public static function getRecommendedForMbti(string $mbtiType, int $limit = 5)
    {
        return static::active()
            ->get()
            ->map(function ($job) use ($mbtiType) {
                $job->compatibility_score = $job->getCompatibilityScore($mbtiType);
                return $job;
            })
            ->sortByDesc('compatibility_score')
            ->take($limit);
    }
}