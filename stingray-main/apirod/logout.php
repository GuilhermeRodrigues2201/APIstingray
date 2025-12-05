<?php
header("Content-Type: text/plain; charset=utf-8");
include("../conexao.php");

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

$sql = "UPDATE users SET token=NULL WHERE token='$token'";
$conn->query($sql);

echo "DESLOGADO";

$conn->close();
?>
