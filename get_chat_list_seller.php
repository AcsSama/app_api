<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: GET, OPTIONS');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0; // seller id
if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'user_id required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare("
        SELECT
            m.post_id,
            m.buyer_id,
            p.title          AS post_title,
            bu.display_name  AS buyer_name,
            MAX(m.created_at) AS last_time,
            SUBSTRING_INDEX(
                (SELECT mm.text
                 FROM messages mm
                 WHERE mm.post_id = m.post_id
                   AND mm.buyer_id = m.buyer_id
                 ORDER BY mm.created_at DESC
                 LIMIT 1),
                '\n', 1
            ) AS last_text
        FROM messages m
        JOIN posts p      ON m.post_id = p.id
        JOIN users bu     ON m.buyer_id = bu.id
        WHERE p.user_id = ?             -- คนขายที่ล็อกอิน
        GROUP BY m.post_id, m.buyer_id, p.title, bu.display_name
        ORDER BY last_time DESC
    ");
    $stmt->execute([$userId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage(),
    ]);
}
