<?php
$apiKey = env('OPENAI_API_KEY');
$ch = curl_init('https://api.openai.com/v1/chat/completions');
$data = [
    'model' => 'gpt-4o-mini',
    'messages' => [['role' => 'user', 'content' => 'Say hello']],
];
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
