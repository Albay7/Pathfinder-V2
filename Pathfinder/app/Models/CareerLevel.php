<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CareerLevel extends Model
{
    protected $fillable = [
        'role_name',
        'level',
        'description',
        'salary_min',
        'salary_max',
        'salary_currency',
        'responsibilities',
        'required_skills',
        'preferred_qualifications',
        'data_version',
        'is_current',
        'scraped_at',
        'data_source',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'responsibilities' => 'array',
        'required_skills' => 'array',
        'preferred_qualifications' => 'array',
        'is_current' => 'boolean',
        'scraped_at' => 'datetime',
    ];

    /**
     * Scope to get only current data
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to get data for a specific role and level
     */
    public function scopeForRole(Builder $query, string $roleName, ?string $level = null): Builder
    {
        $query->where('role_name', $roleName);

        if ($level) {
            $query->where('level', $level);
        }

        return $query;
    }

    /**
     * Get formatted salary range
     */
    public function getFormattedSalaryAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Salary not available';
        }

        $currency = $this->salary_currency === 'PHP' ? '₱' : $this->salary_currency . ' ';

        if ($this->salary_min && $this->salary_max) {
            return $currency . number_format($this->salary_min, 0) . ' - ' .
                   $currency . number_format($this->salary_max, 0) . '/month';
        }

        if ($this->salary_min) {
            return $currency . number_format($this->salary_min, 0) . '+/month';
        }

        return 'Up to ' . $currency . number_format($this->salary_max, 0) . '/month';
    }

    /**
     * Get the data freshness status
     */
    public function getFreshnessAttribute(): string
    {
        if (!$this->scraped_at) {
            return 'unknown';
        }

        $daysOld = now()->diffInDays($this->scraped_at);

        if ($daysOld <= 30) {
            return 'fresh';
        } elseif ($daysOld <= 60) {
            return 'recent';
        } else {
            return 'stale';
        }
    }

    /**
     * Mark all records for a role as not current
     */
    public static function markAsNotCurrent(string $roleName, ?string $level = null): int
    {
        $query = static::where('role_name', $roleName);

        if ($level) {
            $query->where('level', $level);
        }

        return $query->update(['is_current' => false]);
    }
}
