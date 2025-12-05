<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/plain; charset=utf-8");

$categoria = $_GET["nomeCat"] ?? "";

if ($categoria == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$categoria = strtolower(trim($categoria));

$categoriasValidas = ["gabinetes", "processadores", "perifericos", "acessorios"];

if (!in_array($categoria, $categoriasValidas)) {
    echo "CATEGORIA_INVALIDA";
    exit;
}

echo "OK"; // categoria é válida; não há tabela categories, então só retorna OK
?>
