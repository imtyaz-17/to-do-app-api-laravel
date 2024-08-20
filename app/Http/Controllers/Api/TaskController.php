<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return response()->json([
            'success' => true,
            'tasks' => $tasks,
        ]);
    }

    public function completedTasks()
    {
        $completedTasks = auth()->user()->tasks()->where('completed', true)->get();
        return response()->json($completedTasks);
    }
    public function todayTasks()
    {
        $today = Carbon::today()->toDateString();
        $todayTasks = auth()->user()->tasks()->whereDate('due_date', $today)->get();
        return response()->json($todayTasks);
    }
    public function assignedToMeTasks()
    {
        $userId = auth()->id();
        $assignedTasks = Task::where('assigned_to', $userId)->get();
        return response()->json($assignedTasks);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'task_list_id' => 'nullable|exists:task_lists,id',
        ]);

        $task = auth()->user()->tasks()->create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'task_list_id' => 'nullable|exists:task_lists,id',
        ]);

        $task->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }



    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Forbidden');
        }
    }
}
