<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['Masculino', 'Feminino']);
        $activityLevels = [
            'Sedentário',
            'Levemente ativo',
            'Moderadamente ativo',
            'Muito ativo',
            'Extremamente ativo'
        ];
        
        return [
            'user_id' => User::factory(),
            'weight' => $this->faker->randomFloat(1, 45, 140),
            'height' => $this->faker->numberBetween(150, 210),
            'gender' => $gender,
            'age' => $this->faker->numberBetween(18, 80),
            'activity_level' => $this->faker->randomElement($activityLevels),
            'basal_metabolic_rate' => $this->faker->numberBetween(1200, 3000),
            'use_basal_metabolic_rate' => $this->faker->boolean(80), // 80% true
        ];
    }
    
    /**
     * Configure the model to have male gender.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'Masculino',
        ]);
    }
    
    /**
     * Configure the model to have female gender.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'Feminino',
        ]);
    }
    
    /**
     * Configure the model to have a specific activity level.
     */
    public function activityLevel(string $level): static
    {
        return $this->state(fn (array $attributes) => [
            'activity_level' => $level,
        ]);
    }
}
