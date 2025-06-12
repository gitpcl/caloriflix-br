<?php

use App\Models\User;
use App\Models\Food;
use Laravel\Sanctum\Sanctum;

test('food creation validates required fields', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->postJson('/api/v1/foods', []);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'quantity',
            'unit',
            'protein',
            'fat',
            'carbohydrate',
            'calories'
        ]);
});

test('food creation validates unit field', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->postJson('/api/v1/foods', [
        'name' => 'Test Food',
        'quantity' => 100,
        'unit' => 'invalid_unit',
        'protein' => 10,
        'fat' => 5,
        'carbohydrate' => 20,
        'calories' => 150
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['unit']);
});

test('food creation validates numeric fields', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->postJson('/api/v1/foods', [
        'name' => 'Test Food',
        'quantity' => 'not_a_number',
        'unit' => 'g',
        'protein' => 'not_a_number',
        'fat' => 'not_a_number',
        'carbohydrate' => 'not_a_number',
        'calories' => 'not_a_number'
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'quantity',
            'protein',
            'fat',
            'carbohydrate',
            'calories'
        ]);
});

test('food creation validates minimum values', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    
    $response = $this->postJson('/api/v1/foods', [
        'name' => 'Test Food',
        'quantity' => -1,
        'unit' => 'g',
        'protein' => -1,
        'fat' => -1,
        'carbohydrate' => -1,
        'calories' => -1
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'quantity',
            'protein',
            'fat',
            'carbohydrate',
            'calories'
        ]);
});

test('food update validates ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $food = Food::factory()->create(['user_id' => $user1->id]);
    
    Sanctum::actingAs($user2);
    
    $response = $this->putJson("/api/v1/foods/{$food->id}", [
        'name' => 'Updated Name'
    ]);
    
    $response->assertStatus(403);
});

test('food retrieval validates ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $food = Food::factory()->create(['user_id' => $user1->id]);
    
    Sanctum::actingAs($user2);
    
    $response = $this->getJson("/api/v1/foods/{$food->id}");
    
    $response->assertStatus(403);
});

test('food deletion validates ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    $food = Food::factory()->create(['user_id' => $user1->id]);
    
    Sanctum::actingAs($user2);
    
    $response = $this->deleteJson("/api/v1/foods/{$food->id}");
    
    $response->assertStatus(403);
});