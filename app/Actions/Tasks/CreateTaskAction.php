<?php

namespace App\Actions\Tasks;

use App\Models\Task;
use App\Models\User;

class CreateTaskAction
{
    public function execute(array $data, User $user): Task
    {
        /** @var Task $task */
        $task = $user->tasks()->create($data);

        return $task;
    }
}
