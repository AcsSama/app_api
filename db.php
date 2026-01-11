<?php
// db.php
require_once 'config.php';

function get_db() {
    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS;

    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO($dsn, $DB_USER, $DB_PASS, $options);
}
