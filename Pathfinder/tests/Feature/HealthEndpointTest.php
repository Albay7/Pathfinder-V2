<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    /** @test */
    public function health_endpoint_returns_ok_json()
    {
        $response = $this->get('/health');
        $response->assertStatus(200)
                 ->assertJsonStructure(['status','timestamp','service','php_version','laravel_version'])
                 ->assertJson(['status' => 'ok', 'service' => 'pathfinder-app']);
    }
}
