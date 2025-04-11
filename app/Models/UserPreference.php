<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        // Evaluation features
        'glycemic_index_enabled',
        'cholesterol_enabled',
        'keto_diet_enabled',
        'paleo_diet_enabled',
        'low_fodmap_enabled',
        'low_carb_enabled',
        'meal_plan_evaluation_enabled',
        // Preference settings
        'time_zone',
        'silent_mode_enabled',
        'language',
        'prioritize_taco_enabled',
        'daily_log_enabled',
        'photo_with_macros_enabled',
        'auto_fasting_enabled',
        'detailed_foods_enabled',
        'show_dashboard_enabled',
        'advanced_food_analysis_enabled',
        'group_water_enabled',
        'expanded_sections',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'glycemic_index_enabled' => 'boolean',
        'cholesterol_enabled' => 'boolean',
        'keto_diet_enabled' => 'boolean',
        'paleo_diet_enabled' => 'boolean',
        'low_fodmap_enabled' => 'boolean',
        'low_carb_enabled' => 'boolean',
        'meal_plan_evaluation_enabled' => 'boolean',
        'silent_mode_enabled' => 'boolean',
        'prioritize_taco_enabled' => 'boolean',
        'daily_log_enabled' => 'boolean',
        'photo_with_macros_enabled' => 'boolean',
        'auto_fasting_enabled' => 'boolean',
        'detailed_foods_enabled' => 'boolean',
        'show_dashboard_enabled' => 'boolean',
        'advanced_food_analysis_enabled' => 'boolean',
        'group_water_enabled' => 'boolean',
        'expanded_sections' => 'array',
    ];
    
    /**
     * Get the user that owns the preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
