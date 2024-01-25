<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return new TaskCollection(Task::orderBy('created_at','desc')->paginate(10));
    }

    public function show(Request $request, Task $task)
    {
        return new TaskResource($task);
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }

    public function setAsCompleted(Request $request, Task $task)
    {
        $task->update([
            'is_completed' => Task::STATUS_IS_COMPLETED
        ]);

        return response()->json(['message' => 'Task with id '. $task->id .' is completed']);
    }

    public function setAsOpen(Request $request, Task $task)
    {
        $task->update([
            'is_completed' => Task::STATUS_IS_OPEN
        ]);

        return response()->json(['message' => 'Task with id '. $task->id .' is open']);
    }

    public function setAsDeleted(Request $request, Task $task)
    {
        $task->update([
            'is_deleted' => Task::STATUS_IS_DELETED
        ]);

        return response()->json(['message' => 'Task with id '. $task->id .' is deleted']);
    }

    public function setAsNotDeleted(Request $request, Task $task)
    {
        $task->update([
            'is_deleted' => Task::STATUS_IS_NOT_DELETED
        ]);

        return response()->json(['message' => 'Task with id '. $task->id .' is not deleted']);
    }
}
