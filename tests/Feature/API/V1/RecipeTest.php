<?php

use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all recipes', function () {
    $recipes = Recipe::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/recipes');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'user_id', 'name', 'ingredients', 'instructions', 
                        'preparation_time', 'cooking_time', 'servings',
                        'created_at', 'updated_at'
                    ]
                ],
                'current_page',
                'total'
            ]
        ]);
    
    // Check that we have at least the 3 recipes we created
    $data = $response->json('data.data');
    expect(count($data))->toBeGreaterThanOrEqual(3);
    
    // Verify our created entries are in the response
    $createdIds = $recipes->pluck('id')->toArray();
    $responseIds = collect($data)->pluck('id')->toArray();
    foreach ($createdIds as $id) {
        expect($responseIds)->toContain($id);
    }
});

// Test store endpoint
test('user can create a new recipe', function () {
    $recipeData = [
        'name' => 'Test Recipe',
        'description' => 'A delicious test recipe',
        'ingredients' => 'Test ingredients',
        'instructions' => 'Test instructions',
        'preparation_time' => 30,
        'cooking_time' => 45,
        'servings' => 4,
    ];
    
    $response = $this->postJson('/api/v1/recipes', $recipeData);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Test Recipe',
            'servings' => 4,
        ]);
        
    $this->assertDatabaseHas('recipes', [
        'user_id' => $this->user->id,
        'name' => 'Test Recipe',
    ]);
});

// Test show endpoint
test('user can view a specific recipe', function () {
    $recipe = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Specific Recipe'
    ]);
    
    $response = $this->getJson("/api/v1/recipes/{$recipe->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $recipe->id,
            'name' => 'Specific Recipe'
        ]);
});

// Test update endpoint
test('user can update a recipe', function () {
    $recipe = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Original Recipe',
        'servings' => 2
    ]);
    
    $response = $this->putJson("/api/v1/recipes/{$recipe->id}", [
        'name' => 'Updated Recipe',
        'servings' => 4,
        'instructions' => 'New instructions'
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Updated Recipe',
            'servings' => 4,
            'instructions' => 'New instructions'
        ]);
        
    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'name' => 'Updated Recipe',
        'servings' => 4
    ]);
});

// Test destroy endpoint
test('user can delete a recipe', function () {
    $recipe = Recipe::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/recipes/{$recipe->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
});

// Test authorization
test('user cannot view recipes created by other users', function () {
    $otherUser = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/recipes/{$recipe->id}");
    
    $response->assertStatus(404);
});

test('user cannot update recipes created by other users', function () {
    $otherUser = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/recipes/{$recipe->id}", [
        'name' => 'Updated Recipe'
    ]);
    
    $response->assertStatus(404);
});
