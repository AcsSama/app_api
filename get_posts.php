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
    $db = get_db();

    $stmt = $db->query('
        SELECT p.id,
               p.game_name,
               p.title,
               p.description,
               p.price,
               p.status,
               p.image_url,
               p.image_base64,
               p.platform,
               p.rank,
               p.created_at,
               u.display_name AS seller_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = "active"
        ORDER BY p.created_at DESC
        LIMIT 100
    ');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($posts)) {
        error_log("GET_POSTS first=" . print_r($posts[0], true));
    }


    echo json_encode($posts);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
