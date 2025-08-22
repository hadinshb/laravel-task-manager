<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('a user can register and receive a token', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    postJson('/api/v1/register', $userData)
        ->assertStatus(200)
        ->assertJsonStructure(['access_token', 'token_type']);

    assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('a user can not register with low security password', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'pass',
        'password_confirmation' => 'pass',
    ];

    postJson('/api/v1/register', $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);

    assertDatabaseMissing('users', ['email' => 'test@example.com']);

});

test('a user can login and receive a token', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    postJson('api/v1/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertStatus(200)
        ->assertJsonStructure(['access_token', 'token_type']);
});

test('login fails with invalid credentials', function () {
    $user = User::factory()->create();

    postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertStatus(401);
});
