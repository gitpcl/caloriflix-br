<?php

namespace App\Livewire\Foods;

use App\Models\Food;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Index extends Component
{
    use WithPagination;
    
    public string $search = '';
    
    // Mass delete functionality
    public array $selectedFoods = [];
    public bool $selectAll = false;
    public bool $selectionMode = false;
    
    // Modal visibility control
    public bool $showCreateFoodModal = false;
    public bool $showFoodDetailModal = false;
    public bool $showFoodEditModal = false;
    
    // Selected food for detail view
    public ?Food $selectedFood = null;
    public $foodQuantity = 0;
    public $selectedMeal = 'Almoço';
    public $recentlyUsed = false;
    
    // Form data for creating/editing food
    #[Validate('required|string|min:3|max:255')]
    public string $name = '';
    
    #[Validate('required|numeric|min:0.01')]
    public $quantity = 1;
    
    #[Validate('required|string')]
    public string $unit = 'gramas';
    
    #[Validate('required|numeric|min:0')]
    public $protein = 0;
    
    #[Validate('required|numeric|min:0')]
    public $fat = 0;
    
    #[Validate('required|numeric|min:0')]
    public $carbohydrate = 0;
    
    #[Validate('required|numeric|min:0')]
    public $fiber = 0;
    
    #[Validate('required|numeric|min:0')]
    public $calories = 0;
    
    #[Validate('nullable|string')]
    public ?string $barcode = null;
    
    // Cast properties to the appropriate types
    protected $casts = [
        'quantity' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbohydrate' => 'float',
        'fiber' => 'float',
        'calories' => 'float',
    ];
    
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
     * Get the foods for the authenticated user.
     *
     * @return mixed
     */
    public function getFoodsProperty()
    {
        $search = '%' . $this->search . '%';
        
        return Food::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search);
            })
            ->latest()
            ->paginate(10);
    }
    
    /**
     * Open the create food modal.
     *
     * @return void
     */
    public function openCreateFoodModal(): void
    {
        $this->resetValidation();
        $this->reset([
            'name', 'quantity', 'unit', 'protein', 'fat', 
            'carbohydrate', 'fiber', 'calories', 'barcode'
        ]);
        
        // Set default values
        $this->quantity = 1;
        $this->unit = 'gramas';
        
        $this->showCreateFoodModal = true;
    }
    
    /**
     * Open the food detail modal.
     *
     * @param  int  $foodId
     * @return void
     */
    public function openFoodDetailModal(int $foodId): void
    {
        $this->selectedFood = Food::findOrFail($foodId);
        $this->foodQuantity = floor($this->selectedFood->quantity);
        $this->selectedMeal = 'Almoço'; // Default to lunch
        $this->recentlyUsed = false;
        $this->showFoodDetailModal = true;
    }
    
    /**
     * Update the food quantity in the detail modal.
     *
     * @param  int  $newQuantity
     * @return void
     */
    public function updateFoodQuantity(int $newQuantity): void
    {
        $this->foodQuantity = max(5, $newQuantity);
    }
    
    /**
     * Add the selected food to a meal.
     *
     * @param  string  $meal
     * @return void
     */
    public function addFoodToMeal(string $meal = null): void
    {
        if ($meal) {
            $this->selectedMeal = $meal;
        }
        
        // Here you would implement the logic to add the food to the selected meal
        // For now, we'll just show a success message
        
        session()->flash('message', "Food {$this->selectedFood->name} ({$this->foodQuantity}g) added to {$this->selectedMeal}!");
        $this->closeModals();
    }
    
    /**
     * Open the food edit modal.
     *
     * @param  int  $foodId
     * @return void
     */
    public function openFoodEditModal(int $foodId): void
    {
        $this->selectedFood = Food::findOrFail($foodId);
        
        // Initialize form with the selected food's data
        $this->name = $this->selectedFood->name;
        $this->quantity = $this->selectedFood->quantity;
        $this->unit = $this->selectedFood->unit;
        $this->protein = $this->selectedFood->protein;
        $this->fat = $this->selectedFood->fat;
        $this->carbohydrate = $this->selectedFood->carbohydrate;
        $this->fiber = $this->selectedFood->fiber;
        $this->calories = $this->selectedFood->calories;
        $this->barcode = $this->selectedFood->barcode;
        
        $this->showFoodEditModal = true;
    }

    /**
     * Update the selected food.
     *
     * @return void
     */
    public function updateFood(): void
    {
        $this->validate();
        
        $this->selectedFood->update([
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'carbohydrate' => $this->carbohydrate,
            'fiber' => $this->fiber,
            'calories' => $this->calories,
            'barcode' => $this->barcode,
        ]);
        
        session()->flash('message', "Food {$this->name} updated successfully!");
        $this->closeModals();
    }

    /**
     * Close all modals.
     *
     * @return void
     */
    public function closeModals(): void
    {
        $this->showCreateFoodModal = false;
        $this->showFoodDetailModal = false;
        $this->showFoodEditModal = false;
        $this->selectedFood = null;
    }

    /**
     * Save a new food.
     *
     * @return void
     */
    public function save(): void
    {
        $this->validate();
        
        Food::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'carbohydrate' => $this->carbohydrate,
            'fiber' => $this->fiber,
            'calories' => $this->calories,
            'barcode' => $this->barcode,
        ]);
        
        $this->reset([
            'name', 'quantity', 'unit', 'protein', 'fat', 
            'carbohydrate', 'fiber', 'calories', 'barcode'
        ]);
        
        $this->closeModals();
        session()->flash('message', 'Alimento cadastrado com sucesso!');
    }
    
    /**
     * Delete a food.
     *
     * @param  int  $foodId
     * @return void
     */
    public function delete(int $foodId): void
    {
        // First check if the food exists and belongs to the current user
        $food = Food::where('user_id', Auth::id())->where('id', $foodId)->first();
        
        if (!$food) {
            // If no food found, abort with 404 - Not Found
            abort(404, 'Food not found');
        }
        
        $food->delete();
        
        $this->closeModals();
        session()->flash('message', 'Alimento excluído com sucesso!');
    }
    
    /**
     * Mark/unmark a food as favorite.
     *
     * @param  int  $foodId
     * @return void
     */
    public function toggleFavorite(int $foodId): void
    {
        $food = Food::where('user_id', Auth::id())->where('id', $foodId)->first();
        
        if (!$food) {
            abort(404, 'Food not found');
        }
        
        $food->update([
            'is_favorite' => !$food->is_favorite,
        ]);
        
        session()->flash('message', $food->is_favorite 
            ? 'Alimento adicionado aos favoritos!' 
            : 'Alimento removido dos favoritos!');
    }
    
    /**
     * Select all foods.
     *
     * @return void
     */
    public function selectAll(): void
    {
        $this->selectAll = !$this->selectAll;
        
        if ($this->selectAll) {
            $this->selectedFoods = $this->foods->pluck('id')->toArray();
        } else {
            $this->selectedFoods = [];
        }
    }
    
    /**
     * Clear selection.
     *
     * @return void
     */
    public function clearSelection(): void
    {
        $this->selectedFoods = [];
        $this->selectAll = false;
        $this->selectionMode = false;
    }
    
    /**
     * Mass delete foods.
     *
     * @return void
     */
    public function massDelete(): void
    {
        Food::whereIn('id', $this->selectedFoods)->delete();
        
        $this->selectedFoods = [];
        $this->selectAll = false;
        $this->selectionMode = false;
        
        session()->flash('message', 'Alimentos excluídos com sucesso!');
    }
    
    /**
     * Toggle selection mode.
     *
     * @return void
     */
    public function toggleSelectionMode(): void
    {
        $this->selectionMode = !$this->selectionMode;
    }
    
    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.foods.index', [
            'foods' => $this->foods,
            'title' => 'Alimentos',
            'description' => 'Gerencie seus alimentos cadastrados',
        ]);
    }
}
