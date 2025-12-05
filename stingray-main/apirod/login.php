<?php
header("Content-Type: text/plain; charset=utf-8");
session_start();
include("../conexao.php");

$email = $_GET["usuario"] ?? "";
$senha = $_GET["senha"] ?? "";

if ($email == "" || $senha == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    if (password_verify($senha, $user["password"])) {

        $token = bin2hex(random_bytes(25));
        $id = $user["id"];
        $conn->query("UPDATE users SET token='$token' WHERE id=$id");

        echo "TOKEN:$token";

    } else {
        echo "SENHA_ERRADA";
    }

} else {
    echo "EMAIL_NAO_ENCONTRADO";
}

$conn->close();
?>
