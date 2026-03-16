<?php
$apiKey = env('GROQ_API_KEY');
$model = "llama-3.3-70b-versatile";
$url = "https://api.groq.com/openai/v1/chat/completions";

$prompt = "Provide a professional career profile for the role: 'Social Media Manager'. 
Return the response in JSON format (not markdown) with exactly two keys:
1. 'description': A single, high-quality paragraph explaining the role.
2. 'responsibilities': An array of 5 specific duties.";

$data = [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ],
    'response_format' => ['type' => 'json_object']
];

$ch = curl_init($url);
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
