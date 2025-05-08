<?php

use App\Livewire\Foods\Index;
use App\Models\Food;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    // Create a user for testing
    $this->user = User::factory()->create();
    
    // Create some food items for the user
    $this->foods = Food::factory(3)->create([
        'user_id' => $this->user->id,
    ]);
    
    // Create some food items for another user (to test filtering)
    $otherUser = User::factory()->create();
    $this->otherUserFoods = Food::factory(2)->create([
        'user_id' => $otherUser->id,
    ]);
});

test('index page contains the foods livewire component', function () {
    actingAs($this->user);
    
    get(route('foods.index'))
        ->assertSuccessful()
        ->assertSeeLivewire(Index::class);
});

test('can see only their own foods', function () {
    actingAs($this->user);
    
    $component = Livewire::test(Index::class);
    
    // User should see only their own foods
    foreach ($this->foods as $food) {
        $component->assertSee($food->name);
    }
    
    // User should not see other users' foods
    foreach ($this->otherUserFoods as $food) {
        $component->assertDontSee($food->name);
    }
});

test('can search foods', function () {
    actingAs($this->user);
    
    // Create a specific food to search for
    $searchableFood = Food::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Unique Protein Bar'
    ]);
    
    $component = Livewire::test(Index::class)
        ->set('search', 'Protein')
        ->assertSee('Unique Protein Bar');
    
    // Should not see other foods that don't match the search
    foreach ($this->foods as $food) {
        if (!str_contains(strtolower($food->name), 'protein')) {
            $component->assertDontSee($food->name);
        }
    }
});

test('shows empty state when no foods match search', function () {
    actingAs($this->user);
    
    Livewire::test(Index::class)
        ->set('search', 'Definitely not a food name xyz123')
        ->assertSee('Nenhum alimento encontrado');
});

test('shows empty state when user has no foods', function () {
    // Create a user with no foods
    $emptyUser = User::factory()->create();
    actingAs($emptyUser);
    
    Livewire::test(Index::class)
        ->assertSee('Nenhum alimento encontrado');
});

test('can create a new food', function () {
    actingAs($this->user);
    
    $foodData = [
        'name' => 'Test Protein Bar',
        'quantity' => 100,
        'unit' => 'gramas',
        'protein' => 20,
        'fat' => 10,
        'carbohydrate' => 30,
        'fiber' => 5,
        'calories' => 290,
        'barcode' => '123456789012'
    ];
    
    Livewire::test(Index::class)
        ->set('name', $foodData['name'])
        ->set('quantity', $foodData['quantity'])
        ->set('unit', $foodData['unit'])
        ->set('protein', $foodData['protein'])
        ->set('fat', $foodData['fat'])
        ->set('carbohydrate', $foodData['carbohydrate'])
        ->set('fiber', $foodData['fiber'])
        ->set('calories', $foodData['calories'])
        ->set('barcode', $foodData['barcode'])
        ->call('save');
    
    // Check that the food was created in the database
    $this->assertDatabaseHas('foods', [
        'user_id' => $this->user->id,
        'name' => $foodData['name'],
        'quantity' => $foodData['quantity'],
        'unit' => $foodData['unit'],
        'protein' => $foodData['protein'],
        'fat' => $foodData['fat'],
        'carbohydrate' => $foodData['carbohydrate'],
        'fiber' => $foodData['fiber'],
        'calories' => $foodData['calories'],
        'barcode' => $foodData['barcode']
    ]);
});

test('cannot create food with invalid data', function () {
    actingAs($this->user);
    
    Livewire::test(Index::class)
        ->set('name', '') // Empty name (invalid)
        ->call('save')
        ->assertHasErrors(['name']);
});

test('can delete a food', function () {
    actingAs($this->user);
    
    $food = $this->foods->first();
    
    Livewire::test(Index::class)
        ->call('delete', $food->id);
    
    // Check that the food was deleted from the database
    $this->assertDatabaseMissing('foods', [
        'id' => $food->id
    ]);
});

test('cannot delete another users food', function () {
    actingAs($this->user);
    
    $otherUserFood = $this->otherUserFoods->first();
    
    // This should throw a ModelNotFoundException since findOrFail is used with a where condition
    Livewire::test(Index::class)
        ->call('delete', $otherUserFood->id)
        ->assertStatus(404);
});

test('can toggle favorite status', function () {
    actingAs($this->user);
    
    // Create a food that is not a favorite
    $food = Food::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => false
    ]);
    
    // Toggle to favorite
    Livewire::test(Index::class)
        ->call('toggleFavorite', $food->id);
    
    // Check that the food is now a favorite
    $this->assertDatabaseHas('foods', [
        'id' => $food->id,
        'is_favorite' => true
    ]);
    
    // Toggle back to not favorite
    Livewire::test(Index::class)
        ->call('toggleFavorite', $food->id);
    
    // Check that the food is no longer a favorite
    $this->assertDatabaseHas('foods', [
        'id' => $food->id,
        'is_favorite' => false
    ]);
});

test('cannot toggle favorite for another users food', function () {
    actingAs($this->user);
    
    $otherUserFood = $this->otherUserFoods->first();
    
    // This should throw a ModelNotFoundException
    Livewire::test(Index::class)
        ->call('toggleFavorite', $otherUserFood->id)
        ->assertStatus(404);
});

test('can format nutritional info correctly', function () {
    $food = Food::factory()->create([
        'user_id' => $this->user->id,
        'protein' => 20,
        'carbohydrate' => 30,
        'fat' => 10,
        'calories' => 290
    ]);
    
    $this->assertEquals(
        '20g prot · 30g carb · 10g gord · 290kcal',
        $food->getNutritionalInfo()
    );
});
