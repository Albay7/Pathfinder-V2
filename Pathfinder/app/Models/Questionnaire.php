<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_category',
        'target_audience',
        'estimated_duration_minutes',
        'skills_assessed',
        'career_paths',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'skills_assessed' => 'array',
        'career_paths' => 'array',
        'is_active' => 'boolean',
        'estimated_duration_minutes' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Get the questions for this questionnaire
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get the responses for this questionnaire
     */
    public function responses(): HasMany
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    /**
     * Scope to get only active questionnaires
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get questionnaires by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('course_category', $category);
    }

    /**
     * Get questionnaires ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get the total number of questions in this questionnaire
     */
    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Get the average completion time for this questionnaire
     */
    public function getAverageCompletionTimeAttribute()
    {
        $completedResponses = $this->responses()
            ->whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->get();

        if ($completedResponses->isEmpty()) {
            return $this->estimated_duration_minutes;
        }

        $totalMinutes = $completedResponses->sum(function ($response) {
            return $response->started_at->diffInMinutes($response->completed_at);
        });

        return round($totalMinutes / $completedResponses->count());
    }

    /**
     * Get completion rate for this questionnaire
     */
    public function getCompletionRateAttribute()
    {
        $totalResponses = $this->responses()->count();
        
        if ($totalResponses === 0) {
            return 0;
        }

        $completedResponses = $this->responses()
            ->where('completion_percentage', 100)
            ->count();

        return round(($completedResponses / $totalResponses) * 100, 2);
    }
}