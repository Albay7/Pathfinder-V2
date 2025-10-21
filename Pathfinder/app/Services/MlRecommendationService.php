<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class MlRecommendationService
{
    public function recommendForUser(int $userId, array $skills = []): array
    {
        // Placeholder: integrate with Azure ML endpoint once deployed.
        // For now, produce a deterministic mock response.
        $cacheKey = 'ml_reco_v1_' . $userId;
        return Cache::remember($cacheKey, 300, function () use ($userId, $skills) {
            return [
                'user_id' => $userId,
                'generated_at' => now()->toIso8601String(),
                'skills_considered' => $skills,
                'suggested_paths' => [
                    [ 'path' => 'data-analytics', 'score' => 0.82 ],
                    [ 'path' => 'software-engineering', 'score' => 0.78 ],
                ],
            ];
        });
    }
}
