<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\SkillController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    Route::get('skill-graph', function () {
        return Inertia::render('skill-graph');
    })->name('skill-graph');
    
    Route::get('skills', function () {
        return Inertia::render('skills/index');
    })->name('skills.index');
    
    Route::get('activities', function () {
        return Inertia::render('activities/index');
    })->name('activities.index');
    
});

Route::prefix('api')->group(function () {
    Route::apiResource('skills', SkillController::class);
    Route::patch('skills/{skill}/position', [SkillController::class, 'updatePosition']);
    Route::apiResource('activities', ActivityController::class);
    Route::patch('activities/{activity}/position', [ActivityController::class, 'updatePosition']);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
