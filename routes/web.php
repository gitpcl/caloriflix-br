<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\MyFoods;
use App\Livewire\Preferences\Index as PreferencesIndex;

Route::redirect('/', '/login')->name('home');

Route::redirect('dashboard', 'today');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Preferences route
    Volt::route('preferences', PreferencesIndex::class)->name('preferences.index');

    // Goals route
    Route::get('/goals', App\Livewire\Goals\Index::class)->name('goals.index');

    // Reminders route
    Route::get('/reminders', App\Livewire\Reminders\Index::class)->name('reminders.index');

    // Recipes route
    Route::get('/recipes', App\Livewire\Recipes\Index::class)->name('recipes.index');

    // Measurements routes
    Route::get('/measurements', App\Livewire\Measurements\Index::class)->name('measurements.index');

    // Food routes
    Route::get('/foods', App\Livewire\Foods\Index::class)->name('foods.index');
    Route::get('/foods/create', function () {
        return view('foods.create');
    })->name('foods.create');
    Route::get('/foods/{food}', function ($food) {
        return view('foods.show', ['foodId' => $food]);
    })->name('foods.show');

    // Diary route
    Route::get('/diary', App\Livewire\Diary\Index::class)->name('diary.index');

    // Reports route
    Route::get('/reports', App\Livewire\Reports\Index::class)->name('reports.index');

    // Today (Hoje) route
    Route::get('/today', App\Livewire\Today\Index::class)->name('today.index');

    // Meals route
    Route::get('/meals', App\Livewire\Diary\Index::class)->name('meals.index');

    // Roles & Permissions route (SuperAdmin only)
    Route::get('/roles-permissions', App\Livewire\RolesPermissions\Index::class)
        ->middleware('superadmin')
        ->name('roles-permissions.index');

    // Users Management route (SuperAdmin only)
    Route::get('/users', App\Livewire\Users\Index::class)
        ->middleware('superadmin')
        ->name('users.index');
});

require __DIR__.'/auth.php';
