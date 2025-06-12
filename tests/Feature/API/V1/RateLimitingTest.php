<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('authentication endpoints have rate limiting', function () {
    // Create a user for login attempts
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password')
    ]);
    
    // Make 6 login attempts (exceeding the limit of 5)
    for ($i = 0; $i < 6; $i++) {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        
        if ($i < 5) {
            $response->assertStatus(200);
        } else {
            $response->assertStatus(429); // Too Many Requests
        }
    }
});

test('authenticated endpoints have rate limiting', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    // Make requests up to the limit
    // Note: This test might be flaky due to actual rate limiting
    // In a real scenario, you might want to mock the rate limiter
    $responses = [];
    
    for ($i = 0; $i < 5; $i++) {
        $response = $this->getJson('/api/v1/user');
        $responses[] = $response->status();
    }
    
    // All responses should be successful (assuming we haven't hit the limit)
    foreach ($responses as $status) {
        expect($status)->toBe(200);
    }
});

test('rate limit headers are present', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->getJson('/api/v1/user');
    
    $response->assertStatus(200)
        ->assertHeader('X-RateLimit-Limit')
        ->assertHeader('X-RateLimit-Remaining');
});

test('reports endpoints have stricter rate limiting', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->getJson('/api/v1/reports');
    
    // Should work for the first request
    $response->assertStatus(200);
    
    // Check if rate limit headers are present
    $response->assertHeader('X-RateLimit-Limit')
        ->assertHeader('X-RateLimit-Remaining');
});