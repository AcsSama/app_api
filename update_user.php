<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$userId       = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$newName      = trim($input['display_name'] ?? '');
$newPassword  = $input['password'] ?? ''; // ถ้าว่าง = ไม่เปลี่ยนรหัสผ่าน

if ($userId <= 0 || $newName === '') {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, display_name required']);
    exit;
}

try {
    $db = get_db();

    if ($newPassword !== '') {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare('UPDATE users SET display_name = ?, password_hash = ? WHERE id = ?');
        $stmt->execute([$newName, $hash, $userId]);
    } else {
        $stmt = $db->prepare('UPDATE users SET display_name = ? WHERE id = ?');
        $stmt->execute([$newName, $userId]);
    }

    // ส่งข้อมูล user กลับ
    $stmt = $db->prepare('SELECT id, email, display_name, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
