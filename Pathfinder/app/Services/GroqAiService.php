<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAiService
{
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    /**
     * Generate dynamic career data including description and responsibilities.
     *
     * @param string $careerTitle
     * @return array|null
     */
    public function generateCareerData(string $careerTitle): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API key is not configured.');
            return null;
        }

        try {
            $prompt = "Provide a professional career profile for the role: '{$careerTitle}'. 
            Return the response in JSON format (not markdown) with exactly these keys:
            1. 'description': A single, concise paragraph of exactly 5 sentences explaining the role, its impact, and core focus.
            2. 'responsibilities': An array of 5 specific, action-oriented key responsibilities.
            3. 'salary_range': A realistic annual salary range (e.g., '$60,000 - $90,000').
            4. 'education': The typical minimum degree or education required.
            5. 'certificates': An array of 3 professional certifications or licenses relevant to this role.
            6. 'recommended_degree': A specific academic degree relevant to this role (e.g., 'Bachelor of Science in Computer Science').
            
            Ensure the tone is professional and the description is exactly 5 sentences long.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $contentJson = $data['choices'][0]['message']['content'] ?? null;
                
                if (!$contentJson) {
                    return null;
                }

                $content = json_decode($contentJson, true);

                return [
                    'title' => $careerTitle,
                    'tagline' => 'Excel as a ' . $careerTitle . ' in today\'s dynamic market.',
                    'description' => $content['description'] ?? "As a {$careerTitle}, you will drive innovation and solve complex challenges in your field.",
                    'responsibilities' => $content['responsibilities'] ?? [],
                    'salary_range' => $content['salary_range'] ?? 'Competitive',
                    'education_requirements' => $content['education'] ?? 'Degree in related field',
                    'certificates' => $content['certificates'] ?? [],
                    'skills_required' => ['Analytical Thinking', 'Problem Solving', 'Communication', 'Adaptability'],
                    'job_outlook' => 'Growth',
                    'related_careers' => ['Specialist', 'Consultant', 'Manager'],
                    'recommended_degree' => $content['recommended_degree'] ?? 'Bachelor\'s Degree in relevant field',
                    'recommended_courses' => [] // Deprecated in favor of degree focus
                ];
            }

            Log::error('Groq API request failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Error generating career data with Groq: ' . $e->getMessage());
            return null;
        }
    }
}
