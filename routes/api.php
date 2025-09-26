<?php

use App\Http\Controllers\CVAnalysisController;
use App\Http\Controllers\QuestionnaireController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CV Analysis API Routes
Route::prefix('cv-analysis')->name('api.cv-analysis.')->group(function () {
    // Upload and analyze CV
    Route::post('/upload', [CVAnalysisController::class, 'uploadAndAnalyze'])->name('upload');
    
    // Get analysis history for authenticated users
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/history', [CVAnalysisController::class, 'getAnalysisHistory'])->name('history');
        Route::get('/analysis/{id}', [CVAnalysisController::class, 'getAnalysisDetails'])->name('details');
        Route::get('/recommendations/{analysisId}', [CVAnalysisController::class, 'getJobRecommendations'])->name('recommendations');
        Route::post('/compare/{analysisId}/{jobId}', [CVAnalysisController::class, 'compareWithJob'])->name('compare');
    });
    
    // Public endpoints (no authentication required)
    Route::get('/jobs/search', [CVAnalysisController::class, 'searchJobs'])->name('jobs.search');
    Route::get('/skills/popular', [CVAnalysisController::class, 'getPopularSkills'])->name('skills.popular');
});

// Questionnaire API Routes
Route::prefix('questionnaires')->name('api.questionnaires.')->group(function () {
    // Public endpoints (no authentication required)
    Route::get('/', [QuestionnaireController::class, 'index'])->name('index');
    Route::get('/{id}', [QuestionnaireController::class, 'show'])->name('show');
    Route::post('/submit', [QuestionnaireController::class, 'submitResponse'])->name('submit');
    Route::get('/response/{responseId}', [QuestionnaireController::class, 'getResponseDetails'])->name('response.details');
    
    // Authenticated endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user/history', [QuestionnaireController::class, 'getUserHistory'])->name('user.history');
    });
});