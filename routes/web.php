<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\MyFoods;
use App\Livewire\Preferences\Index as PreferencesIndex;

Route::redirect('/', '/login')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    // Preferences route
    Volt::route('preferences', PreferencesIndex::class)->name('preferences.index');
    
    // Food routes
    // Route::get('/foods', MyFoods::class)->name('foods.index');
    Route::get('/foods/create', function () {
        return view('foods.create');
    })->name('foods.create');
    Route::get('/foods/{food}', function ($food) {
        return view('foods.show', ['foodId' => $food]);
    })->name('foods.show');
});

require __DIR__.'/auth.php';
