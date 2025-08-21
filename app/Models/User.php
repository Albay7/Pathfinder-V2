<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's progress records.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the user's recent progress.
     */
    public function recentProgress($limit = 5)
    {
        return $this->progress()->recent($limit)->get();
    }

    /**
     * Get progress count by feature type.
     */
    public function getProgressCount($featureType = null)
    {
        $query = $this->progress()->completed();
        
        if ($featureType) {
            $query->byFeature($featureType);
        }
        
        return $query->count();
    }

    /**
     * Calculate overall progress score.
     */
    public function getProgressScore()
    {
        $totalFeatures = 3; // career_guidance, career_path, skill_gap
        $completedFeatures = 0;
        
        $features = ['career_guidance', 'career_path', 'skill_gap'];
        
        foreach ($features as $feature) {
            if ($this->progress()->byFeature($feature)->completed()->exists()) {
                $completedFeatures++;
            }
        }
        
        return round(($completedFeatures / $totalFeatures) * 100);
    }

    /**
     * Get the user's tutorial progress records.
     */
    public function tutorialProgress(): HasMany
    {
        return $this->hasMany(UserTutorialProgress::class);
    }

    /**
     * Get completed tutorials count.
     */
    public function getCompletedTutorialsCount()
    {
        return $this->tutorialProgress()->completed()->count();
    }

    /**
     * Get in-progress tutorials count.
     */
    public function getInProgressTutorialsCount()
    {
        return $this->tutorialProgress()->inProgress()->count();
    }

    /**
     * Get bookmarked tutorials count.
     */
    public function getBookmarkedTutorialsCount()
    {
        return $this->tutorialProgress()->bookmarked()->count();
    }

    /**
     * Get total time spent on tutorials.
     */
    public function getTotalTutorialTimeSpent()
    {
        return $this->tutorialProgress()->sum('time_spent_minutes');
    }
}
