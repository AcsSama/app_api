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

$userId      = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$oldPassword = $input['old_password'] ?? '';
$newPassword = $input['new_password'] ?? '';

if ($userId <= 0 || $oldPassword === '' || $newPassword === '') {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, old_password, new_password required']);
    exit;
}

try {
    $db = get_db();

    // ดึง user ปัจจุบัน
    $stmt = $db->prepare('SELECT id, password_hash FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // เช็กรหัสเดิม
    if (!password_verify($oldPassword, $user['password_hash'])) {
        http_response_code(400);
        echo json_encode(['error' => 'รหัสผ่านเดิมไม่ถูกต้อง']);
        exit;
    }

    // อัปเดตรหัสใหม่
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    $upd = $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $upd->execute([$hash, $userId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
