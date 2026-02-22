<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RssFeedService
{
    /**
     * Fetch grouped RSS feeds and return parsed items per feed.
     *
     * @param array<string, array<string, array<int, array{title:string,url:string}>>> $feedGroups
     * @param int $itemsPerFeed
     * @return array<string, array<string, array<int, array{title:string,url:string,publishedAt?:string,source?:string}>>>
     */
    public function fetchFeedGroups(array $feedGroups, int $itemsPerFeed = 3): array
    {
        $result = [];

        foreach ($feedGroups as $groupKey => $feeds) {
            $groupItems = [];

            foreach ($feeds as $feedName => $feedList) {
                $items = [];

                foreach ($feedList as $feed) {
                    $feedUrl = $feed['url'] ?? '';
                    if ($feedUrl === '') {
                        continue;
                    }

                    $parsed = $this->fetchFeedItems($feedUrl, $itemsPerFeed, $feedName);
                    if (!empty($parsed)) {
                        $items = array_merge($items, $parsed);
                    }
                }

                if (!empty($items)) {
                    $groupItems[$feedName] = array_slice($items, 0, $itemsPerFeed);
                }
            }

            if (!empty($groupItems)) {
                $result[$groupKey] = $groupItems;
            }
        }

        return $result;
    }

    /**
     * Fetch and parse a single RSS/Atom feed.
     *
     * @param string $url
     * @param int $limit
     * @param string $sourceName
     * @return array<int, array{title:string,url:string,publishedAt?:string,source?:string}>
     */
    private function fetchFeedItems(string $url, int $limit, string $sourceName): array
    {
        $cacheKey = 'rss:' . md5($url . '|' . $limit);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($url, $limit, $sourceName) {
            try {
                $response = Http::get($url);
                if (!$response->successful()) {
                    Log::warning('RSS fetch failed', ['url' => $url, 'status' => $response->status()]);
                    return [];
                }

                $xml = @simplexml_load_string($response->body());
                if ($xml === false) {
                    Log::warning('RSS parse failed', ['url' => $url]);
                    return [];
                }

                $items = [];

                if (isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        $items[] = [
                            'title' => (string) ($item->title ?? 'Untitled'),
                            'url' => (string) ($item->link ?? $url),
                            'publishedAt' => (string) ($item->pubDate ?? ''),
                            'source' => $sourceName,
                        ];
                        if (count($items) >= $limit) {
                            break;
                        }
                    }

                    return $items;
                }

                if (isset($xml->entry)) {
                    foreach ($xml->entry as $entry) {
                        $link = '';
                        if (isset($entry->link)) {
                            $link = (string) ($entry->link['href'] ?? $entry->link ?? '');
                        }

                        $items[] = [
                            'title' => (string) ($entry->title ?? 'Untitled'),
                            'url' => $link !== '' ? $link : $url,
                            'publishedAt' => (string) ($entry->updated ?? ''),
                            'source' => $sourceName,
                        ];
                        if (count($items) >= $limit) {
                            break;
                        }
                    }

                    return $items;
                }
            } catch (\Throwable $e) {
                Log::warning('RSS fetch exception', ['url' => $url, 'message' => $e->getMessage()]);
            }

            return [];
        });
    }
}
