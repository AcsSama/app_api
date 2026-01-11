<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'email and password required']);
    exit;
}

try {
    $db = get_db();
    $stmt = $db->prepare("SELECT id, password_hash, display_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'invalid credentials']);
        exit;
    }

    echo json_encode([
        'id'           => (int)$user['id'],
        'email'        => $email,
        'display_name' => $user['display_name'],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
