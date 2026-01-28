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

$id          = isset($input['id']) ? (int)$input['id'] : 0;
$gameName    = trim($input['game_name'] ?? '');
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$price       = isset($input['price']) ? (float)$input['price'] : 0.0;
$platform    = trim($input['platform'] ?? '');
$rank        = trim($input['rank'] ?? '');
$imageUrl    = isset($input['image_url']) ? trim($input['image_url']) : null;
$imageData   = $input['image_data'] ?? null; // base64 ถ้ามี

if ($id <= 0 || !$gameName || !$title || !$description) {
    http_response_code(400);
    echo json_encode(['error' => 'id, game_name, title, description required']);
    exit;
}

try {
    $db = get_db();

    // กรณีส่ง base64 มาจะเซฟไฟล์ใหม่แล้วตั้ง image_url เป็น path
    if ($imageData !== null && $imageData !== '') {
        $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
        $binary = base64_decode($imageData);
        if ($binary === false) {
            http_response_code(400);
            echo json_encode(['error' => 'invalid image_data']);
            exit;
        }

        $uploadDir = __DIR__ . '/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'post_' . $id . '_' . time() . '.jpg';
        $filePath = $uploadDir . '/' . $fileName;
        file_put_contents($filePath, $binary);

        // เก็บ path ที่จะใช้ใน frontend (เช่น "uploads/xxx.jpg")
        $imageUrl = 'uploads/' . $fileName;
    } else {
        // ถ้าไม่มี image_data แต่มี image_url ส่งมา
        if ($imageUrl === null || $imageUrl === '') {
            $imageUrl = '-';
        }
    }

    $stmt = $db->prepare(
        'UPDATE posts
         SET game_name = ?, title = ?, description = ?, price = ?,
             image_url = ?, platform = ?, rank = ?
         WHERE id = ?'
    );
    $stmt->execute([
        $gameName,
        $title,
        $description,
        $price,
        $imageUrl,
        $platform,
        $rank,
        $id,
    ]);

    echo json_encode(['success' => true, 'image_url' => $imageUrl]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage(),
    ]);
}
