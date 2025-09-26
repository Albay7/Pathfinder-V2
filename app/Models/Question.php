<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'question_type',
        'options',
        'scoring_weights',
        'skill_category',
        'order',
        'is_required',
        'help_text'
    ];

    protected $casts = [
        'options' => 'array',
        'scoring_weights' => 'array',
        'is_required' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the questionnaire that owns this question
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Scope to get questions in order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope to get required questions
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope to get questions by skill category
     */
    public function scopeBySkillCategory($query, $category)
    {
        return $query->where('skill_category', $category);
    }

    /**
     * Validate if an answer is valid for this question
     */
    public function isValidAnswer($answer)
    {
        switch ($this->question_type) {
            case 'multiple_choice':
                return in_array($answer, array_keys($this->options ?? []));
            
            case 'scale':
                return is_numeric($answer) && $answer >= 1 && $answer <= 5;
            
            case 'yes_no':
                return in_array($answer, ['yes', 'no', 'true', 'false', '1', '0']);
            
            case 'text':
                return is_string($answer) && strlen(trim($answer)) > 0;
            
            default:
                return false;
        }
    }

    /**
     * Calculate score for a given answer
     */
    public function calculateScore($answer)
    {
        if (!$this->isValidAnswer($answer)) {
            return 0;
        }

        $weights = $this->scoring_weights ?? [];

        switch ($this->question_type) {
            case 'multiple_choice':
                return $weights[$answer] ?? 0;
            
            case 'scale':
                // For scale questions, score is typically the answer value
                return (int) $answer;
            
            case 'yes_no':
                $normalizedAnswer = in_array($answer, ['yes', 'true', '1']) ? 'yes' : 'no';
                return $weights[$normalizedAnswer] ?? 0;
            
            case 'text':
                // Text questions might have keyword-based scoring
                if (empty($weights)) {
                    return 1; // Default score for text answers
                }
                
                $score = 0;
                foreach ($weights as $keyword => $weight) {
                    if (stripos($answer, $keyword) !== false) {
                        $score += $weight;
                    }
                }
                return $score;
            
            default:
                return 0;
        }
    }

    /**
     * Get formatted options for frontend display
     */
    public function getFormattedOptionsAttribute()
    {
        if ($this->question_type === 'scale') {
            return [
                '1' => 'Strongly Disagree',
                '2' => 'Disagree', 
                '3' => 'Neutral',
                '4' => 'Agree',
                '5' => 'Strongly Agree'
            ];
        }

        if ($this->question_type === 'yes_no') {
            return [
                'yes' => 'Yes',
                'no' => 'No'
            ];
        }

        return $this->options ?? [];
    }
}