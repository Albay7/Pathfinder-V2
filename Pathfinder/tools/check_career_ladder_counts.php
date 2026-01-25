<?php
// Quick helper to print distinct target roles and total ladder steps.
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\CareerLadder;

$paths = CareerLadder::distinct('target_role')->count();
$steps = CareerLadder::count();

echo "Total paths: {$paths}\n";
echo "Total steps: {$steps}\n";
