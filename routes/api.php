<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
Route::middleware(['auth:sanctum'])->group(function(){
Route::post('/logout','logout');
Route::get('/me','me');
Route::patch('/me/password','updatePassword')->middleware('throttle:5,1');;
});
Route::post('/login','login')->middleware('throttle:5,1');
});
