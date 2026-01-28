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

$userId   = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$newName  = trim($input['display_name'] ?? '');
$newEmail = trim($input['email'] ?? '');
$password = $input['password'] ?? ''; // ใช้เป็น "รหัสเดิม" เพื่อยืนยันตอนเปลี่ยนอีเมล

if ($userId <= 0 || $newName === '') {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, display_name required']);
    exit;
}

try {
    $db = get_db();

    // ดึงข้อมูล user ปัจจุบัน
    $stmt = $db->prepare('SELECT id, email, password_hash, display_name FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentUser) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $currentEmail = $currentUser['email'];
    $currentName  = $currentUser['display_name'];

    $isEmailChanged = ($newEmail !== '' && $newEmail !== $currentEmail);
    $isNameChanged  = ($newName !== '' && $newName !== $currentName);

    // ป้องกัน display_name ซ้ำ (ถ้ามีเปลี่ยน)
    if ($isNameChanged) {
        $checkName = $db->prepare('SELECT id FROM users WHERE display_name = ? AND id <> ? LIMIT 1');
        $checkName->execute([$newName, $userId]);
        if ($checkName->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'ชื่อนี้ถูกใช้งานแล้ว']);
            exit;
        }
    }

    // ถ้าอีเมลเปลี่ยน ต้องกรอกรหัสผ่านเดิมและถูกต้อง + เช็คอีเมลซ้ำ
    if ($isEmailChanged) {
        if ($password === '') {
            http_response_code(400);
            echo json_encode(['error' => 'กรุณากรอกรหัสผ่านเพื่อเปลี่ยนอีเมล']);
            exit;
        }

        if (!password_verify($password, $currentUser['password_hash'])) {
            http_response_code(400);
            echo json_encode(['error' => 'รหัสผ่านไม่ถูกต้อง']);
            exit;
        }

        $check = $db->prepare('SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1');
        $check->execute([$newEmail, $userId]);
        if ($check->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'อีเมลนี้ถูกใช้งานแล้ว']);
            exit;
        }
    }

    // อัปเดตตามที่เปลี่ยนจริง
    if ($isEmailChanged) {
        $stmt = $db->prepare('UPDATE users SET display_name = ?, email = ? WHERE id = ?');
        $stmt->execute([$newName, $newEmail, $userId]);
    } else if ($isNameChanged) {
        $stmt = $db->prepare('UPDATE users SET display_name = ? WHERE id = ?');
        $stmt->execute([$newName, $userId]);
    }
    // ถ้าไม่เปลี่ยนอะไรเลยก็ไม่ต้อง UPDATE

    // ส่งข้อมูล user กลับ
    $stmt = $db->prepare(
        'SELECT id, email, display_name, balance, created_at FROM users WHERE id = ? LIMIT 1'
    );
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
