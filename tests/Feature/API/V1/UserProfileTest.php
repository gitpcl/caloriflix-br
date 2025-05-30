<?php

use App\Models\User;
use App\Models\UserProfile;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

// Test show endpoint
test('user can view their profile', function () {
    Sanctum::actingAs($this->user);
    
    // Create a profile for the user
    UserProfile::factory()->create([
        'user_id' => $this->user->id,
        'height' => 180,
        'weight' => 75.0,
        'gender' => 'male',
        'age' => 25,
    ]);
    
    $response = $this->getJson('/api/v1/profile');
    
    $response->assertStatus(200)
        ->assertJsonPath('data.user_id', $this->user->id)
        ->assertJsonPath('data.height', 180)
        ->assertJsonPath('data.weight', '75.0') // Decimal values are returned as strings
        ->assertJsonPath('data.gender', 'male');
});

// Test update endpoint
test('user can update their profile', function () {
    Sanctum::actingAs($this->user);
    
    // Create an existing profile
    UserProfile::factory()->create([
        'user_id' => $this->user->id,
    ]);
    
    $response = $this->putJson('/api/v1/profile', [
        'height' => 182,
        'weight' => 78.0,
        'gender' => 'male',
        'activity_level' => 'moderately_active',
    ]);
    
    $response->assertStatus(200)
        ->assertJsonPath('data.height', 182)
        ->assertJsonPath('data.weight', '78.0') // Decimal values are returned as strings
        ->assertJsonPath('data.gender', 'male')
        ->assertJsonPath('data.activity_level', 'moderately_active');
        
    $this->assertDatabaseHas('user_profiles', [
        'user_id' => $this->user->id,
        'height' => 182,
        'weight' => 78.0,
        'gender' => 'male',
        'activity_level' => 'moderately_active',
    ]);
});

// Test create profile if not exists
test('user can create a profile if it does not exist', function () {
    Sanctum::actingAs($this->user);
    
    // Ensure no profile exists
    UserProfile::where('user_id', $this->user->id)->delete();
    
    $response = $this->putJson('/api/v1/profile', [
        'height' => 175,
        'weight' => 70.0,
        'gender' => 'female',
        'age' => 30,
    ]);
    
    $response->assertStatus(200)
        ->assertJsonPath('data.height', 175)
        ->assertJsonPath('data.weight', '70.0') // Decimal values are returned as strings
        ->assertJsonPath('data.gender', 'female')
        ->assertJsonPath('data.age', 30);
        
    $this->assertDatabaseHas('user_profiles', [
        'user_id' => $this->user->id,
        'height' => 175,
        'weight' => 70.0,
        'gender' => 'female',
        'age' => 30,
    ]);
});

// Test validation
test('user cannot update profile with invalid data', function () {
    Sanctum::actingAs($this->user);
    
    $response = $this->putJson('/api/v1/profile', [
        'height' => -10, // Invalid negative height
        'weight' => 'not-a-number', // Invalid weight
        'gender' => 'invalid-gender', // Invalid gender
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['height', 'weight', 'gender']);
});

// Test unauthenticated access
test('unauthenticated user cannot access profile endpoints', function () {
    // Make request without authentication
    $response = $this->getJson('/api/v1/profile');
    
    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});
