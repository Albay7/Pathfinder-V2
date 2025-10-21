<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProgress;
use App\Http\Controllers\MbtiController;
use App\Http\Controllers\PathfinderController;

class MbtiIntegrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test MBTI questionnaire submission and type calculation
     */
    public function test_mbti_questionnaire_submission()
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Prepare test data - simulating MBTI questionnaire answers
        $answers = [
            'q1' => 'E', 'q2' => 'I', 'q3' => 'E', 'q4' => 'E', 'q5' => 'E', // E dominant
            'q6' => 'S', 'q7' => 'S', 'q8' => 'S', 'q9' => 'N', 'q10' => 'S', // S dominant
            'q11' => 'T', 'q12' => 'T', 'q13' => 'T', 'q14' => 'F', 'q15' => 'T', // T dominant
            'q16' => 'J', 'q17' => 'J', 'q18' => 'J', 'q19' => 'P', 'q20' => 'J', // J dominant
        ];

        // Submit the questionnaire
        $response = $this->post(route('pathfinder.mbti-questionnaire.process'), $answers);

        // Assert the user's MBTI type was calculated and saved
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'mbti_type' => 'ESTJ', // Expected type based on the answers
        ]);

        // Assert the user is redirected to the results page
        $response->assertRedirect(route('pathfinder.mbti.results'));
    }

    /**
     * Test that MBTI type influences job recommendations
     */
    public function test_mbti_influences_job_recommendations()
    {
        // Create a user with a specific MBTI type
        $user = User::factory()->create([
            'mbti_type' => 'INTJ',
            'mbti_scores' => [
                'E_I' => ['E' => 20, 'I' => 80],
                'S_N' => ['S' => 30, 'N' => 70],
                'T_F' => ['T' => 90, 'F' => 10],
                'J_P' => ['J' => 60, 'P' => 40],
            ],
        ]);
        $this->actingAs($user);

        // Create a controller instance for testing
        $controller = new PathfinderController();

        // Use reflection to access the private method
        $reflectionMethod = new \ReflectionMethod(PathfinderController::class, 'generateJobRecommendation');
        $reflectionMethod->setAccessible(true);

        // Test with technology industry answers
        $answers = [
            'job_industry' => 'technology',
            'career_goal' => 'career_advancement',
            'work_schedule' => 'standard',
            'job_responsibilities' => 'technical_execution',
            'job_motivation' => 'growth',
        ];

        // Get recommendation with MBTI influence
        $recommendation = $reflectionMethod->invoke($controller, $answers, 'INTJ', 0.2);

        // Assert the recommendation is one of the expected INTJ recommendations
        $intjRecommendations = ['Data Scientist', 'Software Engineer', 'Systems Analyst', 'Financial Analyst', 'Business Consultant'];
        $this->assertContains($recommendation, $intjRecommendations);

        // Test with a different MBTI type
        $user->update(['mbti_type' => 'ENFP']);
        $recommendation2 = $reflectionMethod->invoke($controller, $answers, 'ENFP', 0.2);

        // Assert the recommendation is different
        $this->assertNotEquals($recommendation, $recommendation2);
    }

    /**
     * Test that MBTI results page displays correctly
     */
    public function test_mbti_results_display()
    {
        // Create a user with MBTI data
        $user = User::factory()->create([
            'mbti_type' => 'INFJ',
            'mbti_scores' => [
                'E_I' => ['E' => 30, 'I' => 70],
                'S_N' => ['S' => 20, 'N' => 80],
                'T_F' => ['T' => 40, 'F' => 60],
                'J_P' => ['J' => 55, 'P' => 45],
            ],
            'mbti_description' => 'INFJ is a rare personality type characterized by deep insights, empathy, and vision.',
        ]);
        $this->actingAs($user);

        // Visit the results page
        $response = $this->get(route('pathfinder.mbti.results'));

        // Assert the page displays correctly
        $response->assertStatus(200);
        $response->assertSee('INFJ');
        $response->assertSee('deep insights');

        // Assert career recommendations are shown
        $response->assertSee('Recommended Career Paths');

        // Assert learning style is shown
        $response->assertSee('Your Learning Style');
    }
}