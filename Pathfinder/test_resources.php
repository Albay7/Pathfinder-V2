<?php
// Test resources array for duplicate keys
try {
    $file = file_get_contents('app/Http/Controllers/PathfinderController.php');

    // Extract the resources array - look for the pattern starting after 'protected $resources'
    if (preg_match('/protected\s+\$resources\s*=\s*\[(.*?)\n\s*\];/s', $file, $matches)) {
        $resourceContent = $matches[1];
        $keys = [];

        // Find all keys in the format 'key' =>
        preg_match_all("/^\s*'([^']+)'\s*=>/m", $resourceContent, $keyMatches);

        if (!empty($keyMatches[1])) {
            $keys = $keyMatches[1];
            $uniqueKeys = array_unique($keys);

            echo "Total resource entries: " . count($keys) . PHP_EOL;
            echo "Unique keys: " . count($uniqueKeys) . PHP_EOL;

            // Find duplicates
            $keyCounts = array_count_values($keys);
            $duplicates = array_filter($keyCounts, function($count) { return $count > 1; });

            if (!empty($duplicates)) {
                echo "\nDuplicate keys found:" . PHP_EOL;
                foreach (array_keys($duplicates) as $key) {
                    $count = $duplicates[$key];
                    echo "  - '$key' appears " . $count . " time(s)" . PHP_EOL;
                }
            } else {
                echo "\nNo duplicate keys found in resources array!" . PHP_EOL;
            }
        }
    } else {
        echo "Could not find resources array" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>
