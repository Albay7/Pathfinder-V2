<?php

namespace App\Services;

use App\Models\CVAnalysis;
use App\Models\JobProfile;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class CVAnalysisService
{
    private array $skillKeywords;
    private array $stopWords;
    
    public function __construct()
    {
        $this->initializeSkillKeywords();
        $this->initializeStopWords();
        
        // Configure PhpWord settings
        Settings::setOutputEscapingEnabled(true);
    }
    
    /**
     * Analyze uploaded CV file
     */
    public function analyzeCVFile(UploadedFile $file, ?int $userId = null, ?string $sessionId = null): CVAnalysis
    {
        $startTime = microtime(true);
        
        // Create initial analysis record
        $analysis = CVAnalysis::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'file_name' => $file->getClientOriginalName(), // Add the file_name field
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
            // Store the file
            $filePath = $file->store('cv-uploads', 'local');
            $analysis->update(['file_path' => $filePath]);

            // Extract text from file
            $extractedText = $this->extractTextFromFile($file);
            $analysis->update(['extracted_text' => $extractedText]);

            // Process text and extract skills
            $processedText = $this->preprocessText($extractedText);
            $skillsExtracted = $this->extractSkillsWithTFIDF($processedText);
            
            // Generate skill vector for similarity calculations
            $skillVector = $this->generateSkillVector($skillsExtracted);
            
            // Find matching jobs
            $jobMatches = $this->findMatchingJobs($skillVector, $skillsExtracted);
            
            // Create analysis summary
            $analysisSummary = $this->createAnalysisSummary($skillsExtracted, $jobMatches);
            
            // Calculate processing time
            $processingTime = microtime(true) - $startTime;
            
            // Update analysis with results
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
     * Extract text from different file types (public method for testing)
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
                    // Try to determine by file extension if MIME type detection fails
                    return $this->extractTextByExtension($fullPath, $extension);
            }
        } catch (\Exception $e) {
            \Log::error('CV text extraction failed', [
                'file' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'error' => $e->getMessage()
            ]);
            
            // Fallback: try to read as plain text
            return $this->extractTextFromTXT($fullPath);
        } finally {
            // Clean up temporary file
            Storage::delete($tempPath);
        }
    }

    /**
     * Extract text by file extension as fallback
     */
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

    /**
     * Extract text from PDF file
     */
    private function extractTextFromPDF(string $filePath): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    /**
     * Extract text from DOCX file
     */
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

    /**
     * Extract text from DOC file (legacy format)
     */
    private function extractTextFromDOC(string $filePath): string
    {
        // For DOC files, we'll try to use PhpWord's reader
        try {
            $phpWord = IOFactory::load($filePath);
            return $this->extractTextFromDOCX($filePath); // Use same method as DOCX
        } catch (\Exception $e) {
            throw new \Exception('DOC file format not fully supported. Please convert to DOCX or PDF.');
        }
    }

    /**
     * Extract text from TXT files
     */
    private function extractTextFromTXT(string $filePath): string
    {
        try {
            return file_get_contents($filePath);
        } catch (\Exception $e) {
            \Log::error('TXT extraction failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Extract text from RTF files
     */
    private function extractTextFromRTF(string $filePath): string
    {
        try {
            $content = file_get_contents($filePath);
            
            // Basic RTF to text conversion
            // Remove RTF control words and formatting
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
    
    /**
     * Extract text from ODT files
     */
    private function extractTextFromODT(string $filePath): string
    {
        try {
            // ODT files are ZIP archives containing XML
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === TRUE) {
                $content = $zip->getFromName('content.xml');
                $zip->close();
                
                if ($content) {
                    // Parse XML and extract text
                    $xml = simplexml_load_string($content);
                    if ($xml) {
                        // Remove XML tags and get plain text
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
     * Enhanced text preprocessing for different CV formats
     */
    public function preprocessText(string $text): string
    {
        // Normalize whitespace and line breaks
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\n+/', "\n", $text);
        
        // Remove common CV formatting artifacts
        $text = preg_replace('/[•·▪▫◦‣⁃]/', '', $text); // Remove bullet points
        $text = preg_replace('/_{3,}/', '', $text); // Remove underlines
        $text = preg_replace('/-{3,}/', '', $text); // Remove dashes
        $text = preg_replace('/={3,}/', '', $text); // Remove equals signs
        
        // Normalize common CV section headers (multilingual support)
        $sectionHeaders = [
            // English
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
            
            // Common international variations
            'EXPERIENCIA LABORAL', 'EXPERIENCIA PROFESIONAL', // Spanish
            'EXPÉRIENCE PROFESSIONNELLE', 'EXPÉRIENCE', // French
            'BERUFSERFAHRUNG', 'ARBEITSERFAHRUNG', // German
            'ESPERIENZA LAVORATIVA', 'ESPERIENZA PROFESSIONALE', // Italian
            'WERKERVARING', 'PROFESSIONELE ERVARING', // Dutch
            'EXPERIÊNCIA PROFISSIONAL', 'EXPERIÊNCIA DE TRABALHO', // Portuguese
        ];
        
        foreach ($sectionHeaders as $header) {
            $text = preg_replace('/\b' . preg_quote($header, '/') . '\b/i', "\n" . $header . "\n", $text);
        }
        
        // Convert to lowercase
        $text = strtolower($text);
        
        // Remove special characters but keep spaces and alphanumeric
        $text = preg_replace('/[^a-z0-9\s\+\#]/', ' ', $text);
        
        // Clean up extra whitespace
        $text = trim(preg_replace('/\s+/', ' ', $text));
        
        return $text;
    }
    
    /**
     * Extract skills using TF-IDF algorithm (public method for testing)
     */
    public function extractSkillsWithTFIDF(string $text): array
    {
        $words = explode(' ', $text);
        $totalWords = count($words);
        
        // Calculate term frequency (TF)
        $termFrequency = [];
        foreach ($words as $word) {
            if (!in_array($word, $this->stopWords) && strlen($word) > 2) {
                $termFrequency[$word] = ($termFrequency[$word] ?? 0) + 1;
            }
        }
        
        // Calculate TF scores
        foreach ($termFrequency as $term => $freq) {
            $termFrequency[$term] = $freq / $totalWords;
        }
        
        // Calculate IDF and TF-IDF for skill-related terms
        $skillScores = [];
        foreach ($this->skillKeywords as $category => $skills) {
            foreach ($skills as $skill) {
                $skillLower = strtolower($skill);
                
                // Check for exact matches and partial matches
                $matches = 0;
                if (isset($termFrequency[$skillLower])) {
                    $matches = $termFrequency[$skillLower] * $totalWords;
                } else {
                    // Check for partial matches in the text
                    $matches = substr_count($text, $skillLower);
                }
                
                if ($matches > 0) {
                    // Simple IDF calculation (can be enhanced with a larger corpus)
                    $idf = log(1000 / (1 + $matches)); // Assuming corpus of 1000 documents
                    $tfIdf = ($matches / $totalWords) * $idf;
                    
                    $skillScores[$skill] = [
                        'category' => $category,
                        'score' => $tfIdf,
                        'frequency' => $matches
                    ];
                }
            }
        }
        
        // Sort by TF-IDF score and return top skills
        uasort($skillScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return array_slice($skillScores, 0, 20, true); // Return top 20 skills
    }
    
    /**
     * Generate skill vector for cosine similarity matching (public method for testing)
     */
    public function generateSkillVector(array $extractedSkills): array
    {
        $vector = [
            'programming' => 0,
            'web_development' => 0,
            'database' => 0,
            'cloud_devops' => 0,
            'mobile_development' => 0,
            'data_science' => 0,
            'ui_ux' => 0,
            'project_management' => 0,
            'communication' => 0,
            'leadership' => 0,
            'analytical_thinking' => 0,
            'problem_solving' => 0
        ];
        
        foreach ($extractedSkills as $skill => $data) {
            $category = $data['category'];
            $score = min($data['score'] * 10, 1.0); // Normalize to 0-1 range
            
            switch ($category) {
                case 'programming':
                    $vector['programming'] = max($vector['programming'], $score);
                    break;
                case 'web_development':
                    $vector['web_development'] = max($vector['web_development'], $score);
                    break;
                case 'database':
                    $vector['database'] = max($vector['database'], $score);
                    break;
                case 'cloud_devops':
                    $vector['cloud_devops'] = max($vector['cloud_devops'], $score);
                    break;
                case 'mobile_development':
                    $vector['mobile_development'] = max($vector['mobile_development'], $score);
                    break;
                case 'data_science':
                    $vector['data_science'] = max($vector['data_science'], $score);
                    break;
                case 'ui_ux':
                    $vector['ui_ux'] = max($vector['ui_ux'], $score);
                    break;
                case 'project_management':
                    $vector['project_management'] = max($vector['project_management'], $score);
                    break;
                case 'soft_skills':
                    // Distribute soft skills across relevant categories
                    if (strpos(strtolower($skill), 'communication') !== false) {
                        $vector['communication'] = max($vector['communication'], $score);
                    } elseif (strpos(strtolower($skill), 'leadership') !== false) {
                        $vector['leadership'] = max($vector['leadership'], $score);
                    } elseif (strpos(strtolower($skill), 'analytical') !== false) {
                        $vector['analytical_thinking'] = max($vector['analytical_thinking'], $score);
                    } else {
                        $vector['problem_solving'] = max($vector['problem_solving'], $score);
                    }
                    break;
            }
        }
        
        return $vector;
    }
    
    /**
     * Find matching jobs using cosine similarity
     */
    private function findMatchingJobs(array $skillVector, array $skillsExtracted): array
    {
        $jobProfiles = JobProfile::all();
        $matches = [];
        
        foreach ($jobProfiles as $job) {
            $jobSkillVector = $job->skill_vector ?? [];
            
            if (empty($jobSkillVector)) {
                continue;
            }
            
            $similarity = $this->calculateCosineSimilarity($skillVector, $jobSkillVector);
            
            if ($similarity > 0.1) { // Only include jobs with >10% similarity
                $matchingSkills = $this->findMatchingSkills($skillsExtracted, $job);
                
                $matches[] = [
                    'job_id' => $job->id,
                    'job_title' => $job->job_title,
                    'company' => $job->company ?? null,
                    'description' => Str::limit($job->description, 200),
                    'similarity_score' => round($similarity * 100, 1),
                    'matching_skills' => $matchingSkills,
                    'required_skills' => $job->required_skills ?? [],
                    'salary_range' => [
                        'min' => $job->salary_min ?? null,
                        'max' => $job->salary_max ?? null
                    ]
                ];
            }
        }
        
        // Sort by similarity score (highest first)
        usort($matches, function($a, $b) {
            return $b['similarity_score'] <=> $a['similarity_score'];
        });
        
        return array_slice($matches, 0, 10); // Return top 10 matches
    }

    /**
     * Find matching skills between extracted skills and job requirements
     */
    private function findMatchingSkills(array $extractedSkills, JobProfile $job): array
    {
        $matchingSkills = [];
        $jobRequiredSkills = $job->required_skills ?? [];
        
        foreach ($extractedSkills as $skillName => $skillData) {
            $tfidfScore = $skillData['score'];
            
            // Check if this skill matches any job requirement
            foreach ($jobRequiredSkills as $requiredSkill) {
                $similarity = $this->calculateStringSimilarity($skillName, $requiredSkill);
                
                if ($similarity > 0.8) { // 80% string similarity
                    $matchStrength = $this->determineMatchStrength($tfidfScore, $similarity);
                    
                    $matchingSkills[] = [
                        'skill' => $skillName,
                        'required_skill' => $requiredSkill,
                        'match_strength' => $matchStrength,
                        'tfidf_score' => $tfidfScore,
                        'similarity' => $similarity
                    ];
                    break;
                }
            }
        }
        
        return $matchingSkills;
    }

    /**
     * Calculate string similarity between two skills
     */
    private function calculateStringSimilarity(string $str1, string $str2): float
    {
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));
        
        if ($str1 === $str2) {
            return 1.0;
        }
        
        // Use Levenshtein distance for similarity
        $maxLen = max(strlen($str1), strlen($str2));
        if ($maxLen === 0) {
            return 0.0;
        }
        
        $distance = levenshtein($str1, $str2);
        return 1 - ($distance / $maxLen);
    }

    /**
     * Determine match strength based on TF-IDF score and similarity
     */
    private function determineMatchStrength(float $tfidfScore, float $similarity): string
    {
        $combinedScore = ($tfidfScore * 0.6) + ($similarity * 0.4);
        
        if ($combinedScore >= 0.8) {
            return 'Strong';
        } elseif ($combinedScore >= 0.6) {
            return 'Medium';
        } else {
            return 'Weak';
        }
    }
    
    /**
     * Calculate cosine similarity between two vectors
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
     * Get matching skills between user and job vectors
     */
    private function getMatchingSkills(array $userVector, array $jobVector): array
    {
        $matchingSkills = [];
        
        foreach ($userVector as $skill => $userScore) {
            $jobScore = $jobVector[$skill] ?? 0;
            if ($userScore > 0.1 && $jobScore > 0.1) {
                $matchingSkills[] = [
                    'skill' => ucfirst(str_replace('_', ' ', $skill)),
                    'user_score' => round($userScore, 2),
                    'job_score' => round($jobScore, 2),
                    'match_strength' => round(min($userScore, $jobScore), 2)
                ];
            }
        }
        
        return $matchingSkills;
    }
    
    /**
     * Create analysis summary
     */
    private function createAnalysisSummary(array $skillsExtracted, array $jobMatches): array
    {
        // Sort skills by TF-IDF score descending
        usort($skillsExtracted, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        $topSkills = array_slice(
            array_keys(
                array_slice($skillsExtracted, 0, 10, true)
            ), 
            0, 
            10
        );
        
        $bestMatch = !empty($jobMatches) ? $jobMatches[0] : null;
        
        return [
            'total_skills_found' => count($skillsExtracted),
            'total_job_matches' => count($jobMatches),
            'top_skills' => $topSkills,
            'best_match' => $bestMatch ? [
                'job_title' => $bestMatch['job_title'],
                'similarity' => $bestMatch['similarity_score']
            ] : null,
            'skill_categories' => $this->categorizeSkills($skillsExtracted),
            'analysis_date' => now()->toDateTimeString()
        ];
    }

    /**
     * Categorize skills into different types (public method for testing)
     */
    public function categorizeSkills(array $skills): array
    {
        $categories = [
            'technical' => [],
            'soft' => [],
            'tools' => [],
            'languages' => [],
            'other' => []
        ];
        
        $technicalKeywords = ['programming', 'development', 'software', 'database', 'api', 'framework', 'algorithm'];
        $softKeywords = ['communication', 'leadership', 'teamwork', 'management', 'problem-solving'];
        $toolKeywords = ['excel', 'word', 'powerpoint', 'photoshop', 'git', 'docker', 'kubernetes'];
        $languageKeywords = ['english', 'spanish', 'french', 'german', 'chinese', 'japanese'];
        
        foreach ($skills as $skillName => $skillData) {
            $skillNameLower = strtolower($skillName);
            $categorized = false;
            
            foreach ($technicalKeywords as $keyword) {
                if (strpos($skillNameLower, $keyword) !== false) {
                    $categories['technical'][] = $skillName;
                    $categorized = true;
                    break;
                }
            }
            
            if (!$categorized) {
                foreach ($softKeywords as $keyword) {
                    if (strpos($skillNameLower, $keyword) !== false) {
                        $categories['soft'][] = $skillName;
                        $categorized = true;
                        break;
                    }
                }
            }
            
            if (!$categorized) {
                foreach ($toolKeywords as $keyword) {
                    if (strpos($skillNameLower, $keyword) !== false) {
                        $categories['tools'][] = $skillName;
                        $categorized = true;
                        break;
                    }
                }
            }
            
            if (!$categorized) {
                foreach ($languageKeywords as $keyword) {
                    if (strpos($skillNameLower, $keyword) !== false) {
                        $categories['languages'][] = $skillName;
                        $categorized = true;
                        break;
                    }
                }
            }
            
            if (!$categorized) {
                $categories['other'][] = $skillName;
            }
        }
        
        return $categories;
    }
    
    /**
     * Initialize skill keywords for different categories
     */
    private function initializeSkillKeywords(): void
    {
        $this->skillKeywords = [
            'programming' => [
                'PHP', 'JavaScript', 'Python', 'Java', 'C++', 'C#', 'Ruby', 'Go', 'Rust',
                'TypeScript', 'Kotlin', 'Swift', 'Scala', 'R', 'MATLAB', 'Perl', 'Shell',
                'PowerShell', 'Bash', 'SQL', 'HTML', 'CSS', 'SASS', 'LESS'
            ],
            'web_development' => [
                'React', 'Vue.js', 'Angular', 'Node.js', 'Express', 'Laravel', 'Django',
                'Flask', 'Spring Boot', 'ASP.NET', 'Ruby on Rails', 'Next.js', 'Nuxt.js',
                'Svelte', 'jQuery', 'Bootstrap', 'Tailwind CSS', 'Webpack', 'Vite'
            ],
            'database' => [
                'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite', 'Oracle', 'SQL Server',
                'Cassandra', 'DynamoDB', 'Elasticsearch', 'Neo4j', 'Firebase', 'Supabase'
            ],
            'cloud_devops' => [
                'AWS', 'Azure', 'Google Cloud', 'Docker', 'Kubernetes', 'Jenkins', 'GitLab CI',
                'GitHub Actions', 'Terraform', 'Ansible', 'Chef', 'Puppet', 'Vagrant',
                'Linux', 'Ubuntu', 'CentOS', 'NGINX', 'Apache'
            ],
            'mobile_development' => [
                'React Native', 'Flutter', 'iOS', 'Android', 'Xamarin', 'Ionic', 'Cordova',
                'Swift', 'Objective-C', 'Kotlin', 'Java Android', 'Dart'
            ],
            'data_science' => [
                'Machine Learning', 'Deep Learning', 'TensorFlow', 'PyTorch', 'Scikit-learn',
                'Pandas', 'NumPy', 'Matplotlib', 'Seaborn', 'Jupyter', 'Apache Spark',
                'Hadoop', 'Tableau', 'Power BI', 'Statistics', 'Data Mining'
            ],
            'ui_ux' => [
                'Figma', 'Adobe XD', 'Sketch', 'Photoshop', 'Illustrator', 'InVision',
                'Principle', 'Framer', 'User Research', 'Wireframing', 'Prototyping',
                'User Testing', 'Design Systems', 'Accessibility'
            ],
            'project_management' => [
                'Agile', 'Scrum', 'Kanban', 'JIRA', 'Trello', 'Asana', 'Monday.com',
                'Project Planning', 'Risk Management', 'Stakeholder Management',
                'Budget Management', 'Team Coordination'
            ],
            'soft_skills' => [
                'Communication', 'Leadership', 'Team Work', 'Problem Solving',
                'Critical Thinking', 'Analytical Thinking', 'Creativity', 'Adaptability',
                'Time Management', 'Conflict Resolution', 'Negotiation', 'Presentation'
            ]
        ];
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