<?php

namespace Database\Seeders;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Seeder;

class FixedRemindersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create a default one if none exists
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Creating a default user...');
            $user = User::factory()->create([
                'name' => 'Default User',
                'email' => 'user@example.com',
            ]);
        }
        
        $userId = $user->id;
        $this->command->info("Creating fixed reminders for user: {$user->name} (ID: {$userId})");
        
        $fixedReminders = [
            [
                'name' => 'Café da manhã',
                'description' => 'Lembrete para o café da manhã',
                'reminder_type' => 'horário específico',
                'interval_hours' => 0,
                'interval_minutes' => 0,
                'start_time' => '08:00',
                'end_time' => '08:30',
                'buttons_enabled' => false,
                'auto_command_enabled' => false,
                'auto_command' => '',
                'active' => true,
            ],
            [
                'name' => 'Almoço',
                'description' => 'Lembrete para o almoço',
                'reminder_type' => 'horário específico',
                'interval_hours' => 0,
                'interval_minutes' => 0,
                'start_time' => '12:00',
                'end_time' => '13:00',
                'buttons_enabled' => false,
                'auto_command_enabled' => false,
                'auto_command' => '',
                'active' => true,
            ],
            [
                'name' => 'Lanche da tarde',
                'description' => 'Lembrete para o lanche da tarde',
                'reminder_type' => 'horário específico',
                'interval_hours' => 0,
                'interval_minutes' => 0,
                'start_time' => '15:00',
                'end_time' => '15:30',
                'buttons_enabled' => false,
                'auto_command_enabled' => false,
                'auto_command' => '',
                'active' => false,
            ],
            [
                'name' => 'Jantar',
                'description' => 'Lembrete para o jantar',
                'reminder_type' => 'horário específico',
                'interval_hours' => 0,
                'interval_minutes' => 0,
                'start_time' => '19:00',
                'end_time' => '19:30',
                'buttons_enabled' => false,
                'auto_command_enabled' => false,
                'auto_command' => '',
                'active' => true,
            ],
        ];
        
        foreach ($fixedReminders as $index => $reminderData) {
            $id = $index + 1; // Assign sequential IDs starting from 1
            $reminderData['user_id'] = $userId;
            
            Reminder::updateOrCreate(
                [
                    'id' => $id,
                    'user_id' => $userId,
                ],
                $reminderData
            );
        }
        
        $this->command->info('Fixed reminders created successfully.');
    }
}
