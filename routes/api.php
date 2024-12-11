<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ExerciseController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Exercises
    Route::get('/exercises', [ExerciseController::class, 'index']);
    Route::get('/exercises/{id}', [ExerciseController::class, 'show']);
    Route::get('/exercises/categories', [ExerciseController::class, 'categories']);
    
    // Workouts
    Route::apiResource('workouts', WorkoutController::class);
    
    // Schedules
    Route::apiResource('schedules', ScheduleController::class);
    Route::patch('/schedules/{id}/complete', [ScheduleController::class, 'markAsComplete']);
    
    // User Profile
    Route::get('/profile', [UserController::class, 'show']);
    Route::put('/profile', [UserController::class, 'update']);
});