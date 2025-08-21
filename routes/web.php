<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PathfinderController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

// Redirect root to Pathfinder
Route::get('/', [PathfinderController::class, 'index'])->name('pathfinder.index');

// Dashboard route (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Pathfinder routes (accessible to all)
Route::prefix('pathfinder')->name('pathfinder.')->group(function () {
    Route::get('/', [PathfinderController::class, 'index'])->name('index');
    Route::get('/career-guidance', [PathfinderController::class, 'careerGuidance'])->name('career-guidance');
    Route::get('/questionnaire/{type}', [PathfinderController::class, 'questionnaire'])->name('questionnaire');
    Route::post('/questionnaire/process', [PathfinderController::class, 'processQuestionnaire'])->name('questionnaire.process');
    Route::get('/career-path', [PathfinderController::class, 'careerPath'])->name('career-path');
    Route::post('/career-path/show', [PathfinderController::class, 'showCareerPath'])->name('career-path.show');
    Route::get('/skill-gap', [PathfinderController::class, 'skillGap'])->name('skill-gap');
    Route::post('/skill-gap/analyze', [PathfinderController::class, 'analyzeSkillGap'])->name('skill-gap.analyze');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Tutorial routes
    Route::prefix('tutorials')->name('tutorials.')->group(function () {
        Route::get('/', [TutorialController::class, 'index'])->name('index');
        Route::post('/bookmark', [TutorialController::class, 'bookmark'])->name('bookmark');
        Route::post('/start', [TutorialController::class, 'start'])->name('start');
        Route::post('/update-progress', [TutorialController::class, 'updateProgress'])->name('update-progress');
        Route::post('/complete', [TutorialController::class, 'complete'])->name('complete');
        Route::delete('/remove', [TutorialController::class, 'remove'])->name('remove');
        Route::get('/recommendations', [TutorialController::class, 'getRecommendations'])->name('recommendations');
    });
});

require __DIR__.'/auth.php';
