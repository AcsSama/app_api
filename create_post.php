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
$rawBody = file_get_contents('php://input');
error_log("CREATE_POST raw body len=" . strlen($rawBody));
error_log("CREATE_POST raw body preview=" . substr($rawBody, 0, 400));

$input = json_decode($rawBody, true);
if ($input === null) {
    error_log("CREATE_POST json_decode error: " . json_last_error_msg());
}

// อ่านค่าจาก JSON
$userId      = isset($input['user_id']) ? (int)$input['user_id'] : 0;
$gameName    = trim($input['game_name'] ?? '');
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$price       = isset($input['price']) ? (float)$input['price'] : 0.0;
$platform    = trim($input['platform'] ?? '');
$rank        = trim($input['rank'] ?? '');

// base64 image data (อาจเป็น null)
$imageData   = $input['image_data'] ?? null;
$imageUrl    = '-';

// ถ้ามี image_data ให้ decode แล้วเซฟลงโฟลเดอร์ uploads/
if ($imageData) {
    error_log("CREATE_POST: received image_data length=" . strlen($imageData));
    $imageData = str_replace(' ', '+', $imageData);

    if (strpos($imageData, 'base64,') !== false) {
        $parts = explode('base64,', $imageData);
        $imageData = end($parts);
    }

    $binary = base64_decode($imageData);

    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = 'img_' . time() . '_' . rand(1000, 9999) . '.jpg';
    $relativePath = 'uploads/' . $fileName;
    $fullPath     = __DIR__ . '/' . $relativePath;

    file_put_contents($fullPath, $binary);

    error_log("CREATE_POST: saved image to $relativePath");

    $imageUrl = $relativePath;
} elseif (!empty($input['image_url'])) {
    // fallback ถ้าอยากรองรับลิงก์จากช่อง text อยู่
    $imageUrl = trim($input['image_url']);
    error_log("CREATE_POST: using image_url=$imageUrl");
} else {
    error_log("CREATE_POST: no image provided");
}

// validate เบื้องต้น
if ($userId <= 0 || $gameName === '' || $title === '' || $description === '') {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, game_name, title, description required']);
    exit;
}

try {
    $db = get_db();

    // INSERT post
    $stmt = $db->prepare('
        INSERT INTO posts (user_id, game_name, title, description, price, status, image_url, platform, rank)
        VALUES (?, ?, ?, ?, ?, "active", ?, ?, ?)
    ');
    $stmt->execute([$userId, $gameName, $title, $description, $price, $imageUrl, $platform, $rank]);

    $id = (int)$db->lastInsertId();

    // ดึงข้อมูลแถวที่เพิ่ง insert ให้ format เหมือน get_posts.php
    $stmt = $db->prepare('
        SELECT p.id,
               p.game_name,
               p.title,
               p.description,
               p.price,
               p.status,
               p.image_url,
               p.platform,
               p.rank,
               p.created_at,
               u.display_name AS seller_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.id = ?
        LIMIT 1
    ');
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
