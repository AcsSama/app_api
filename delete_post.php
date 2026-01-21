<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$postId = isset($input['post_id']) ? (int)$input['post_id'] : 0;

if ($postId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id required']);
    exit;
}

try {
    $db = get_db();

    // ลบจริง ๆ หรือจะ soft delete ก็ได้
    $stmt = $db->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$postId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage(),
    ]);
}
