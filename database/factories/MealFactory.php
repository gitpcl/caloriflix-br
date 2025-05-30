<?php

namespace Database\Factories;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'meal_type' => $this->faker->randomElement(['cafe_da_manha', 'almoco', 'lanche_da_tarde', 'jantar']),
            'meal_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
