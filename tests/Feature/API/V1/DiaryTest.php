<?php

use App\Models\Diary;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all diary entries', function () {
    // Create 3 diary entries for the test user with different dates
    $diaries = collect([0, 1, 2])->map(function ($days) {
        return Diary::factory()->create([
            'user_id' => $this->user->id,
            'date' => now()->subDays($days)->format('Y-m-d')
        ]);
    });
    
    $response = $this->getJson('/api/v1/diary');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'user_id', 'date', 'notes', 'mood', 'water', 'sleep',
                        'created_at', 'updated_at'
                    ]
                ],
                'current_page',
                'total'
            ]
        ]);
    
    // Check that we have at least the 3 diary entries we created
    $responseData = $response->json('data.data');
    expect(count($responseData))->toBeGreaterThanOrEqual(3);
    
    // Verify our created entries are in the response
    $createdIds = $diaries->pluck('id')->toArray();
    $responseIds = collect($responseData)->pluck('id')->toArray();
    foreach ($createdIds as $id) {
        expect($responseIds)->toContain($id);
    }
});

// Test store endpoint
test('user can create a new diary entry', function () {
    $diaryData = [
        'date' => now()->format('Y-m-d'),
        'water' => 1500,
        'notes' => 'Felt good today',
    ];
    
    $response = $this->postJson('/api/v1/diary', $diaryData);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'water' => 1500,
            'notes' => 'Felt good today',
        ]);
        
    // Check database with LIKE for date to handle time component
    $diary = Diary::where('user_id', $this->user->id)
        ->where('water', 1500)
        ->whereDate('date', now()->format('Y-m-d'))
        ->first();
        
    expect($diary)->not->toBeNull();
});

// Test show endpoint
test('user can view a specific diary entry', function () {
    $diary = Diary::factory()->create([
        'user_id' => $this->user->id,
        'water' => 2000,
        'notes' => 'Specific notes',
    ]);
    
    $response = $this->getJson("/api/v1/diary/{$diary->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $diary->id,
            'water' => 2000,
            'notes' => 'Specific notes',
        ]);
});

// Test update endpoint
test('user can update a diary entry', function () {
    $diary = Diary::factory()->create([
        'user_id' => $this->user->id,
        'water' => 1000,
        'notes' => 'Original notes',
    ]);
    
    $response = $this->putJson("/api/v1/diary/{$diary->id}", [
        'water' => 2500,
        'notes' => 'Updated notes',
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'water' => 2500,
            'notes' => 'Updated notes',
        ]);
        
    $this->assertDatabaseHas('diaries', [
        'id' => $diary->id,
        'water' => 2500,
        'notes' => 'Updated notes',
    ]);
});

// Test destroy endpoint
test('user can delete a diary entry', function () {
    $diary = Diary::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/diary/{$diary->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('diaries', ['id' => $diary->id]);
});

// Test byDate endpoint
test('user can view diary entry by date', function () {
    $date = now()->format('Y-m-d');
    
    // Create diary with specific date format
    $diary = Diary::factory()->create([
        'user_id' => $this->user->id,
        'date' => $date,
        'water' => 1800,
    ]);
    
    // Create entries for other days (different dates to avoid unique constraint)
    Diary::factory()->create([
        'user_id' => $this->user->id,
        'date' => now()->subDays(1)->format('Y-m-d'),
    ]);
    Diary::factory()->create([
        'user_id' => $this->user->id,
        'date' => now()->subDays(2)->format('Y-m-d'),
    ]);
    
    $response = $this->getJson("/api/v1/diary/date/{$date}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'water' => 1800,
        ]);
});

// Test authorization
test('user cannot view diary entries created by other users', function () {
    $otherUser = User::factory()->create();
    $diary = Diary::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/diary/{$diary->id}");
    
    $response->assertStatus(404);
});

test('user cannot update diary entries created by other users', function () {
    $otherUser = User::factory()->create();
    $diary = Diary::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/diary/{$diary->id}", [
        'water' => 2000
    ]);
    
    $response->assertStatus(404);
});
