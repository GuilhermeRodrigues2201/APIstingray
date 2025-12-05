<?php
header("Content-Type: application/json");
include("../conexao.php");

// aceita GET e POST
$token = $_POST["token"] ?? $_GET["token"] ?? "";
$carrinho = $_POST["carrinho"] ?? $_GET["carrinho"] ?? "";

// valida usuário
$check = $conn->prepare("SELECT id FROM users WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode(["status"=>"ERRO_TOKEN"]); exit; }

$user_id = $res->fetch_assoc()["id"];

// cria pedido
$stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, 0, 'pendente')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_id = $stmt->insert_id;

$items = json_decode($carrinho, true);

if (!is_array($items)) {
    echo json_encode(["status"=>"ERRO_CARRINHO"]);
    exit;
}

foreach ($items as $i) {

    $idProd = $i["idProd"];  // ← corrigido
    $qtd = $i["quantidade"];

    $q = $conn->prepare("SELECT price, quantity FROM products WHERE id=?");
    $q->bind_param("i", $idProd);
    $q->execute();
    $r = $q->get_result()->fetch_assoc();

    if (!$r) continue;

    $preco = $r["price"];
    $novoEst = $r["quantity"] - $qtd;

    // salva item
    $ins = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?,?,?,?)
    ");
    $ins->bind_param("iiid", $order_id, $idProd, $qtd, $preco);
    $ins->execute();

    // atualiza estoque
    $up = $conn->prepare("UPDATE products SET quantity=? WHERE id=?");
    $up->bind_param("ii", $novoEst, $idProd);
    $up->execute();
}

// total
$sql_total = "SELECT SUM(quantity * price) AS total
              FROM order_items
              WHERE order_id = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $order_id);
$stmt_total->execute();
$total = $stmt_total->get_result()->fetch_assoc()["total"] ?? 0;

$sql_update_total = "UPDATE orders SET total=? WHERE id=?";
$stmt_update_total = $conn->prepare($sql_update_total);
$stmt_update_total->bind_param("di", $total, $order_id);
$stmt_update_total->execute();

echo json_encode([
    "status" => "OK",
    "idPedido" => $order_id,
    "total" => $total
]);
?>
