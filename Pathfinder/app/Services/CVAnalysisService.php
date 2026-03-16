<?php

namespace App\Services;

use App\Models\CVAnalysis;
use App\Models\JobProfile;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class CVAnalysisService
{
    private array $stopWords;

    // TF-IDF model data (loaded from JSON)
    private array $vocabulary;
    private array $idfValues;
    private array $categoryCentroids;
    private array $categoryRoles;
    private array $categoryTopKeywords;
    private array $termClusters;
    private array $categoryClusterProfiles;
    private array $skillFlags;

    // Transient state for current analysis
    private array $currentFullVector = [];
    private array $allTfidfScores = []; // All matched term scores (for full vector)

    public function __construct()
    {
        $this->loadTfidfModel();
        $this->initializeStopWords();

        // Configure PhpWord settings
        Settings::setOutputEscapingEnabled(true);
    }

    /**
     * Load pre-computed TF-IDF model from JSON artifact
     */
    private function loadTfidfModel(): void
    {
        // FORCE 'file' store specifically for this large object to avoid DB max_allowed_packet errors
        $model = Cache::store('file')->remember('tfidf_model_v3', 86400, function () {
            $modelPath = storage_path('app/data/tfidf_model.json');

            if (!file_exists($modelPath)) {
                // Try alternate path if first fails
                $modelPath = storage_path('app/tfidf_model.json');
                if (!file_exists($modelPath)) {
                    throw new \RuntimeException(
                        'TF-IDF model not found. Run: python Datasets/preprocess_resumes.py'
                    );
                }
            }

            \Log::info('Loading TF-IDF model from file...', ['path' => $modelPath]);
            $data = json_decode(file_get_contents($modelPath), true);
            
            if (!$data) {
                throw new \RuntimeException('Failed to decode TF-IDF model JSON.');
            }
            
            return $data;
        });

        $this->vocabulary = $model['vocabulary'] ?? [];
        $this->idfValues = $model['idf_values'] ?? [];
        $this->categoryCentroids = $model['category_centroids'] ?? [];
        $this->categoryRoles = $model['category_roles'] ?? [];
        $this->categoryTopKeywords = $model['category_top_keywords'] ?? [];
        $this->termClusters = $model['term_clusters'] ?? [];
        $this->categoryClusterProfiles = $model['category_cluster_profiles'] ?? [];
        $this->skillFlags = $model['skill_flags'] ?? array_fill(0, count($this->vocabulary), true);
    }

    /**
     * Analyze uploaded CV file (legacy database method)
     */
    public function analyzeCVFile(UploadedFile $file, ?int $userId = null, ?string $sessionId = null): CVAnalysis
    {
        $startTime = microtime(true);

        // Create initial analysis record
        $analysis = CVAnalysis::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'file_name' => $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => '',
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'extracted_text' => '',
            'skills_extracted' => [],
            'skill_vector' => [],
            'analysis_summary' => [],
            'status' => 'processing',
        ]);

        try {
            $filePath = $file->store('cv-uploads', 'local');
            $analysis->update(['file_path' => $filePath]);

            $extractedText = $this->extractTextFromFile($file);
            $analysis->update(['extracted_text' => $extractedText]);

            $processedText = $this->preprocessText($extractedText);
            $skillsExtracted = $this->extractSkillsWithTFIDF($processedText);
            $skillVector = $this->generateSkillVector($skillsExtracted);
            $jobMatches = $this->matchJobsFromBuiltInRoles($skillVector, $skillsExtracted);
            $analysisSummary = $this->createAnalysisSummaryFromBuiltIn($skillsExtracted, $jobMatches);

            $processingTime = microtime(true) - $startTime;

            $analysis->update([
                'skills_extracted' => $skillsExtracted,
                'skill_vector' => $skillVector,
                'job_matches' => $jobMatches,
                'analysis_summary' => $analysisSummary,
                'processing_time' => $processingTime,
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            $analysis->markAsFailed($e->getMessage());
            throw $e;
        }

        return $analysis;
    }

    /**
     * Analyze CV and return results as array (no database dependency)
     */
    public function analyzeCV(UploadedFile $file): array
    {
        $extractedText = $this->extractTextFromFile($file);
        $processedText = $this->preprocessText($extractedText);
        $skillsExtracted = $this->extractSkillsWithTFIDF($processedText);
        $skillVector = $this->generateSkillVector($skillsExtracted);
        $jobMatches = $this->matchJobsFromBuiltInRoles($skillVector, $skillsExtracted);
        $analysisSummary = $this->createAnalysisSummaryFromBuiltIn($skillsExtracted, $jobMatches);

        return [
            'extracted_skills' => $skillsExtracted,
            'skill_vector' => $skillVector,
            'job_matches' => $jobMatches,
            'analysis_summary' => $analysisSummary,
            'file_name' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Extract text from different file types
     */
    public function extractTextFromFile(UploadedFile $file): string
    {
        $mimeType = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        $tempPath = $file->store('temp_cv');
        $fullPath = Storage::path($tempPath);

        try {
            switch ($mimeType) {
                case 'application/pdf':
                    return $this->extractTextFromPDF($fullPath);
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return $this->extractTextFromDOCX($fullPath);
                case 'application/msword':
                    return $this->extractTextFromDOC($fullPath);
                case 'text/plain':
                    return $this->extractTextFromTXT($fullPath);
                case 'application/rtf':
                case 'text/rtf':
                    return $this->extractTextFromRTF($fullPath);
                case 'application/vnd.oasis.opendocument.text':
                    return $this->extractTextFromODT($fullPath);
                default:
                    return $this->extractTextByExtension($fullPath, $extension);
            }
        } catch (\Exception $e) {
            \Log::error('CV text extraction failed', [
                'file' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'error' => $e->getMessage()
            ]);
            return $this->extractTextFromTXT($fullPath);
        } finally {
            Storage::delete($tempPath);
        }
    }

    private function extractTextByExtension(string $filePath, string $extension): string
    {
        switch ($extension) {
            case 'pdf':
                return $this->extractTextFromPDF($filePath);
            case 'docx':
                return $this->extractTextFromDOCX($filePath);
            case 'doc':
                return $this->extractTextFromDOC($filePath);
            case 'txt':
                return $this->extractTextFromTXT($filePath);
            case 'rtf':
                return $this->extractTextFromRTF($filePath);
            case 'odt':
                return $this->extractTextFromODT($filePath);
            default:
                throw new \Exception("Unsupported file format: {$extension}");
        }
    }

    private function extractTextFromPDF(string $filePath): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    private function extractTextFromDOCX(string $filePath): string
    {
        $phpWord = IOFactory::load($filePath);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . ' ';
                } elseif (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $childElement) {
                        if (method_exists($childElement, 'getText')) {
                            $text .= $childElement->getText() . ' ';
                        }
                    }
                }
            }
        }

        return $text;
    }

    private function extractTextFromDOC(string $filePath): string
    {
        try {
            $phpWord = IOFactory::load($filePath);
            return $this->extractTextFromDOCX($filePath);
        } catch (\Exception $e) {
            throw new \Exception('DOC file format not fully supported. Please convert to DOCX or PDF.');
        }
    }

    private function extractTextFromTXT(string $filePath): string
    {
        try {
            return file_get_contents($filePath);
        } catch (\Exception $e) {
            \Log::error('TXT extraction failed: ' . $e->getMessage());
            return '';
        }
    }

    private function extractTextFromRTF(string $filePath): string
    {
        try {
            $content = file_get_contents($filePath);
            $text = preg_replace('/\{\\\\[^}]*\}/', '', $content);
            $text = preg_replace('/\\\\[a-z]+[0-9]*\s?/', '', $text);
            $text = preg_replace('/\{|\}/', '', $text);
            $text = str_replace(['\par', '\line'], "\n", $text);
            $text = html_entity_decode($text);
            return trim($text);
        } catch (\Exception $e) {
            \Log::error('RTF extraction failed: ' . $e->getMessage());
            return '';
        }
    }

    private function extractTextFromODT(string $filePath): string
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === TRUE) {
                $content = $zip->getFromName('content.xml');
                $zip->close();

                if ($content) {
                    $xml = simplexml_load_string($content);
                    if ($xml) {
                        $text = strip_tags($xml->asXML());
                        return trim($text);
                    }
                }
            }
            throw new \Exception('Could not extract ODT content');
        } catch (\Exception $e) {
            \Log::error('ODT extraction failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Enhanced text preprocessing for CV formats
     */
    public function preprocessText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\n+/', "\n", $text);
        $text = preg_replace('/[•·▪▫◦‣⁃]/', '', $text);
        $text = preg_replace('/_{3,}/', '', $text);
        $text = preg_replace('/-{3,}/', '', $text);
        $text = preg_replace('/={3,}/', '', $text);

        $sectionHeaders = [
            'WORK EXPERIENCE', 'PROFESSIONAL EXPERIENCE', 'EMPLOYMENT HISTORY',
            'EDUCATION', 'ACADEMIC BACKGROUND', 'QUALIFICATIONS',
            'SKILLS', 'TECHNICAL SKILLS', 'CORE COMPETENCIES',
            'PROJECTS', 'KEY PROJECTS', 'NOTABLE PROJECTS',
            'CERTIFICATIONS', 'CERTIFICATES', 'PROFESSIONAL CERTIFICATIONS',
            'LANGUAGES', 'LANGUAGE SKILLS',
            'REFERENCES', 'PROFESSIONAL REFERENCES',
            'SUMMARY', 'PROFESSIONAL SUMMARY', 'CAREER SUMMARY',
            'OBJECTIVE', 'CAREER OBJECTIVE',
            'ACHIEVEMENTS', 'KEY ACHIEVEMENTS', 'ACCOMPLISHMENTS',
            'EXPERIENCIA LABORAL', 'EXPERIENCIA PROFESIONAL',
            'EXPÉRIENCE PROFESSIONNELLE', 'EXPÉRIENCE',
            'BERUFSERFAHRUNG', 'ARBEITSERFAHRUNG',
            'ESPERIENZA LAVORATIVA', 'ESPERIENZA PROFESSIONALE',
            'WERKERVARING', 'PROFESSIONELE ERVARING',
            'EXPERIÊNCIA PROFISSIONAL', 'EXPERIÊNCIA DE TRABALHO',
        ];

        foreach ($sectionHeaders as $header) {
            $text = preg_replace('/\b' . preg_quote($header, '/') . '\b/i', "\n" . $header . "\n", $text);
        }

        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s\+\#\.]/', ' ', $text);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        return $text;
    }

    /**
     * Extract skills using real TF-IDF with pre-computed IDF values from corpus
     */
    public function extractSkillsWithTFIDF(string $text): array
    {
        $words = explode(' ', $text);
        $totalWords = count($words);

        if ($totalWords === 0) {
            return [];
        }

        // Build vocabulary lookup
        $vocabFlipped = array_flip($this->vocabulary);

        // Count term frequencies for vocabulary terms (unigrams)
        $termCounts = [];
        for ($i = 0, $len = count($words); $i < $len; $i++) {
            $word = $words[$i];
            if (isset($vocabFlipped[$word])) {
                $termCounts[$word] = ($termCounts[$word] ?? 0) + 1;
            }
            // Check bigrams
            if ($i < $len - 1) {
                $bigram = $word . ' ' . $words[$i + 1];
                if (isset($vocabFlipped[$bigram])) {
                    $termCounts[$bigram] = ($termCounts[$bigram] ?? 0) + 1;
                }
            }
        }

        if (empty($termCounts)) {
            return [];
        }

        // Compute TF-IDF using real IDF values
        $tfidfScores = [];
        foreach ($termCounts as $term => $count) {
            $termIndex = $vocabFlipped[$term];
            $tf = 1 + log($count); // sublinear TF, matching sklearn
            $idf = $this->idfValues[$termIndex];
            $tfidfScores[$term] = $tf * $idf;
        }

        // Sort descending by score
        arsort($tfidfScores);

        // Store ALL matched term scores for full vector construction
        $this->allTfidfScores = $tfidfScores;

        // Build top 20 skills with category assignment (filtered by skill_flags)
        $skillScores = [];
        $count = 0;
        foreach ($tfidfScores as $term => $score) {
            if ($count >= 20) break;
            $termIndex = $vocabFlipped[$term];

            // Only display terms tagged as real skills by O*NET whitelist
            if (!($this->skillFlags[$termIndex] ?? true)) {
                continue;
            }

            // Find which category this term is most distinctive for
            $bestCategory = '';
            $bestCategoryScore = 0;
            foreach ($this->categoryCentroids as $cat => $centroid) {
                if ($centroid[$termIndex] > $bestCategoryScore) {
                    $bestCategoryScore = $centroid[$termIndex];
                    $bestCategory = $cat;
                }
            }

            $skillScores[$term] = [
                'category' => $this->formatCategoryName($bestCategory),
                'score' => $score,
                'frequency' => $termCounts[$term],
            ];
            $count++;
        }

        return $skillScores;
    }

    /**
     * Generate skill vector: full 500-dim for matching + 8-dim cluster profile for display
     */
    public function generateSkillVector(array $extractedSkills): array
    {
        // Build full 500-dimensional TF-IDF vector using ALL matched terms
        $fullVector = array_fill(0, count($this->vocabulary), 0.0);
        $vocabFlipped = array_flip($this->vocabulary);

        // Use all matched terms (not just top 20 displayed skills) for accurate matching
        foreach ($this->allTfidfScores as $term => $score) {
            if (isset($vocabFlipped[$term])) {
                $fullVector[$vocabFlipped[$term]] = $score;
            }
        }

        // Store for use by matchJobsFromBuiltInRoles()
        $this->currentFullVector = $fullVector;

        // Compute 8-dimensional cluster profile for display and storage
        $clusterProfile = [];
        foreach ($this->termClusters as $clusterName => $indices) {
            $sum = 0;
            foreach ($indices as $idx) {
                $sum += $fullVector[$idx];
            }
            $clusterProfile[$clusterName] = count($indices) > 0
                ? round($sum / count($indices), 4)
                : 0;
        }

        return $clusterProfile;
    }

    /**
     * Match CV against category centroids using cosine similarity
     */
    private function matchJobsFromBuiltInRoles(array $skillVector, array $skillsExtracted): array
    {
        $matches = [];

        // Prepare weights: technical skills count 3x more than generic words
        $weights = [];
        foreach ($this->skillFlags as $isSkill) {
            $weights[] = $isSkill ? 10.0 : 1.0;
        }

        // Compare full vector against each category centroid using weighted similarity
        $categoryScores = [];
        foreach ($this->categoryCentroids as $category => $centroid) {
            $similarity = $this->calculateCosineSimilarityArrays(
                $this->currentFullVector,
                $centroid,
                $weights
            );

            // Keyword Tie-breaker: Check if top keywords for this category are present in the CV
            $keywordHitCount = 0;
            $topKeywords = $this->categoryTopKeywords[$category] ?? [];
            foreach ($topKeywords as $keyword) {
                if (isset($skillsExtracted[$keyword])) {
                    $keywordHitCount++;
                }
            }
            
            // Add a small boost for direct keyword hits (0.5% per hit)
            $similarity += ($keywordHitCount * 0.005);

            // Surgical Filter: High-precision terms that lock/exclude roles for 80% accuracy target.
            $surgicalFilters = [
                'INFORMATION-TECHNOLOGY' => ['javascript', 'php', 'python', 'java', 'sql', 'coding', 'developer', 'linux', 'mysql', 'css', 'html', 'react', 'laravel', 'c++', 'c#', 'cloud', 'aws', 'docker', 'typescript'],
                'ADVOCATE' => ['paralegal', 'litigation', 'legal', 'affidavit', 'jurisdiction', 'courtroom', 'testimony', 'lawyer', 'attorney', 'notary', 'mediation'],
                'ACCOUNTANT' => ['cpa', 'accounting', 'auditing', 'gaap', 'ledger', 'payroll', 'taxation', 'bookkeeping', 'audit', 'tax', 'accounting'],
                'HEALTHCARE' => ['clinical', 'patient', 'medical', 'diagnosis', 'nurse', 'physician', 'surgical', 'pharmacology', 'hospital', 'nursing', 'therapy', 'dental'],
                'CHEF' => ['culinary', 'kitchen', 'bakery', 'pastry', 'sous chef', 'restaurant', 'cooking', 'chef', 'catering', 'food safety', 'menu'],
                'AVIATION' => ['pilot', 'flight', 'aircraft', 'airline', 'cockpit', 'avionics', 'aviation', 'navigation', 'aerospace'],
                'AGRICULTURE' => ['farming', 'crop', 'livestock', 'irrigation', 'agronomy', 'horticulture', 'agriculture', 'forestry', 'harvesting', 'agri'],
                'SALES' => ['prospecting', 'lead generation', 'salesforce', 'crm', 'cold calling', 'account manager', 'sales', 'retail', 'selling', 'merchandising'],
                'CONSTRUCTION' => ['structural', 'contractor', 'blueprints', 'excavation', 'carpentry', 'construction', 'building', 'plumbing', 'welding'],
                'ENGINEERING' => ['mechanical', 'electrical', 'civil', 'structural', 'solidworks', 'cad', 'engineering', 'robotics', 'automation', 'blueprints'],
                'APPAREL' => ['fashion', 'textile', 'clothing', 'apparel', 'garment', 'merchandising', 'retail', 'tailoring', 'couture'],
                'FITNESS' => ['trainer', 'gym', 'wellness', 'nutrition', 'athlete', 'coaching', 'aerobics', 'kinesiology', 'personal trainer'],
                'DIGITAL-MEDIA' => ['social media', 'content', 'seo', 'sem', 'digital marketing', 'advertising', 'copywriting', 'engagement', 'analytics', 'content writer'],
                'HR' => ['recruiting', 'hiring', 'compensation', 'benefits', 'payroll', 'employee relations', 'onboarding', 'hris', 'recruitment'],
                'BPO' => ['business process', 'outsourcing', 'call center', 'customer support', 'inbound', 'outbound', 'service level', 'sla', 'zendesk', 'bpo'],
                'FINANCE' => ['investment', 'securities', 'banking', 'equity', 'capital', 'wealth', 'portfolio', 'trading', 'finance', 'underwriting'],
                'CONSULTANT' => ['strategy', 'optimization', 'business analyst', 'roadmap', 'transformation', 'management consulting', 'stakeholder'],
                'PUBLIC-RELATIONS' => ['media relations', 'press release', 'publicity', 'branding', 'crisis management', 'press kit', 'spokesperson'],
                'ARTS' => ['fine arts', 'gallery', 'curator', 'visual arts', 'sculpture', 'painting', 'exhibition', 'arts'],
            ];

            foreach ($surgicalFilters as $filterCat => $filterWords) {
                $hits = 0;
                foreach ($filterWords as $word) {
                    if (isset($skillsExtracted[$word])) { $hits++; }
                }

                if ($hits > 0) {
                    if ($category === $filterCat) {
                        $similarity += ($hits * 0.15); // Massive boost (15% per hit) for matched domain
                    } else {
                        // Penalty: If you have skills from Domain A, you are likely NOT in Domain B
                        $isTechOverlap = in_array($category, ['INFORMATION-TECHNOLOGY', 'ENGINEERING']) && in_array($filterCat, ['INFORMATION-TECHNOLOGY', 'ENGINEERING']);
                        if (!$isTechOverlap) {
                            $similarity -= ($hits * 0.08); // 8% penalty per mismatch hit
                        }
                    }
                }
            }

            if ($similarity > 0.05) {
                $categoryScores[$category] = $similarity;
            }
        }

        // Sort by similarity, take top 5
        arsort($categoryScores);
        $topCategories = array_slice($categoryScores, 0, 5, true);

        // Expand each category into a specific job role
        foreach ($topCategories as $category => $similarity) {
            $roles = $this->categoryRoles[$category] ?? [];
            $role = $roles[0] ?? [
                'title' => $this->formatCategoryName($category),
                'description' => 'Career in ' . $this->formatCategoryName($category),
            ];

            // Compute matching dimensions from cluster profiles
            $userClusterProfile = $skillVector; // already 8-dimensional
            $categoryClusterProfile = $this->categoryClusterProfiles[$category] ?? [];
            $matchingDimensions = $this->getMatchingDimensions($userClusterProfile, $categoryClusterProfile);

            // Score Scaling: Apply a "Human-Friendly" boost to low raw similarities.
            // A raw cosine similarity of 0.1 is actually quite strong for sparse data.
            // Formula: Log-odds or power scaling to stretch the range.
            $scaledSimilarity = 1 - pow(1 - $similarity, 6);
            
            $matches[] = [
                'job_title' => $role['title'],
                'category' => $this->formatCategoryName($category),
                'description' => $role['description'],
                'similarity_score' => round($scaledSimilarity * 100, 1),
                'matching_dimensions' => $matchingDimensions,
                'raw_score' => round($similarity, 4) // Keep for debugging
            ];
        }

        return $matches;
    }

    /**
     * Identify which cluster dimensions contributed most to a match
     */
    private function getMatchingDimensions(array $userProfile, array $categoryProfile): array
    {
        $dimensions = [];

        foreach ($userProfile as $cluster => $userScore) {
            $catScore = $categoryProfile[$cluster] ?? 0;
            if ($userScore > 0.001 && $catScore > 0.01) {
                $dimensions[] = [
                    'dimension' => $cluster,
                    'user_score' => round($userScore, 2),
                    'job_score' => round($catScore, 2),
                    'contribution' => round($userScore * $catScore, 3),
                ];
            }
        }

        usort($dimensions, fn($a, $b) => $b['contribution'] <=> $a['contribution']);

        return array_slice($dimensions, 0, 4);
    }

    /**
     * Create analysis summary
     */
    private function createAnalysisSummaryFromBuiltIn(array $skillsExtracted, array $jobMatches): array
    {
        $topSkills = array_slice(array_keys($skillsExtracted), 0, 10);
        $bestMatch = !empty($jobMatches) ? $jobMatches[0] : null;

        return [
            'total_skills_found' => count($skillsExtracted),
            'total_job_matches' => count($jobMatches),
            'top_skills' => $topSkills,
            'best_match' => $bestMatch ? [
                'job_title' => $bestMatch['job_title'],
                'similarity' => $bestMatch['similarity_score'],
            ] : null,
            'skill_categories' => $this->categorizeSkills($skillsExtracted),
        ];
    }

    /**
     * Categorize skills using cluster assignments from the TF-IDF model
     */
    public function categorizeSkills(array $skills): array
    {
        $categories = [
            'technical' => [],
            'soft' => [],
            'tools' => [],
            'languages' => [],
            'other' => [],
        ];

        $vocabFlipped = array_flip($this->vocabulary);
        $technicalClusters = ['Technical Skills', 'Healthcare & Sciences', 'Trades & Applied'];
        $softClusters = ['Communication & Interpersonal', 'Education & Training'];
        $businessClusters = ['Business & Management', 'Legal & Compliance'];
        $creativeClusters = ['Creative & Design'];

        foreach ($skills as $skillName => $skillData) {
            $termIndex = $vocabFlipped[strtolower($skillName)] ?? null;
            if ($termIndex === null) {
                $categories['other'][] = $skillName;
                continue;
            }

            $assignedCluster = null;
            foreach ($this->termClusters as $cluster => $indices) {
                if (in_array($termIndex, $indices)) {
                    $assignedCluster = $cluster;
                    break;
                }
            }

            if (in_array($assignedCluster, $technicalClusters)) {
                $categories['technical'][] = $skillName;
            } elseif (in_array($assignedCluster, $softClusters)) {
                $categories['soft'][] = $skillName;
            } elseif (in_array($assignedCluster, $businessClusters)) {
                $categories['tools'][] = $skillName;
            } elseif (in_array($assignedCluster, $creativeClusters)) {
                $categories['languages'][] = $skillName; // reuse slot for creative
            } else {
                $categories['other'][] = $skillName;
            }
        }

        return $categories;
    }

    /**
     * Cosine similarity for two numeric arrays of the same length
     */
    private function calculateCosineSimilarityArrays(array $a, array $b, array $weights = []): float
    {
        $dot = 0;
        $magA = 0;
        $magB = 0;
        $len = min(count($a), count($b));
        $hasWeights = !empty($weights);

        for ($i = 0; $i < $len; $i++) {
            $w = $hasWeights ? ($weights[$i] ?? 1.0) : 1.0;
            
            $valA = $a[$i] * $w;
            $valB = $b[$i] * $w;

            $dot += $valA * $valB;
            $magA += $valA * $valA;
            $magB += $valB * $valB;
        }

        $magA = sqrt($magA);
        $magB = sqrt($magB);

        return ($magA > 0 && $magB > 0) ? $dot / ($magA * $magB) : 0;
    }

    /**
     * Cosine similarity for two associative arrays (kept for legacy compatibility)
     */
    private function calculateCosineSimilarity(array $vectorA, array $vectorB): float
    {
        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($vectorA as $key => $valueA) {
            $valueB = $vectorB[$key] ?? 0;
            $dotProduct += $valueA * $valueB;
            $magnitudeA += $valueA * $valueA;
            $magnitudeB += $valueB * $valueB;
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    /**
     * Format category name for display (e.g., HEALTHCARE -> Healthcare)
     */
    private function formatCategoryName(string $category): string
    {
        return ucwords(strtolower(str_replace('-', ' ', $category)));
    }

    /**
     * Initialize stop words for text processing
     */
    private function initializeStopWords(): void
    {
        $this->stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of',
            'with', 'by', 'from', 'up', 'about', 'into', 'through', 'during', 'before',
            'after', 'above', 'below', 'between', 'among', 'is', 'are', 'was', 'were',
            'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will',
            'would', 'could', 'should', 'may', 'might', 'must', 'can', 'this', 'that',
            'these', 'those', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him',
            'her', 'us', 'them', 'my', 'your', 'his', 'its', 'our', 'their'
        ];
    }
}
