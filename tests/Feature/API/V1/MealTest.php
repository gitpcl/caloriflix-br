<?php

use App\Models\Meal;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all meals', function () {
    Meal::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/meals');
    
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data.data')
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'meal_type', 'meal_date', 'notes', 'user_id', 'created_at', 'updated_at'
                    ]
                ]
            ]
        ]);
});

// Test store endpoint
test('user can create a new meal', function () {
    $mealData = [
        'meal_type' => 'cafe_da_manha',
        'meal_date' => now()->format('Y-m-d'),
        'notes' => 'Healthy breakfast',
    ];
    
    $response = $this->postJson('/api/v1/meals', $mealData);
    
    $response->assertStatus(201)
        ->assertJsonFragment([
            'meal_type' => 'cafe_da_manha',
        ]);
        
    $this->assertDatabaseHas('meals', [
        'meal_type' => 'cafe_da_manha',
        'user_id' => $this->user->id
    ]);
});

// Test show endpoint
test('user can view a specific meal', function () {
    $meal = Meal::factory()->create([
        'user_id' => $this->user->id,
        'meal_type' => 'almoco'
    ]);
    
    $response = $this->getJson("/api/v1/meals/{$meal->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $meal->id,
            'meal_type' => 'almoco'
        ]);
});

// Test update endpoint
test('user can update a meal', function () {
    $meal = Meal::factory()->create([
        'user_id' => $this->user->id,
        'meal_type' => 'cafe_da_manha'
    ]);
    
    $response = $this->putJson("/api/v1/meals/{$meal->id}", [
        'meal_type' => 'almoco',
        'notes' => 'Updated notes'
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'meal_type' => 'almoco',
            'notes' => 'Updated notes'
        ]);
        
    $this->assertDatabaseHas('meals', [
        'id' => $meal->id,
        'meal_type' => 'almoco'
    ]);
});

// Test destroy endpoint
test('user can delete a meal', function () {
    $meal = Meal::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/meals/{$meal->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('meals', ['id' => $meal->id]);
});

// Test today endpoint
test('user can view today meals', function () {
    // Create meals for today
    Meal::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'meal_date' => now()->format('Y-m-d')
    ]);
    
    // Create meals for other days
    Meal::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'meal_date' => now()->subDays(2)->format('Y-m-d')
    ]);
    
    $response = $this->getJson('/api/v1/meals/today');
    
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

// Test byDate endpoint
test('user can view meals by date', function () {
    $date = now()->subDays(3)->format('Y-m-d');
    
    // Create meals for specific date
    Meal::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'meal_date' => $date
    ]);
    
    // Create meals for other days
    Meal::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'meal_date' => now()->format('Y-m-d')
    ]);
    
    $response = $this->getJson("/api/v1/meals/date/{$date}");
    
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

// Test authorization
test('user cannot view meals created by other users', function () {
    $otherUser = User::factory()->create();
    $meal = Meal::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/meals/{$meal->id}");
    
    $response->assertStatus(403);
});

test('user cannot update meals created by other users', function () {
    $otherUser = User::factory()->create();
    $meal = Meal::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/meals/{$meal->id}", [
        'meal_type' => 'jantar'
    ]);
    
    $response->assertStatus(403);
});
