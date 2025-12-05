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

$idItem = $_GET['idItem'] ?? "";

if ($idItem == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$idItem = intval($idItem);

$sql = "DELETE FROM produtos WHERE id_produto = $idItem";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
