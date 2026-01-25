<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CareerLadder extends Model
{
    protected $fillable = [
        'target_role',
        'step_role',
        'level',
        'sequence_order',
        'prerequisites',
        'typical_duration_months',
        'min_years_experience',
        'max_years_experience',
        'transition_requirements',
        'is_active',
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'typical_duration_months' => 'integer',
        'min_years_experience' => 'integer',
        'max_years_experience' => 'integer',
        'is_active' => 'boolean',
        'sequence_order' => 'integer',
    ];

    /**
     * Get the career level details for this ladder step
     */
    public function careerLevel(): HasOne
    {
        return $this->hasOne(CareerLevel::class, 'role_name', 'step_role')
            ->where('level', $this->level)
            ->where('is_current', true);
    }

    /**
     * Get the complete career progression for a target role
     */
    public static function getProgression(string $targetRole): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('target_role', $targetRole)
            ->where('is_active', true)
            ->orderBy('sequence_order')
            ->get();
    }

    /**
     * Get progression starting from a specific level
     */
    public static function getProgressionFromLevel(string $targetRole, string $startLevel): \Illuminate\Database\Eloquent\Collection
    {
        $startSequence = static::where('target_role', $targetRole)
            ->where('level', $startLevel)
            ->where('is_active', true)
            ->value('sequence_order') ?? 1;

        return static::where('target_role', $targetRole)
            ->where('sequence_order', '>=', $startSequence)
            ->where('is_active', true)
            ->orderBy('sequence_order')
            ->get();
    }
}
