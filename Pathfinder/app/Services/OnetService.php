<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OnetService
{
    protected ?string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.onet.key');
        $this->baseUrl = rtrim(config('services.onet.base_url', 'https://services.onetcenter.org/webservices/rest'), '/');
    }

    /**
     * Get occupation data by search title.
     * Searches for SOC code first, then fetches summary.
     */
    public function getOccupationData(string $title): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('O*NET API key is not configured.');
            return null;
        }

        $cacheKey = 'onet_occupation_' . md5($title);
        return Cache::remember($cacheKey, 86400 * 30, function () use ($title) {
            $socCode = $this->searchForSocCode($title);
            if (!$socCode) {
                return null;
            }

            return $this->getOccupationSummary($socCode);
        });
    }

    /**
     * Search for the best O*NET-SOC code for a given title.
     */
    protected function searchForSocCode(string $title): ?string
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/mnm/search", [
                'keyword' => $title,
                'end' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['occupation'][0]['code'] ?? null;
            }

            Log::error('O*NET Search failed: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('O*NET Search exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch the summary report for a specific SOC code.
     */
    protected function getOccupationSummary(string $socCode): ?array
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/mnm/occupations/{$socCode}/summary");

            if ($response->successful()) {
                $data = $response->json();
                return $this->mapOnetDataToPathfinder($data);
            }

            Log::error('O*NET Summary fetch failed: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('O*NET Summary exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Map O*NET JSON response to Pathfinder's internal career data structure.
     */
    protected function mapOnetDataToPathfinder(array $data): array
    {
        $tasks = [];
        if (isset($data['tasks']['task'])) {
            $tasks = array_slice(array_map(fn($t) => $t['name'], $data['tasks']['task']), 0, 5);
        }

        $skills = [];
        if (isset($data['skills']['skill'])) {
            $skills = array_slice(array_map(fn($s) => $s['name'], $data['skills']['skill']), 0, 5);
        }

        return [
            'onet_title' => $data['occupation']['title'] ?? '',
            'onet_description' => $data['occupation']['description'] ?? '',
            'onet_tasks' => $tasks,
            'onet_skills' => $skills,
            'onet_soc' => $data['occupation']['code'] ?? '',
            'education' => $data['summary']['education']['level_of_education'][0]['name'] ?? 'Degree in related field',
            // Salaries in O*NET are US-centric, we'll let Groq localize these in the next step
            'raw_onet_data' => $data 
        ];
    }
}
