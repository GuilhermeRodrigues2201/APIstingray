<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) {
    echo "ERRO_CON";
    exit;
}

$categoria = $_GET["categoria"] ?? "";

if ($categoria == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$categoria = mysqli_real_escape_string($conn, $categoria);

$sql = "DELETE FROM produtos WHERE categoria = '$categoria'";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
