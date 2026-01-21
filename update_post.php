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
$imageUrl    = isset($input['image_url']) && trim($input['image_url']) !== ''
    ? trim($input['image_url'])
    : '-';

if ($id <= 0 || !$gameName || !$title || !$description) {
    http_response_code(400);
    echo json_encode(['error' => 'id, game_name, title, description required']);
    exit;
}

try {
    $db = get_db();

    $stmt = $db->prepare('UPDATE posts SET game_name = ?, title = ?, description = ?, price = ?, image_url = ?, platform = ?, rank = ? WHERE id = ?');
    $stmt->execute([$gameName, $title, $description, $price, $imageUrl, $platform, $rank, $id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'server error',
        'message' => $e->getMessage(),
    ]);
}
