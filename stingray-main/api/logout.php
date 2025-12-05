<?php
header("Content-Type: text/plain; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) { 
    echo "ERRO_CON";
    exit;
}

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

$token = mysqli_real_escape_string($conn, $token);

$conn->query("UPDATE usuarios SET token='' WHERE token='$token'");
$conn->query("UPDATE usuariosgestao SET token='' WHERE token='$token'");

echo "DESLOGADO";

$conn->close();
?>
