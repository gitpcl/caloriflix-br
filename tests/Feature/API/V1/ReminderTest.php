<?php

use App\Models\Reminder;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAsSanctum($this->user);
});

// Test index endpoint
test('user can view all reminders', function () {
    // Create 3 reminders for the test user
    $reminders = Reminder::factory()->count(3)->create(['user_id' => $this->user->id]);
    
    $response = $this->getJson('/api/v1/reminders');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id', 'user_id', 'title', 'description', 'type', 'time', 
                        'days', 'repeat_type', 'active', 'created_at', 'updated_at'
                    ]
                ],
                'current_page',
                'total'
            ]
        ]);
    
    // Check that we have at least the 3 reminders we created
    $data = $response->json('data.data');
    expect(count($data))->toBeGreaterThanOrEqual(3);
    
    // Verify our created reminders are in the response
    $createdIds = $reminders->pluck('id')->toArray();
    $responseIds = collect($data)->pluck('id')->toArray();
    foreach ($createdIds as $id) {
        expect($responseIds)->toContain($id);
    }
});

// Test store endpoint
test('user can create a new reminder', function () {
    $reminderData = [
        'title' => 'Take Medication',
        'description' => 'Remember to take your daily medication',
        'type' => 'medication',
        'time' => '09:00',
        'days' => [1, 2, 3, 4, 5], // Monday to Friday
        'repeat_type' => 'specific_days',
        'active' => true,
    ];
    
    $response = $this->postJson('/api/v1/reminders', $reminderData);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Take Medication',
            'active' => true,
        ]);
        
    $this->assertDatabaseHas('reminders', [
        'user_id' => $this->user->id,
        'title' => 'Take Medication',
    ]);
});

// Test show endpoint
test('user can view a specific reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Log Breakfast',
        'type' => 'meal',
        'time' => '07:30',
        'days' => json_encode([1, 2, 3, 4, 5]),
        'repeat_type' => 'specific_days',
    ]);
    
    $response = $this->getJson("/api/v1/reminders/{$reminder->id}");
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $reminder->id,
            'title' => 'Log Breakfast',
            'time' => '07:30',
        ]);
});

// Test update endpoint
test('user can update a reminder', function () {
    $reminder = Reminder::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Original Title',
        'type' => 'meal',
        'time' => '12:00',
        'days' => json_encode([1, 2, 3, 4, 5]),
        'repeat_type' => 'specific_days',
        'is_active' => false,
    ]);
    
    $response = $this->putJson("/api/v1/reminders/{$reminder->id}", [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'type' => 'water',
        'time' => '13:30',
        'days' => [0, 6], // Weekend only
        'repeat_type' => 'specific_days',
        'active' => true,
    ]);
    
    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Updated Title',
            'time' => '13:30',
            'active' => true,
        ]);
        
    $this->assertDatabaseHas('reminders', [
        'id' => $reminder->id,
        'title' => 'Updated Title',
        'type' => 'water',
    ]);
});

// Test destroy endpoint
test('user can delete a reminder', function () {
    $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);
    
    $response = $this->deleteJson("/api/v1/reminders/{$reminder->id}");
    
    $response->assertStatus(200);
    $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
});

// Test authorization
test('user cannot view reminders created by other users', function () {
    $otherUser = User::factory()->create();
    $reminder = Reminder::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->getJson("/api/v1/reminders/{$reminder->id}");
    
    $response->assertStatus(404);
});

test('user cannot update reminders created by other users', function () {
    $otherUser = User::factory()->create();
    $reminder = Reminder::factory()->create(['user_id' => $otherUser->id]);
    
    $response = $this->putJson("/api/v1/reminders/{$reminder->id}", [
        'title' => 'Updated Title',
    ]);
    
    $response->assertStatus(404);
});
