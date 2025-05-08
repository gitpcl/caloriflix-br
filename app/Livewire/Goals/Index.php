<?php

namespace App\Livewire\Goals;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\NutritionalGoal;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public $debug = false; // Debug mode toggle
    
    /**
     * Component mount
     */
    public function mount()
    {
        $this->debug = config('app.debug', false);
        $this->loadUserData();
    }
    
    /**
     * Load user data
     */
    private function loadUserData()
    {
        $user = Auth::user();
        
        // Profile data
        $profile = UserProfile::where('user_id', $user->id)->first();
        if ($profile) {
            $this->weight = $profile->weight;
            $this->height = $profile->height;
            $this->gender = $profile->gender ?? 'Masculino';
            $this->age = $profile->age;
            $this->activityLevel = $profile->activity_level ?? 'Moderadamente ativo';
            $this->basalMetabolicRate = $profile->basal_metabolic_rate;
            $this->useBasalMetabolicRate = $profile->use_basal_metabolic_rate;
        }
        
        // Nutritional goals
        $goals = NutritionalGoal::where('user_id', $user->id)->first();
        if ($goals) {
            $this->protein = $goals->protein;
            $this->carbs = $goals->carbs;
            $this->fat = $goals->fat;
            $this->fiber = $goals->fiber;
            $this->calories = $goals->calories;
            $this->water = $goals->water;
            $this->objective = $goals->objective ?? 'Perder gordura';
        }
        
        // Plans
        $dietPlan = UserPlan::where('user_id', $user->id)->where('type', 'diet')->first();
        if ($dietPlan) {
            $this->dietPlan = $dietPlan->content;
        }
        
        $trainingPlan = UserPlan::where('user_id', $user->id)->where('type', 'training')->first();
        if ($trainingPlan) {
            $this->trainingPlan = $trainingPlan->content;
        }
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
        // Enable debug mode for troubleshooting
        $this->debug = true;
        
        $this->debugLog('Starting saveProfile method', [
            'weight' => $this->weight,
            'height' => $this->height,
            'gender' => $this->gender,
            'age' => $this->age,
        ]);
        
        // Validate input
        $validatedData = $this->validate([
            'weight' => 'required|numeric|min:30|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'gender' => 'required|in:Masculino,Feminino',
            'age' => 'required|numeric|min:10|max:120',
            'activityLevel' => 'required',
            'basalMetabolicRate' => 'required|numeric|min:500|max:10000',
        ]);
        
        $this->debugLog('Validation passed', $validatedData);
        
        $user = Auth::user();
        $this->debugLog('Current user', ['id' => $user->id, 'name' => $user->name]);
        
        // Get or create the user profile
        $profile = UserProfile::where('user_id', $user->id)->first();
        
        if (!$profile) {
            // Create new profile
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $this->debugLog('Creating new profile for user', ['user_id' => $user->id]);
        } else {
            $this->debugLog('Updating existing profile', ['profile_id' => $profile->id]);
        }
        
        // Update profile data
        $profile->weight = $this->weight;
        $profile->height = $this->height;
        $profile->gender = $this->gender;
        $profile->age = $this->age;
        $profile->activity_level = $this->activityLevel;
        $profile->basal_metabolic_rate = $this->basalMetabolicRate;
        $profile->use_basal_metabolic_rate = $this->useBasalMetabolicRate;
        
        $this->debugLog('Profile data before save', $profile->toArray());
        
        // Save with try/catch to debug
        try {
            $profile->saveOrFail();
            $this->debugLog('Profile saved successfully', ['profile_id' => $profile->id]);
            
            $this->dispatch('notify', [
                'message' => 'Perfil salvo com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->debugLog('Error saving profile', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'message' => 'Erro: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
    
    /**
     * Save nutritional goals
     */
    public function saveGoals()
    {
        // Enable debug mode for troubleshooting
        $this->debug = true;
        
        $this->debugLog('Starting saveGoals method', [
            'protein' => $this->protein,
            'carbs' => $this->carbs,
            'fat' => $this->fat,
            'calories' => $this->calories,
        ]);
        
        // Validate input
        $validatedData = $this->validate([
            'protein' => 'required|numeric|min:0|max:500',
            'carbs' => 'required|numeric|min:0|max:1000',
            'fat' => 'required|numeric|min:0|max:500',
            'fiber' => 'required|numeric|min:0|max:200',
            'calories' => 'required|numeric|min:500|max:10000',
            'water' => 'required|numeric|min:0|max:10000',
            'objective' => 'required',
        ]);
        
        $this->debugLog('Validation passed', $validatedData);
        
        $user = Auth::user();
        $this->debugLog('Current user', ['id' => $user->id, 'name' => $user->name]);
        
        // Get or create the nutritional goals
        $goals = NutritionalGoal::where('user_id', $user->id)->first();
        
        if (!$goals) {
            // Create new goals
            $goals = new NutritionalGoal();
            $goals->user_id = $user->id;
            $this->debugLog('Creating new nutritional goals for user', ['user_id' => $user->id]);
        } else {
            $this->debugLog('Updating existing nutritional goals', ['goals_id' => $goals->id]);
        }
        
        // Update goals data
        $goals->protein = $this->protein;
        $goals->carbs = $this->carbs;
        $goals->fat = $this->fat;
        $goals->fiber = $this->fiber;
        $goals->calories = $this->calories;
        $goals->water = $this->water;
        $goals->objective = $this->objective;
        
        $this->debugLog('Goals data before save', $goals->toArray());
        
        // Save with try/catch to debug
        try {
            $goals->saveOrFail();
            $this->debugLog('Nutritional goals saved successfully', ['goals_id' => $goals->id]);
            
            $this->dispatch('notify', [
                'message' => 'Metas salvas com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->debugLog('Error saving nutritional goals', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'message' => 'Erro: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
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
        $filePath = null;
        
        if ($this->dietPlanFile) {
            $this->validate([
                'dietPlanFile' => 'file|mimes:pdf|max:2048', // 2MB max
            ]);
            
            $filePath = $this->dietPlanFile->store('diet-plans', 'public');
        } else {
            $this->validate([
                'dietPlan' => 'nullable|string',
            ]);
        }
        
        $user = Auth::user();
        
        // Find existing plan or create new one
        $plan = UserPlan::where('user_id', $user->id)->where('type', 'diet')->first();
        
        if ($plan) {
            // Update existing plan
            $plan->content = $this->dietPlan;
            if ($filePath) {
                $plan->file_path = $filePath;
            }
            
            // Save with try/catch to debug
            try {
                $saved = $plan->save();
                
                if (!$saved) {
                    $this->dispatch('notify', [
                        'message' => 'Erro ao salvar plano alimentar. Tente novamente.',
                        'type' => 'error'
                    ]);
                    return;
                }
                
                $this->showDietPlanModal = false;
                $this->dispatch('notify', [
                    'message' => 'Plano alimentar salvo com sucesso!',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->debugLog('Error saving diet plan', ['error' => $e->getMessage()]);
                $this->dispatch('notify', [
                    'message' => 'Erro: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        } else {
            // Create new plan
            try {
                UserPlan::create([
                    'user_id' => $user->id,
                    'type' => 'diet',
                    'content' => $this->dietPlan,
                    'file_path' => $filePath
                ]);
                
                $this->showDietPlanModal = false;
                $this->dispatch('notify', [
                    'message' => 'Plano alimentar salvo com sucesso!',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->debugLog('Error creating diet plan', ['error' => $e->getMessage()]);
                $this->dispatch('notify', [
                    'message' => 'Erro ao criar plano: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        }
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
        
        // Find existing plan or create new one
        $plan = UserPlan::where('user_id', $user->id)->where('type', 'training')->first();
        
        if ($plan) {
            // Update existing plan
            $plan->content = $this->trainingPlan;
            
            // Save with try/catch to debug
            try {
                $saved = $plan->save();
                
                if (!$saved) {
                    $this->dispatch('notify', [
                        'message' => 'Erro ao salvar plano de treino. Tente novamente.',
                        'type' => 'error'
                    ]);
                    return;
                }
                
                $this->showTrainingPlanModal = false;
                $this->dispatch('notify', [
                    'message' => 'Plano de treino salvo com sucesso!',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->debugLog('Error saving training plan', ['error' => $e->getMessage()]);
                $this->dispatch('notify', [
                    'message' => 'Erro: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        } else {
            // Create new plan
            try {
                UserPlan::create([
                    'user_id' => $user->id,
                    'type' => 'training',
                    'content' => $this->trainingPlan
                ]);
                
                $this->showTrainingPlanModal = false;
                $this->dispatch('notify', [
                    'message' => 'Plano de treino salvo com sucesso!',
                    'type' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->debugLog('Error creating training plan', ['error' => $e->getMessage()]);
                $this->dispatch('notify', [
                    'message' => 'Erro ao criar plano: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        }
    }
    
    /**
     * Debug log method
     */
    private function debugLog($message, $data = [])
    {
        if ($this->debug) {
            try {
                \Illuminate\Support\Facades\Log::debug('[Goals Component] ' . $message, $data);
            } catch (\Exception $e) {
                // Fallback if logging fails
                error_log('[Goals Component Debug] ' . $message . ' - Error logging: ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.goals.index');
    }
}
