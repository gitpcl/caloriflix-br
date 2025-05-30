<?php

namespace Database\Factories;

use App\Models\MealItem;
use App\Models\Meal;
use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealItem>
 */
class MealItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MealItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meal_id' => Meal::factory(),
            'food_id' => Food::factory(),
            'quantity' => $this->faker->randomFloat(1, 50, 500),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
