<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MbtiTestSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_type',
        'responses',
        'questions_asked',
        'rl_predictions',
        'final_result',
        'questions_used',
        'efficiency',
        'confidence',
        'e_score',
        'i_score',
        's_score',
        'n_score',
        't_score',
        'f_score',
        'j_score',
        'p_score',
        'result_type',
        'personality_type_id',
        'completed',
        'completed_at'
    ];

    protected $casts = [
        'responses' => 'array',
        'questions_asked' => 'array',
        'rl_predictions' => 'array',
        'final_result' => 'array',
        'efficiency' => 'decimal:4',
        'confidence' => 'decimal:4',
        'completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the test session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the personality type result.
     */
    public function personalityType(): BelongsTo
    {
        return $this->belongsTo(MbtiPersonalityType::class, 'personality_type_id');
    }

    /**
     * Scope for adaptive sessions
     */
    public function scopeAdaptive($query)
    {
        return $query->where('session_type', 'adaptive');
    }

    /**
     * Scope for traditional sessions
     */
    public function scopeTraditional($query)
    {
        return $query->where('session_type', 'traditional');
    }

    /**
     * Get efficiency percentage
     */
    public function getEfficiencyPercentageAttribute()
    {
        return $this->efficiency ? round($this->efficiency * 100, 1) : null;
    }

    /**
     * Get confidence percentage
     */
    public function getConfidencePercentageAttribute()
    {
        return $this->confidence ? round($this->confidence * 100, 1) : null;
    }

    /**
     * Get time saved compared to traditional assessment
     */
    public function getTimeSavedAttribute()
    {
        if (!$this->questions_used) return null;
        
        $traditionalQuestions = 60;
        $questionsSaved = $traditionalQuestions - $this->questions_used;
        return round(($questionsSaved / $traditionalQuestions) * 100, 1);
    }

    /**
     * Calculate and return the MBTI type based on scores.
     */
    public function calculateMbtiType(): string
    {
        $type = '';

        // Extraversion vs Introversion
        $type .= $this->e_score > $this->i_score ? 'E' : 'I';

        // Sensing vs Intuition
        $type .= $this->s_score > $this->n_score ? 'S' : 'N';

        // Thinking vs Feeling
        $type .= $this->t_score > $this->f_score ? 'T' : 'F';

        // Judging vs Perceiving
        $type .= $this->j_score > $this->p_score ? 'J' : 'P';

        return $type;
    }
}
