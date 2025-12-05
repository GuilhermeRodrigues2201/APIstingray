<?php
header("Content-Type: text/plain; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) { 
    echo "NEGADO";
    exit;
}

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

$token = mysqli_real_escape_string($conn, $token);

$r1 = $conn->query("SELECT id FROM usuarios WHERE token='$token' LIMIT 1");
$r2 = $conn->query("SELECT id FROM usuariosgestao WHERE token='$token' LIMIT 1");

if ($r1->num_rows == 0 && $r2->num_rows == 0) {
    echo "NEGADO";
    exit;
}
?>
