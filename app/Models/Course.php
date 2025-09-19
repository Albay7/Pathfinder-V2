<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'field',
        'level',
        'duration',
        'career_outcomes',
        'skills_developed',
        'mbti_compatibility',
        'mbti_explanation',
        'institution',
        'tuition_fee',
        'prerequisites',
        'subjects',
        'is_active'
    ];

    protected $casts = [
        'career_outcomes' => 'array',
        'skills_developed' => 'array',
        'mbti_compatibility' => 'array',
        'prerequisites' => 'array',
        'subjects' => 'array',
        'tuition_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get users who have this course in their recommendations
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_course_recommendations')
                    ->withPivot('compatibility_score', 'recommended_at')
                    ->withTimestamps();
    }

    /**
     * Users who have been recommended this course
     */
    public function recommendedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_course_recommendations')
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
        $baseExplanation = $this->mbti_explanation ?? 'This course aligns with your personality traits.';
        
        $strengthsMap = [
            'E' => 'collaborative projects and group work',
            'I' => 'independent study and research opportunities',
            'S' => 'practical applications and hands-on learning',
            'N' => 'theoretical concepts and innovative thinking',
            'T' => 'logical analysis and systematic problem-solving',
            'F' => 'people-centered applications and ethical considerations',
            'J' => 'structured curriculum and clear objectives',
            'P' => 'flexible learning paths and diverse opportunities'
        ];

        $strengths = [];
        for ($i = 0; $i < 4; $i++) {
            $trait = $mbtiType[$i];
            if (isset($strengthsMap[$trait])) {
                $strengths[] = $strengthsMap[$trait];
            }
        }

        $explanation = $baseExplanation;
        if (!empty($strengths)) {
            $explanation .= ' Your ' . $mbtiType . ' personality particularly benefits from ' . implode(', ', array_slice($strengths, 0, 2)) . '.';
        }

        if ($score >= 80) {
            $explanation .= ' This is an excellent match for your personality type.';
        } elseif ($score >= 60) {
            $explanation .= ' This course offers good alignment with your natural preferences.';
        } else {
            $explanation .= ' While this course may present some challenges, it can help you develop new skills and perspectives.';
        }

        return $explanation;
    }

    /**
     * Calculate default compatibility based on MBTI traits and course field
     */
    private function calculateDefaultCompatibility(string $mbtiType): int
    {
        $baseScore = 50;
        $fieldBonus = $this->getFieldCompatibilityBonus($mbtiType);
        
        return min(95, max(30, $baseScore + $fieldBonus));
    }

    /**
     * Get field-specific compatibility bonus
     */
    private function getFieldCompatibilityBonus(string $mbtiType): int
    {
        $fieldCompatibility = [
            'engineering' => ['ISTJ' => 25, 'INTJ' => 30, 'ISTP' => 25, 'INTP' => 20],
            'computer_science' => ['INTJ' => 35, 'INTP' => 30, 'ISTJ' => 25, 'ISTP' => 20],
            'business' => ['ENTJ' => 35, 'ESTJ' => 30, 'ENFJ' => 25, 'ESFJ' => 20],
            'education' => ['ENFJ' => 35, 'ESFJ' => 30, 'INFJ' => 25, 'ISFJ' => 25],
            'accounting' => ['ISTJ' => 35, 'ESTJ' => 30, 'ISFJ' => 25, 'INTJ' => 20],
            'liberal_arts' => ['INFP' => 35, 'ENFP' => 30, 'INFJ' => 25, 'ENFJ' => 20],
            'tourism' => ['ESFP' => 35, 'ENFP' => 30, 'ESFJ' => 25, 'ESTP' => 20],
            'science' => ['INTP' => 35, 'INTJ' => 30, 'ISTJ' => 25, 'INFJ' => 20],
            'criminal_justice' => ['ESTJ' => 35, 'ISTJ' => 30, 'ESTP' => 25, 'ISTP' => 20]
        ];

        return $fieldCompatibility[$this->field][$mbtiType] ?? 0;
    }

    /**
     * Scope for active courses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for courses by field
     */
    public function scopeByField($query, string $field)
    {
        return $query->where('field', $field);
    }

    /**
     * Get recommended courses for MBTI type
     */
    public static function getRecommendedForMbti(string $mbtiType, int $limit = 5)
    {
        return static::active()
            ->get()
            ->map(function ($course) use ($mbtiType) {
                $course->compatibility_score = $course->getCompatibilityScore($mbtiType);
                return $course;
            })
            ->sortByDesc('compatibility_score')
            ->take($limit);
    }
}