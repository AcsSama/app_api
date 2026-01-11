<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$postId  = intval($_GET['post_id']  ?? 0);
$buyerId = intval($_GET['buyer_id'] ?? 0);

if (!$postId || !$buyerId) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id and buyer_id required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare("
        SELECT m.id,
               m.post_id,
               m.buyer_id,
               m.sender_id,
               m.text,
               m.created_at,
               u.display_name AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.post_id = ? AND m.buyer_id = ?
        ORDER BY m.created_at ASC
    ");
    $stmt->execute([$postId, $buyerId]);

    $messages = $stmt->fetchAll();
    echo json_encode($messages);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
