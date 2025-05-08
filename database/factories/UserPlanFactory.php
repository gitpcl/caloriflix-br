<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPlan>
 */
class UserPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['diet', 'training'];
        
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement($types),
            'content' => $this->faker->paragraphs(3, true),
            'file_path' => null,
        ];
    }
    
    /**
     * Configure the model as a diet plan.
     */
    public function diet(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'diet',
                'content' => $this->faker->paragraph(5) . "\n\n" .
                             "Refeição 1: " . $this->faker->sentence() . "\n" .
                             "Refeição 2: " . $this->faker->sentence() . "\n" .
                             "Refeição 3: " . $this->faker->sentence() . "\n" .
                             "Refeição 4: " . $this->faker->sentence() . "\n" .
                             "Refeição 5: " . $this->faker->sentence(),
            ];
        });
    }
    
    /**
     * Configure the model as a training plan.
     */
    public function training(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'training',
                'content' => $this->faker->paragraph(4) . "\n\n" .
                             "Segunda: " . $this->faker->sentence() . "\n" .
                             "Terça: " . $this->faker->sentence() . "\n" .
                             "Quarta: " . $this->faker->sentence() . "\n" .
                             "Quinta: " . $this->faker->sentence() . "\n" .
                             "Sexta: " . $this->faker->sentence() . "\n" .
                             "Sábado: " . $this->faker->sentence() . "\n" .
                             "Domingo: Descanso",
            ];
        });
    }
    
    /**
     * Configure the model to have a file path.
     */
    public function withFile(): static
    {
        return $this->state(function (array $attributes) {
            $fileName = 'plan_' . $this->faker->randomNumber(5) . '.pdf';
            return [
                'file_path' => 'diet-plans/' . $fileName,
            ];
        });
    }
}
