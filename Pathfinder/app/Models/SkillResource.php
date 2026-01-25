<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillResource extends Model
{
    use HasFactory;

    protected $table = 'skill_resources';

    protected $fillable = [
        'job_category',
        'skill_key',
        'skill_display_name',
        'resource_label',
        'url',
        'description',
        'platform',
        'level',
        'is_playlist',
        'duration_minutes',
        'tags',
    ];

    protected $casts = [
        'is_playlist' => 'boolean',
        'duration_minutes' => 'integer',
        'tags' => 'array',
    ];
}
