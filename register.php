<?php
require_once 'db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, ngrok-skip-browser-warning');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$display = trim($input['display_name'] ?? '');

if (!$email || !$password || !$display) {
    http_response_code(400);
    echo json_encode(['error' => 'email, password, display_name required']);
    exit;
}

try {
    $db = get_db();

    // เช็ค email ซ้ำ
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'email already exists']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare("
        INSERT INTO users (email, password_hash, display_name)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$email, $hash, $display]);

    $id = (int)$db->lastInsertId();

    echo json_encode([
        'id'           => $id,
        'email'        => $email,
        'display_name' => $display,
    ]);
    error_log("REGISTER new user id=$id email=$email");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
