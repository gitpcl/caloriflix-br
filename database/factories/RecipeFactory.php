<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(rand(2, 5), true),
            'ingredients' => $this->faker->paragraphs(rand(2, 5), true),
            'instructions' => $this->faker->paragraphs(rand(2, 5), true),
            'preparation_time' => $this->faker->numberBetween(5, 60),
            'cooking_time' => $this->faker->numberBetween(10, 120),
            'servings' => $this->faker->numberBetween(1, 12),
        ];
    }
}
