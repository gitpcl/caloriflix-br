<?php

use App\Models\NutritionalGoal;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all nutritional goals', function () {
    NutritionalGoal::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/nutritional-goals');
    
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'user_id', 'calories', 'protein', 'fat', 
                    'carbs', 'fiber', 'water', 'start_date', 
                    'end_date', 'is_active', 'created_at', 'updated_at'
                ]
            ]
        ]);
});

// Test store endpoint
test('user can create a new nutritional goal', function () {
    $goalData = [
        'calories' => 2000,
        'protein' => 150,
        'fat' => 70,
        'carbohydrate' => 200,
        'fiber' => 30,
        'water' => 2000,
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonths(3)->format('Y-m-d'),
        'is_active' => true,
    ];
    
    $response = $this->postJson('/api/v1/nutritional-goals', $goalData);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'calories' => 2000,
            'protein' => 150,
        ]);
        
    $this->assertDatabaseHas('nutritional_goals', [
        'user_id' => $this->user->id,
        'calories' => 2000,
    ]);
});

// Test show endpoint
test('user can view a specific nutritional goal', function () {
    $goal = NutritionalGoal::factory()->create([
        'user_id' => $this->user->id,
        'calories' => 2500,
        'protein' => 180,
    ]);
    
    $response = $this->getJson("/api/v1/nutritional-goals/{$goal->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $goal->id,
            'calories' => 2500,
            'protein' => 180,
        ]);
});

// Test update endpoint
test('user can update a nutritional goal', function () {
    $goal = NutritionalGoal::factory()->create([
        'user_id' => $this->user->id,
        'calories' => 2000,
        'protein' => 150,
    ]);
    
    $response = $this->putJson("/api/v1/nutritional-goals/{$goal->id}", [
        'calories' => 2200,
        'protein' => 180,
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'calories' => 2200,
            'protein' => 180,
        ]);
        
    $this->assertDatabaseHas('nutritional_goals', [
        'id' => $goal->id,
        'calories' => 2200,
        'protein' => 180,
    ]);
});

// Test destroy endpoint
test('user can delete a nutritional goal', function () {
    $goal = NutritionalGoal::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/nutritional-goals/{$goal->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('nutritional_goals', ['id' => $goal->id]);
});

// Test current endpoint
test('user can view current nutritional goal', function () {
    // Create inactive goal
    NutritionalGoal::factory()->create([
        'user_id' => $this->user->id,
        'is_active' => false,
    ]);
    
    // Create active goal
    $activeGoal = NutritionalGoal::factory()->create([
        'user_id' => $this->user->id,
        'calories' => 2300,
        'is_active' => true,
    ]);
    
    $response = $this->getJson('/api/v1/nutritional-goals/current');
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $activeGoal->id,
            'calories' => 2300,
            'is_active' => true,
        ]);
});

// Test authorization
test('user cannot view nutritional goals created by other users', function () {
    $otherUser = User::factory()->create();
    $goal = NutritionalGoal::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/nutritional-goals/{$goal->id}");
    
    $response->assertStatus(404);
});

test('user cannot update nutritional goals created by other users', function () {
    $otherUser = User::factory()->create();
    $goal = NutritionalGoal::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/nutritional-goals/{$goal->id}", [
        'calories' => 2500,
    ]);
    
    $response->assertStatus(404);
});
