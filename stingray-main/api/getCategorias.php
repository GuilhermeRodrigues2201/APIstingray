<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) { 
    die(json_encode(["erro" => "ERRO_CON"]));
}

$categorias = ["gabinetes", "processadores", "perifericos", "acessorios"];

$lista = [];
foreach ($categorias as $cat) {
    $lista[] = ["name" => $cat];
}

echo json_encode($lista);

$conn->close();
?>
