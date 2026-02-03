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

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid JSON body']);
    exit;
}

$id          = isset($input['id']) ? (int)$input['id'] : 0;
$gameName    = trim($input['game_name'] ?? '');
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$price       = isset($input['price']) ? (float)$input['price'] : 0.0;
$platform    = trim($input['platform'] ?? '');
$rank        = trim($input['rank'] ?? '');

// เดิมใช้ image_url, ตอนนี้ให้รองรับ image_base64 ด้วย
$imageUrl    = isset($input['image_url']) ? trim($input['image_url']) : '-';
$imageData   = $input['image_data'] ?? null;     // base64 ถ้ามี
$imageBase64 = null;

if ($id <= 0 || $gameName === '' || $title === '' || $description === '') {
    http_response_code(400);
    echo json_encode(['error' => 'id, game_name, title, description required']);
    exit;
}

// ถ้ามี image_data ใหม่ส่งมา ให้แทนค่าลง image_base64
if (!empty($imageData)) {
    $imageBase64 = $imageData;
    // ถ้าใช้ base64 แล้วจะไม่ใช้ URL อีกก็ให้ตั้งเป็น "-"
    if ($imageUrl === '' || $imageUrl === null) {
        $imageUrl = '-';
    }
} else {
    // ไม่ได้ส่งรูปใหม่มา → ควรดึงค่าปัจจุบันจาก DB แล้วเก็บไว้เหมือนเดิม
    try {
        $db = get_db();
        $stmt = $db->prepare('SELECT image_url, image_base64 FROM posts WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if ($imageUrl === null || $imageUrl === '') {
                $imageUrl = $row['image_url'];
            }
            $imageBase64 = $row['image_base64'];
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'post not found']);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error'   => 'server error',
            'message' => $e->getMessage(),
        ]);
        exit;
    }
}

try {
    $db = isset($db) ? $db : get_db();

    $stmt = $db->prepare(
        'UPDATE posts
         SET game_name = ?,
             title = ?,
             description = ?,
             price = ?,
             image_url = ?,
             image_base64 = ?,
             platform = ?,
             rank = ?
         WHERE id = ?'
    );
    $stmt->execute([
        $gameName,
        $title,
        $description,
        $price,
        $imageUrl,
        $imageBase64,
        $platform,
        $rank,
        $id,
    ]);

    echo json_encode([
        'success'      => true,
        'image_url'    => $imageUrl,
        'image_base64' => $imageBase64,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'server error',
        'message' => $e->getMessage(),
    ]);
}
