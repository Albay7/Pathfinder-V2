<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'questionnaire_id',
        'session_id',
        'responses',
        'calculated_scores',
        'recommended_courses',
        'completion_percentage',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'responses' => 'array',
        'calculated_scores' => 'array',
        'recommended_courses' => 'array',
        'completion_percentage' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user that owns this response
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the questionnaire that this response belongs to
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Scope to get completed responses
     */
    public function scopeCompleted($query)
    {
        return $query->where('completion_percentage', 100);
    }

    /**
     * Scope to get responses by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get responses by session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Check if the response is completed
     */
    public function isCompleted()
    {
        return $this->completion_percentage >= 100;
    }

    /**
     * Calculate completion percentage based on answered questions
     */
    public function calculateCompletionPercentage()
    {
        $totalQuestions = $this->questionnaire->questions()->count();
        $answeredQuestions = count($this->responses ?? []);

        if ($totalQuestions === 0) {
            return 100;
        }

        return min(100, round(($answeredQuestions / $totalQuestions) * 100));
    }

    /**
     * Update completion percentage
     */
    public function updateCompletionPercentage()
    {
        $this->completion_percentage = $this->calculateCompletionPercentage();
        
        if ($this->completion_percentage >= 100 && !$this->completed_at) {
            $this->completed_at = now();
        }

        $this->save();
    }

    /**
     * Add or update a response for a specific question
     */
    public function addResponse($questionId, $answer)
    {
        $responses = $this->responses ?? [];
        $responses[$questionId] = $answer;
        
        $this->responses = $responses;
        $this->updateCompletionPercentage();
        
        return $this;
    }

    /**
     * Calculate skill scores based on responses
     */
    public function calculateSkillScores()
    {
        $scores = [];
        $responses = $this->responses ?? [];
        
        foreach ($this->questionnaire->questions as $question) {
            if (!isset($responses[$question->id])) {
                continue;
            }

            $answer = $responses[$question->id];
            $score = $question->calculateScore($answer);
            
            if ($question->skill_category) {
                if (!isset($scores[$question->skill_category])) {
                    $scores[$question->skill_category] = [
                        'total_score' => 0,
                        'question_count' => 0,
                        'average_score' => 0
                    ];
                }
                
                $scores[$question->skill_category]['total_score'] += $score;
                $scores[$question->skill_category]['question_count']++;
                $scores[$question->skill_category]['average_score'] = 
                    $scores[$question->skill_category]['total_score'] / 
                    $scores[$question->skill_category]['question_count'];
            }
        }

        $this->calculated_scores = $scores;
        $this->save();

        return $scores;
    }

    /**
     * Get the duration of completion in minutes
     */
    public function getCompletionDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    /**
     * Get the top skill categories based on scores
     */
    public function getTopSkillCategories($limit = 3)
    {
        $scores = $this->calculated_scores ?? [];
        
        if (empty($scores)) {
            return [];
        }

        // Sort by average score descending
        uasort($scores, function ($a, $b) {
            return $b['average_score'] <=> $a['average_score'];
        });

        return array_slice($scores, 0, $limit, true);
    }
}