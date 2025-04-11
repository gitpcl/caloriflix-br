<?php

namespace App\Livewire\Preferences;

use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    // Track which sections are expanded/collapsed
    public $expandedSections = [
        'preferences' => false,
        'evaluations' => false,
        'personal_info' => false,
        'subscription_info' => false,
        'api_integration' => false,
    ];

    // Track evaluation feature toggles
    public $glycemicIndexEnabled = false;
    public $cholesterolEnabled = false;
    public $ketoDietEnabled = false;
    public $paleoDietEnabled = false;
    public $lowFodmapEnabled = false;
    public $lowCarbEnabled = false;
    public $mealPlanEvaluationEnabled = false;
    
    // Track preferences toggles
    public $timeZone = 'UTC-3';
    public $silentModeEnabled = false;
    public $language = 'Português';
    public $prioritizeTacoEnabled = false;
    public $dailyLogEnabled = true; // On by default as shown in image
    public $photoWithMacrosEnabled = false;
    public $autoFastingEnabled = true; // On by default as shown in image
    public $detailedFoodsEnabled = false;
    public $showDashboardEnabled = false;
    public $advancedFoodAnalysisEnabled = false;
    public $groupWaterEnabled = false;

    /**
     * Toggle section expansion state
     */
    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
        
        // Save expanded sections state to database
        $this->saveExpandedSections();
    }

    /**
     * Update preference value (for dropdowns and other non-toggle inputs)
     */
    public function updatePreference($preference, $value)
    {
        if (property_exists($this, $preference)) {
            $this->$preference = $value;
            // Save will be triggered by updated() hook
        }
    }

    /**
     * Hook that runs after any property is updated
     * This is part of Livewire's lifecycle hooks
     */
    public function updated($name)
    {
        // Save any changes to preferences immediately, except for expanded sections
        if (str_contains($name, 'expandedSections')) {
            $this->saveExpandedSections();
        } else {
            $this->savePreferences();
        }
    }

    /**
     * Save preferences to database
     */
    protected function savePreferences()
    {
        $user = Auth::user();
        if (!$user) return;

        // Convert camelCase property names to snake_case for database
        $data = [
            'glycemic_index_enabled' => $this->glycemicIndexEnabled,
            'cholesterol_enabled' => $this->cholesterolEnabled,
            'keto_diet_enabled' => $this->ketoDietEnabled,
            'paleo_diet_enabled' => $this->paleoDietEnabled,
            'low_fodmap_enabled' => $this->lowFodmapEnabled,
            'low_carb_enabled' => $this->lowCarbEnabled,
            'meal_plan_evaluation_enabled' => $this->mealPlanEvaluationEnabled,
            'time_zone' => $this->timeZone,
            'silent_mode_enabled' => $this->silentModeEnabled,
            'language' => $this->language,
            'prioritize_taco_enabled' => $this->prioritizeTacoEnabled,
            'daily_log_enabled' => $this->dailyLogEnabled,
            'photo_with_macros_enabled' => $this->photoWithMacrosEnabled,
            'auto_fasting_enabled' => $this->autoFastingEnabled,
            'detailed_foods_enabled' => $this->detailedFoodsEnabled,
            'show_dashboard_enabled' => $this->showDashboardEnabled,
            'advanced_food_analysis_enabled' => $this->advancedFoodAnalysisEnabled,
            'group_water_enabled' => $this->groupWaterEnabled,
        ];

        // Update or create preferences
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }
    
    /**
     * Save expanded sections to database
     */
    protected function saveExpandedSections()
    {
        $user = Auth::user();
        if (!$user) return;
        
        // Get existing preference or create new
        $preference = UserPreference::firstOrCreate([
            'user_id' => $user->id
        ]);
        
        // Update expanded sections JSON
        $preference->expanded_sections = $this->expandedSections;
        $preference->save();
    }

    /**
     * Load preferences from database on component mount
     */
    public function mount()
    {
        $user = Auth::user();
        if (!$user) return;

        // Get user's preferences
        $preference = $user->preference;
        
        // If user has no preferences yet, create default preferences
        if (!$preference) {
            $preference = new UserPreference([
                'user_id' => $user->id,
                // Default values are already set as model attributes
            ]);
            $preference->save();
            return;
        }
        
        // Load values from database to component properties
        $this->glycemicIndexEnabled = $preference->glycemic_index_enabled;
        $this->cholesterolEnabled = $preference->cholesterol_enabled;
        $this->ketoDietEnabled = $preference->keto_diet_enabled;
        $this->paleoDietEnabled = $preference->paleo_diet_enabled;
        $this->lowFodmapEnabled = $preference->low_fodmap_enabled;
        $this->lowCarbEnabled = $preference->low_carb_enabled;
        $this->mealPlanEvaluationEnabled = $preference->meal_plan_evaluation_enabled;
        
        $this->timeZone = $preference->time_zone;
        $this->silentModeEnabled = $preference->silent_mode_enabled;
        $this->language = $preference->language;
        $this->prioritizeTacoEnabled = $preference->prioritize_taco_enabled;
        $this->dailyLogEnabled = $preference->daily_log_enabled;
        $this->photoWithMacrosEnabled = $preference->photo_with_macros_enabled;
        $this->autoFastingEnabled = $preference->auto_fasting_enabled;
        $this->detailedFoodsEnabled = $preference->detailed_foods_enabled;
        $this->showDashboardEnabled = $preference->show_dashboard_enabled;
        $this->advancedFoodAnalysisEnabled = $preference->advanced_food_analysis_enabled;
        $this->groupWaterEnabled = $preference->group_water_enabled;
        
        // Load expanded sections if set
        if ($preference->expanded_sections) {
            $this->expandedSections = $preference->expanded_sections;
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.preferences.index');
    }
}
