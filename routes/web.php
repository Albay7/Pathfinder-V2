<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PathfinderController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\MbtiController;
use App\Http\Controllers\CVAnalysisController;
use Illuminate\Support\Facades\Route;

// Health check endpoint for Railway
Route::get('/health', function () {
    try {
        // Simple health check without database dependency
        return response()->json([
            'status' => 'ok',
            'timestamp' => date('c'), // Use PHP date instead of Laravel's now()
            'service' => 'pathfinder-app',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => date('c')
        ], 500);
    }
});

// Redirect root to Pathfinder
Route::get('/', [PathfinderController::class, 'index'])->name('pathfinder.index');

// Dashboard route (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Pathfinder routes (accessible to all)
Route::prefix('pathfinder')->name('pathfinder.')->group(function () {
    Route::get('/', [PathfinderController::class, 'index'])->name('home');
    Route::get('/career-guidance', [PathfinderController::class, 'careerGuidance'])->name('career-guidance');
    Route::get('/questionnaire/{type}', [PathfinderController::class, 'questionnaire'])->name('questionnaire');
    Route::get('/questionnaire-simple', function() {
        return view('pathfinder.questionnaire-simple');
    })->name('questionnaire-simple');
    Route::post('/questionnaire/process', [PathfinderController::class, 'processQuestionnaire'])->name('questionnaire.process');
    Route::post('/questionnaire/submit', [PathfinderController::class, 'submitQuestionnaire'])->name('questionnaire.submit');
    Route::get('/career-path', [PathfinderController::class, 'careerPath'])->name('career-path');
    Route::match(['get', 'post'], '/career-path/show', [PathfinderController::class, 'showCareerPath'])->name('career-path.show');
    Route::get('/skill-gap', [PathfinderController::class, 'skillGap'])->name('skill-gap');
    Route::post('/skill-gap/analyze', [PathfinderController::class, 'analyzeSkillGap'])->name('skill-gap.analyze');

    // MBTI Personality Assessment routes
    Route::get('/mbti-questionnaire', [MbtiController::class, 'showQuestionnaire'])->name('mbti-questionnaire');
    Route::post('/mbti-questionnaire/process', [MbtiController::class, 'processQuestionnaire'])->name('mbti-questionnaire.process');
    Route::get('/mbti-results', [MbtiController::class, 'showResults'])->name('mbti.results');

    Route::get('/career/details/{career}', [PathfinderController::class, 'careerDetails'])->name('career.details');
    Route::get('/career', [PathfinderController::class, 'careerGuidance'])->name('career');
    Route::get('/courses', [PathfinderController::class, 'courses'])->name('courses');
    Route::get('/external-resources', [PathfinderController::class, 'externalResources'])->name('external-resources');

    // CV Analysis routes
    Route::get('/cv-upload', [CVAnalysisController::class, 'showUploadForm'])->name('cv-upload');
    Route::get('/cv-analysis/history', [CVAnalysisController::class, 'showAnalysisHistory'])->name('cv-analysis.history');
    Route::get('/cv-analysis/{id}', [CVAnalysisController::class, 'showAnalysisDetails'])->name('cv-analysis.details');
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
