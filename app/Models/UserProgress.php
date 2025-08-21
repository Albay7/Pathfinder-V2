<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'feature_type',
        'assessment_type',
        'questionnaire_answers',
        'recommendation',
        'current_role',
        'target_role',
        'current_skills',
        'analysis_result',
        'match_percentage',
        'completed'
    ];

    protected $casts = [
        'questionnaire_answers' => 'array',
        'current_skills' => 'array',
        'analysis_result' => 'array',
        'match_percentage' => 'decimal:2',
        'completed' => 'boolean'
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get progress by feature type.
     */
    public function scopeByFeature($query, $featureType)
    {
        return $query->where('feature_type', $featureType);
    }

    /**
     * Scope to get completed progress.
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Scope to get recent progress.
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
