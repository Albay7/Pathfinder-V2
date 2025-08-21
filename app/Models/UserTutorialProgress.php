<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTutorialProgress extends Model
{
    protected $fillable = [
        'user_id',
        'tutorial_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
        'time_spent_minutes',
        'user_rating',
        'notes',
        'bookmarks'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'user_rating' => 'decimal:2',
        'bookmarks' => 'array'
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tutorial for this progress.
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class);
    }

    /**
     * Scope to get progress by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get completed tutorials.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get in-progress tutorials.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope to get bookmarked tutorials.
     */
    public function scopeBookmarked($query)
    {
        return $query->where('status', 'bookmarked');
    }

    /**
     * Mark tutorial as started.
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
    }

    /**
     * Mark tutorial as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);
    }

    /**
     * Update progress percentage.
     */
    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'status' => $percentage >= 100 ? 'completed' : 'in_progress'
        ]);
        
        if ($percentage >= 100) {
            $this->markAsCompleted();
        }
    }

    /**
     * Add time spent on tutorial.
     */
    public function addTimeSpent($minutes)
    {
        $this->increment('time_spent_minutes', $minutes);
    }

    /**
     * Get formatted time spent.
     */
    public function getFormattedTimeSpentAttribute()
    {
        if (!$this->time_spent_minutes) {
            return '0 minutes';
        }
        
        $hours = floor($this->time_spent_minutes / 60);
        $minutes = $this->time_spent_minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        
        return $minutes . ' minutes';
    }
}
