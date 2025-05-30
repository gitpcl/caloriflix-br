<?php

use App\Models\User;
use App\Models\Food;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->user = User::factory()->create();
    $this->food = Food::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Test Food',
        'protein' => 20,
        'fat' => 10,
        'carbohydrate' => 30,
    ]);
});

test('can access foods endpoint when authenticated', function() {
    // Using Laravel's built-in authentication for testing
    $this->actingAs($this->user, 'sanctum');
    
    // Make the request
    $response = $this->getJson('/api/v1/foods');
    
    // Assert it succeeds (or returns the correct error if that's what we expect)
    $response->assertStatus(200);
});

test('cannot access foods endpoint when unauthenticated', function() {
    // No authentication
    
    // Make the request
    $response = $this->getJson('/api/v1/foods');
    
    // Assert it fails with 401 Unauthorized
    $response->assertStatus(401);
});
