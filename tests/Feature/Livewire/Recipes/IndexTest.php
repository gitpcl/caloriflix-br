<?php

use App\Livewire\Recipes\Index;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    // Create a user for testing
    $this->user = User::factory()->create();
    
    // Create some recipes for the user
    $this->recipes = Recipe::factory(3)->create([
        'user_id' => $this->user->id,
    ]);
    
    // Create some recipes for another user (to test filtering)
    $otherUser = User::factory()->create();
    $this->otherUserRecipes = Recipe::factory(2)->create([
        'user_id' => $otherUser->id,
    ]);
});

test('index page contains the recipes livewire component', function () {
    actingAs($this->user);
    
    get(route('recipes.index'))
        ->assertSuccessful()
        ->assertSeeLivewire(Index::class);
});

test('can see only their own recipes', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // User should see only their own recipes
    foreach ($this->recipes as $recipe) {
        $component->assertSee($recipe->name);
    }
    
    // User should not see other users' recipes
    foreach ($this->otherUserRecipes as $recipe) {
        $component->assertDontSee($recipe->name);
    }
});

test('can search recipes', function () {
    actingAs($this->user);
    
    // Create a specific recipe to search for
    $searchableRecipe = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Unique Pancake Recipe'
    ]);
    
    $component = Livewire::test(Index::class)
        ->set('search', 'Pancake')
        ->assertSee('Unique Pancake Recipe');
    
    // Should not see other recipes that don't match the search
    foreach ($this->recipes as $recipe) {
        $component->assertDontSee($recipe->name);
    }
});

test('can search recipes by ingredients', function () {
    actingAs($this->user);
    
    // Create a recipe with specific ingredients
    $recipeWithSpecificIngredients = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Test Recipe with Specific Ingredient',
        'ingredients' => 'Special flour, eggs, and sugar'
    ]);
    
    $component = Livewire::test(Index::class)
        ->set('search', 'Special flour')
        ->assertSee('Test Recipe with Specific Ingredient');
});

test('shows empty state when no recipes match search', function () {
    actingAs($this->user);
    
    Livewire::test(Index::class)
        ->set('search', 'Definitely not a recipe name xyz123')
        ->assertSee('Nenhuma receita encontrada');
});

test('shows empty state when user has no recipes', function () {
    // Create a user with no recipes
    $emptyUser = User::factory()->create();
    actingAs($emptyUser);
    
    Livewire::test(Index::class)
        ->assertSee('Nenhuma receita encontrada')
        ->assertSee('Comece cadastrando sua primeira receita');
});

test('can create a new recipe', function () {
    actingAs($this->user);
    
    $recipeData = [
        'name' => 'New Test Recipe',
        'ingredients' => '200g sugar, 3 eggs, milk',
        'instructions' => 'Mix everything and bake at 180°C',
        'preparation_time' => 15,
        'cooking_time' => 30,
        'servings' => 4
    ];
    
    Livewire::test(Index::class)
        ->set('name', $recipeData['name'])
        ->set('ingredients', $recipeData['ingredients'])
        ->set('instructions', $recipeData['instructions'])
        ->set('preparation_time', $recipeData['preparation_time'])
        ->set('cooking_time', $recipeData['cooking_time'])
        ->set('servings', $recipeData['servings'])
        ->call('save');
    
    // Check that the recipe was created in the database
    $this->assertDatabaseHas('recipes', [
        'user_id' => $this->user->id,
        'name' => $recipeData['name'],
        'ingredients' => $recipeData['ingredients'],
        'instructions' => $recipeData['instructions'],
        'preparation_time' => $recipeData['preparation_time'],
        'cooking_time' => $recipeData['cooking_time'],
        'servings' => $recipeData['servings']
    ]);
});

test('cannot create recipe with invalid data', function () {
    actingAs($this->user);
    
    Livewire::test(Index::class)
        ->set('name', '') // Empty name (invalid)
        ->call('save')
        ->assertHasErrors(['name']);
});

test('can delete a recipe', function () {
    actingAs($this->user);
    
    $recipe = $this->recipes->first();
    
    Livewire::test(Index::class)
        ->call('delete', $recipe->id);
    
    // Check that the recipe was deleted from the database
    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id
    ]);
});

test('cannot delete another users recipe', function () {
    actingAs($this->user);
    
    $otherUserRecipe = $this->otherUserRecipes->first();
    
    // This should throw a ModelNotFoundException since findOrFail is used with a where condition
    Livewire::test(Index::class)
        ->call('delete', $otherUserRecipe->id)
        ->assertStatus(404);
});

test('recipe displays total time as preparation + cooking time', function () {
    actingAs($this->user);
    
    $recipe = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'preparation_time' => 15,
        'cooking_time' => 25
    ]);
    
    // The total time should be 40 minutes (15 + 25)
    $this->assertEquals(40, $recipe->total_time);
});

test('total time attribute returns null when preparation or cooking time is null', function () {
    actingAs($this->user);
    
    $recipe1 = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'preparation_time' => null,
        'cooking_time' => 25
    ]);
    
    $recipe2 = Recipe::factory()->create([
        'user_id' => $this->user->id,
        'preparation_time' => 15,
        'cooking_time' => null
    ]);
    
    $this->assertNull($recipe1->total_time);
    $this->assertNull($recipe2->total_time);
});
