<?php
// Simple test to check if the keyword map loads properly
try {
    $file = file_get_contents('app/Http/Controllers/PathfinderController.php');

    // Extract the keyword map using regex
    if (preg_match('/\$keywordMap\s*=\s*\[(.*?)\];/s', $file, $matches)) {
        $mapContent = $matches[1];
        $keys = [];

        // Find all keys in the format 'key' =>
        preg_match_all("/^\s*'([^']+)'\s*=>/m", $mapContent, $keyMatches);

        if (!empty($keyMatches[1])) {
            $keys = $keyMatches[1];
            $uniqueKeys = array_unique($keys);
            $duplicates = array_diff_assoc($keys, $uniqueKeys);

            echo "Total keyword entries: " . count($keys) . PHP_EOL;
            echo "Unique keys: " . count($uniqueKeys) . PHP_EOL;

            if (!empty($duplicates)) {
                echo "\nDuplicate keys found:" . PHP_EOL;
                foreach ($duplicates as $index => $key) {
                    echo "  - $key (at position $index)" . PHP_EOL;
                }
            } else {
                echo "\nNo duplicate keys found!" . PHP_EOL;
            }

            // Check for our soft skills
            $softSkills = ['communication', 'leadership', 'customer service', 'problem solving'];
            foreach ($softSkills as $skill) {
                $count = count(array_keys($keys, $skill));
                echo "  '$skill' appears " . $count . " time(s)" . PHP_EOL;
            }
        }
    } else {
        echo "Could not find keyword map in file" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>
