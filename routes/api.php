<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Default API version
Route::get('/', function () {
    return response()->json([
        'name' => 'Caloriflix API',
        'version' => '1.0.0',
        'current_time' => now()->toIso8601String(),
        'documentation' => 'https://your-domain.com/api/documentation'
    ]);
});

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    require base_path('routes/api_v1.php');
});

// Route for invalid version access
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found. Please check your URL and try again.',
        'available_versions' => ['v1']
    ], 404);
});
