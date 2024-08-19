<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Make a GET request to the home route
        $response = $this->get('/');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);
    }
    

    
}
