<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
Route::middleware(['auth:sanctum'])->group(function(){
Route::post('/logout','logout');
Route::get('/me','me');
Route::patch('/me/password','updatePassword')->middleware('throttle:5,1');;
});
Route::post('/login','login')->middleware('throttle:5,1');
});

Route::middleware(['auth:sanctum'])->prefix('projects')->controller(ProjectController::class)->group(function(){
Route::get('/','index');
Route::post('/','store');
Route::put('/{project}','update');
Route::patch('/{project}','update');
Route::get('/{project}','show');
Route::delete('/{project}','destroy');
Route::post('/{project}/members','addMember');
Route::delete('/{project}/members/{user}','removeMember');
});

Route::middleware(['auth:sanctum'])->controller(TaskController::class)->group(function(){
Route::prefix('tasks')->group(function(){
Route::get('/','index');
Route::post('/','store');
Route::get('/{task}','show');
Route::put('/{task}','update');
Route::patch('/{task}','update');
Route::patch('/{task}/status','updateStatus');
Route::delete('/{task}','destroy');
});
Route::get('/projects/{project}/tasks','projectTasks');
});

Route::middleware(['auth:sanctum'])->controller(TaskCommentController::class)->group(function(){
Route::prefix('tasks/{task}/comments')->group(function(){
Route::get('/','index');
Route::post('/','store');
});
Route::prefix('comments/{comment}')->group(function(){
Route::patch('/','update');
Route::delete('/','destroy');
});
});

Route::middleware(['auth:sanctum'])->group(function(){
Route::get('dashboard/statistics',[DashboardController::class,'statistics']);
});