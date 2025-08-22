<?php

namespace Tests\Unit\Policies;

use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use PHPUnit\Framework\TestCase;

class TaskPolicyTest extends TestCase
{
    public function test_view_with_own_user(): void
    {
        $user = new User;
        $user->id = 1;

        $task = new Task(['user_id' => $user->id]);
        $policy = new TaskPolicy;

        $this->assertTrue($policy->view($user, $task));
    }

    public function test_view_with_other_user(): void
    {
        $user = new User;
        $user->id = 1;

        $otherUserTask = new Task(['user_id' => 2]);
        $policy = new TaskPolicy;

        $this->assertFalse($policy->view($user, $otherUserTask));
    }

    public function test_delete_with_own_user(): void
    {
        $user = new User;
        $user->id = 1;

        $task = new Task(['user_id' => $user->id]);
        $policy = new TaskPolicy;

        $this->assertTrue($policy->delete($user, $task));
    }

    public function test_delete_with_other_user(): void
    {
        $user = new User;
        $user->id = 1;

        $otherUserTask = new Task(['user_id' => 2]);
        $policy = new TaskPolicy;

        $this->assertFalse($policy->delete($user, $otherUserTask));
    }

    public function test_update_with_own_user(): void
    {
        $user = new User;
        $user->id = 1;

        $task = new Task(['user_id' => $user->id]);
        $policy = new TaskPolicy;

        $this->assertTrue($policy->update($user, $task));
    }

    public function test_update_with_other_user(): void
    {
        $user = new User;
        $user->id = 1;

        $otherUserTask = new Task(['user_id' => 2]);
        $policy = new TaskPolicy;

        $this->assertFalse($policy->update($user, $otherUserTask));
    }
}
