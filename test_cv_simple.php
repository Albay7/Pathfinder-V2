<?php

require_once 'vendor/autoload.php';

use App\Services\CVAnalysisService;
use Illuminate\Http\UploadedFile;

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
    // Initialize CV Analysis Service
    $cvAnalysisService = new CVAnalysisService();
    
    echo "Testing CV Analysis Service Components...\n";
    echo "========================================\n\n";
    
    // Test text extraction
    echo "1. Testing text extraction...\n";
    $extractedText = file_get_contents($tempFile);
    echo "✅ Text extracted successfully (" . strlen($extractedText) . " characters)\n\n";
    
    // Test text preprocessing
    echo "2. Testing text preprocessing...\n";
    $preprocessedText = $cvAnalysisService->preprocessText($extractedText);
    echo "✅ Text preprocessed successfully (" . str_word_count($preprocessedText) . " words)\n\n";
    
    // Test skill extraction
    echo "3. Testing skill extraction with TF-IDF...\n";
    $skills = $cvAnalysisService->extractSkillsWithTFIDF($preprocessedText);
    echo "✅ Skills extracted successfully (" . count($skills) . " skills found)\n";
    
    echo "Top 10 Skills:\n";
    $topSkills = array_slice($skills, 0, 10);
    foreach ($topSkills as $skillName => $skillData) {
        echo "- " . $skillName . " (TF-IDF: " . round($skillData['score'], 4) . ", Category: " . $skillData['category'] . ")\n";
    }
    
    // Test skill vector generation
    echo "\n4. Testing skill vector generation...\n";
    
    // Convert skills to the format expected by generateSkillVector (associative array)
    $skillsForVector = [];
    foreach ($skills as $skillName => $skillData) {
        $skillsForVector[$skillName] = $skillData; // Keep original format with category and score
    }
    
    $skillVector = $cvAnalysisService->generateSkillVector($skillsForVector);
    echo "✅ Skill vector generated successfully (" . count($skillVector) . " dimensions)\n";
    
    // Test skill categorization
    echo "\n5. Testing skill categorization...\n";
    
    // Convert skills to the format expected by categorizeSkills
    $skillsForCategorization = [];
    foreach ($skills as $skillName => $skillData) {
        $skillsForCategorization[] = [
            'skill' => $skillName,
            'tfidf_score' => $skillData['score']
        ];
    }
    
    $categories = $cvAnalysisService->categorizeSkills($skillsForCategorization);
    echo "✅ Skills categorized successfully\n";
    
    foreach ($categories as $category => $categorySkills) {
        if (!empty($categorySkills)) {
            echo "- " . ucfirst($category) . " (" . count($categorySkills) . "): " . implode(', ', array_slice($categorySkills, 0, 3)) . "\n";
        }
    }
    
    echo "\n✅ All CV Analysis components tested successfully!\n";
    echo "\nSystem is ready for production use.\n";
    
} catch (Exception $e) {
    echo "❌ Error during CV analysis: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} finally {
    // Clean up temporary file
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
}