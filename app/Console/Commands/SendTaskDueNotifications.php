<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueSoonNotification;
use Illuminate\Console\Command;

class SendTaskDueNotifications extends Command
{
    protected $signature = 'tasks:send-due-notifications';

    protected $description = 'Send notifications for tasks that are due tomorrow.';

    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $tasks = Task::whereDate('due_date', $tomorrow)->get();

        foreach ($tasks as $task) {
            /** @var User $user */
            $user = $task->user;
            $user->notify(new TaskDueSoonNotification($task));
        }

        $this->info('Sent '.count($tasks).' due date notifications.');
    }
}
