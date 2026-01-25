<?php

namespace Tests\Feature;

use Tests\TestCase;

class CareerPathTest extends TestCase
{
    /** @test */
    public function career_path_show_displays_results()
    {
        $payload = [
            'current_role' => 'Entry Level Professional',
            'target_role' => 'Software Engineer',
        ];

        $response = $this->post('/pathfinder/career-path/show', $payload);
        $response->assertStatus(200)
                 ->assertSee('Tips for Success');
    }
}
