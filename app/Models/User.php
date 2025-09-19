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
        'mbti_type',
        'mbti_scores',
        'mbti_description',
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
            'mbti_scores' => 'array',
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
     * Get the MBTI personality type description.
     */
    public function getMbtiDescription()
    {
        if (!$this->mbti_type) {
            return null;
        }
        
        $descriptions = [
            'INTJ' => 'Architect - Imaginative and strategic thinkers, with a plan for everything.',
            'INTP' => 'Logician - Innovative inventors with an unquenchable thirst for knowledge.',
            'ENTJ' => 'Commander - Bold, imaginative and strong-willed leaders, always finding a way.',
            'ENTP' => 'Debater - Smart and curious thinkers who cannot resist an intellectual challenge.',
            'INFJ' => 'Advocate - Quiet and mystical, yet very inspiring and tireless idealists.',
            'INFP' => 'Mediator - Poetic, kind and altruistic people, always eager to help a good cause.',
            'ENFJ' => 'Protagonist - Charismatic and inspiring leaders, able to mesmerize their listeners.',
            'ENFP' => 'Campaigner - Enthusiastic, creative and sociable free spirits, who can always find a reason to smile.',
            'ISTJ' => 'Logistician - Practical and fact-minded individuals, whose reliability cannot be doubted.',
            'ISFJ' => 'Defender - Very dedicated and warm protectors, always ready to defend their loved ones.',
            'ESTJ' => 'Executive - Excellent administrators, unsurpassed at managing things or people.',
            'ESFJ' => 'Consul - Extraordinarily caring, social and popular people, always eager to help.',
            'ISTP' => 'Virtuoso - Bold and practical experimenters, masters of all kinds of tools.',
            'ISFP' => 'Adventurer - Flexible and charming artists, always ready to explore and experience something new.',
            'ESTP' => 'Entrepreneur - Smart, energetic and very perceptive people, who truly enjoy living on the edge.',
            'ESFP' => 'Entertainer - Spontaneous, energetic and enthusiastic people – life is never boring around them.',
        ];
        
        return $descriptions[$this->mbti_type] ?? 'Personality type description not available.';
    }
    
    /**
     * Get career recommendations based on MBTI type.
     */
    public function getMbtiCareerRecommendations()
    {
        if (!$this->mbti_type) {
            return [];
        }
        
        $recommendations = [
            'INTJ' => ['Software Architect', 'Systems Analyst', 'Data Scientist', 'Strategic Planner', 'Investment Banker'],
            'INTP' => ['Software Developer', 'Research Scientist', 'Systems Analyst', 'Mathematician', 'University Professor'],
            'ENTJ' => ['Executive', 'Entrepreneur', 'Project Manager', 'Management Consultant', 'Business Analyst'],
            'ENTP' => ['Entrepreneur', 'Creative Director', 'Attorney', 'Marketing Director', 'Systems Analyst'],
            'INFJ' => ['Counselor', 'HR Developer', 'Writer', 'Psychologist', 'Teacher'],
            'INFP' => ['Writer', 'Graphic Designer', 'Psychologist', 'Social Worker', 'HR Specialist'],
            'ENFJ' => ['Training Manager', 'Public Relations Specialist', 'Sales Manager', 'HR Manager', 'Teacher'],
            'ENFP' => ['Journalist', 'Marketing Specialist', 'Event Planner', 'Advertising Creative', 'Public Relations'],
            'ISTJ' => ['Accountant', 'Financial Analyst', 'Project Manager', 'Database Administrator', 'Quality Assurance Specialist'],
            'ISFJ' => ['Nurse', 'Administrative Assistant', 'Customer Service Representative', 'Elementary Teacher', 'Accountant'],
            'ESTJ' => ['Operations Manager', 'Project Manager', 'Financial Manager', 'Sales Manager', 'Police Officer'],
            'ESFJ' => ['Nurse', 'Elementary Teacher', 'Sales Representative', 'HR Specialist', 'Office Manager'],
            'ISTP' => ['Civil Engineer', 'Software Developer', 'Mechanic', 'Pilot', 'Data Analyst'],
            'ISFP' => ['Graphic Designer', 'Fashion Designer', 'Interior Designer', 'Photographer', 'Veterinarian'],
            'ESTP' => ['Sales Representative', 'Marketing Executive', 'Entrepreneur', 'Project Manager', 'Police Officer'],
            'ESFP' => ['Event Planner', 'Sales Representative', 'Tour Guide', 'Public Relations Specialist', 'Performer'],
        ];
        
        return $recommendations[$this->mbti_type] ?? [];
    }
    
    /**
     * Get learning style recommendations based on MBTI type.
     */
    public function getMbtiLearningStyle()
    {
        if (!$this->mbti_type) {
            return null;
        }
        
        $styles = [
            'INTJ' => 'Conceptual and independent learning with focus on theory and logical analysis.',
            'INTP' => 'Self-directed learning with emphasis on understanding concepts and theoretical frameworks.',
            'ENTJ' => 'Structured learning with clear objectives and practical applications of theories.',
            'ENTP' => 'Debate-oriented learning with exploration of multiple perspectives and creative problem-solving.',
            'INFJ' => 'Reflective learning with focus on meaning, values, and personal growth.',
            'INFP' => 'Creative and individualized learning that aligns with personal values and interests.',
            'ENFJ' => 'Collaborative learning with focus on personal development and helping others.',
            'ENFP' => 'Experiential and group-based learning with variety and creative expression.',
            'ISTJ' => 'Structured and sequential learning with practical applications and clear instructions.',
            'ISFJ' => 'Practical learning with clear guidelines and real-world applications.',
            'ESTJ' => 'Structured learning with clear objectives, timelines, and practical outcomes.',
            'ESFJ' => 'Collaborative learning with practical applications and supportive environment.',
            'ISTP' => 'Hands-on learning with practical problem-solving and technical applications.',
            'ISFP' => 'Experiential learning with artistic expression and personal relevance.',
            'ESTP' => 'Active, hands-on learning with immediate application and problem-solving.',
            'ESFP' => 'Interactive and social learning with practical applications and group activities.',
        ];
        
        return $styles[$this->mbti_type] ?? 'Learning style information not available.';
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
