<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('a user can get a list of their own tasks', function () {
    Task::factory()->count(3)->create(['user_id' => $this->user->id]);
    Task::factory()->count(2)->create();

    getJson('/api/v1/tasks')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('a user can create a task', function () {
    $taskData = [
        'title' => 'New Awesome Task',
        'description' => 'Details about the task.',
    ];

    postJson('/api/v1/tasks', $taskData)
        ->assertStatus(201)
        ->assertJsonFragment(['title' => 'New Awesome Task']);

    assertDatabaseHas('tasks', [
        'title' => 'New Awesome Task',
        'user_id' => $this->user->id,
    ]);
});

test('a user can get a single task they own', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    getJson('/api/v1/tasks/'.$task->id)
        ->assertStatus(200)
        ->assertJsonFragment(['id' => $task->id]);
});

test('a user cannot get a task they do not own', function () {
    $task = Task::factory()->create();

    getJson('/api/v1/tasks/'.$task->id)
        ->assertStatus(403);
});

test('a user can update a task they own', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
        'status' => TaskStatus::Pending->value,
    ]);

    $updateData = [
        'title' => 'Updated Title',
        'status' => TaskStatus::Completed->value,
    ];

    putJson('/api/v1/tasks/'.$task->id, $updateData)
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Title', 'status' => 'completed']);
});

test('a user can delete a task they own', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    deleteJson('/api/v1/tasks/'.$task->id)
        ->assertStatus(204);
});
