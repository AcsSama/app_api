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

// รับ JSON body
$input = json_decode(file_get_contents('php://input'), true);

// *** แก้ตรงนี้: ใช้ $input ไม่ใช่ $data ***
$userId      = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$gameName    = trim($input['game_name'] ?? '');
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$price       = isset($input['price']) ? (float)$input['price'] : 0.0;

// ตอนนี้ยังไม่ทำอัปโหลดรูป image_url = NULL ไปก่อน
$imageUrl    = null;

// validate เบื้องต้น
if ($userId <= 0 || $gameName === '' || $title === '' || $description === '') {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, game_name, title, description required']);
    exit;
}

try {
    $db = get_db();

    // INSERT post
    $stmt = $db->prepare("
        INSERT INTO posts (user_id, game_name, title, description, price, status, image_url)
        VALUES (?, ?, ?, ?, ?, 'active', ?)
    ");
    $stmt->execute([
        $userId,
        $gameName,
        $title,
        $description,
        $price,
        $imageUrl, // ตอนนี้เป็น NULL
    ]);

    $id = (int)$db->lastInsertId();

    // ดึงข้อมูลแถวที่เพิ่ง insert ให้ format เหมือน get_posts.php
    $stmt = $db->prepare("
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
        WHERE p.id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($post);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
