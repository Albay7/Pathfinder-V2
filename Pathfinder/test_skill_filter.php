<?php
echo "Testing TF-IDF model with skill filters...\n";

$model = json_decode(file_get_contents('storage/app/data/tfidf_model.json'), true);

echo "Vocabulary: " . count($model['vocabulary']) . " terms\n";
echo "Skill flags: " . count($model['skill_flags']) . " flags\n";

$skillCount = array_sum($model['skill_flags']);
echo "Recognized skills: " . $skillCount . " terms\n\n";

echo "Sample recognized skills (first 20):\n";
$count = 0;
foreach ($model['vocabulary'] as $idx => $term) {
    if ($model['skill_flags'][$idx] == 1 && $count < 20) {
        echo "  - " . $term . "\n";
        $count++;
    }
}

echo "\nSample non-skill words (first 10):\n";
$count = 0;
foreach ($model['vocabulary'] as $idx => $term) {
    if ($model['skill_flags'][$idx] == 0 && $count < 10) {
        echo "  - " . $term . "\n";
        $count++;
    }
}
