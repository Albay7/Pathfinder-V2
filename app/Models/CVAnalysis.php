<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CVAnalysis extends Model
{
    use HasFactory;

    protected $table = 'cv_analyses';

    protected $fillable = [
        'session_id',
        'user_id',
        'file_name',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'extracted_text',
        'skills_extracted',
        'skill_vector',
        'analysis_summary',
        'job_matches',
        'processing_time',
        'status',
        'error_message',
    ];

    protected $casts = [
        'skills_extracted' => 'array',
        'skill_vector' => 'array',
        'analysis_summary' => 'array',
        'job_matches' => 'array',
        'processing_time' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the CV analysis.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for completed analyses.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed analyses.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for processing analyses.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for user analyses (authenticated users).
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for session analyses (anonymous users).
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get the top skills from the analysis.
     */
    public function getTopSkillsAttribute($limit = 10)
    {
        if (!$this->skills_extracted) {
            return [];
        }

        // Sort skills by TF-IDF score and return top N
        $skills = collect($this->skills_extracted)
            ->sortByDesc('tfidf_score')
            ->take($limit)
            ->pluck('skill')
            ->toArray();

        return $skills;
    }

    /**
     * Get the best job match.
     */
    public function getBestJobMatchAttribute()
    {
        if (!$this->job_matches || empty($this->job_matches)) {
            return null;
        }

        return collect($this->job_matches)->first();
    }

    /**
     * Get total skills found.
     */
    public function getTotalSkillsFoundAttribute()
    {
        return $this->skills_extracted ? count($this->skills_extracted) : 0;
    }

    /**
     * Get total job matches.
     */
    public function getTotalJobMatchesAttribute()
    {
        return $this->job_matches ? count($this->job_matches) : 0;
    }

    /**
     * Check if analysis is successful.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if analysis failed.
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if analysis is still processing.
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Mark analysis as completed.
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark analysis as failed.
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get file size in human readable format.
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}