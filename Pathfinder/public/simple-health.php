<?php

// Ultra-simple health check that bypasses Laravel entirely
// Access via: /simple-health.php

header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'ok',
    'timestamp' => date('c'),
    'service' => 'pathfinder-app-direct',
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown'
]);