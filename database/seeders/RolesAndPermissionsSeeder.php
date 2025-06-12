<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions by category
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            
            // Profile & Settings
            'profile.view',
            'profile.update',
            'settings.view',
            'settings.update',
            
            // Foods
            'foods.view',
            'foods.create',
            'foods.update',
            'foods.delete',
            'foods.favorites.view',
            
            // Meals
            'meals.view',
            'meals.create',
            'meals.update',
            'meals.delete',
            
            // Meal Items
            'meal-items.view',
            'meal-items.create',
            'meal-items.update',
            'meal-items.delete',
            
            // Diary
            'diary.view',
            'diary.create',
            'diary.update',
            'diary.delete',
            
            // Recipes
            'recipes.view',
            'recipes.create',
            'recipes.update',
            'recipes.delete',
            
            // Measurements
            'measurements.view',
            'measurements.create',
            'measurements.update',
            'measurements.delete',
            
            // Nutritional Goals
            'nutritional-goals.view',
            'nutritional-goals.create',
            'nutritional-goals.update',
            'nutritional-goals.delete',
            
            // Preferences
            'preferences.view',
            'preferences.update',
            
            // Reminders
            'reminders.view',
            'reminders.create',
            'reminders.update',
            'reminders.delete',
            
            // Reports
            'reports.view',
            'reports.generate',
            
            // API Tokens
            'tokens.view',
            'tokens.create',
            'tokens.delete',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $customer = Role::create(['name' => 'customer']);

        // SuperAdmin gets all permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Admin permissions (all except user management)
        $adminPermissions = [
            // Profile & Settings
            'profile.view',
            'profile.update',
            'settings.view',
            'settings.update',
            
            // Foods - all permissions
            'foods.view',
            'foods.create',
            'foods.update',
            'foods.delete',
            'foods.favorites.view',
            
            // Meals - all permissions
            'meals.view',
            'meals.create',
            'meals.update',
            'meals.delete',
            
            // Meal Items - all permissions
            'meal-items.view',
            'meal-items.create',
            'meal-items.update',
            'meal-items.delete',
            
            // Diary - all permissions
            'diary.view',
            'diary.create',
            'diary.update',
            'diary.delete',
            
            // Recipes - all permissions
            'recipes.view',
            'recipes.create',
            'recipes.update',
            'recipes.delete',
            
            // Measurements - all permissions
            'measurements.view',
            'measurements.create',
            'measurements.update',
            'measurements.delete',
            
            // Nutritional Goals - all permissions
            'nutritional-goals.view',
            'nutritional-goals.create',
            'nutritional-goals.update',
            'nutritional-goals.delete',
            
            // Preferences
            'preferences.view',
            'preferences.update',
            
            // Reminders - all permissions
            'reminders.view',
            'reminders.create',
            'reminders.update',
            'reminders.delete',
            
            // Reports
            'reports.view',
            'reports.generate',
            
            // API Tokens
            'tokens.view',
            'tokens.create',
            'tokens.delete',
        ];
        $admin->givePermissionTo($adminPermissions);

        // Customer permissions (limited to their own data)
        $customerPermissions = [
            // Profile & Settings
            'profile.view',
            'profile.update',
            'settings.view',
            'settings.update',
            
            // Foods - view and create only
            'foods.view',
            'foods.create',
            'foods.favorites.view',
            
            // Meals - own meals only
            'meals.view',
            'meals.create',
            'meals.update',
            'meals.delete',
            
            // Meal Items - own items only
            'meal-items.view',
            'meal-items.create',
            'meal-items.update',
            'meal-items.delete',
            
            // Diary - own diary only
            'diary.view',
            'diary.create',
            'diary.update',
            'diary.delete',
            
            // Recipes - view and create only
            'recipes.view',
            'recipes.create',
            
            // Measurements - own measurements only
            'measurements.view',
            'measurements.create',
            'measurements.update',
            'measurements.delete',
            
            // Nutritional Goals - own goals only
            'nutritional-goals.view',
            'nutritional-goals.create',
            'nutritional-goals.update',
            'nutritional-goals.delete',
            
            // Preferences
            'preferences.view',
            'preferences.update',
            
            // Reminders - own reminders only
            'reminders.view',
            'reminders.create',
            'reminders.update',
            'reminders.delete',
            
            // Reports - own reports only
            'reports.view',
            'reports.generate',
            
            // API Tokens - own tokens only
            'tokens.view',
            'tokens.create',
            'tokens.delete',
        ];
        $customer->givePermissionTo($customerPermissions);
    }
}