<?php

namespace Tests\Unit\Actions\Tasks;

use App\Actions\Tasks\CreateTaskAction;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateTaskActionTest extends TestCase
{
    public function test_execute(): void
    {
        $taskData = ['title' => 'New Task from test'];

        $relationshipMock = $this->mock(HasMany::class);
        $relationshipMock->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn(new Task($taskData));

        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($relationshipMock) {
            $mock->shouldReceive('tasks')->once()->andReturn($relationshipMock);
        });

        $action = new CreateTaskAction;
        $result = $action->execute($taskData, $userMock);

        $this->assertInstanceOf(Task::class, $result);
    }
}
