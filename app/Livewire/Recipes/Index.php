<?php

namespace App\Livewire\Recipes;

use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Index extends Component
{
    use WithPagination;
    
    public string $search = '';
    
    // Modal control properties
    public bool $showCreateRecipeModal = false;
    public bool $showRecipeDetailModal = false;
    public bool $showRecipeViewModal = false;
    
    // Selected recipe for detail view
    public ?Recipe $selectedRecipe = null;
    public int $quantity = 100;
    
    // Form data for editing recipe
    public array $form = [
        'name' => '',
        'quantity' => 340,
        'protein' => 0,
        'fat' => 0,
        'carbs' => 0,
        'fiber' => 0,
        'calories' => 0,
        'ingredients' => '',
        'instructions' => '',
        'preparation_time' => null,
        'cooking_time' => null,
        'servings' => null,
    ];
    
    #[Validate('required|string|min:3|max:255')]
    public string $name = '';
    
    #[Validate('nullable|string')]
    public ?string $ingredients = null;
    
    #[Validate('nullable|string')]
    public ?string $instructions = null;
    
    #[Validate('nullable|integer|min:0')]
    public ?int $preparation_time = null;
    
    #[Validate('nullable|integer|min:0')]
    public ?int $cooking_time = null;
    
    #[Validate('nullable|integer|min:1')]
    public ?int $servings = null;
    
    /**
     * The rules for the component properties.
     *
     * @var array
     */
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    
    /**
     * Reset pagination when the search term changes.
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    
    /**
     * Get the recipes for the authenticated user.
     *
     * @return mixed
     */
    public function getRecipesProperty()
    {
        $search = '%' . $this->search . '%';
        
        return Recipe::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('ingredients', 'like', $search);
            })
            ->latest()
            ->paginate(10);
    }
    
    /**
     * Open the create recipe modal.
     *
     * @return void
     */
    public function openCreateRecipeModal(): void
    {
        $this->showCreateRecipeModal = true;
    }
    
    /**
     * Open the recipe detail modal.
     *
     * @param  int  $recipeId
     * @return void
     */
    public function openRecipeDetailModal(int $recipeId): void
    {
        $this->selectedRecipe = Recipe::findOrFail($recipeId);
        $this->quantity = 100; // Reset to default quantity
        $this->showRecipeDetailModal = true;
    }
    
    /**
     * Add quantity to a meal (to be implemented).
     *
     * @return void
     */
    public function addToMeal(): void
    {
        // This would be implemented to add the recipe to a meal with the selected quantity
        session()->flash('message', 'Recipe added to meal successfully!');
        $this->closeModals();
    }
    
    /**
     * Increment quantity by 5.
     *
     * @return void
     */
    public function incrementQuantity(): void
    {
        $this->quantity += 5;
    }
    
    /**
     * Decrement quantity by 5, but not below 5.
     *
     * @return void
     */
    public function decrementQuantity(): void
    {
        $this->quantity = max(5, $this->quantity - 5);
    }
    
    /**
     * Open the recipe view modal.
     *
     * @param  int  $recipeId
     * @return void
     */
    public function openRecipeViewModal(int $recipeId): void
    {
        $this->selectedRecipe = Recipe::findOrFail($recipeId);
        
        // Populate the form with the selected recipe data
        $this->form = [
            'name' => $this->selectedRecipe->name,
            'quantity' => 340, // Default value
            'protein' => $this->selectedRecipe->protein ?? 0,
            'fat' => $this->selectedRecipe->fat ?? 0,
            'carbs' => $this->selectedRecipe->carbs ?? 0,
            'fiber' => $this->selectedRecipe->fiber ?? 0,
            'calories' => $this->selectedRecipe->calories ?? 0,
            'ingredients' => $this->selectedRecipe->ingredients,
            'instructions' => $this->selectedRecipe->instructions,
            'preparation_time' => $this->selectedRecipe->preparation_time,
            'cooking_time' => $this->selectedRecipe->cooking_time,
            'servings' => $this->selectedRecipe->servings,
        ];
        
        $this->showRecipeViewModal = true;
    }
    
    /**
     * Update an existing recipe.
     *
     * @return void
     */
    public function updateRecipe(): void
    {
        $this->validate([
            'form.name' => 'required|string|min:3|max:255',
            'form.protein' => 'nullable|numeric|min:0',
            'form.fat' => 'nullable|numeric|min:0',
            'form.carbs' => 'nullable|numeric|min:0',
            'form.fiber' => 'nullable|numeric|min:0',
            'form.calories' => 'nullable|numeric|min:0',
            'form.ingredients' => 'nullable|string',
            'form.instructions' => 'nullable|string',
            'form.preparation_time' => 'nullable|integer|min:0',
            'form.cooking_time' => 'nullable|integer|min:0',
            'form.servings' => 'nullable|integer|min:1',
        ]);
        
        // Update the recipe
        $this->selectedRecipe->update([
            'name' => $this->form['name'],
            'protein' => $this->form['protein'],
            'fat' => $this->form['fat'],
            'carbs' => $this->form['carbs'],
            'fiber' => $this->form['fiber'],
            'calories' => $this->form['calories'],
            'ingredients' => $this->form['ingredients'],
            'instructions' => $this->form['instructions'],
            'preparation_time' => $this->form['preparation_time'],
            'cooking_time' => $this->form['cooking_time'],
            'servings' => $this->form['servings'],
        ]);
        
        $this->closeModals();
        session()->flash('message', 'Receita atualizada com sucesso!');
    }
    
    /**
     * Delete a recipe from the view modal.
     *
     * @param  int  $recipeId
     * @return void
     */
    public function deleteRecipe(int $recipeId): void
    {
        $recipe = Recipe::where('user_id', Auth::id())->findOrFail($recipeId);
        $recipe->delete();
        
        $this->closeModals();
        session()->flash('message', 'Receita excluída com sucesso!');
    }
    
    /**
     * Close all modals.
     *
     * @return void
     */
    public function closeModals(): void
    {
        $this->showCreateRecipeModal = false;
        $this->showRecipeDetailModal = false;
        $this->showRecipeViewModal = false;
        $this->selectedRecipe = null;
    }

    /**
     * Save a new recipe.
     *
     * @return void
     */
    public function save(): void
    {
        $this->validate();
        
        $recipe = Recipe::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            'preparation_time' => $this->preparation_time,
            'cooking_time' => $this->cooking_time,
            'servings' => $this->servings,
        ]);
        
        $this->reset(['name', 'ingredients', 'instructions', 'preparation_time', 'cooking_time', 'servings']);
        
        $this->closeModals();
        session()->flash('message', 'Receita criada com sucesso!');
    }
    
    /**
     * Delete a recipe.
     *
     * @param  int  $recipeId
     * @return void
     */
    public function delete(int $recipeId): void
    {
        // First check if the recipe exists and belongs to the current user
        $recipe = Recipe::where('user_id', Auth::id())->where('id', $recipeId)->first();
        
        if (!$recipe) {
            // If no recipe found, abort with 404 - Not Found
            abort(404, 'Recipe not found');
        }
        
        $recipe->delete();
        
        session()->flash('message', 'Receita excluída com sucesso!');
    }
    
    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.recipes.index', [
            'title' => 'Receitas',
            'description' => 'Gerencie suas receitas',
        ]);
    }
}
