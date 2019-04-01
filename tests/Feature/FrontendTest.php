<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FrontendTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanListExperiences()
    {
        $response = $this->json('GET', '/api/experiences');
        $response->assertStatus(200);
        
    }

    public function testCanListEvents(){
        $response = $this->json('GET', '/api/events');
        $response->assertStatus(200);
    }

    public function testCanListRestaurants(){
        $response = $this->json("GET", '/api/restaurants');
        $response->assertStatus(200);
    }

    public function testCanSearchExperienceByBudget(){
        $response = $this->json("GET", '/api/experiences', ['min_price' => 50, 'max_price' => 90]);
        $response->assertSee('80');
    }
}
