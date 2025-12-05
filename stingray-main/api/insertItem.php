<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) {
    die("Falha de conexão: " . $conn->connect_error);
}

$nome = $_GET['nome'] ?? "";
$quantidade = $_GET['quantidade'] ?? "";
$categoria = $_GET['categoria'] ?? "acessorios"; // categorias: gabinetes, processadores, perifericos, acessorios
$marca = $_GET['marca'] ?? "Outros";

if ($nome == "" || $quantidade == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nome = mysqli_real_escape_string($conn, $nome);
$quantidade = intval($quantidade);
$categoria = mysqli_real_escape_string($conn, $categoria);
$marca = mysqli_real_escape_string($conn, $marca);

$sql = "INSERT INTO produtos (nome_produto, quantidade_estoque, categoria, marca)
        VALUES ('$nome', $quantidade, '$categoria', '$marca')";

if ($conn->query($sql) === TRUE) echo "OK";
else echo "ERRO";

$conn->close();
?>
