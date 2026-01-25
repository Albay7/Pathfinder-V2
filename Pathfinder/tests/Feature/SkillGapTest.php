<?php

namespace Tests\Feature;

use Tests\TestCase;

class SkillGapTest extends TestCase
{
    /** @test */
    public function skill_gap_analyze_shows_recommended_tutorials()
    {
        $payload = [
            'target_role' => 'Data Analyst',
            'current_skills' => ['excel', 'sql'],
        ];

        $response = $this->post('/pathfinder/skill-gap/analyze', $payload);
        $response->assertStatus(200)
                 ->assertSee('Recommended Tutorials')
                 ->assertSee('Learning Resources');
    }
}
