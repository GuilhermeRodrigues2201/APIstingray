<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "stingray");
if ($conn->connect_error) { 
    echo "NEGADO";
    exit;
}

$token = $_GET["token"] ?? "";
if ($token == "") {
    echo "NEGADO";
    exit;
}

// valida token em ambas tabelas
$t = mysqli_real_escape_string($conn, $token);
$r1 = $conn->query("SELECT id FROM usuarios WHERE token='$t' LIMIT 1");
$r2 = $conn->query("SELECT id FROM usuariosgestao WHERE token='$t' LIMIT 1");

if ($r1->num_rows == 0 && $r2->num_rows == 0) {
    echo "NEGADO";
    exit;
}

if (isset($_GET["categoria"])) {
    $cat = mysqli_real_escape_string($conn, $_GET["categoria"]);
    $sql = "SELECT * FROM produtos WHERE categoria = '$cat' ORDER BY nome_produto ASC";
} else {
    $sql = "SELECT * FROM produtos ORDER BY nome_produto ASC";
}

$result = $conn->query($sql);

$lista = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $lista[] = $row;
    }
}

echo json_encode($lista);
$conn->close();
?>
