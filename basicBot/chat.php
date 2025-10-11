<?php
header('Content-Type: application/json');

// Replace with your OpenAI API key
$api_key = 'sk-proj';

$input = json_decode(file_get_contents('php://input'), true);
$user_message = $input['message'];

// Prepare data for the OpenAI API
$data = [
    "model" => "gpt-4o", // Use your Custom GPT or GPT-4o etc.
    "messages" => [
        ["role" => "system", "content" => "You are the Eastlink Chatbot. Answer as a helpful assistant for Eastlink ChatBot users."],
        ["role" => "user", "content" => $user_message]
    ],
    "temperature" => 0.7
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? 'Sorry, I could not process your request.';

echo json_encode(["reply" => $reply]);
?>
