<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index(Request $request)
    {
        $taskLists = TaskList::where('user_id', auth()->user()->id)->with('tasks')->get();
        return response()->json([
            'success' => true,
            'message' => 'Task lists retrieved successfully.',
            'taskLists' => $taskLists
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        // Check for existing TaskList with the same title for the user
        $existingTaskList = TaskList::where('title', $request->title)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($existingTaskList) {
            return response()->json([
                'success' => false,
                'message' => 'A task list with this title already exists for the user.',
            ], 422);
        }

        $taskList = auth()->user()->taskLists()->create([
            'title' => $request->title,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Task list created successfully.',
            'taskList' => $taskList
        ], 201);
    }

    public function show(TaskList $taskList)
    {
        if (auth()->id() !== $taskList->user_id) {
            return response()->json([
                'message' => 'You can not access this task list',
            ], 403);
        }
        $taskList->load('tasks');
        return response()->json([
            'success' => true,
            'message' => 'Task list retrieved successfully.',
            'tskLists' => $taskList
        ]);
    }

    public function update(Request $request, TaskList $taskList)
    {
        if (auth()->id() !== $taskList->user_id) {
            return response()->json([
                'message' => 'You can not access this task list',
            ], 403);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
        ]);
        // Check if the title is being updated and if it's unique
        if (isset($validated['title']) && $validated['title'] !== $taskList->title) {
            $existingTaskList = TaskList::where('title', $validated['title'])
                ->where('user_id', auth()->user()->id)
                ->first();

            if ($existingTaskList) {
                return response()->json([
                    'success' => false,
                    'message' => 'A task list with this title already exists for the user.',
                ], 422);
            }
        }

        $taskList->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Task list updated successfully.',
            'taskList' => $taskList
        ]);
    }

    public function destroy(TaskList $taskList)
    {
        if (auth()->id() !== $taskList->user_id) {
            return response()->json([
                'message' => 'You can not access this task list',
            ], 403);
        }
        $taskList->delete();
        return response()->noContent();
    }
}
