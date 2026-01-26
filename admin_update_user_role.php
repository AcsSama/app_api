<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$userId = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$role   = isset($input['role']) ? trim($input['role']) : '';

if ($userId <= 0 || ($role !== 'user' && $role !== 'admin')) {
    http_response_code(400);
    echo json_encode(['error' => 'user_id and valid role required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare('UPDATE users SET role = ? WHERE id = ?');
    $stmt->execute([$role, $userId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
