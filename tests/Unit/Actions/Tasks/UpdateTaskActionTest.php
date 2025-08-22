<?php

namespace Tests\Unit\Actions\Tasks;

use App\Actions\Tasks\UpdateTaskAction;
use App\Models\Task;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateTaskActionTest extends TestCase
{
    public function test_execute(): void
    {
        $updateData = [
            'title' => 'Updated Title',
        ];

        $taskMock = $this->mock(Task::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
                ->once();
        });

        $action = new UpdateTaskAction;
        $action->execute($taskMock, $updateData);
    }
}
