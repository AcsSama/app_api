<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

$userId      = intval($input['user_id'] ?? 0);
$gameName    = trim($input['game_name'] ?? '');
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$price       = $input['price'] ?? 0;
$imageUrl    = trim($input['image_url'] ?? '');

if (!$userId || !$gameName || !$title || !$description) {
    http_response_code(400);
    echo json_encode(['error' => 'user_id, game_name, title, description required']);
    exit;
}

try {
    $db = get_db();

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
        $imageUrl ?: null,
    ]);

    $id = (int)$db->lastInsertId();

    echo json_encode([
        'id'         => $id,
        'user_id'    => $userId,
        'game_name'  => $gameName,
        'title'      => $title,
        'description'=> $description,
        'price'      => $price,
        'status'     => 'active',
        'image_url'  => $imageUrl ?: null,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error', 'message' => $e->getMessage()]);
}
