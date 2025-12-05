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

$nome  = $_GET["nome"]  ?? "";
$email = $_GET["email"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($nome == "" || $email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$nome  = mysqli_real_escape_string($conn, $nome);
$email = mysqli_real_escape_string($conn, $email);

// verifica email duplicado
$ver = $conn->query("SELECT id FROM usuarios WHERE email='$email' LIMIT 1");
if ($ver->num_rows > 0) {
    echo "EMAIL_EXISTE";
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$hash')";

if ($conn->query($sql)) {

    $idNovo = $conn->insert_id;

    $token = bin2hex(random_bytes(25));
    $conn->query("UPDATE usuarios SET token='$token' WHERE id=$idNovo");

    echo "OK|" . $token . "|" . $nome;

} else {
    echo "ERRO_INSERT";
}

$conn->close();
?>
