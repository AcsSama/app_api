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

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'user_id required']);
    exit;
}

try {
    $db = get_db();

    // ถ้ามี table อื่นที่ FK อยู่ อาจต้องลบโพสต์/ข้อความก่อน
    $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$userId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
