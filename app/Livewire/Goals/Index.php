<?php

namespace App\Livewire\Goals;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    
    // Profile properties
    public $weight;
    public $height;
    public $gender;
    public $age;
    public $activityLevel;
    public $basalMetabolicRate;
    public $useBasalMetabolicRate = true;
    
    // Nutritional goals properties
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;
    public $calories;
    public $water;
    public $objective;
    
    // Diet plan properties
    public $dietPlan;
    public $dietPlanFile;
    
    // Training plan properties
    public $trainingPlan;
    
    // UI state
    public $profileExpanded = false;
    public $goalsExpanded = false;
    public $showDietPlanModal = false;
    public $showTrainingPlanModal = false;
    
    /**
     * Component mount
     */
    public function mount()
    {
        $this->loadUserData();
    }
    
    /**
     * Load user data
     */
    private function loadUserData()
    {
        $user = Auth::user();
        
        // Profile data
        $this->weight = $user->weight ?? '';
        $this->height = $user->height ?? '';
        $this->gender = $user->gender ?? 'Masculino';
        $this->age = $user->age ?? '';
        $this->activityLevel = $user->activity_level ?? 'Moderadamente ativo';
        $this->basalMetabolicRate = $user->basal_metabolic_rate ?? '';
        $this->useBasalMetabolicRate = $user->use_basal_metabolic_rate ?? true;
        
        // Nutritional goals
        $this->protein = $user->protein_goal ?? '';
        $this->carbs = $user->carbs_goal ?? '';
        $this->fat = $user->fat_goal ?? '';
        $this->fiber = $user->fiber_goal ?? '';
        $this->calories = $user->calorie_goal ?? '';
        $this->water = $user->water_goal ?? '';
        $this->objective = $user->objective ?? 'Perder gordura';
        
        // Plans
        $this->dietPlan = $user->diet_plan ?? '';
        $this->trainingPlan = $user->training_plan ?? '';
    }
    
    /**
     * Toggle profile accordion
     */
    public function toggleProfile()
    {
        $this->profileExpanded = !$this->profileExpanded;
    }
    
    /**
     * Toggle goals accordion
     */
    public function toggleGoals()
    {
        $this->goalsExpanded = !$this->goalsExpanded;
    }
    
    /**
     * Calculate basal metabolic rate
     */
    public function calculateBMR()
    {
        // Simple Mifflin-St Jeor Equation
        if ($this->weight && $this->height && $this->age && $this->gender) {
            if ($this->gender === 'Masculino') {
                $this->basalMetabolicRate = round((10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) + 5);
            } else {
                $this->basalMetabolicRate = round((10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) - 161);
            }
            
            // Apply activity level multiplier
            $activityMultipliers = [
                'Sedentário' => 1.2,
                'Levemente ativo' => 1.375,
                'Moderadamente ativo' => 1.55,
                'Muito ativo' => 1.725,
                'Extremamente ativo' => 1.9
            ];
            
            if (isset($activityMultipliers[$this->activityLevel])) {
                $this->basalMetabolicRate = round($this->basalMetabolicRate * $activityMultipliers[$this->activityLevel]);
            }
        }
    }
    
    /**
     * Save profile data
     */
    public function saveProfile()
    {
        $this->validate([
            'weight' => 'required|numeric|min:30|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'gender' => 'required|in:Masculino,Feminino',
            'age' => 'required|numeric|min:10|max:120',
            'activityLevel' => 'required',
            'basalMetabolicRate' => 'required|numeric|min:500|max:10000',
        ]);
        
        $user = Auth::user();
        
        // Get an instance of the User model that we can update
        $userModel = User::find($user->id);
        
        $userModel->weight = $this->weight;
        $userModel->height = $this->height;
        $userModel->gender = $this->gender;
        $userModel->age = $this->age;
        $userModel->activity_level = $this->activityLevel;
        $userModel->basal_metabolic_rate = $this->basalMetabolicRate;
        $userModel->use_basal_metabolic_rate = $this->useBasalMetabolicRate;
        $userModel->save();
        
        $this->dispatch('notify', [
            'message' => 'Perfil salvo com sucesso!',
            'type' => 'success'
        ]);
    }
    
    /**
     * Save nutritional goals
     */
    public function saveGoals()
    {
        $this->validate([
            'protein' => 'required|numeric|min:0|max:500',
            'carbs' => 'required|numeric|min:0|max:1000',
            'fat' => 'required|numeric|min:0|max:500',
            'fiber' => 'required|numeric|min:0|max:200',
            'calories' => 'required|numeric|min:500|max:10000',
            'water' => 'required|numeric|min:0|max:10000',
            'objective' => 'required',
        ]);
        
        $user = Auth::user();
        
        // Get an instance of the User model that we can update
        $userModel = User::find($user->id);
        
        $userModel->protein_goal = $this->protein;
        $userModel->carbs_goal = $this->carbs;
        $userModel->fat_goal = $this->fat;
        $userModel->fiber_goal = $this->fiber;
        $userModel->calorie_goal = $this->calories;
        $userModel->water_goal = $this->water;
        $userModel->objective = $this->objective;
        $userModel->save();
        
        $this->dispatch('notify', [
            'message' => 'Metas salvas com sucesso!',
            'type' => 'success'
        ]);
    }
    
    /**
     * Suggest goals based on profile
     */
    public function suggestGoals()
    {
        // Calculate calories based on BMR and objective
        if ($this->basalMetabolicRate) {
            switch ($this->objective) {
                case 'Perder gordura':
                    $this->calories = round($this->basalMetabolicRate * 0.8); // 20% deficit
                    break;
                case 'Manter peso':
                    $this->calories = $this->basalMetabolicRate;
                    break;
                case 'Ganhar massa':
                    $this->calories = round($this->basalMetabolicRate * 1.1); // 10% surplus
                    break;
                default:
                    $this->calories = $this->basalMetabolicRate;
            }
            
            // Calculate macros based on body weight and objective
            if ($this->weight) {
                switch ($this->objective) {
                    case 'Perder gordura':
                        $this->protein = round($this->weight * 2.2); // 2.2g per kg
                        $this->fat = round($this->weight * 1); // 1g per kg
                        break;
                    case 'Manter peso':
                        $this->protein = round($this->weight * 1.8); // 1.8g per kg
                        $this->fat = round($this->weight * 1); // 1g per kg
                        break;
                    case 'Ganhar massa':
                        $this->protein = round($this->weight * 2); // 2g per kg
                        $this->fat = round($this->weight * 1); // 1g per kg
                        break;
                    default:
                        $this->protein = round($this->weight * 2);
                        $this->fat = round($this->weight * 1);
                }
                
                // Calculate remaining calories from carbs
                $proteinCalories = $this->protein * 4;
                $fatCalories = $this->fat * 4;
                $remainingCalories = $this->calories - $proteinCalories - $fatCalories;
                $this->carbs = max(round($remainingCalories / 4), 0);
                
                // Set fiber and water
                $this->fiber = round($this->weight * 0.15); // 0.15g per kg
                $this->water = round($this->weight * 35); // 35ml per kg
            }
        }
        
        $this->dispatch('notify', [
            'message' => 'Metas sugeridas com base no seu perfil.',
            'type' => 'info'
        ]);
    }
    
    /**
     * Open diet plan modal
     */
    public function openDietPlanModal()
    {
        $this->showDietPlanModal = true;
    }
    
    /**
     * Open training plan modal
     */
    public function openTrainingPlanModal()
    {
        $this->showTrainingPlanModal = true;
    }
    
    /**
     * Close modals
     */
    public function closeModals()
    {
        $this->showDietPlanModal = false;
        $this->showTrainingPlanModal = false;
    }
    
    /**
     * Save diet plan
     */
    public function saveDietPlan()
    {
        if ($this->dietPlanFile) {
            $this->validate([
                'dietPlanFile' => 'file|mimes:pdf|max:2048', // 2MB max
            ]);
            
            $path = $this->dietPlanFile->store('diet-plans', 'public');
            $this->dietPlan = $path;
        } else {
            $this->validate([
                'dietPlan' => 'nullable|string',
            ]);
        }
        
        $user = Auth::user();
        
        // Get an instance of the User model that we can update
        $userModel = User::find($user->id);
        $userModel->diet_plan = $this->dietPlan;
        $userModel->save();
        
        $this->showDietPlanModal = false;
        
        $this->dispatch('notify', [
            'message' => 'Plano alimentar salvo com sucesso!',
            'type' => 'success'
        ]);
    }
    
    /**
     * Save training plan
     */
    public function saveTrainingPlan()
    {
        $this->validate([
            'trainingPlan' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        
        // Get an instance of the User model that we can update
        $userModel = User::find($user->id);
        $userModel->training_plan = $this->trainingPlan;
        $userModel->save();
        
        $this->showTrainingPlanModal = false;
        
        $this->dispatch('notify', [
            'message' => 'Plano de treino salvo com sucesso!',
            'type' => 'success'
        ]);
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.goals.index');
    }
}
