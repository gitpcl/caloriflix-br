<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reminderTypes = ['intervalo de tempo', 'horário específico', 'diário'];
        $intervalHours = $this->faker->numberBetween(0, 12);
        $intervalMinutes = $this->faker->numberBetween(0, 59);
        
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(8),
            'reminder_type' => $this->faker->randomElement($reminderTypes),
            'interval_hours' => $intervalHours,
            'interval_minutes' => $intervalMinutes,
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'buttons_enabled' => $this->faker->boolean(30), // 30% chance of having buttons
            'auto_command_enabled' => $this->faker->boolean(20), // 20% chance of having auto command
            'auto_command' => $this->faker->boolean(20) ? $this->faker->sentence(5) : '',
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
    
    /**
     * Create a reminder with buttons enabled.
     *
     * @return static
     */
    public function withButtons()
    {
        return $this->state(function (array $attributes) {
            return [
                'buttons_enabled' => true,
            ];
        });
    }
    
    /**
     * Create a reminder with auto command enabled.
     *
     * @return static
     */
    public function withAutoCommand()
    {
        return $this->state(function (array $attributes) {
            return [
                'auto_command_enabled' => true,
                'auto_command' => 'Automatically execute this action',
            ];
        });
    }
    
    /**
     * Create an active reminder.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => true,
            ];
        });
    }
    
    /**
     * Create an inactive reminder.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }
}
