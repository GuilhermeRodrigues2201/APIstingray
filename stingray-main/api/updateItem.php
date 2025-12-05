<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) {
    die("Falha de conexão: " . $conn->connect_error);
}

$idItem = intval($_GET['idItem']);
$nome = $_GET['nome'] ?? "";
$quantidade = $_GET['quantidade'] ?? "";

if ($nome == "" || $quantidade == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nome = mysqli_real_escape_string($conn, $nome);
$quantidade = intval($quantidade);

$sql = "UPDATE produtos 
        SET nome_produto = '$nome', quantidade_estoque = $quantidade
        WHERE id_produto = $idItem";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
