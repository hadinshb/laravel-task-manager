<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\TaskResource; 
use App\Models\Task;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {        
        $task->update($request->validated());
        
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        
        return response()->noContent();
    }
}