<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";

$check = $conn->prepare("SELECT id FROM users WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode([]); exit; }

$idUser = $res->fetch_assoc()["id"];

$q = $conn->query("
    SELECT id, total, status, created_at
    FROM orders
    WHERE user_id=$idUser
    ORDER BY created_at DESC
");

$dados = [];
while ($r = $q->fetch_assoc()) $dados[] = $r;

echo json_encode($dados);
?>
