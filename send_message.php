<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // <-- บรรทัดนี้สำคัญต้องใส่

require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

$postId   = intval($input['post_id']   ?? 0);
$buyerId  = intval($input['buyer_id']  ?? 0);
$senderId = intval($input['sender_id'] ?? 0);
$text     = trim($input['text'] ?? '');

if (!$postId || !$buyerId || !$senderId || !$text) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id, buyer_id, sender_id, text required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare("
        INSERT INTO messages (post_id, buyer_id, sender_id, text)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$postId, $buyerId, $senderId, $text]);

    $id = (int)$db->lastInsertId();

    echo json_encode([
        'id'         => $id,
        'post_id'    => $postId,
        'buyer_id'   => $buyerId,
        'sender_id'  => $senderId,
        'text'       => $text,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
