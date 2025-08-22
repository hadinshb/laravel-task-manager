<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\TaskDueSoonNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);

test('it queues notifications for tasks due tomorrow', function () {
    Notification::fake();

    $user = User::factory()->create();

    $taskDueTomorrow = $user->tasks()->create([
        'title' => 'Task due tomorrow',
        'due_date' => now()->addDay(),
    ]);

    $taskDueInTwoDays = $user->tasks()->create([
        'title' => 'Task due in two days',
        'due_date' => now()->addDays(2),
    ]);

    artisan('tasks:send-due-notifications');

    Notification::assertSentTo(
        $user,
        TaskDueSoonNotification::class,
        function ($notification) use ($taskDueTomorrow) {
            return $notification->task->id === $taskDueTomorrow->id;
        }
    );

    Notification::assertNotSentTo(
        $user,
        TaskDueSoonNotification::class,
        function ($notification) use ($taskDueInTwoDays) {
            return $notification->task->id === $taskDueInTwoDays->id;
        }
    );
});
