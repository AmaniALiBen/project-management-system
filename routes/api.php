<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware(['auth:sanctum'])->group(function () {
    // auth
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
        Route::patch('/me/password', 'updatePassword')->middleware('throttle:5,1');
    });

    // projects
    Route::apiResource('projects', ProjectController::class);

    Route::prefix('projects')->group(function () {

        Route::controller(ProjectController::class)->group(function () {
            Route::post('/{project}/members', 'addMember');
            Route::delete('/{project}/members/{user}', 'removeMember');

        });

        Route::get('/{project}/tasks', [TaskController::class,'projectTasks']);

    });

    // tasks

    Route::apiResource('tasks', TaskController::class);

    Route::prefix('tasks')->controller(TaskController::class)->group(function () {
        Route::patch('/{task}/status', 'updateStatus');
    });

    // task comments
    Route::controller(TaskCommentController::class)->group(function () {
        Route::prefix('tasks/{task}/comments')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
        });
        Route::prefix('comments/{comment}')->group(function () {
            Route::patch('/', 'update');
            Route::delete('/', 'destroy');
        });
    });

    // statistics
    Route::prefix('dashboard')->group(function () {
        Route::get('/statistics', [DashboardController::class, 'statistics']);
    });
});
