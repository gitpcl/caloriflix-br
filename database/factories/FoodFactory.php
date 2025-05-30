<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodTypes = [
            'Arroz', 'Feijão', 'Frango', 'Carne', 'Salada', 'Batata', 
            'Ovo', 'Pão', 'Queijo', 'Leite', 'Iogurte', 'Aveia', 
            'Barra proteica', 'Shake', 'Wrap', 'Whey protein'
        ];
        
        $measurements = ['gramas', 'ml', 'unidade', 'porção', 'colher'];
        
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement($foodTypes) . ' ' . $this->faker->word,
            'quantity' => $this->faker->randomFloat(2, 10, 500),
            'unit' => $this->faker->randomElement($measurements),
            'protein' => $this->faker->randomFloat(2, 0, 50),
            'fat' => $this->faker->randomFloat(2, 0, 30),
            'carbohydrate' => $this->faker->randomFloat(2, 0, 100),
            'fiber' => $this->faker->randomFloat(2, 0, 15),
            'calories' => $this->faker->randomFloat(2, 50, 700),
            'barcode' => $this->faker->boolean(30) ? $this->faker->numerify('############') : null,
            'is_favorite' => $this->faker->boolean(20),
            'source' => $this->faker->randomElement(['manual', 'whatsapp']),
        ];
    }
    
    /**
     * Indicate that the food is a favorite.
     *
     * @return static
     */
    public function favorite()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_favorite' => true,
            ];
        });
    }
    
    /**
     * Indicate that the food is a protein-rich item.
     *
     * @return static
     */
    public function highProtein()
    {
        return $this->state(function (array $attributes) {
            return [
                'protein' => $this->faker->randomFloat(2, 20, 50),
                'fat' => $this->faker->randomFloat(2, 5, 15),
                'carbohydrate' => $this->faker->randomFloat(2, 5, 20),
                'calories' => $this->faker->randomFloat(2, 150, 350),
            ];
        });
    }
    
    /**
     * Indicate that the food is a carb-rich item.
     *
     * @return static
     */
    public function highCarb()
    {
        return $this->state(function (array $attributes) {
            return [
                'protein' => $this->faker->randomFloat(2, 2, 10),
                'fat' => $this->faker->randomFloat(2, 1, 10),
                'carbohydrate' => $this->faker->randomFloat(2, 30, 80),
                'calories' => $this->faker->randomFloat(2, 150, 400),
            ];
        });
    }
}
