<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\StoreTaskRequest;
use App\Http\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        return TaskResource::collection($request->user()->tasks()->paginate(10));
    }

    public function store(StoreTaskRequest $request, CreateTaskAction $createTaskAction)
    {
        $task = $createTaskAction->execute($request->validated(), $request->user());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task, UpdateTaskAction $updateTaskAction)
    {
        $this->authorize('update', $task);
        $updatedTask = $updateTaskAction->execute($task, $request->validated());

        return new TaskResource($updatedTask);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->noContent();
    }
}
