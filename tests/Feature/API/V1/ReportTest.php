<?php

use App\Models\User;
use App\Models\Meal;
use App\Models\MealItem;
use App\Models\Food;
use App\Models\Diary;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
    
    // Create some test data for reports
    $this->food = Food::factory()->create([
        'user_id' => $this->user->id,
        'protein' => 20,
        'fat' => 10,
        'carbohydrate' => 30,
        'fiber' => 5,
        'calories' => 290,
    ]);
    
    $this->meal = Meal::factory()->create([
        'user_id' => $this->user->id,
        'meal_date' => now()->format('Y-m-d'),
    ]);
    
    MealItem::factory()->create([
        'meal_id' => $this->meal->id,
        'food_id' => $this->food->id,
        'quantity' => 150,
    ]);
    
    $this->diary = Diary::factory()->create([
        'user_id' => $this->user->id,
        'date' => now()->format('Y-m-d'),
        'water' => 1500,
    ]);
});

// Test index endpoint
test('user can view overall reports', function () {
    $response = $this->getJson('/api/v1/reports');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'period_type',
                'start_date',
                'end_date',
                'number_of_days',
                'period_display',
                'nutrition' => [
                    'calories',
                    'macros',
                    'fiber',
                ],
                'water_consumption',
                'glucose_measurements',
            ]
        ])
        ->assertJsonPath('data.period_type', 'daily');
});

// Test byPeriod endpoint
test('user can view reports by period', function () {
    // Test daily period
    $response = $this->getJson('/api/v1/reports/period/daily');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'period_type',
                'start_date',
                'end_date',
                'number_of_days',
                'nutrition',
                'water_consumption',
            ]
        ])
        ->assertJsonPath('data.period_type', 'daily');
    
    // Test weekly period
    $response = $this->getJson('/api/v1/reports/period/weekly');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'period_type',
                'start_date',
                'end_date',
                'number_of_days',
                'nutrition',
                'water_consumption',
            ]
        ])
        ->assertJsonPath('data.period_type', 'weekly');
    
    // Test monthly period
    $response = $this->getJson('/api/v1/reports/period/monthly');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'period_type',
                'start_date',
                'end_date',
                'number_of_days',
                'nutrition',
                'water_consumption',
            ]
        ])
        ->assertJsonPath('data.period_type', 'monthly');
});

// Test customRange endpoint
test('user can view reports with custom date range', function () {
    $startDate = now()->subDays(7)->format('Y-m-d');
    $endDate = now()->format('Y-m-d');
    
    $response = $this->getJson("/api/v1/reports/custom?start_date={$startDate}&end_date={$endDate}");
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'period_type',
                'start_date',
                'end_date',
                'number_of_days',
                'period_display',
                'nutrition',
                'water_consumption',
            ]
        ])
        ->assertJsonPath('data.period_type', 'custom')
        ->assertJsonPath('data.start_date', $startDate)
        ->assertJsonPath('data.end_date', $endDate);
});

// Test error handling with invalid custom range
test('user cannot view reports with invalid custom date range', function () {
    $startDate = now()->format('Y-m-d');
    $endDate = now()->subDays(7)->format('Y-m-d'); // End date before start date
    
    $response = $this->getJson("/api/v1/reports/custom?start_date={$startDate}&end_date={$endDate}");
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

// Test authorization
test('unauthenticated user cannot access report endpoints', function () {
    // Make request without authentication
    $response = test()->getJson('/api/v1/reports');
    
    $response->assertStatus(401);
});
