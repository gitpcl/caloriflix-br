<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('authenticated user can access user endpoint', function () {
    $user = User::factory()->create();
    actingAsSanctum($user);

    $response = $this->getJson('/api/v1/user');
    
    $response->assertStatus(200)
        ->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

test('unauthenticated user cannot access protected endpoint', function () {
    $response = test()->getJson('/api/v1/user');
    
    $response->assertStatus(401);
});
