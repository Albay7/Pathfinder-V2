<?php

namespace Tests\Feature;

use Tests\TestCase;

class MbtiFlowTest extends TestCase
{
    /** @test */
    public function mbti_questionnaire_and_results_routes_work()
    {
        $this->get('/pathfinder/mbti-questionnaire')
             ->assertStatus(200)
             ->assertSee('MBTI Personality Assessment');

        // Results page should be accessible or redirect appropriately
        $this->get('/pathfinder/mbti-results')
             ->assertStatus(200);
    }
}
