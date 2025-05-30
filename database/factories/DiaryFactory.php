<?php

namespace Database\Factories;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Diary>
 */
class DiaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Diary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes' => $this->faker->optional()->paragraph(),
            'mood' => $this->faker->numberBetween(1, 5), // 1-5 scale as per migration comment
            'water' => $this->faker->numberBetween(0, 4000), // Water intake in ml
            'sleep' => $this->faker->optional()->numberBetween(240, 600), // Sleep in minutes (4-10 hours)
        ];
    }

    /**
     * Indicate that the diary entry is for today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the diary entry has no mood tracking.
     */
    public function withoutMood(): static
    {
        return $this->state(fn (array $attributes) => [
            'mood' => null,
        ]);
    }
}
