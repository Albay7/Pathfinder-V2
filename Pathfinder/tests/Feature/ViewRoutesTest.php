<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewRoutesTest extends TestCase
{
    /** @test */
    public function home_and_core_views_render()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Pathfinder');

        $this->get('/pathfinder/career-guidance')
            ->assertStatus(200)
            ->assertSee('Career Guidance');

        $this->get('/pathfinder/skill-gap')
            ->assertStatus(200)
            ->assertSee('Skill Gap');

        $this->get('/pathfinder/career-path')
            ->assertStatus(200)
            ->assertSee('Career Path');
    }
}
