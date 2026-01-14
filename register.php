<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // <-- บรรทัดนี้สำคัญต้องใส่

require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$display  = trim($input['display_name'] ?? '');

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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
