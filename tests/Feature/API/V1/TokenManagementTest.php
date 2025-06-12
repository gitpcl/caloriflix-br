<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('user can list their active tokens', function () {
    $user = User::factory()->create();
    $token1 = $user->createToken('token1');
    $token2 = $user->createToken('token2');
    
    Sanctum::actingAs($user, ['*']);
    
    $response = $this->getJson('/api/v1/tokens');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'abilities',
                    'last_used_at',
                    'expires_at',
                    'created_at'
                ]
            ]
        ]);
});

test('user can create a new token', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    
    $response = $this->postJson('/api/v1/tokens', [
        'name' => 'Test Token',
        'abilities' => ['read', 'write'],
        'expires_in_days' => 30
    ]);
    
    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'expires_at',
                'abilities'
            ]
        ]);
});

test('user can revoke a specific token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');
    
    Sanctum::actingAs($user, ['*']);
    
    $response = $this->deleteJson("/api/v1/tokens/{$token->accessToken->id}");
    
    $response->assertStatus(204);
    
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $token->accessToken->id
    ]);
});

test('user can revoke all other tokens', function () {
    $user = User::factory()->create();
    $token1 = $user->createToken('token1');
    $token2 = $user->createToken('token2');
    $currentToken = $user->createToken('current');
    
    Sanctum::actingAs($user, ['*'], 'current');
    
    $response = $this->deleteJson('/api/v1/tokens/revoke-all');
    
    $response->assertStatus(200);
    
    // Current token should still exist
    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'current'
    ]);
    
    // Other tokens should be deleted
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $token1->accessToken->id
    ]);
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $token2->accessToken->id
    ]);
});

test('user can refresh their current token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token');
    
    Sanctum::actingAs($user, ['*'], 'test-token');
    
    $response = $this->postJson('/api/v1/tokens/refresh');
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'expires_at',
                'abilities'
            ]
        ]);
    
    // Original token should be deleted
    $this->assertDatabaseMissing('personal_access_tokens', [
        'id' => $token->accessToken->id
    ]);
});