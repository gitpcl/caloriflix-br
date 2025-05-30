<?php

use App\Models\Food;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all foods', function () {
    Food::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/foods');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'name', 'quantity', 'unit', 'protein', 'fat', 
                        'carbohydrate', 'fiber', 'calories', 'barcode', 
                        'is_favorite', 'created_at', 'updated_at'
                    ]
                ],
                'current_page',
                'total'
            ]
        ]);
    
    // Check that we have at least the 3 foods we created
    $responseData = $response->json('data.data');
    expect(count($responseData))->toBeGreaterThanOrEqual(3);
});

// Test store endpoint
test('user can create a new food', function () {
    $foodData = [
        'name' => 'Test Food',
        'quantity' => 100,
        'unit' => 'g',
        'protein' => 20,
        'fat' => 10,
        'carbohydrate' => 30,
        'fiber' => 5,
        'calories' => 290,
        'barcode' => '1234567890123',
        'is_favorite' => true,
    ];
    
    $response = $this->postJson('/api/v1/foods', $foodData);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Test Food',
            'calories' => '290.00',
        ]);
        
    $this->assertDatabaseHas('foods', [
        'name' => 'Test Food',
        'user_id' => $this->user->id
    ]);
});

// Test show endpoint
test('user can view a specific food', function () {
    $food = Food::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Specific Food'
    ]);
    
    $response = $this->getJson("/api/v1/foods/{$food->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $food->id,
            'name' => 'Specific Food'
        ]);
});

// Test update endpoint
test('user can update a food', function () {
    $food = Food::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Original Name'
    ]);
    
    $response = $this->putJson("/api/v1/foods/{$food->id}", [
        'name' => 'Updated Name',
        'protein' => 25
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Updated Name',
            'protein' => '25.00'
        ]);
        
    $this->assertDatabaseHas('foods', [
        'id' => $food->id,
        'name' => 'Updated Name'
    ]);
});

// Test destroy endpoint
test('user can delete a food', function () {
    $food = Food::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/foods/{$food->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('foods', ['id' => $food->id]);
});

// Test favorites endpoint
test('user can view favorite foods', function () {
    Food::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'is_favorite' => true
    ]);
    
    Food::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'is_favorite' => false
    ]);
    
    $response = $this->getJson('/api/v1/foods/favorites');
    
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

// Test authorization
test('user cannot view foods created by other users', function () {
    $otherUser = User::factory()->create();
    $food = Food::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/foods/{$food->id}");
    
    $response->assertStatus(403);
});

test('user cannot update foods created by other users', function () {
    $otherUser = User::factory()->create();
    $food = Food::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/foods/{$food->id}", [
        'name' => 'Updated Name'
    ]);
    
    $response->assertStatus(403);
});
