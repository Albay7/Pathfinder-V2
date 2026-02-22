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
            'INTJ' => 'Strategic visionaries who blend long-range vision with disciplined execution. INTJs excel at developing comprehensive systems and implementing transformative ideas. They thrive in environments requiring independent thinking, strategic planning, and the ability to see patterns others miss.',
            'INTP' => 'Innovative problem-solvers driven by an endless curiosity about how things work. INTPs love diving deep into complex theoretical concepts and developing novel solutions. They bring original thinking and analytical rigor to fields ranging from technology to pure research.',
            'ENTJ' => 'Natural leaders with a strategic mindset and commanding presence. ENTJs excel at organizing people and resources toward ambitious goals. Their directness, decisiveness, and ability to inspire teams make them effective executives, strategists, and visionary leaders.',
            'ENTP' => 'Skilled debaters and enterprising innovators who thrive on intellectual challenges. ENTPs love exploring new ideas, challenging assumptions, and finding unconventional solutions. They bring adaptability, strategic thinking, and persuasive communication to dynamic environments.',
            'INFJ' => 'Thoughtful idealists driven by a deep desire to help others and make a positive impact. INFJs combine intuitive insight with genuine empathy, making them natural counselors and mentors. They excel at understanding people\'s potential and inspiring meaningful personal growth.',
            'INFP' => 'Creative idealists who follow their authentic values with quiet determination. INFPs bring depth, authenticity, and compassion to their work and relationships. They excel in roles allowing personal meaning-making and helping others align with their own values.',
            'ENFJ' => 'Charismatic leaders who inspire others through genuine care and compelling vision. ENFJs combine emotional intelligence with natural persuasiveness to motivate teams toward shared goals. They thrive in environments where they can develop people and drive meaningful change.',
            'ENFP' => 'Enthusiastic catalysts who bring energy, creativity, and social passion to any endeavor. ENFPs excel at connecting with people, exploring diverse perspectives, and generating innovative ideas. They thrive in dynamic roles requiring adaptability, emotional connection, and creative problem-solving.',
            'ISTJ' => 'Dependable organizers who take pride in executing plans with precision and integrity. ISTJs create order and efficiency through systematic thinking and unwavering commitment to responsibility. They excel in roles requiring careful planning, detailed execution, and reliable follow-through.',
            'ISFJ' => 'Loyal protectors who create harmony through quiet dedication and genuine care for others\' wellbeing. ISFJs notice details others miss and work tirelessly behind the scenes to support their communities. They excel in roles combining practical service with meaningful human connection.',
            'ESTJ' => 'Efficient administrators who command respect through competence, decisiveness, and clear standards. ESTJs excel at establishing structure, coordinating teams, and ensuring results. Their directness and organizational skill make them effective managers and reliable leaders in complex environments.',
            'ESFJ' => 'Warmhearted organizers who bring people together and create supportive environments. ESFJs combine practical efficiency with genuine interest in others\' happiness and wellbeing. They excel in roles emphasizing collaboration, community service, and creating positive team dynamics.',
            'ISTP' => 'Pragmatic problem-solvers with a hands-on approach to understanding how things work. ISTPs combine logical analysis with practical troubleshooting ability, excelling in technical fields. They bring cool objectivity and mechanical insight to complex technical challenges.',
            'ISFP' => 'Artistic explorers who bring aesthetic sensitivity and authentic self-expression to their pursuits. ISFPs notice beauty in the present moment and create meaningful experiences through their creativity. They thrive in roles allowing personal freedom, artistic expression, and hands-on engagement.',
            'ESTP' => 'Dynamic risk-takers who seize opportunities and navigate challenges with resourceful adaptability. ESTPs bring energy, pragmatism, and social confidence to high-stakes environments. They excel in roles requiring quick decision-making, negotiation, and the ability to think on their feet.',
            'ESFP' => 'Spontaneous performers who bring enthusiasm, charm, and joy to every situation. ESFPs excel at connecting with others emotionally and creating memorable experiences. They thrive in dynamic, people-focused roles where their energy, optimism, and social skill shine.',
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
