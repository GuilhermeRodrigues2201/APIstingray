<?php
header('Content-Type: application/json; charset=utf-8');
include("../conexao.php");

$token = $_GET["token"] ?? "";
if ($token == "") { echo "NEGADO"; exit; }

$check = $conn->prepare("SELECT id FROM users WHERE token=?");
$check->bind_param("s", $token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo "NEGADO"; exit; }

$q = $conn->query("SELECT id, name, description, price, quantity, image_url, status
                   FROM products WHERE quantity > 0 ORDER BY name ASC");

$lista = [];
while ($r = $q->fetch_assoc()) $lista[] = $r;

echo json_encode($lista);
?>
