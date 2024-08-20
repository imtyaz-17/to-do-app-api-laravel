<?php

use App\Http\Controllers\Api\Admin\AdminTaskController;
use App\Http\Controllers\Api\Admin\AdminTaskListController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// User Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/task-lists', [TaskListController::class, 'index'])->name('task-lists.index');
    Route::post('/task-lists', [TaskListController::class, 'store'])->name('task-lists.store');
    Route::get('/task-lists/{taskList}', [TaskListController::class, 'show'])->name('task-lists.show');
    Route::put('/task-lists/{taskList}', [TaskListController::class, 'update'])->name('task-lists.update');
    Route::delete('/task-lists/{taskList}', [TaskListController::class, 'destroy'])->name('task-lists.delete');

    Route::apiResource('tasks', TaskController::class);
    Route::get('/tasks/completed', [TaskController::class, 'completedTasks'])->name('tasks.completed');
    Route::get('/tasks/today', [TaskController::class, 'todayTasks'])->name('tasks.today');
    Route::get('/tasks/assigned', [TaskController::class, 'assignedToMeTasks'])->name('tasks.assigned');
});

// Admin Routes
Route::middleware(['auth:sanctum', 'auth.admin'])->prefix('admin')->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');

    // Task List Management
    Route::get('/task-lists', [AdminTaskListController::class, 'index'])->name('admin.task-lists.index');
    Route::post('/task-lists', [AdminTaskListController::class, 'store'])->name('admin.task-lists.store');
    Route::get('/task-lists/{taskList}', [AdminTaskListController::class, 'show'])->name('admin.task-lists.show');
    Route::put('/task-lists/{taskList}', [AdminTaskListController::class, 'update'])->name('admin.task-lists.update');
    Route::delete('/task-lists/{taskList}', [AdminTaskListController::class, 'destroy'])->name('admin.task-lists.delete');

    // Task Management
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('admin.tasks.store');
    Route::get('/tasks/{task}', [AdminTaskController::class, 'show'])->name('admin.tasks.show');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('admin.tasks.delete');

});
