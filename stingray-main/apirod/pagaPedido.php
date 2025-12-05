<?php
header("Content-Type: text/plain");
include("../conexao.php");

$token = $_GET["token"] ?? "";
$idPedido = $_GET["idPedido"] ?? 0;

// valida token
$check = $conn->prepare("SELECT id FROM users WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo "NEGADO"; exit; }

$idUser = $res->fetch_assoc()["id"];

// atualiza status
$sql = $conn->prepare("UPDATE orders SET status='processando' WHERE id=? AND user_id=?");
$sql->bind_param("ii",$idPedido,$idUser);
$sql->execute();

echo ($sql->affected_rows > 0) ? "OK" : "ERRO";
?>
