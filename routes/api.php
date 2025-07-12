<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

   // API routes with web middleware
Route::prefix('api')->group(function () {
    Route::apiResource('skills', SkillController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::patch('activities/{activity}/position', [ActivityController::class, 'updatePosition']);
});

// API routes moved to web.php to use session-based authentication 