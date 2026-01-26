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

$data = json_decode(file_get_contents('php://input'), true);

$postId  = isset($data['post_id'])  ? (int)$data['post_id']  : 0;
$buyerId = isset($data['buyer_id']) ? (int)$data['buyer_id'] : 0;

if ($postId <= 0 || $buyerId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id and buyer_id required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare("
        DELETE FROM messages
        WHERE post_id = ? AND buyer_id = ?
    ");
    $stmt->execute([$postId, $buyerId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage(),
    ]);
}
