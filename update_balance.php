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

try {
    // ใช้ฟังก์ชันจาก db.php
    $pdo = get_db();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connect failed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['user_id']) || !isset($data['delta'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user_id or delta']);
    exit;
}

$userId = (int)$data['user_id'];
$delta  = (float)$data['delta']; // บวก = เติม, ลบ = ถอน

try {
    // อัปเดต balance: balance = balance + delta
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$delta, $userId]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // ดึงยอดล่าสุดกลับไปให้แอพ
    $stmt2 = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt2->execute([$userId]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'balance' => (float)$row['balance'],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Update failed', 'detail' => $e->getMessage()]);
}
