<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorial;
use App\Models\UserTutorialProgress;
use App\Models\UserResourceTracking;
use App\Models\UserProgress;

class TutorialController extends Controller
{
    /**
     * Display user's learning journey dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Tutorial progress
        $inProgress = $user->tutorialProgress()->inProgress()->with('tutorial')->get();
        $completed = $user->tutorialProgress()->completed()->with('tutorial')->get();
        $bookmarked = $user->tutorialProgress()->bookmarked()->with('tutorial')->get();

        // Resource tracking
        $savedResources = $user->resourceTracking()->saved()->orderBy('saved_at', 'desc')->get();
        $inProgressResources = $user->resourceTracking()->inProgress()->orderBy('started_at', 'desc')->get();
        $completedResources = $user->resourceTracking()->completed()->orderBy('completed_at', 'desc')->get();
        $recentActivity = $user->resourceTracking()->recent(10)->get();

        // Skill gap data (session + DB fallback, same as PathfinderController)
        $missingSkills = session('skill_gap_missing_skills', []);
        $targetRole = session('skill_gap_role');

        if (empty($missingSkills)) {
            $latestSkillGap = UserProgress::where('user_id', $user->id)
                ->where('feature_type', 'skill_gap')
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestSkillGap) {
                $targetRole = $latestSkillGap->target_role;
                $analysisResult = $latestSkillGap->analysis_result;
                $missingSkills = $analysisResult['missing_skill_names']
                    ?? array_column($analysisResult['missing_skills'] ?? [], 'name')
                    ?? [];
            }
        }

        // Per-skill resource counts
        $skillProgress = [];
        if (!empty($missingSkills)) {
            foreach ($missingSkills as $skill) {
                $total = $user->resourceTracking()->bySkill($skill)->count();
                $done = $user->resourceTracking()->bySkill($skill)->completed()->count();
                $skillProgress[$skill] = [
                    'total' => $total,
                    'completed' => $done,
                ];
            }
        }

        // Stats
        $stats = [
            'in_progress' => $inProgress->count() + $inProgressResources->count(),
            'completed' => $completed->count() + $completedResources->count(),
            'bookmarked' => $bookmarked->count(),
            'saved_resources' => $savedResources->count(),
            'skills_tracked' => $user->resourceTracking()->whereNotNull('skill')->distinct('skill')->count('skill'),
            'total_time' => $user->getTotalTutorialTimeSpent(),
        ];

        return view('tutorials.index', compact(
            'inProgress', 'completed', 'bookmarked',
            'savedResources', 'inProgressResources', 'completedResources',
            'recentActivity', 'missingSkills', 'targetRole',
            'skillProgress', 'stats'
        ));
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

    /**
     * Save an external resource to the user's learning journey.
     */
    public function saveResource(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'url' => 'required|url|max:2000',
            'resource_type' => 'required|in:youtube_playlist,article,job_platform',
            'skill' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'thumbnail_url' => 'nullable|string|max:2000',
            'metadata' => 'nullable|array',
        ]);

        $user = Auth::user();

        $resource = UserResourceTracking::updateOrCreate(
            ['user_id' => $user->id, 'url' => $request->url],
            [
                'title' => $request->title,
                'resource_type' => $request->resource_type,
                'skill' => $request->skill,
                'source' => $request->source,
                'description' => $request->description,
                'thumbnail_url' => $request->thumbnail_url,
                'metadata' => $request->metadata,
                'status' => 'saved',
                'saved_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Resource saved to your journey!', 'resource' => $resource]);
    }

    /**
     * Remove a saved resource from user's learning journey.
     */
    public function unsaveResource(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        $user = Auth::user();
        UserResourceTracking::where('user_id', $user->id)->where('url', $request->url)->delete();

        return response()->json(['success' => true, 'message' => 'Resource removed from your journey.']);
    }

    /**
     * Mark a saved resource as in-progress.
     */
    public function startResource(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        $user = Auth::user();
        $resource = UserResourceTracking::where('user_id', $user->id)->where('url', $request->url)->firstOrFail();
        $resource->markAsStarted();

        return response()->json(['success' => true, 'message' => 'Resource marked as in progress.', 'resource' => $resource]);
    }

    /**
     * Mark a saved resource as completed.
     */
    public function completeResource(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        $user = Auth::user();
        $resource = UserResourceTracking::where('user_id', $user->id)->where('url', $request->url)->firstOrFail();
        $resource->markAsCompleted();

        return response()->json(['success' => true, 'message' => 'Resource marked as completed!', 'resource' => $resource]);
    }

    /**
     * Check which resource URLs are already saved (for External Resources page).
     */
    public function checkSavedResources(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'urls.*' => 'string',
        ]);

        $user = Auth::user();
        $saved = UserResourceTracking::where('user_id', $user->id)
            ->whereIn('url', $request->urls)
            ->pluck('status', 'url');

        return response()->json(['saved' => $saved]);
    }
}
