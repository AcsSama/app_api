<?php
header('Content-Type: application/json');
// อนุญาตให้เรียกจากทุก origin (สำหรับ Flutter Web DEV เท่านั้น)
header('Access-Control-Allow-Origin: *'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // <-- บรรทัดนี้สำคัญต้องใส่

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db.php';

try {
    $db = get_db();
    $stmt = $db->query("
        SELECT id, email, password_hash, display_name
        FROM users
    ");
    $users = $stmt->fetchAll();
    echo json_encode($users);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage()
    ]);
}
