<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        // gemini-1.5-flash is extremely fast and capable for this use case
        $this->model = 'gemini-1.5-flash'; 
    }

    /**
     * Generate dynamic career roadmap tailored to the Philippine market using Gemini.
     *
     * @param string $currentRole
     * @param string $targetRole
     * @return array|null
     */
    public function generateCareerRoadmap(string $currentRole, string $targetRole): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Gemini API key is not configured.');
            return null;
        }

        try {
            $prompt = "Create a realistic, step-by-step career transition roadmap from '$currentRole' to '$targetRole'.
            Important Context: The user is in the Philippines. Tailor all advice, certifications, and salaries to the Philippine job market.

            Return the response strictly as a JSON array of objects (no markdown blocks or other text), representing 4 to 6 sequential career steps.
            Each object must contain the following keys exactly:
            - 'level': A title for this milestone step (e.g., 'Entry-Level', 'Junior-Level', 'Mid-Level Transition', 'Senior-Level').
            - 'duration': The estimated time spent in this stage (e.g., '0 - 1.5 Years', '2 - 4 Years').
            - 'salary_range': Realistic estimated monthly salary range in PHP for the Philippines market (e.g., '₱20,000 - ₱30,000/month').
            - 'description': A concise 2-3 sentence overview of the focus and goals during this stage. Include any required Philippine-specific certifications or training (e.g., TESDA, PRC, PhilNITS) if applicable.
            - 'responsibilities': An array of EXACTLY 4 to 5 bullet points describing the key daily tasks, skills to learn, or duties at this stage.";

            // Gemini API Endpoint configuration
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $contentJson = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if (!$contentJson) {
                    Log::error('Gemini API returned an unexpected structure', [
                        'status' => $response->status(),
                        'data' => $data
                    ]);
                    return null;
                }

                $careerLadder = json_decode($contentJson, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Failed to decode JSON from Gemini API response', [
                        'error' => json_last_error_msg(),
                        'raw_content' => $contentJson
                    ]);
                    return null;
                }

                if (is_array($careerLadder) && count($careerLadder) >= 1) {
                    return $careerLadder;
                }
            }

            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url_sanitized' => "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key=HIDDEN"
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error generating career roadmap with Gemini: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return null;
        }
    }
}
