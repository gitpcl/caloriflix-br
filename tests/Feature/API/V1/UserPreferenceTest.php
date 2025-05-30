<?php

use App\Models\User;
use App\Models\UserPreference;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

// Test show endpoint
test('user can view their preferences', function () {
    Sanctum::actingAs($this->user);
    
    UserPreference::factory()->create([
        'user_id' => $this->user->id,
        'language' => 'pt',
        'silent_mode_enabled' => true,
        'daily_log_enabled' => true,
    ]);
    
    $response = $this->getJson('/api/v1/preferences');
    
    $response->assertStatus(200)
        ->assertJsonPath('data.user_id', $this->user->id)
        ->assertJsonPath('data.language', 'pt')
        ->assertJsonPath('data.silent_mode_enabled', true)
        ->assertJsonPath('data.daily_log_enabled', true);
});

// Test update endpoint
test('user can update their preferences', function () {
    Sanctum::actingAs($this->user);
    
    $preference = UserPreference::factory()->create([
        'user_id' => $this->user->id,
        'language' => 'en',
        'silent_mode_enabled' => false,
        'daily_log_enabled' => false,
    ]);
    
    $response = $this->putJson('/api/v1/preferences', [
        'language' => 'pt',
        'silent_mode_enabled' => true,
        'daily_log_enabled' => true,
        'detailed_foods_enabled' => true,
    ]);
    
    $response->assertStatus(200)
        ->assertJsonPath('data.language', 'pt')
        ->assertJsonPath('data.silent_mode_enabled', true)
        ->assertJsonPath('data.daily_log_enabled', true)
        ->assertJsonPath('data.detailed_foods_enabled', true);
        
    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
        'language' => 'pt',
        'silent_mode_enabled' => 1,
        'daily_log_enabled' => 1,
    ]);
});

// Test that user can create preferences if they don't exist
test('user can create preferences if they do not exist', function () {
    Sanctum::actingAs($this->user);
    
    // Ensure no preferences exist
    $this->assertDatabaseMissing('user_preferences', [
        'user_id' => $this->user->id,
    ]);
    
    $response = $this->putJson('/api/v1/preferences', [
        'language' => 'es',
        'silent_mode_enabled' => true,
        'keto_diet_enabled' => true,
    ]);
    
    $response->assertStatus(200)
        ->assertJsonPath('data.language', 'es')
        ->assertJsonPath('data.silent_mode_enabled', true)
        ->assertJsonPath('data.keto_diet_enabled', true);
        
    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
        'language' => 'es',
        'silent_mode_enabled' => 1,
        'keto_diet_enabled' => 1,
    ]);
});

// Test validation
test('user cannot update preferences with invalid data', function () {
    Sanctum::actingAs($this->user);
    
    UserPreference::factory()->create([
        'user_id' => $this->user->id,
    ]);
    
    $response = $this->putJson('/api/v1/preferences', [
        'language' => 'invalid', // Should be 2 characters
        'theme' => 'invalid', // Should be light or dark
        'display_units' => 'invalid', // Should be metric or imperial
        'notifications_enabled' => 'not-a-boolean', // Should be boolean
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['language', 'theme', 'display_units', 'notifications_enabled']);
});

// Test authorization
test('unauthenticated user cannot access preferences endpoints', function () {
    // Make request without authentication
    $response = $this->getJson('/api/v1/preferences');
    
    $response->assertStatus(401);
});
