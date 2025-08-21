<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get progress statistics
        $assessmentCount = $user->getProgressCount('career_guidance');
        $careerPathCount = $user->getProgressCount('career_path');
        $skillAnalysisCount = $user->getProgressCount('skill_gap');
        $progressScore = $user->getProgressScore();
        
        // Get tutorial statistics
        $completedTutorials = $user->getCompletedTutorialsCount();
        $inProgressTutorials = $user->getInProgressTutorialsCount();
        $totalTutorialTime = $user->getTotalTutorialTimeSpent();
        
        // Get recent progress
        $recentProgress = $user->recentProgress(5);
        
        return view('dashboard', compact(
            'assessmentCount',
            'careerPathCount', 
            'skillAnalysisCount',
            'progressScore',
            'completedTutorials',
            'inProgressTutorials',
            'totalTutorialTime',
            'recentProgress'
        ));
    }
}
