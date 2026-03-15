<?php
$apiKey = "AIzaSyAsthjsakxIRyh1W0P7rhJDtOePQjSDCYM";
$model = "gemini-2.0-flash";
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$prompt = "Provide a professional career profile for the role: 'Social Media Manager'. 
Return the response in JSON format with exactly two keys:
1. 'description': A single, high-quality paragraph explaining the role.
2. 'responsibilities': An array of 5 specific duties.";

$data = [
    'contents' => [['parts' => [['text' => $prompt]]]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
