<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo json_encode(["status"=>"ERRO"]);
    exit;
}

$check = $conn->prepare("SELECT id, name, email, address, phone FROM users WHERE token=?");
$check->bind_param("s", $token);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    echo json_encode(["status"=>"ERRO"]);
    exit;
}

$u = $res->fetch_assoc();
$id = $u["id"];

$sqlPed = $conn->prepare("
    SELECT id, total, status, created_at
    FROM orders
    WHERE user_id=?
    ORDER BY created_at DESC
");
$sqlPed->bind_param("i", $id);
$sqlPed->execute();
$resPed = $sqlPed->get_result();

$listaPedidos = [];
while ($p = $resPed->fetch_assoc()) {
    $listaPedidos[] = $p;
}

echo json_encode([
    "status"  => "OK",
    "nome"    => $u["name"],
    "email"   => $u["email"],
    "endereco"=> $u["address"],
    "telefone"=> $u["phone"],
    "pedidos" => $listaPedidos
]);
?>
