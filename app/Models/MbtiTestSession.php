<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MbtiTestSession extends Model
{
    protected $fillable = [
        'user_id',
        'responses',
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
