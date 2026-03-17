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
            1. 'description': A high-impact professional overview of EXACTLY 5 sentences. No more, no less. Each sentence must be substantial and professional.
            2. 'short_description': A very concise 2-sentence summary for a quick result page.
            3. 'responsibilities': An array of 5 specific, action-oriented key responsibilities.
            4. 'salary_range': A realistic annual salary range in Philippine Peso localized for the Philippines (e.g., '₱400,000 - ₱700,000 Per year').
            5. 'education': The typical minimum degree or education required.
            6. 'certificates': An array of 3 professional certifications or licenses relevant to this role.
            7. 'recommended_degree': A specific academic degree relevant to this role (e.g., 'Bachelor of Science in Computer Science').
            
            CRITICAL REQUIREMENT: The 'description' MUST be EXACTLY 5 sentences long. Avoid very short sentences; they should be high-impact.";

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
                    'short_description' => $content['short_description'] ?? "A dynamic role offering significant opportunities for professional growth and impact.",
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

    /**
     * Enrich O*NET factual data into a professional career profile.
     *
     * @param string $careerTitle
     * @param array $onetData
     * @return array|null
     */
    public function enrichOnetData(string $careerTitle, array $onetData): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API key is not configured for enrichment.');
            return null;
        }

        try {
            $tasks = implode(', ', $onetData['onet_tasks'] ?? []);
            $skills = implode(', ', $onetData['onet_skills'] ?? []);
            
            $prompt = "I have professional data for the role '{$careerTitle}' from O*NET.
            O*NET Description: {$onetData['onet_description']}
            Key Tasks: {$tasks}
            Key Skills: {$skills}
            
            Based on these facts, provide a professional career profile.
            Return the response in JSON format (not markdown) with exactly these keys:
            1. 'description': A high-impact professional overview of EXACTLY 5 sentences. No more, no less. Each sentence must be substantial and professional.
            2. 'short_description': A very concise 2-sentence summary for a quick result page.
            3. 'responsibilities': An array of 5 specific, action-oriented key responsibilities.
            4. 'salary_range': A realistic annual salary range in Philippine Peso localized for the Philippines (e.g., '₱400,000 - ₱700,000 Per year').
            5. 'education': The typical minimum degree or education required.
            6. 'certificates': An array of 3 professional certifications or licenses relevant to this role.
            7. 'recommended_degree': A specific academic degree relevant to this role.
            
            CRITICAL REQUIREMENT: The 'description' MUST be EXACTLY 5 sentences long. Avoid very short sentences; they should be high-impact.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.5, // Lower temperature for more factual consistency
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
                    'description' => $content['description'] ?? $onetData['onet_description'],
                    'short_description' => $content['short_description'] ?? (substr($onetData['onet_description'], 0, 150) . '...'),
                    'responsibilities' => $content['responsibilities'] ?? $onetData['onet_tasks'],
                    'salary_range' => $content['salary_range'] ?? 'Competitive',
                    'education_requirements' => $content['education'] ?? $onetData['education'],
                    'certificates' => $content['certificates'] ?? [],
                    'skills_required' => array_unique(array_merge($onetData['onet_skills'], ['Problem Solving', 'Communication'])),
                    'job_outlook' => 'Growth',
                    'related_careers' => ['Specialist', 'Consultant', 'Manager'],
                    'recommended_degree' => $content['recommended_degree'] ?? 'Bachelor\'s Degree in relevant field',
                    'recommended_courses' => []
                ];
            }

            Log::error('Groq Enrichment failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Error enriching O*NET data with Groq: ' . $e->getMessage());
            return null;
        }
    }
}
