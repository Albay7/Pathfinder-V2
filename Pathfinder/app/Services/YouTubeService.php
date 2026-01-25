<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    /**
     * Search YouTube playlists for given skills/keywords.
     * API-first with caching to avoid quota overuse; returns grouped by skill label.
     *
     * @param array<int,string> $skills
     * @param int $perSkill
     * @param int $maxSkills
     * @param string|null $roleContext Optional role to add context to searches
     * @param string|null $categoryContext Optional category to add context to searches
     * @return array<int,array{skill:string,items:array<int,array{label:string,url:string,description:string,channel?:string,type:string,videoCount?:int,updatedAt?:string}>}>
     */
    public function searchPlaylistsForSkills(array $skills, int $perSkill = 2, int $maxSkills = 6, ?string $roleContext = null, ?string $categoryContext = null): array
    {
        $apiKey = config('services.youtube.api_key');
        if (empty($apiKey)) {
            Log::warning('YouTube API key not configured in services.youtube.api_key');
            return [];
        }

        $skills = array_values(array_unique(array_filter(array_map('trim', $skills))));
        if (empty($skills)) {
            return [];
        }

        $skills = array_slice($skills, 0, $maxSkills);
        $groups = [];

        foreach ($skills as $skill) {
            // Build context-aware search query
            $queryParts = [$skill];
            if (!empty($roleContext)) {
                $queryParts[] = $roleContext;
            }
            $queryParts[] = 'learning';

            $query = trim(implode(' ', $queryParts));

            // Include role/category in cache key for unique results per context
            $cacheKeyParts = [strtolower($query)];
            if (!empty($categoryContext)) {
                $cacheKeyParts[] = strtolower($categoryContext);
            }
            $cacheKey = 'yt:skill:' . md5(implode('|', $cacheKeyParts));

            $items = Cache::remember($cacheKey, now()->addHours(6), function () use ($apiKey, $query, $perSkill) {
                $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                    'part' => 'snippet',
                    'q' => $query,
                    'type' => 'playlist',
                    'maxResults' => $perSkill,
                    'key' => $apiKey,
                    'safeSearch' => 'moderate',
                    'relevanceLanguage' => 'en',
                ]);

                if (!$response->successful()) {
                    Log::warning('YouTube API search failed', ['query' => $query, 'status' => $response->status()]);
                    return [];
                }

                $playlistIds = collect($response->json('items', []))
                    ->pluck('id.playlistId')
                    ->filter()
                    ->values()
                    ->all();

                if (empty($playlistIds)) {
                    return [];
                }

                // Fetch detailed playlist info (video count, updated date)
                $detailsResponse = Http::get('https://www.googleapis.com/youtube/v3/playlists', [
                    'part' => 'snippet,contentDetails',
                    'id' => implode(',', $playlistIds),
                    'key' => $apiKey,
                ]);

                if (!$detailsResponse->successful()) {
                    Log::warning('YouTube API playlist details failed', ['ids' => $playlistIds, 'status' => $detailsResponse->status()]);
                    // Fall back to basic info
                    return collect($response->json('items', []))
                        ->map(function ($item) {
                            $playlistId = $item['id']['playlistId'] ?? null;
                            if (!$playlistId) {
                                return null;
                            }

                            return [
                                'label' => $item['snippet']['title'] ?? 'YouTube Playlist',
                                'url' => 'https://www.youtube.com/playlist?list=' . $playlistId,
                                'description' => $this->cleanDescription($item['snippet']['description'] ?? ''),
                                'channel' => $item['snippet']['channelTitle'] ?? '',
                                'type' => 'Playlist',
                            ];
                        })
                        ->filter()
                        ->take($perSkill)
                        ->values()
                        ->all();
                }

                return collect($detailsResponse->json('items', []))
                    ->map(function ($item) {
                        $playlistId = $item['id'] ?? null;
                        if (!$playlistId) {
                            return null;
                        }

                        return [
                            'label' => $item['snippet']['title'] ?? 'YouTube Playlist',
                            'url' => 'https://www.youtube.com/playlist?list=' . $playlistId,
                            'description' => $this->cleanDescription($item['snippet']['description'] ?? ''),
                            'channel' => $item['snippet']['channelTitle'] ?? '',
                            'type' => 'Playlist',
                            'videoCount' => $item['contentDetails']['itemCount'] ?? null,
                            'updatedAt' => $item['snippet']['publishedAt'] ?? null,
                        ];
                    })
                    ->filter()
                    ->take($perSkill)
                    ->values()
                    ->all();
            });

            if (!empty($items)) {
                $groups[] = [
                    'skill' => $skill,
                    'items' => $items,
                ];
            }
        }

        return $groups;
    }

    /**
     * Clean and truncate playlist descriptions for display.
     *
     * @param string $description
     * @return string
     */
    private function cleanDescription(string $description): string
    {
        // Remove excessive whitespace and newlines
        $cleaned = preg_replace('/\s+/', ' ', trim($description));

        // Truncate to reasonable length (300 chars) with ellipsis
        if (strlen($cleaned) > 300) {
            $cleaned = substr($cleaned, 0, 297) . '...';
        }

        return $cleaned;
    }
}
