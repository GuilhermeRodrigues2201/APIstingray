<?php
header("Content-Type: text/plain; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) { 
    echo "ERRO_CON";
    exit;
}

$email = $_GET["email"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$email = mysqli_real_escape_string($conn, $email);

// tenta em usuarios
$sql = "SELECT * FROM usuarios WHERE email='$email' LIMIT 1";
$result = $conn->query($sql);

// se não achou: tenta em usuariosgestao
if ($result->num_rows == 0) {
    $sql = "SELECT * FROM usuariosgestao WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);
}

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($senha, $user["senha"])) {

        $token = bin2hex(random_bytes(25));

        // detecta tabela fonte
        if (isset($user["cargo"])) {
            $conn->query("UPDATE usuariosgestao SET token='$token' WHERE id=" . $user["id"]);
        } else {
            $conn->query("UPDATE usuarios SET token='$token' WHERE id=" . $user["id"]);
        }

        echo "TOKEN:$token";

    } else {
        echo "SENHA_ERRADA";
    }

} else {
    echo "EMAIL_NAO_ENCONTRADO";
}

$conn->close();
?>
