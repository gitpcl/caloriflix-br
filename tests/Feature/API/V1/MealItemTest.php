<?php

use App\Models\Food;
use App\Models\Meal;
use App\Models\MealItem;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
    
    $this->meal = Meal::factory()->create(['user_id' => $this->user->id]);
    $this->food = Food::factory()->create(['user_id' => $this->user->id]);
});

// Test index endpoint
test('user can view all meal items', function () {
    MealItem::factory()->count(3)->create([
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
    ]);
    
    $response = $this->getJson('/api/v1/meal-items');
    
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data.data')
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'meal_id', 'food_id', 'quantity', 'notes', 'created_at', 'updated_at'
                    ]
                ]
            ]
        ]);
});

// Test store endpoint
test('user can create a new meal item', function () {
    $mealItemData = [
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
        'quantity' => 150,
        'notes' => 'Test notes',
    ];
    
    $response = $this->postJson('/api/v1/meal-items', $mealItemData);
    
    $response->assertStatus(201)
        ->assertJsonFragment([
            'meal_id' => $this->meal->id,
            'food_id' => $this->food->id,
            'quantity' => 150,
        ]);
        
    $this->assertDatabaseHas('meal_items', [
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
        'quantity' => 150,
    ]);
});

// Test show endpoint
test('user can view a specific meal item', function () {
    $mealItem = MealItem::factory()->create([
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
        'quantity' => 200,
    ]);
    
    $response = $this->getJson("/api/v1/meal-items/{$mealItem->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $mealItem->id,
            'meal_id' => $this->meal->id,
            'quantity' => 200,
        ]);
});

// Test update endpoint
test('user can update a meal item', function () {
    $mealItem = MealItem::factory()->create([
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
        'quantity' => 100,
        'notes' => 'Original notes',
    ]);
    
    $response = $this->putJson("/api/v1/meal-items/{$mealItem->id}", [
        'quantity' => 250,
        'notes' => 'Updated notes',
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'quantity' => 250,
            'notes' => 'Updated notes',
        ]);
        
    $this->assertDatabaseHas('meal_items', [
        'id' => $mealItem->id,
        'quantity' => 250,
        'notes' => 'Updated notes',
    ]);
});

// Test destroy endpoint
test('user can delete a meal item', function () {
    $mealItem = MealItem::factory()->create([
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
    ]);
    
    $response = $this->deleteJson("/api/v1/meal-items/{$mealItem->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('meal_items', ['id' => $mealItem->id]);
});

// Test authorization
test('user cannot view meal items from meals created by other users', function () {
    $otherUser = User::factory()->create();
    $otherMeal = Meal::factory()->create(['user_id' => $otherUser->id]);
    $otherFood = Food::factory()->create(['user_id' => $otherUser->id]);
    
    $mealItem = MealItem::factory()->create([
        'meal_id' => $otherMeal->id,
        'food_id' => $otherFood->id,
    ]);
    
    $response = $this->getJson("/api/v1/meal-items/{$mealItem->id}");
    
    $response->assertStatus(403);
});

test('user cannot add meal items to meals created by other users', function () {
    $otherUser = User::factory()->create();
    $otherMeal = Meal::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->postJson('/api/v1/meal-items', [
        'meal_id' => $otherMeal->id,
        'food_id' => $this->food->id,
        'quantity' => 150,
    ]);
    
    $response->assertStatus(403);
});
