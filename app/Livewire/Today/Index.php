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
        'Café da manhã',
        'Almoço',
        'Lanche da tarde',
        'Jantar'
    ];
    
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
    
    #[Title('Hoje')]
    public function render()
    {
        return view('livewire.today.index');
    }
}
