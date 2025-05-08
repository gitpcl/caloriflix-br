<?php

namespace Database\Factories;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReminderDetail>
 */
class ReminderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actionTypes = [
            'Registrado',
            'Pulado',
            'Lembrar mais tarde',
            'Ignorar hoje',
            'Concluído',
            'Preciso de ajuda'
        ];
        
        return [
            'reminder_id' => Reminder::factory()->withButtons(),
            'button_text' => $this->faker->randomElement($actionTypes),
            'button_action' => $this->faker->sentence(3),
            'display_order' => $this->faker->numberBetween(0, 5),
        ];
    }
}
