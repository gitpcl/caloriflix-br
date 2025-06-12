<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Food;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Food::factory(100)->create();

        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        $users = [
            [
                'name' => 'Pedro Lopes',
                'email' => 'admin@pedroclopes.com',
                'password' => Hash::make('*Password1'),
                'role' => 'superadmin', // Assigning SuperAdmin role
            ],
            [
                'name' => 'Richard Silva',
                'email' => 'richard.ypsilva@gmail.com',
                'password' => Hash::make('*Password1'),
                'role' => 'admin', // Assigning Admin role
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $user->assignRole($role);
        }
    }
}
