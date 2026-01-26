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

$postId   = isset($input['post_id'])   ? (int)$input['post_id']   : 0;
$buyerId  = isset($input['buyer_id'])  ? (int)$input['buyer_id']  : 0;
$senderId = isset($input['sender_id']) ? (int)$input['sender_id'] : 0;
$text     = isset($input['text'])      ? trim($input['text'])     : '';

if ($postId <= 0 || $buyerId <= 0 || $senderId <= 0 || $text === '') {
    http_response_code(400);
    echo json_encode(['error' => 'post_id, buyer_id, sender_id, text required']);
    exit;
}

try {
    $db = get_db();

    // insert
    $stmt = $db->prepare("
        INSERT INTO messages (post_id, buyer_id, sender_id, text, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$postId, $buyerId, $senderId, $text]);

    $id = (int)$db->lastInsertId();

    // ดึงแถวที่เพิ่ง insert พร้อม sender_name
    $stmt2 = $db->prepare("
        SELECT m.id,
               m.post_id,
               m.buyer_id,
               m.sender_id,
               m.text,
               m.created_at,
               u.display_name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.id = ?
        LIMIT 1
    ");
    $stmt2->execute([$id]);
    $message = $stmt2->fetch(PDO::FETCH_ASSOC);

    echo json_encode($message);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
