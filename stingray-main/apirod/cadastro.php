<?php
header("Content-Type: text/plain; charset=utf-8");
include("../conexao.php");

$name = $_GET["nome"] ?? "";
$email = $_GET["email"] ?? "";
$senha = $_GET["senha"] ?? "";
$endereco = $_GET["endereco"] ?? "";
$telefone = $_GET["telefone"] ?? "";

if ($name == "" || $email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$name  = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);

$verifica = $conn->query("SELECT id FROM users WHERE email='$email' LIMIT 1");

if ($verifica->num_rows > 0) {
    echo "USER_EXISTE";
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password, address, phone)
        VALUES ('$name', '$email', '$hash', '$endereco', '$telefone')";

if ($conn->query($sql)) {

    $idNovo = $conn->insert_id;
    $token = hash("sha256", uniqid() . rand() . microtime());

    $conn->query("UPDATE users SET token='$token' WHERE id=$idNovo");

    echo "OK|" . $token . "|" . $name;

} else {
    echo "ERRO_INSERT";
}

$conn->close();
?>
