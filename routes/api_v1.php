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

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Foods
    Route::get('/foods/favorites', [FoodController::class, 'favorites'])->name('api.foods.favorites');
    Route::apiResource('foods', FoodController::class)->names([
        'index' => 'api.foods.index',
        'store' => 'api.foods.store',
        'show' => 'api.foods.show',
        'update' => 'api.foods.update',
        'destroy' => 'api.foods.destroy',
    ]);
    
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
    Route::get('/profile', [UserProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('api.profile.update');
    
    // User Preferences
    Route::get('/preferences', [UserPreferenceController::class, 'show'])->name('api.preferences.show');
    Route::put('/preferences', [UserPreferenceController::class, 'update'])->name('api.preferences.update');
    
    // Reminders
    Route::apiResource('reminders', ReminderController::class)->names([
        'index' => 'api.reminders.index',
        'store' => 'api.reminders.store',
        'show' => 'api.reminders.show',
        'update' => 'api.reminders.update',
        'destroy' => 'api.reminders.destroy',
    ]);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('api.reports.index');
    Route::get('/reports/period/{period}', [ReportController::class, 'byPeriod'])->name('api.reports.by-period');
    Route::get('/reports/custom', [ReportController::class, 'customRange'])->name('api.reports.custom-range');
});
