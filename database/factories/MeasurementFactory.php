<?php

namespace Database\Factories;

use App\Models\Measurement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measurement>
 */
class MeasurementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Measurement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['weight', 'body_fat', 'muscle_mass', 'waist', 'hip', 'chest', 'arm', 'thigh']);
        
        $values = [
            'weight' => $this->faker->randomFloat(1, 50, 120),
            'body_fat' => $this->faker->randomFloat(1, 10, 40),
            'muscle_mass' => $this->faker->randomFloat(1, 20, 60),
            'waist' => $this->faker->randomFloat(1, 60, 120),
            'hip' => $this->faker->randomFloat(1, 80, 130),
            'chest' => $this->faker->randomFloat(1, 80, 120),
            'arm' => $this->faker->randomFloat(1, 20, 40),
            'thigh' => $this->faker->randomFloat(1, 40, 70),
        ];
        
        return [
            'user_id' => User::factory(),
            'type' => $type,
            'value' => $values[$type],
            'unit' => in_array($type, ['weight', 'body_fat', 'muscle_mass']) ? 'kg' : 'cm',
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'time' => $this->faker->time('H:i:s'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
