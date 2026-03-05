<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiService
{
    /**
     * Fetch articles relevant to the target role/job from NewsAPI.
     *
     * @param string|null $targetRole The user's target job title for query context
     * @param int $limit Maximum articles to return
     * @return array<int, array{title:string, link:string, description:string, source:string, date:string, imageUrl:string|null}>
     */
    public function fetchArticlesForRole(?string $targetRole = null, int $limit = 9): array
    {
        $apiKey = config('services.newsapi.api_key');
        if (empty($apiKey)) {
            Log::warning('NewsAPI key not configured in services.newsapi.api_key');
            return [];
        }

        // Build a search query from the target role, with fallback
        $query = !empty($targetRole)
            ? trim($targetRole) . ' career Philippines'
            : 'technology career skills Philippines';

        $cacheKey = 'newsapi:' . md5(strtolower($query) . '|' . $limit);

        return Cache::remember($cacheKey, now()->addHours(3), function () use ($apiKey, $query, $limit) {
            try {
                $response = Http::get(config('services.newsapi.base_url') . '/everything', [
                    'q' => $query,
                    'language' => 'en',
                    'sortBy' => 'relevancy',
                    'pageSize' => $limit,
                    'apiKey' => $apiKey,
                ]);

                if (!$response->successful()) {
                    Log::warning('NewsAPI fetch failed', [
                        'query' => $query,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return [];
                }

                $articles = $response->json('articles', []);

                return collect($articles)
                    ->map(function ($article) {
                        return [
                            'title' => $article['title'] ?? 'Untitled',
                            'link' => $article['url'] ?? '#',
                            'description' => $article['description'] ?? '',
                            'source' => $article['source']['name'] ?? 'News',
                            'date' => $article['publishedAt'] ?? '',
                            'imageUrl' => $article['urlToImage'] ?? null,
                        ];
                    })
                    ->filter(fn($a) => $a['title'] !== '[Removed]')
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('NewsAPI exception', ['message' => $e->getMessage()]);
                return [];
            }
        });
    }
}
