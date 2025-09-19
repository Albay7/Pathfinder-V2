<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MbtiPersonalityType extends Model
{
    protected $fillable = [
        'type_code',
        'name',
        'description',
        'strengths',
        'weaknesses',
        'career_paths',
        'temperament',
        'role'
    ];

    /**
     * Get the test sessions for this personality type.
     */
    public function testSessions(): HasMany
    {
        return $this->hasMany(MbtiTestSession::class, 'personality_type_id');
    }

    /**
     * Get users with this personality type.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'mbti_type', 'type_code');
    }
}
