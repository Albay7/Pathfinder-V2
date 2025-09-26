<?php

require_once 'vendor/autoload.php';

use App\Services\CVAnalysisService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// Initialize Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test CV file
$testCVContent = "John Doe
Software Developer

EXPERIENCE:
- 5 years of experience in web development using PHP, Laravel, JavaScript, and MySQL
- Proficient in React, Vue.js, and Node.js for frontend and backend development
- Experience with database design, API development, and RESTful services
- Strong problem-solving skills and teamwork abilities
- Project management experience using Agile methodologies

SKILLS:
- Programming Languages: PHP, JavaScript, Python, Java
- Web Technologies: HTML, CSS, Bootstrap, jQuery
- Frameworks: Laravel, React, Vue.js, Express.js
- Databases: MySQL, PostgreSQL, MongoDB
- Tools: Git, Docker, VS Code, Postman
- Soft Skills: Communication, Leadership, Time Management

EDUCATION:
Bachelor of Computer Science
University of Technology, 2018

CERTIFICATIONS:
- AWS Certified Developer
- Laravel Certified Developer
- Scrum Master Certification";

// Save test CV to temporary file
$tempFile = tempnam(sys_get_temp_dir(), 'test_cv');
file_put_contents($tempFile, $testCVContent);

try {
    // Create UploadedFile instance
    $uploadedFile = new UploadedFile(
        $tempFile,
        'test_cv.txt',
        'text/plain',
        null,
        true
    );

    // Initialize CV Analysis Service
    $cvAnalysisService = new CVAnalysisService();
    
    echo "Testing CV Analysis Service...\n";
    echo "================================\n\n";
    
    // Test CV analysis
    $result = $cvAnalysisService->analyzeCVFile($uploadedFile, 1);
    
    echo "Analysis Result:\n";
    echo "ID: " . $result->id . "\n";
    echo "Status: " . $result->status . "\n";
    echo "File Name: " . $result->file_name . "\n";
    echo "Processing Time: " . $result->processing_time . "ms\n\n";
    
    echo "Skills Found:\n";
    $skills = json_decode($result->skills_extracted, true);
    foreach (array_slice($skills, 0, 10) as $skill) {
        echo "- " . $skill['skill'] . " (TF-IDF: " . round($skill['tfidf_score'], 4) . ")\n";
    }
    
    echo "\nJob Matches:\n";
    $jobMatches = json_decode($result->job_matches, true);
    foreach (array_slice($jobMatches, 0, 5) as $match) {
        echo "- " . $match['job_title'] . " (Similarity: " . round($match['similarity_score'], 4) . ")\n";
    }
    
    echo "\nAnalysis Summary:\n";
    $summary = json_decode($result->analysis_summary, true);
    echo "- Total Skills: " . $summary['total_skills_found'] . "\n";
    echo "- Total Job Matches: " . $summary['total_job_matches'] . "\n";
    echo "- Top Skills: " . implode(', ', $summary['top_skills']) . "\n";
    
    if ($summary['best_match']) {
        echo "- Best Match: " . $summary['best_match']['job_title'] . " (" . round($summary['best_match']['similarity'], 4) . ")\n";
    }
    
    echo "\nSkill Categories:\n";
    foreach ($summary['skill_categories'] as $category => $categorySkills) {
        if (!empty($categorySkills)) {
            echo "- " . ucfirst($category) . ": " . implode(', ', array_slice($categorySkills, 0, 5)) . "\n";
        }
    }
    
    echo "\n✅ CV Analysis test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error during CV analysis: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} finally {
    // Clean up temporary file
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}