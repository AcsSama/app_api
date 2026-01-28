<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

$buyerId = isset($input['buyer_id']) ? (int)$input['buyer_id'] : 0;
$postId  = isset($input['post_id'])  ? (int)$input['post_id']  : 0;

if ($buyerId <= 0 || $postId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'buyer_id and post_id required']);
    exit;
}

try {
    $db = get_db();

    // 1) ดึงโพสต์
    $stmt = $db->prepare(
        "SELECT id, user_id, price, status 
         FROM posts 
         WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        http_response_code(404);
        echo json_encode(['error' => 'Post not found']);
        exit;
    }

    if ($post['status'] !== 'active') {
        http_response_code(400);
        echo json_encode(['error' => 'โพสต์นี้ถูกขายไปแล้วหรือไม่พร้อมขาย']);
        exit;
    }

    $sellerId = (int)$post['user_id'];
    $price    = (float)$post['price'];

    if ($sellerId === $buyerId) {
        http_response_code(400);
        echo json_encode(['error' => 'ไม่สามารถซื้อโพสต์ของตัวเองได้']);
        exit;
    }

    // 2) ดึง balance ผู้ซื้อ
    $stmt = $db->prepare("SELECT balance FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$buyerId]);
    $buyer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$buyer) {
        http_response_code(404);
        echo json_encode(['error' => 'Buyer not found']);
        exit;
    }

    $buyerBalance = (float)$buyer['balance'];
    if ($buyerBalance < $price) {
        http_response_code(400);
        echo json_encode(['error' => 'ยอดเงินไม่เพียงพอ']);
        exit;
    }

    // 3) หักเงิน buyer, เติมให้ seller, เปลี่ยนสถานะโพสต์, บันทึก orders
    $db->beginTransaction();

    // หัก buyer
    $stmt = $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$price, $buyerId]);

    // เติม seller (ถ้าต้องการ)
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$price, $sellerId]);

    // เปลี่ยนสถานะโพสต์
    $stmt = $db->prepare("UPDATE posts SET status = 'soldout' WHERE id = ?");
    $stmt->execute([$postId]);

    // บันทึกคำสั่งซื้อ
    $stmt = $db->prepare(
        "INSERT INTO orders (buyer_id, post_id, price, status)
         VALUES (?, ?, ?, 'completed')"
    );
    $stmt->execute([$buyerId, $postId, $price]);

    // ดึงยอด buyer ล่าสุด
    $stmt = $db->prepare("SELECT balance FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$buyerId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $db->commit();

    echo json_encode([
        'success'      => true,
        'new_balance'  => (float)$row['balance'],
    ]);
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
