<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    // Skills API
    Route::apiResource('skills', SkillController::class);
    
    // Activities API
    Route::apiResource('activities', ActivityController::class);
    Route::patch('activities/{activity}/position', [ActivityController::class, 'updatePosition']);
}); 