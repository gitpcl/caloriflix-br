<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\FoodController;
use App\Http\Controllers\API\V1\MealController;
use App\Http\Controllers\API\V1\MealItemController;
use App\Http\Controllers\API\V1\DiaryController;
use App\Http\Controllers\API\V1\RecipeController;
use App\Http\Controllers\API\V1\MeasurementController;
use App\Http\Controllers\API\V1\NutritionalGoalController;
use App\Http\Controllers\API\V1\UserProfileController;
use App\Http\Controllers\API\V1\UserPreferenceController;
use App\Http\Controllers\API\V1\ReminderController;
use App\Http\Controllers\API\V1\ReportController;
use App\Http\Controllers\API\V1\TokenController;

// Public routes with rate limiting
Route::middleware('throttle:auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes with authenticated rate limiting
Route::middleware(['auth:sanctum', 'throttle:authenticated'])->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Token management
    Route::prefix('tokens')->group(function () {
        Route::get('/', [TokenController::class, 'index'])->name('api.tokens.index');
        Route::post('/', [TokenController::class, 'store'])->name('api.tokens.store');
        Route::post('/refresh', [TokenController::class, 'refresh'])->name('api.tokens.refresh');
        Route::delete('/revoke-all', [TokenController::class, 'revokeAll'])->name('api.tokens.revoke-all');
        Route::delete('/{token}', [TokenController::class, 'destroy'])->name('api.tokens.destroy');
    });
    
    // Foods
    Route::get('/foods/favorites', [FoodController::class, 'favorites'])
        ->middleware('cache.response:30')
        ->name('api.foods.favorites');
    
    Route::get('/foods', [FoodController::class, 'index'])
        ->middleware('cache.response:15')
        ->name('api.foods.index');
    
    Route::get('/foods/{food}', [FoodController::class, 'show'])
        ->middleware('cache.response:60')
        ->name('api.foods.show');
    
    Route::post('/foods', [FoodController::class, 'store'])
        ->middleware('cache.invalidate:foods')
        ->name('api.foods.store');
    
    Route::put('/foods/{food}', [FoodController::class, 'update'])
        ->middleware('cache.invalidate:foods')
        ->name('api.foods.update');
    
    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])
        ->middleware('cache.invalidate:foods')
        ->name('api.foods.destroy');
    
    // Meals
    Route::apiResource('meals', MealController::class)->names([
        'index' => 'api.meals.index',
        'store' => 'api.meals.store',
        'show' => 'api.meals.show',
        'update' => 'api.meals.update',
        'destroy' => 'api.meals.destroy',
    ]);
    Route::get('/meals/today', [MealController::class, 'today'])->name('api.meals.today');
    Route::get('/meals/date/{date}', [MealController::class, 'byDate'])->name('api.meals.by-date');
    
    // Meal Items
    Route::apiResource('meal-items', MealItemController::class)->names([
        'index' => 'api.meal-items.index',
        'store' => 'api.meal-items.store',
        'show' => 'api.meal-items.show',
        'update' => 'api.meal-items.update',
        'destroy' => 'api.meal-items.destroy',
    ]);
    
    // Diary
    Route::get('/diary/date/{date}', [DiaryController::class, 'byDate'])->name('api.diary.by-date');
    Route::apiResource('diary', DiaryController::class)->names([
        'index' => 'api.diary.index',
        'store' => 'api.diary.store',
        'show' => 'api.diary.show',
        'update' => 'api.diary.update',
        'destroy' => 'api.diary.destroy',
    ]);
    
    // Recipes
    Route::apiResource('recipes', RecipeController::class)->names([
        'index' => 'api.recipes.index',
        'store' => 'api.recipes.store',
        'show' => 'api.recipes.show',
        'update' => 'api.recipes.update',
        'destroy' => 'api.recipes.destroy',
    ]);
    
    // Measurements (glucose, weight, etc.)
    Route::apiResource('measurements', MeasurementController::class)->names([
        'index' => 'api.measurements.index',
        'store' => 'api.measurements.store',
        'show' => 'api.measurements.show',
        'update' => 'api.measurements.update',
        'destroy' => 'api.measurements.destroy',
    ]);
    Route::get('/measurements/latest', [MeasurementController::class, 'latest'])->name('api.measurements.latest');
    Route::get('/measurements/type/{type}', [MeasurementController::class, 'byType'])->name('api.measurements.by-type');
    
    // Nutritional Goals
    Route::get('/nutritional-goals/current', [NutritionalGoalController::class, 'current'])->name('api.nutritional-goals.current');
    Route::apiResource('nutritional-goals', NutritionalGoalController::class)->names([
        'index' => 'api.nutritional-goals.index',
        'store' => 'api.nutritional-goals.store',
        'show' => 'api.nutritional-goals.show',
        'update' => 'api.nutritional-goals.update',
        'destroy' => 'api.nutritional-goals.destroy',
    ]);
    
    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show'])
        ->middleware('cache.response:120')
        ->name('api.profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])
        ->middleware('cache.invalidate:profile,preferences')
        ->name('api.profile.update');
    
    // User Preferences
    Route::get('/preferences', [UserPreferenceController::class, 'show'])
        ->middleware('cache.response:120')
        ->name('api.preferences.show');
    Route::put('/preferences', [UserPreferenceController::class, 'update'])
        ->middleware('cache.invalidate:preferences')
        ->name('api.preferences.update');
    
    // Reminders
    Route::apiResource('reminders', ReminderController::class)->names([
        'index' => 'api.reminders.index',
        'store' => 'api.reminders.store',
        'show' => 'api.reminders.show',
        'update' => 'api.reminders.update',
        'destroy' => 'api.reminders.destroy',
    ]);
    
    // Reports (with stricter rate limiting)
    Route::middleware('throttle:reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('api.reports.index');
        Route::get('/reports/period/{period}', [ReportController::class, 'byPeriod'])->name('api.reports.by-period');
        Route::get('/reports/custom', [ReportController::class, 'customRange'])->name('api.reports.custom-range');
    });
});
