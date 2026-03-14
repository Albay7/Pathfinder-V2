<?php
$filepath = 'C:/Users/Hendrix/OneDrive/Desktop/Projects/PathfinderApp/Pathfinder/app/Http/Controllers/PathfinderController.php';
$content = file_get_contents($filepath);

$map = [
    'Software Developer' => ['₱300k - ₱900k / year', 'Very High Growth'],
    'Data Analyst' => ['₱350k - ₱850k / year', 'High Demand'],
    'Cybersecurity Analyst' => ['₱400k - ₱1.2M / year', 'Very High Demand'],
    'Financial Analyst' => ['₱300k - ₱800k / year', 'Stable Growth'],
    'Database Administrator' => ['₱350k - ₱900k / year', 'Steady Demand'],
    'Systems Administrator' => ['₱300k - ₱800k / year', 'Consistent Demand'],
    'Network Administrator' => ['₱280k - ₱850k / year', 'Strong Demand'],
    'Web Developer' => ['₱250k - ₱750k / year', 'High Demand'],
    'IT Support Specialist' => ['₱200k - ₱450k / year', 'High Turnover / High Demand'],
    'Staff Nurse' => ['₱300k - ₱600k / year', 'Very High Demand (Local & Abroad)'],
    'Human Resources Specialist' => ['₱250k - ₱550k / year', 'Stable'],
    'Educational Coordinator' => ['₱250k - ₱550k / year', 'Moderate'],
    'Content Writer' => ['₱240k - ₱600k / year', 'Fast Growing (Freelance/BPO)'],
    'Social Worker' => ['₱200k - ₱400k / year', 'Steady Demand'],
    'Compliance Officer' => ['₱350k - ₱900k / year', 'High Demand'],
    'Administrative Officer' => ['₱200k - ₱450k / year', 'Stable'],
    'Operations Manager' => ['₱500k - ₱1.5M / year', 'Very Strong Demand'],
    'Business Development Manager' => ['₱450k - ₱1.2M / year', 'High Growth'],
    'Marketing Coordinator' => ['₱250k - ₱650k / year', 'Rapidly Growing'],
    'Sales Representative' => ['₱200k - ₱500k / year + commission', 'High Demand'],
    'Customer Service Representative' => ['₱250k - ₱450k / year', 'Massive Demand (BPO)'],
    'Event Coordinator' => ['₱220k - ₱600k / year', 'Strong Recovery'],
    'Hotel Front Desk Agent' => ['₱180k - ₱350k / year', 'Recovering/Growing'],
    'Public Relations Officer' => ['₱250k - ₱700k / year', 'Stable Growth'],
    'Communications Specialist' => ['₱250k - ₱650k / year', 'Steady Demand'],
    'Market Research Analyst' => ['₱250k - ₱600k / year', 'Growing'],
    'Curriculum Developer' => ['₱250k - ₱600k / year', 'Moderate'],
    'Computer Engineer' => ['₱300k - ₱850k / year', 'Strong Growth'],
    'Electronics Engineer' => ['₱250k - ₱750k / year', 'Stable'],
    'Physical Therapist' => ['₱250k - ₱600k / year', 'High Demand'],
    'Occupational Therapist' => ['₱250k - ₱550k / year', 'High Demand'],
    'Elementary School Teacher' => ['₱320k - ₱550k / year', 'Consistent Demand'],
];

$success = 0;
foreach($map as $career => $data) {
    $searchKey = "'$career' => [";
    $pos = strpos($content, $searchKey);
    if ($pos !== false) {
        $salPos = strpos($content, "'salary_range' => '", $pos);
        if ($salPos !== false) {
            $salStart = $salPos + 19;
            $salEnd = strpos($content, "'", $salStart);
            $content = substr_replace($content, $data[0], $salStart, $salEnd - $salStart);
            
            $pos = strpos($content, $searchKey); 
            $outPos = strpos($content, "'job_outlook' => '", $pos);
            if ($outPos !== false) {
                $outStart = $outPos + 18;
                $outEnd = strpos($content, "'", $outStart);
                $content = substr_replace($content, $data[1], $outStart, $outEnd - $outStart);
                $success++;
            }
        }
    }
}
file_put_contents($filepath, $content);
echo "Successfully updated $success careers.\n";
