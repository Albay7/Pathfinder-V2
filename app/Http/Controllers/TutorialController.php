<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorial;
use App\Models\UserTutorialProgress;

class TutorialController extends Controller
{
    /**
     * Display user's tutorials dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's tutorial progress
        $inProgress = $user->tutorialProgress()->inProgress()->with('tutorial')->get();
        $completed = $user->tutorialProgress()->completed()->with('tutorial')->get();
        $bookmarked = $user->tutorialProgress()->bookmarked()->with('tutorial')->get();
        
        return view('tutorials.index', compact('inProgress', 'completed', 'bookmarked'));
    }
    
    /**
     * Bookmark a tutorial.
     */
    public function bookmark(Request $request)
    {
        $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id'
        ]);
        
        $user = Auth::user();
        $tutorialId = $request->tutorial_id;
        
        // Check if progress already exists
        $progress = UserTutorialProgress::where('user_id', $user->id)
            ->where('tutorial_id', $tutorialId)
            ->first();
            
        if ($progress) {
            // Update existing progress to bookmarked
            $progress->update(['status' => 'bookmarked']);
        } else {
            // Create new bookmarked progress
            UserTutorialProgress::create([
                'user_id' => $user->id,
                'tutorial_id' => $tutorialId,
                'status' => 'bookmarked'
            ]);
        }
        
        return back()->with('success', 'Tutorial bookmarked successfully!');
    }
    
    /**
     * Start a tutorial.
     */
    public function start(Request $request)
    {
        $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id'
        ]);
        
        $user = Auth::user();
        $tutorialId = $request->tutorial_id;
        
        // Check if progress already exists
        $progress = UserTutorialProgress::where('user_id', $user->id)
            ->where('tutorial_id', $tutorialId)
            ->first();
            
        if ($progress) {
            $progress->markAsStarted();
        } else {
            $progress = UserTutorialProgress::create([
                'user_id' => $user->id,
                'tutorial_id' => $tutorialId,
                'status' => 'in_progress',
                'started_at' => now()
            ]);
        }
        
        $tutorial = Tutorial::find($tutorialId);
        
        return redirect($tutorial->url);
    }
    
    /**
     * Update tutorial progress.
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'time_spent' => 'nullable|integer|min:0'
        ]);
        
        $user = Auth::user();
        $progress = UserTutorialProgress::where('user_id', $user->id)
            ->where('tutorial_id', $request->tutorial_id)
            ->firstOrFail();
            
        $progress->updateProgress($request->progress_percentage);
        
        if ($request->time_spent) {
            $progress->addTimeSpent($request->time_spent);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully',
            'progress' => $progress->progress_percentage,
            'status' => $progress->status
        ]);
    }
    
    /**
     * Mark tutorial as completed.
     */
    public function complete(Request $request)
    {
        $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id',
            'user_rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        $user = Auth::user();
        $progress = UserTutorialProgress::where('user_id', $user->id)
            ->where('tutorial_id', $request->tutorial_id)
            ->firstOrFail();
            
        $progress->markAsCompleted();
        
        if ($request->user_rating) {
            $progress->update(['user_rating' => $request->user_rating]);
        }
        
        if ($request->notes) {
            $progress->update(['notes' => $request->notes]);
        }
        
        return back()->with('success', 'Tutorial marked as completed!');
    }
    
    /**
     * Remove tutorial from user's list.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'tutorial_id' => 'required|exists:tutorials,id'
        ]);
        
        $user = Auth::user();
        UserTutorialProgress::where('user_id', $user->id)
            ->where('tutorial_id', $request->tutorial_id)
            ->delete();
            
        return back()->with('success', 'Tutorial removed from your list!');
    }
    
    /**
     * Get tutorial recommendations for a specific skill.
     */
    public function getRecommendations(Request $request)
    {
        $request->validate([
            'skill' => 'required|string',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'limit' => 'nullable|integer|min:1|max:10'
        ]);
        
        $skill = $request->skill;
        $level = $request->level;
        $limit = $request->limit ?? 5;
        
        $query = Tutorial::bySkill($skill)->active();
        
        if ($level) {
            $query->byLevel($level);
        }
        
        $tutorials = $query->orderBy('rating', 'desc')
            ->orderBy('difficulty', 'asc')
            ->limit($limit)
            ->get();
            
        return response()->json($tutorials);
    }
}
