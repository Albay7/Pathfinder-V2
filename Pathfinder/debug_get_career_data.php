<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\PathfinderController;

$titles = ['Software Developer', 'Data Scientist', 'Nurse', 'Civil Engineer', 'Non-Existent Role'];

foreach ($titles as $title) {
    try {
        $data = PathfinderController::getCareerData($title);
        echo "Title: $title\n";
        echo "Data returned: " . (is_array($data) ? "Array" : gettype($data)) . "\n";
        if (is_array($data)) {
            echo "Description: " . ($data['description'] ?? 'MISSING') . "\n";
        }
        echo "-------------------\n";
    } catch (\Exception $e) {
        echo "Error for $title: " . $e->getMessage() . "\n";
    }
}
