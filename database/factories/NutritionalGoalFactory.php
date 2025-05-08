<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NutritionalGoal>
 */
class NutritionalGoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $objectives = [
            'Perder gordura',
            'Manter peso',
            'Ganhar massa'
        ];
        
        return [
            'user_id' => User::factory(),
            'protein' => $this->faker->numberBetween(80, 250),
            'carbs' => $this->faker->numberBetween(80, 400),
            'fat' => $this->faker->numberBetween(40, 140),
            'fiber' => $this->faker->numberBetween(20, 60),
            'calories' => $this->faker->numberBetween(1200, 4000),
            'water' => $this->faker->numberBetween(1500, 4000),
            'objective' => $this->faker->randomElement($objectives),
        ];
    }
    
    /**
     * Configure the model for losing fat.
     */
    public function forFatLoss(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'objective' => 'Perder gordura',
                'calories' => $this->faker->numberBetween(1200, 2000),
                'protein' => $this->faker->numberBetween(150, 250),
                'carbs' => $this->faker->numberBetween(80, 200),
                'fat' => $this->faker->numberBetween(40, 80),
            ];
        });
    }
    
    /**
     * Configure the model for maintaining weight.
     */
    public function forMaintenance(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'objective' => 'Manter peso',
                'calories' => $this->faker->numberBetween(1800, 2500),
                'protein' => $this->faker->numberBetween(120, 200),
                'carbs' => $this->faker->numberBetween(150, 300),
                'fat' => $this->faker->numberBetween(60, 100),
            ];
        });
    }
    
    /**
     * Configure the model for muscle gain.
     */
    public function forMuscleGain(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'objective' => 'Ganhar massa',
                'calories' => $this->faker->numberBetween(2500, 4000),
                'protein' => $this->faker->numberBetween(180, 250),
                'carbs' => $this->faker->numberBetween(250, 400),
                'fat' => $this->faker->numberBetween(80, 140),
            ];
        });
    }
}
