<?php

namespace App\Livewire\Today;

use App\Models\Food;
use App\Models\Meal;
use App\Models\MealItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    public $meals = [];
    public $date;
    public $mealTypes = [
        'cafe_da_manha',
        'almoco',
        'lanche_da_tarde',
        'jantar'
    ];
    
    // Edit modal properties
    public $showEditModal = false;
    public $editingMealItem = null;
    public $editQuantity = '';
    public $editNotes = '';
    public $editProtein = '';
    public $editFat = '';
    public $editCarbohydrate = '';
    public $editFiber = '';
    public $editCalories = '';
    
    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadMeals();
    }
    
    public function loadMeals()
    {
        $this->meals = [];
        
        // Get all meal types for today
        $todayMeals = Meal::where('user_id', Auth::id())
            ->whereDate('meal_date', $this->date)
            ->get()
            ->keyBy('meal_type');
            
        // Initialize meals array with empty or existing meals
        foreach ($this->mealTypes as $type) {
            if (isset($todayMeals[$type])) {
                $meal = $todayMeals[$type];
                $mealItems = $meal->mealItems()->with('food')->get()->toArray();
                
                $this->meals[$type] = [
                    'id' => $meal->id,
                    'items' => $mealItems,
                    'empty' => empty($mealItems)
                ];
            } else {
                $this->meals[$type] = [
                    'id' => null,
                    'items' => [],
                    'empty' => true
                ];
            }
        }
    }
    

    
    #[On('date-changed')]
    public function updateDate($newDate)
    {
        $this->date = $newDate;
        $this->loadMeals();
    }
    
    public function previousDay()
    {
        $this->date = Carbon::parse($this->date)->subDay()->format('Y-m-d');
        $this->loadMeals();
    }
    
    public function nextDay()
    {
        $this->date = Carbon::parse($this->date)->addDay()->format('Y-m-d');
        $this->loadMeals();
    }
    
    public function today()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadMeals();
    }
    
    public function getMealDisplayName($mealType)
    {
        $displayNames = [
            'cafe_da_manha' => 'Café da manhã',
            'almoco' => 'Almoço',
            'lanche_da_tarde' => 'Lanche da tarde',
            'jantar' => 'Jantar',
        ];
        
        return $displayNames[$mealType] ?? $mealType;
    }
    
    public function editMealItem($mealItemId)
    {
        $mealItem = MealItem::with('food')->where('id', $mealItemId)
            ->whereHas('meal', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($mealItem) {
            $this->editingMealItem = $mealItem;
            $this->editQuantity = $mealItem->quantity;
            $this->editNotes = $mealItem->notes ?? '';
            
            // Set nutrition values based on current quantity
            $this->editProtein = round($mealItem->food->protein * $mealItem->quantity, 1);
            $this->editFat = round($mealItem->food->fat * $mealItem->quantity, 1);
            $this->editCarbohydrate = round($mealItem->food->carbohydrate * $mealItem->quantity, 1);
            $this->editFiber = round(($mealItem->food->fiber ?? 0) * $mealItem->quantity, 1);
            $this->editCalories = round($mealItem->food->calories * $mealItem->quantity, 1);
            
            $this->showEditModal = true;
        }
    }
    
    public function updateMealItem()
    {
        $this->validate([
            'editQuantity' => 'required|numeric|min:0.1',
            'editNotes' => 'nullable|string|max:255',
            'editProtein' => 'required|numeric|min:0',
            'editFat' => 'required|numeric|min:0',
            'editCarbohydrate' => 'required|numeric|min:0',
            'editFiber' => 'required|numeric|min:0',
            'editCalories' => 'required|numeric|min:0',
        ]);

        if ($this->editingMealItem) {
            // Update the meal item
            $this->editingMealItem->update([
                'quantity' => $this->editQuantity,
                'notes' => $this->editNotes,
            ]);

            // Update the food's nutrition values per unit
            $this->editingMealItem->food->update([
                'protein' => $this->editQuantity > 0 ? $this->editProtein / $this->editQuantity : 0,
                'fat' => $this->editQuantity > 0 ? $this->editFat / $this->editQuantity : 0,
                'carbohydrate' => $this->editQuantity > 0 ? $this->editCarbohydrate / $this->editQuantity : 0,
                'fiber' => $this->editQuantity > 0 ? $this->editFiber / $this->editQuantity : 0,
                'calories' => $this->editQuantity > 0 ? $this->editCalories / $this->editQuantity : 0,
            ]);

            $this->closeEditModal();
            $this->loadMeals();
            session()->flash('message', 'Item atualizado com sucesso!');
        }
    }
    
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingMealItem = null;
        $this->editQuantity = '';
        $this->editNotes = '';
        $this->editProtein = '';
        $this->editFat = '';
        $this->editCarbohydrate = '';
        $this->editFiber = '';
        $this->editCalories = '';
        $this->resetValidation();
    }
    
    public function deleteMealItem($mealItemId)
    {
        $mealItem = MealItem::where('id', $mealItemId)
            ->whereHas('meal', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($mealItem) {
            $mealItem->delete();
            $this->loadMeals(); // Reload meals to update the display
            session()->flash('message', 'Item removido com sucesso!');
        }
    }
    
    #[Title('Hoje')]
    public function render()
    {
        return view('livewire.today.index');
    }
}
