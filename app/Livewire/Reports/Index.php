<?php

namespace App\Livewire\Reports;

use App\Models\Meal;
use App\Models\MealItem;
use App\Models\Food;
use App\Models\Diary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class Index extends Component
{
    public string $period_type = 'daily';
    public string $current_date;
    public string $custom_start_date = '';
    public string $custom_end_date = '';
    public bool $show_custom_modal = false;
    public bool $show_water_modal = false;
    public string $date_mode = 'absolute'; // 'absolute' or 'relative'
    public int $relative_amount = 7;
    public string $relative_unit = 'days'; // 'days', 'weeks', 'months'
    public array $nutrient_macros = [
        'protein' => 0,
        'carbs' => 0,
        'fat' => 0,
        'calories' => 0
    ];
    public float $water_consumption = 0;
    public int $water_amount = 250;
    public string $water_date;
    public array $period_options = ['daily', 'weekly', 'monthly', 'custom'];
    public int $daily_calorie_goal = 2000;
    public float $caloric_deficit = 0;

    public function mount()
    {
        $this->period_type = 'daily';
        $this->current_date = now()->format('Y-m-d');
        $this->water_date = now()->format('Y-m-d');
        $this->loadReportData();
    }

    public function previousPeriod()
    {
        $date = Carbon::parse($this->current_date);
        
        switch ($this->period_type) {
            case 'daily':
                $this->current_date = $date->subDay()->format('Y-m-d');
                break;
            case 'weekly':
                $this->current_date = $date->subWeek()->format('Y-m-d');
                break;
            case 'monthly':
                $this->current_date = $date->subMonth()->format('Y-m-d');
                break;
        }
        
        $this->loadReportData();
    }

    public function nextPeriod()
    {
        $date = Carbon::parse($this->current_date);
        
        switch ($this->period_type) {
            case 'daily':
                $this->current_date = $date->addDay()->format('Y-m-d');
                break;
            case 'weekly':
                $this->current_date = $date->addWeek()->format('Y-m-d');
                break;
            case 'monthly':
                $this->current_date = $date->addMonth()->format('Y-m-d');
                break;
        }
        
        $this->loadReportData();
    }

    public function today()
    {
        $this->current_date = now()->format('Y-m-d');
        $this->loadReportData();
    }

    public function changePeriodType($type)
    {
        if (in_array($type, $this->period_options)) {
            $this->period_type = $type;
            
            if ($type === 'custom') {
                $this->openCustomModal();
            } else {
                $this->loadReportData();
            }
        }
    }
    
    public function openCustomModal()
    {
        $this->show_custom_modal = true;
        // Set default dates if empty
        if (empty($this->custom_start_date)) {
            $this->custom_start_date = now()->subDays(7)->format('Y-m-d');
        }
        if (empty($this->custom_end_date)) {
            $this->custom_end_date = now()->format('Y-m-d');
        }
    }
    
    public function closeCustomModal()
    {
        $this->show_custom_modal = false;
        // Reset to daily if custom was cancelled
        if ($this->period_type === 'custom' && (empty($this->custom_start_date) || empty($this->custom_end_date))) {
            $this->period_type = 'daily';
        }
    }
    
    public function openWaterModal()
    {
        $this->show_water_modal = true;
        $this->water_amount = 250;
        $this->water_date = now()->format('Y-m-d');
    }
    
    public function closeWaterModal()
    {
        $this->show_water_modal = false;
    }
    
    public function applyCustomRange()
    {
        // If using relative mode, calculate the dates first
        if ($this->date_mode === 'relative') {
            $this->calculateRelativeDates();
        }
        
        // Validate dates
        if (empty($this->custom_start_date) || empty($this->custom_end_date)) {
            session()->flash('error', 'Por favor, selecione ambas as datas.');
            return;
        }
        
        $start = Carbon::parse($this->custom_start_date);
        $end = Carbon::parse($this->custom_end_date);
        
        if ($start->gt($end)) {
            session()->flash('error', 'A data de início deve ser anterior à data de término.');
            return;
        }
        
        $this->show_custom_modal = false;
        $this->loadReportData();
        
        if ($this->date_mode === 'relative') {
            session()->flash('message', "Período relativo aplicado: últimos {$this->relative_amount} {$this->relative_unit}.");
        } else {
            session()->flash('message', 'Período personalizado aplicado com sucesso.');
        }
    }

    public function setQuickPeriod($period)
    {
        $now = now();
        
        switch ($period) {
            case '24h':
                $this->custom_start_date = $now->copy()->subDay()->format('Y-m-d\TH:i');
                $this->custom_end_date = $now->format('Y-m-d\TH:i');
                break;
            case '3d':
                $this->custom_start_date = $now->copy()->subDays(3)->format('Y-m-d\TH:i');
                $this->custom_end_date = $now->format('Y-m-d\TH:i');
                break;
            case '7d':
                $this->custom_start_date = $now->copy()->subDays(7)->format('Y-m-d\TH:i');
                $this->custom_end_date = $now->format('Y-m-d\TH:i');
                break;
            case '1m':
                $this->custom_start_date = $now->copy()->subMonth()->format('Y-m-d\TH:i');
                $this->custom_end_date = $now->format('Y-m-d\TH:i');
                break;
        }
    }
    
    public function setDateMode($mode)
    {
        $this->date_mode = $mode;
        
        // If switching to relative mode, set default values
        if ($mode === 'relative') {
            $this->relative_amount = 7;
            $this->relative_unit = 'days';
        }
    }
    
    public function calculateRelativeDates()
    {
        $now = now();
        $end_date = $now;
        
        switch ($this->relative_unit) {
            case 'days':
                $start_date = $now->copy()->subDays($this->relative_amount);
                break;
            case 'weeks':
                $start_date = $now->copy()->subWeeks($this->relative_amount);
                break;
            case 'months':
                $start_date = $now->copy()->subMonths($this->relative_amount);
                break;
            default:
                $start_date = $now->copy()->subDays($this->relative_amount);
        }
        
        $this->custom_start_date = $start_date->format('Y-m-d\TH:i');
        $this->custom_end_date = $end_date->format('Y-m-d\TH:i');
    }
    
    public function addWaterEntry()
    {
        // Validate inputs
        $this->validate([
            'water_amount' => 'required|integer|min:1|max:5000',
            'water_date' => 'required|date'
        ]);
        
        $user_id = Auth::id();
        
        // Ensure date is in correct format (Y-m-d) without time
        $date = Carbon::parse($this->water_date)->format('Y-m-d');
        
        // Use DB::table to avoid any model casting issues
        $existingEntry = DB::table('diaries')
            ->where('user_id', $user_id)
            ->whereDate('date', $date)
            ->first();
        
        if ($existingEntry) {
            // Update existing entry
            DB::table('diaries')
                ->where('user_id', $user_id)
                ->whereDate('date', $date)
                ->increment('water', $this->water_amount);
        } else {
            // Create new entry
            DB::table('diaries')->insert([
                'user_id' => $user_id,
                'date' => $date,
                'water' => $this->water_amount,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $this->show_water_modal = false;
        $this->loadReportData();
        
        session()->flash('message', "Adicionados {$this->water_amount}ml de água para " . Carbon::parse($this->water_date)->format('d/m/Y'));
    }
    
    protected function loadReportData()
    {
        $user_id = Auth::id();
        $date = Carbon::parse($this->current_date);
        
        // Initialize default values
        $this->nutrient_macros = [
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0,
            'calories' => 0
        ];
        $this->water_consumption = 0;
        $this->caloric_deficit = 0;
        
        // Different date ranges based on period type
        $start_date = null;
        $end_date = null;
        
        switch ($this->period_type) {
            case 'daily':
                $start_date = $date->copy()->startOfDay();
                $end_date = $date->copy()->endOfDay();
                break;
            case 'weekly':
                $start_date = $date->copy()->startOfWeek();
                $end_date = $date->copy()->endOfWeek();
                break;
            case 'monthly':
                $start_date = $date->copy()->startOfMonth();
                $end_date = $date->copy()->endOfMonth();
                break;
            case 'custom':
                if (!empty($this->custom_start_date) && !empty($this->custom_end_date)) {
                    $start_date = Carbon::parse($this->custom_start_date)->startOfDay();
                    $end_date = Carbon::parse($this->custom_end_date)->endOfDay();
                } else {
                    // Fallback to daily if custom dates are not set
                    $start_date = $date->copy()->startOfDay();
                    $end_date = $date->copy()->endOfDay();
                }
                break;
        }
        
        // Pull actual meal data from database
        $this->loadMealData($user_id, $start_date, $end_date);
        
        // Pull water consumption data
        $this->loadWaterConsumptionData($user_id, $start_date, $end_date);
        
        // Calculate caloric deficit
        $this->calculateCaloricDeficit($user_id, $start_date, $end_date);
    }
    
    protected function loadMealData($user_id, $start_date, $end_date)
    {
        // Get all meals for the user in the date range
        $meals = Meal::where('user_id', $user_id)
            ->whereBetween('meal_date', [
                $start_date->format('Y-m-d'), 
                $end_date->format('Y-m-d')
            ])
            ->with(['mealItems.food'])
            ->get();
            
        // Initialize counters
        $total_protein = 0;
        $total_carbs = 0;
        $total_fat = 0;
        $total_calories = 0;
        
        // Calculate actual days with meals for proper averaging
        $days_with_meals = $meals->groupBy('meal_date')->count();
        $total_days = max(1, $start_date->diffInDays($end_date) + 1);
        
        // Get meal item totals only if meals exist
        if ($meals->isNotEmpty()) {
            foreach ($meals as $meal) {
                foreach ($meal->mealItems as $item) {
                    $food = $item->food;
                    if ($food) {
                        // Convert quantity to proper multiplier (assuming quantity is in grams and food nutrition is per 100g)
                        $multiplier = $item->quantity / 100;
                        
                        $total_protein += ($food->protein ?? 0) * $multiplier;
                        $total_carbs += ($food->carbohydrate ?? 0) * $multiplier; 
                        $total_fat += ($food->fat ?? 0) * $multiplier;
                        $total_calories += ($food->calories ?? 0) * $multiplier;
                    }
                }
            }
        }
        
        // Calculate averages based on period type
        $divisor = $this->period_type === 'daily' ? 1 : $total_days;
        
        $this->nutrient_macros = [
            'protein' => $divisor > 0 ? round($total_protein / $divisor, 1) : 0,
            'carbs' => $divisor > 0 ? round($total_carbs / $divisor, 1) : 0,
            'fat' => $divisor > 0 ? round($total_fat / $divisor, 1) : 0,
            'calories' => $divisor > 0 ? round($total_calories / $divisor, 1) : 0
        ];
    }
    
    protected function loadWaterConsumptionData($user_id, $start_date, $end_date)
    {
        // Check if there's a Diary model with water consumption data
        $waterEntries = Diary::where('user_id', $user_id)
            ->whereDate('date', '>=', $start_date->format('Y-m-d'))
            ->whereDate('date', '<=', $end_date->format('Y-m-d'))
            ->whereNotNull('water')
            ->where('water', '>', 0)
            ->get();
            
        // Calculate water consumption based on period type
        $total_water = $waterEntries->sum('water');
        $total_days = max(1, $start_date->diffInDays($end_date) + 1);
        $days_with_data = $waterEntries->count();
        
        if ($this->period_type === 'daily') {
            // For daily view, show total for that day
            $this->water_consumption = $total_water;
        } else {
            // For weekly/monthly/custom, show daily average
            $this->water_consumption = $days_with_data > 0 ? round($total_water / $days_with_data) : 0;
        }
    }
    
    protected function calculateCaloricDeficit($user_id, $start_date, $end_date)
    {
        $total_calories = MealItem::join('meals', 'meal_items.meal_id', '=', 'meals.id')
            ->join('foods', 'meal_items.food_id', '=', 'foods.id')
            ->where('meals.user_id', $user_id)
            ->whereBetween('meals.meal_date', [
                $start_date->format('Y-m-d'), 
                $end_date->format('Y-m-d')
            ])
            ->sum(DB::raw('foods.calories * meal_items.quantity'));
        
        // For non-daily periods, calculate daily average
        if ($this->period_type === 'daily') {
            $this->caloric_deficit = $this->daily_calorie_goal - $total_calories;
        } else {
            $total_days = max(1, $start_date->diffInDays($end_date) + 1);
            $average_daily_calories = $total_calories / $total_days;
            $this->caloric_deficit = $this->daily_calorie_goal - $average_daily_calories;
        }
    }
    
    public function getCaloricDeficitColor()
    {
        return $this->caloric_deficit >= 0 ? 'text-green-400' : 'text-red-400';
    }
    
    public function getCaloricDeficitFormatted()
    {
        $sign = $this->caloric_deficit > 0 ? '+' : '';
        return $sign . number_format($this->caloric_deficit, 0) . ' kcal';
    }
    
    #[On('meal-updated')]
    #[On('meal-item-updated')]
    #[On('meal-item-deleted')]
    public function refreshReports()
    {
        $this->loadReportData();
    }
    
    public function getMacroTotal()
    {
        return $this->nutrient_macros['protein'] + $this->nutrient_macros['carbs'] + $this->nutrient_macros['fat'];
    }
    
    public function getProteinPercentage()
    {
        $total = $this->getMacroTotal();
        return $total > 0 ? round(($this->nutrient_macros['protein'] / $total) * 100) : 0;
    }
    
    public function getCarbsPercentage()
    {
        $total = $this->getMacroTotal();
        return $total > 0 ? round(($this->nutrient_macros['carbs'] / $total) * 100) : 0;
    }
    
    public function getFatPercentage()
    {
        $total = $this->getMacroTotal();
        return $total > 0 ? round(($this->nutrient_macros['fat'] / $total) * 100) : 0;
    }

    public function getPeriodDisplayText()
    {
        $date = Carbon::parse($this->current_date);
        
        switch ($this->period_type) {
            case 'daily':
                return $date->format('d/m/Y');
            case 'weekly':
                $start = $date->copy()->startOfWeek();
                $end = $date->copy()->endOfWeek();
                return $start->format('d/m') . ' - ' . $end->format('d/m/Y');
            case 'monthly':
                return $date->format('F Y');
            case 'custom':
                if (!empty($this->custom_start_date) && !empty($this->custom_end_date)) {
                    $start = Carbon::parse($this->custom_start_date);
                    $end = Carbon::parse($this->custom_end_date);
                    return $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
                }
                return 'Período personalizado';
            default:
                return '';
        }
    }

    public function getWaterConsumptionLabel()
    {
        return $this->period_type === 'daily' ? 'Consumo do Dia' : 'Média Diária';
    }

    #[Title('Relatórios')]
    public function render()
    {
        return view('livewire.reports.index');
    }
}
