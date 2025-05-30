<?php

use App\Models\Measurement;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all measurements', function () {
    Measurement::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/measurements');
    
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'user_id', 'type', 'value', 'date', 'time', 'notes', 'created_at', 'updated_at'
                ]
            ]
        ]);
});

// Test store endpoint
test('user can create a new measurement', function () {
    $measurementData = [
        'type' => 'glucose',
        'value' => 120,
        'date' => now()->format('Y-m-d'),
        'time' => '08:00:00',
        'notes' => 'Fasting measurement',
    ];
    
    $response = $this->postJson('/api/v1/measurements', $measurementData);
    
    $response->assertStatus(201)
        ->assertJsonFragment([
            'type' => 'glucose',
            'value' => 120,
        ]);
        
    $this->assertDatabaseHas('measurements', [
        'user_id' => $this->user->id,
        'type' => 'glucose',
        'value' => 120,
    ]);
});

// Test show endpoint
test('user can view a specific measurement', function () {
    $measurement = Measurement::factory()->create([
        'user_id' => $this->user->id,
        'type' => 'weight',
        'value' => 75.5,
    ]);
    
    $response = $this->getJson("/api/v1/measurements/{$measurement->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $measurement->id,
            'type' => 'weight',
            'value' => 75.5,
        ]);
});

// Test update endpoint
test('user can update a measurement', function () {
    $measurement = Measurement::factory()->create([
        'user_id' => $this->user->id,
        'type' => 'weight',
        'value' => 80,
        'notes' => 'Original notes',
    ]);
    
    $response = $this->putJson("/api/v1/measurements/{$measurement->id}", [
        'value' => 78.5,
        'notes' => 'Updated notes',
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'value' => 78.5,
            'notes' => 'Updated notes',
        ]);
        
    $this->assertDatabaseHas('measurements', [
        'id' => $measurement->id,
        'value' => 78.5,
        'notes' => 'Updated notes',
    ]);
});

// Test destroy endpoint
test('user can delete a measurement', function () {
    $measurement = Measurement::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/measurements/{$measurement->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('measurements', ['id' => $measurement->id]);
});

// Test latest endpoint
test('user can view latest measurements', function () {
    // Create measurements with different dates
    Measurement::factory()->create([
        'user_id' => $this->user->id,
        'type' => 'glucose',
        'date' => now()->subDays(5)->format('Y-m-d'),
        'value' => 110,
    ]);
    
    Measurement::factory()->create([
        'user_id' => $this->user->id,
        'type' => 'glucose',
        'date' => now()->format('Y-m-d'),
        'value' => 120,
    ]);
    
    $response = $this->getJson('/api/v1/measurements/latest');
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'type' => 'glucose',
            'value' => 120,
        ]);
});

// Test byType endpoint
test('user can view measurements by type', function () {
    // Create glucose measurements
    Measurement::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'type' => 'glucose',
    ]);
    
    // Create weight measurements
    Measurement::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'type' => 'weight',
    ]);
    
    $response = $this->getJson('/api/v1/measurements/type/glucose');
    
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

// Test authorization
test('user cannot view measurements created by other users', function () {
    $otherUser = User::factory()->create();
    $measurement = Measurement::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/measurements/{$measurement->id}");
    
    $response->assertStatus(403);
});

test('user cannot update measurements created by other users', function () {
    $otherUser = User::factory()->create();
    $measurement = Measurement::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/measurements/{$measurement->id}", [
        'value' => 100,
    ]);
    
    $response->assertStatus(403);
});
