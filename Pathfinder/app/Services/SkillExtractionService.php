<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SkillExtractionService
{
    private $technicalSkills;
    private $softSkills;
    private $stopWords;
    
    public function __construct()
    {
        $this->initializeSkillDictionaries();
        $this->initializeStopWords();
    }
    
    /**
     * Extract skills using TF-IDF and keyword matching
     */
    public function extractSkills($jobDescription, $jobTitle = '')
    {
        $text = strtolower($jobDescription . ' ' . $jobTitle);
        
        // Clean and tokenize text
        $tokens = $this->tokenizeText($text);
        
        // Extract different types of skills
        $technicalSkills = $this->extractTechnicalSkills($tokens, $text);
        $softSkills = $this->extractSoftSkills($tokens, $text);
        $frameworksLibraries = $this->extractFrameworksAndLibraries($tokens, $text);
        $tools = $this->extractTools($tokens, $text);
        
        // Calculate TF-IDF scores for extracted skills
        $allSkills = array_merge($technicalSkills, $softSkills, $frameworksLibraries, $tools);
        $skillScores = $this->calculateTFIDF($allSkills, $text);
        
        return [
            'technical_skills' => $technicalSkills,
            'soft_skills' => $softSkills,
            'frameworks_libraries' => $frameworksLibraries,
            'tools' => $tools,
            'skill_scores' => $skillScores,
            'all_skills' => array_unique($allSkills)
        ];
    }
    
    /**
     * Generate skill vector for cosine similarity
     */
    public function generateSkillVector($extractedSkills, $jobTitle = '')
    {
        $vector = [];
        
        // Define skill categories and their weights
        $skillCategories = [
            'programming' => 0.0,
            'web_development' => 0.0,
            'database' => 0.0,
            'cloud_devops' => 0.0,
            'mobile_development' => 0.0,
            'data_science' => 0.0,
            'ui_ux' => 0.0,
            'project_management' => 0.0,
            'communication' => 0.0,
            'leadership' => 0.0,
            'analytical_thinking' => 0.0,
            'problem_solving' => 0.0
        ];
        
        // Map skills to categories
        foreach ($extractedSkills['all_skills'] as $skill) {
            $category = $this->mapSkillToCategory($skill);
            if (isset($skillCategories[$category])) {
                $weight = $extractedSkills['skill_scores'][$skill] ?? 0.1;
                $skillCategories[$category] += $weight;
            }
        }
        
        // Normalize vector values to 0-1 range
        $maxValue = max($skillCategories) ?: 1;
        foreach ($skillCategories as $category => $value) {
            $vector[$category] = min($value / $maxValue, 1.0);
        }
        
        // Apply job title boost
        $vector = $this->applyJobTitleBoost($vector, $jobTitle);
        
        return $vector;
    }
    
    /**
     * Tokenize and clean text
     */
    private function tokenizeText($text)
    {
        // Remove special characters and split into words
        $text = preg_replace('/[^a-zA-Z0-9\s\+\#\.\-]/', ' ', $text);
        $tokens = preg_split('/\s+/', $text);
        
        // Remove stop words and short tokens
        $tokens = array_filter($tokens, function($token) {
            return strlen($token) > 2 && !in_array($token, $this->stopWords);
        });
        
        return array_values($tokens);
    }
    
    /**
     * Extract technical skills
     */
    private function extractTechnicalSkills($tokens, $text)
    {
        $found = [];
        
        foreach ($this->technicalSkills as $skill) {
            if ($this->skillExistsInText($skill, $text, $tokens)) {
                $found[] = $skill;
            }
        }
        
        return array_unique($found);
    }
    
    /**
     * Extract soft skills
     */
    private function extractSoftSkills($tokens, $text)
    {
        $found = [];
        
        foreach ($this->softSkills as $skill) {
            if ($this->skillExistsInText($skill, $text, $tokens)) {
                $found[] = $skill;
            }
        }
        
        return array_unique($found);
    }
    
    /**
     * Extract frameworks and libraries
     */
    private function extractFrameworksAndLibraries($tokens, $text)
    {
        $frameworks = [
            'react', 'angular', 'vue', 'svelte', 'ember',
            'laravel', 'django', 'flask', 'spring', 'rails',
            'express', 'fastapi', 'nest.js', 'next.js', 'nuxt.js',
            'bootstrap', 'tailwind', 'material-ui', 'ant-design',
            'jquery', 'lodash', 'axios', 'redux', 'mobx'
        ];
        
        $found = [];
        foreach ($frameworks as $framework) {
            if ($this->skillExistsInText($framework, $text, $tokens)) {
                $found[] = $framework;
            }
        }
        
        return array_unique($found);
    }
    
    /**
     * Extract tools
     */
    private function extractTools($tokens, $text)
    {
        $tools = [
            'git', 'github', 'gitlab', 'bitbucket',
            'docker', 'kubernetes', 'jenkins', 'travis',
            'aws', 'azure', 'gcp', 'heroku', 'netlify',
            'jira', 'trello', 'slack', 'teams',
            'figma', 'sketch', 'adobe', 'photoshop'
        ];
        
        $found = [];
        foreach ($tools as $tool) {
            if ($this->skillExistsInText($tool, $text, $tokens)) {
                $found[] = $tool;
            }
        }
        
        return array_unique($found);
    }
    
    /**
     * Check if skill exists in text
     */
    private function skillExistsInText($skill, $text, $tokens)
    {
        $skill = strtolower($skill);
        
        // Direct match in text
        if (strpos($text, $skill) !== false) {
            return true;
        }
        
        // Token match (for compound skills)
        $skillTokens = explode(' ', $skill);
        if (count($skillTokens) > 1) {
            $allFound = true;
            foreach ($skillTokens as $skillToken) {
                if (!in_array($skillToken, $tokens)) {
                    $allFound = false;
                    break;
                }
            }
            return $allFound;
        }
        
        return in_array($skill, $tokens);
    }
    
    /**
     * Calculate TF-IDF scores for skills
     */
    private function calculateTFIDF($skills, $text)
    {
        $scores = [];
        $totalWords = str_word_count($text);
        
        foreach ($skills as $skill) {
            // Term Frequency
            $tf = substr_count(strtolower($text), strtolower($skill)) / $totalWords;
            
            // Simple IDF approximation (in real implementation, you'd use a corpus)
            $idf = log(1000 / (1 + $this->getSkillFrequency($skill)));
            
            $scores[$skill] = $tf * $idf;
        }
        
        return $scores;
    }
    
    /**
     * Get estimated skill frequency (mock implementation)
     */
    private function getSkillFrequency($skill)
    {
        // Mock frequency data - in real implementation, use actual corpus statistics
        $commonSkills = ['javascript', 'python', 'java', 'communication', 'teamwork'];
        return in_array(strtolower($skill), $commonSkills) ? 500 : 50;
    }
    
    /**
     * Map skill to category
     */
    private function mapSkillToCategory($skill)
    {
        $skill = strtolower($skill);
        
        $categoryMap = [
            'programming' => ['javascript', 'python', 'java', 'php', 'c++', 'c#', 'ruby', 'go', 'rust', 'typescript'],
            'web_development' => ['html', 'css', 'react', 'angular', 'vue', 'node.js', 'express', 'laravel', 'django'],
            'database' => ['mysql', 'postgresql', 'mongodb', 'redis', 'sqlite', 'sql'],
            'cloud_devops' => ['aws', 'azure', 'gcp', 'docker', 'kubernetes', 'jenkins', 'ci/cd'],
            'mobile_development' => ['ios', 'android', 'react native', 'flutter', 'swift', 'kotlin'],
            'data_science' => ['machine learning', 'ai', 'data analysis', 'pandas', 'numpy', 'tensorflow'],
            'ui_ux' => ['figma', 'sketch', 'adobe', 'user experience', 'user interface', 'design'],
            'project_management' => ['agile', 'scrum', 'kanban', 'jira', 'project management'],
            'communication' => ['communication', 'presentation', 'writing', 'documentation'],
            'leadership' => ['leadership', 'team lead', 'management', 'mentoring'],
            'analytical_thinking' => ['analytical', 'problem solving', 'critical thinking', 'debugging'],
            'problem_solving' => ['problem solving', 'troubleshooting', 'debugging', 'optimization']
        ];
        
        foreach ($categoryMap as $category => $skills) {
            if (in_array($skill, $skills)) {
                return $category;
            }
        }
        
        return 'programming'; // Default category
    }
    
    /**
     * Apply job title boost to vector
     */
    private function applyJobTitleBoost($vector, $jobTitle)
    {
        $title = strtolower($jobTitle);
        
        // Boost relevant categories based on job title
        if (strpos($title, 'frontend') !== false || strpos($title, 'ui') !== false) {
            $vector['web_development'] *= 1.3;
            $vector['ui_ux'] *= 1.5;
        }
        
        if (strpos($title, 'backend') !== false || strpos($title, 'api') !== false) {
            $vector['programming'] *= 1.3;
            $vector['database'] *= 1.2;
        }
        
        if (strpos($title, 'fullstack') !== false || strpos($title, 'full stack') !== false) {
            $vector['web_development'] *= 1.2;
            $vector['programming'] *= 1.2;
        }
        
        if (strpos($title, 'data') !== false || strpos($title, 'analyst') !== false) {
            $vector['data_science'] *= 1.4;
            $vector['analytical_thinking'] *= 1.3;
        }
        
        if (strpos($title, 'lead') !== false || strpos($title, 'senior') !== false || strpos($title, 'manager') !== false) {
            $vector['leadership'] *= 1.4;
            $vector['project_management'] *= 1.3;
        }
        
        // Ensure values don't exceed 1.0
        foreach ($vector as $key => $value) {
            $vector[$key] = min($value, 1.0);
        }
        
        return $vector;
    }
    
    /**
     * Initialize skill dictionaries
     */
    private function initializeSkillDictionaries()
    {
        $this->technicalSkills = [
            // Programming Languages
            'javascript', 'python', 'java', 'php', 'c++', 'c#', 'ruby', 'go', 'rust',
            'typescript', 'swift', 'kotlin', 'scala', 'r', 'matlab', 'sql', 'html', 'css',
            
            // Databases
            'mysql', 'postgresql', 'mongodb', 'redis', 'elasticsearch', 'sqlite', 'oracle',
            
            // Cloud & DevOps
            'aws', 'azure', 'gcp', 'docker', 'kubernetes', 'jenkins', 'git', 'ci/cd',
            'terraform', 'ansible', 'vagrant', 'nginx', 'apache',
            
            // Mobile
            'ios', 'android', 'react native', 'flutter', 'xamarin',
            
            // Data Science & AI
            'machine learning', 'artificial intelligence', 'data science', 'pandas',
            'numpy', 'tensorflow', 'pytorch', 'scikit-learn', 'keras'
        ];
        
        $this->softSkills = [
            'communication', 'teamwork', 'leadership', 'problem solving', 'critical thinking',
            'analytical thinking', 'creativity', 'adaptability', 'time management',
            'project management', 'collaboration', 'mentoring', 'presentation skills',
            'writing', 'documentation', 'customer service', 'negotiation', 'conflict resolution'
        ];
    }
    
    /**
     * Initialize stop words
     */
    private function initializeStopWords()
    {
        $this->stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with',
            'by', 'from', 'up', 'about', 'into', 'through', 'during', 'before', 'after',
            'above', 'below', 'between', 'among', 'is', 'are', 'was', 'were', 'be', 'been',
            'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could',
            'should', 'may', 'might', 'must', 'can', 'this', 'that', 'these', 'those'
        ];
    }
}