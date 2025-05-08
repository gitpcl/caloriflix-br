<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            // Evaluation features
            'glycemic_index_enabled' => $this->faker->boolean(),
            'cholesterol_enabled' => $this->faker->boolean(),
            'keto_diet_enabled' => $this->faker->boolean(),
            'paleo_diet_enabled' => $this->faker->boolean(),
            'low_fodmap_enabled' => $this->faker->boolean(),
            'low_carb_enabled' => $this->faker->boolean(),
            'meal_plan_evaluation_enabled' => $this->faker->boolean(),
            // Preference settings
            'time_zone' => $this->faker->randomElement(['UTC-3', 'UTC-4', 'UTC-5', 'UTC+0']),
            'silent_mode_enabled' => $this->faker->boolean(),
            'language' => $this->faker->randomElement(['Português', 'English', 'Español']),
            'prioritize_taco_enabled' => $this->faker->boolean(),
            'daily_log_enabled' => $this->faker->boolean(70), // 70% true, as this is a common setting
            'photo_with_macros_enabled' => $this->faker->boolean(),
            'auto_fasting_enabled' => $this->faker->boolean(70), // 70% true, as this is a common setting
            'detailed_foods_enabled' => $this->faker->boolean(),
            'show_dashboard_enabled' => $this->faker->boolean(),
            'advanced_food_analysis_enabled' => $this->faker->boolean(),
            'group_water_enabled' => $this->faker->boolean(),
            'expanded_sections' => [
                'preferences' => $this->faker->boolean(),
                'evaluations' => $this->faker->boolean(),
                'personal_info' => $this->faker->boolean(),
                'subscription_info' => $this->faker->boolean(),
                'api_integration' => $this->faker->boolean(),
            ],
        ];
    }
}
