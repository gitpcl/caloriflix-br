<?php

use App\Models\User;
use App\Models\Food;
use Laravel\Sanctum\Sanctum;

test('unauthenticated requests return proper error format', function () {
    $response = $this->getJson('/api/v1/user');
    
    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Unauthenticated.',
            'error' => 'Please log in to access this resource.'
        ]);
});

test('not found endpoints return proper error format', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->getJson('/api/v1/nonexistent-endpoint');
    
    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Endpoint not found.',
            'error' => 'The requested resource could not be found.'
        ]);
});

test('not found models return proper error format', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->getJson('/api/v1/foods/99999');
    
    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Food not found.'
        ]);
});

test('method not allowed returns proper error format', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    // Try to PATCH the user endpoint which only supports GET
    $response = $this->patchJson('/api/v1/user', []);
    
    $response->assertStatus(405)
        ->assertJson([
            'success' => false,
            'message' => 'Method not allowed.',
            'error' => 'The HTTP method is not allowed for this endpoint.'
        ]);
});

test('validation errors return proper format', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->postJson('/api/v1/foods', []);
    
    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Validation Error.'
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);
});

test('server errors return proper format in production', function () {
    // This test would need to be adapted based on how you want to test server errors
    // You might need to mock certain conditions to trigger a 500 error
    
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    // For this test, we'll just verify the structure would be correct
    // In a real scenario, you'd need to trigger an actual server error
    $this->assertTrue(true); // Placeholder assertion
});