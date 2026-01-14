<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type'); // <-- บรรทัดนี้สำคัญต้องใส่
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // <-- บรรทัดนี้สำคัญต้องใส่

require_once 'db.php';

try {
    $db = get_db();

    $stmt = $db->query("
        SELECT p.id,
               p.game_name,
               p.title,
               p.description,
               p.price,
               p.status,
               p.image_url,
               p.created_at,
               u.display_name AS seller_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC
        LIMIT 100
    ");

    $posts = $stmt->fetchAll();
    echo json_encode($posts);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
