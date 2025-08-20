<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\TaskResource; 
use App\Models\Task;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::paginate(10));
    }

    public function store(StoreTaskRequest $request, CreateTaskAction $createTaskAction)
    {
        $task = $createTaskAction->execute($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task, UpdateTaskAction $updateTaskAction)
    {      
        $updatedTask = $updateTaskAction->execute($task, $request->validated());  
        
        return new TaskResource($updatedTask);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        
        return response()->noContent();
    }
}