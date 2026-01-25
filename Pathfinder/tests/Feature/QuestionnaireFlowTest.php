<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuestionnaireFlowTest extends TestCase
{
    /** @test */
    public function questionnaire_process_returns_recommendations_view()
    {
        $payload = [
            'type' => 'job',
            'selected_category' => 'Technology',
            'all_responses' => json_encode([
                'programming' => 0.7,
                'web' => 0.6,
                'database' => 0.5,
            ]),
        ];

        $response = $this->post('/pathfinder/questionnaire/process', $payload);
        $response->assertStatus(200)
                 ->assertSee('Analyze Skill Requirements');
    }
}
