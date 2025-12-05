<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";
$idPedido = $_GET["idPedido"] ?? 0;

$check = $conn->prepare("SELECT id FROM users WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode(["status"=>"NEGADO"]); exit; }

$idUser = $res->fetch_assoc()["id"];

$ver = $conn->prepare("
    SELECT * FROM orders
    WHERE id=? AND user_id=?
");
$ver->bind_param("ii",$idPedido,$idUser);
$ver->execute();
$pedido = $ver->get_result()->fetch_assoc();

if (!$pedido) {
    echo json_encode(["status"=>"NEGADO"]);
    exit;
}

$sql = "
SELECT oi.*, p.name, p.image_url
FROM order_items oi
JOIN products p ON p.id = oi.product_id
WHERE order_id = ?
";
$stmt = $conn->prepare($sql);
stmt->bind_param("i",$idPedido);
stmt->execute();
$resItens = $stmt->get_result();

$itens = [];
while ($r = $resItens->fetch_assoc()) $itens[] = $r;

echo json_encode([
    "status"=>"OK",
    "pedido"=>$pedido,
    "itens"=>$itens
]);
?>
