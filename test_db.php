<?php
require_once 'db.php';
header('Content-Type: text/plain');

try {
    $db = get_db();
    echo "DB OK\n";

    $stmt = $db->query("SELECT COUNT(*) AS c FROM users");
    $row = $stmt->fetch();
    echo "users count = " . $row['c'] . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
