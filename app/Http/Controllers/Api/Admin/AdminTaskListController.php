<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\Request;

class AdminTaskListController extends Controller
{
    public function index()
    {
        $taskLists = TaskList::latest('id')->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Task lists retrieved successfully.',
            'taskLists' => $taskLists
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $taskList = TaskList::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task list created successfully.',
            'taskList' => $taskList
        ], 201);
    }
    public function show(TaskList $taskList)
    {
        return response()->json([
            'success' => true,
            'message' => 'Task list retrieved successfully.',
            'tskLists' => $taskList
        ]);
    }
    public function update(Request $request, TaskList $taskList)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
        ]);

        $taskList->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task list updated successfully.',
            'data' => $taskList
        ]);
    }

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task list deleted successfully.'
        ]);
    }
}
