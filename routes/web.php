<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
