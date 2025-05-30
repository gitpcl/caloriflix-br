<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

// Test login endpoint
test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'name',
            ],
        ]);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $response = $this->postJson('/api/v1/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Unauthorized.',
        ]);
});

// Test register endpoint
test('user can register with valid data', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'name',
            ],
        ]);
        
    $this->assertDatabaseHas('users', [
        'email' => 'newuser@example.com',
        'name' => 'Test User',
    ]);
});

test('user cannot register with existing email', function () {
    User::factory()->create(['email' => 'existing@example.com']);
    
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

// Test logout endpoint
test('user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/v1/logout');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
});

test('unauthenticated user cannot access protected endpoints', function () {
    $response = $this->getJson('/api/v1/user');

    $response->assertStatus(401);
});

test('authenticated user can access user profile', function () {
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
