<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: https://your-trusted-domain.com"); // Restrict CORS policy
header("Access-Control-Allow-Headers: Content-Type");

$input = json_decode(file_get_contents("php://input"), true);

$user = $input['user'] ?? 'Guest';
$message = strtolower(trim($input['message'] ?? ''));

if (!$message) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

// Load configuration
require_once 'config/config.php'; // Ensure this file contains $db_config and $api_key

// Connect to MySQL
$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Helper: query products
function getProducts($mysqli, $filter = []) {
    $sql = "SELECT name, price, category FROM products WHERE 1";
    if (!empty($filter['category'])) {
        $category = $mysqli->real_escape_string($filter['category']);
        $sql .= " AND category LIKE '%$category%'";
    }
    if (!empty($filter['max_price'])) {
        $max_price = (float)$filter['max_price'];
        $sql .= " AND price <= $max_price";
    }
    $result = $mysqli->query($sql);
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Detect intent (basic example — can expand later)
$productIntent = false;
$filter = [];

if (strpos($message, 'product') !== false || strpos($message, 'show me') !== false || strpos($message, 'price') !== false) {
    $productIntent = true;
}

// Simple parsing for filters
if (preg_match('/under \$?(\d+)/', $message, $m)) {
    $filter['max_price'] = $m[1];
}
if (preg_match('/(shoes|hoodie|electronics|footwear|clothing)/', $message, $m)) {
    $filter['category'] = ucfirst($m[1]);
}

if ($productIntent) {
    $products = getProducts($mysqli, $filter);
    if (count($products) > 0) {
        $list = "";
        foreach ($products as $p) {
            $list .= "{$p['name']} ({$p['category']}) - \\${$p['price']}\n";
        }
        $reply = "Here are some matching products:\n\n" . $list;
    } else {
        $reply = "Sorry, I couldn't find any products matching that request.";
    }
} else {
    // 🧠 Call OpenAI API
    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ]);

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            ["role" => "system", "content" => "You are a friendly shopping assistant. Keep replies short and helpful."],
            ["role" => "user", "content" => $message]
        ]
    ];
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $reply = $result['choices'][0]['message']['content'] ?? 'No response from AI.';
}

// 💾 Save chat
$stmt = $mysqli->prepare("INSERT INTO chat_history (user, message, response) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $user, $message, $reply);
$stmt->execute();
$stmt->close();
$mysqli->close();

echo json_encode(['reply' => $reply]);
?>
