<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "athi_011";

$conn = mysqli_connect($host, $user, $pass, $db);
mysqli_set_charset($conn, "utf8");

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *, ngrok-skip-browser-warning, content-type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$sql = "SELECT * FROM athi_011";
$result = $conn->query($sql);

$data = array();
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);