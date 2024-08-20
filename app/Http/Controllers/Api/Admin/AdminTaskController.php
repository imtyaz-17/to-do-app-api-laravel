<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest('id')->paginate(10);
        return response()->json([
            'success' => true,
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
            'due_date' => 'nullable|date',
            'task_list_id' => 'nullable|exists:task_lists,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Create the task
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'completed' => $validated['completed'] ?? false,
            'due_date' => $validated['due_date'] ?? null,
            'user_id' => auth()->id() ?? $request->user_id,
            'task_list_id' => $validated['task_list_id'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
            'due_date' => 'nullable|date',
            'task_list_id' => 'nullable|exists:task_lists,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? $task->description,
            'completed' => $validated['completed'] ?? $task->completed,
            'due_date' => $validated['due_date'] ?? $task->due_date,
            'user_id' => auth()->id() ?? $task->user_id,
            'task_list_id' => $validated['task_list_id'] ?? $task->task_list_id,
            'assigned_to' => $validated['assigned_to'] ?? $task->assigned_to,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $task,
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
