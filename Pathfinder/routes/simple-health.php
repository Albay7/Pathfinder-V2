<?php

// Ultra-simple health check that doesn't depend on Laravel framework
// This can be used as a backup if the main health endpoint fails

header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'ok',
    'timestamp' => date('c'),
    'service' => 'pathfinder-app-simple',
    'php_version' => PHP_VERSION
]);