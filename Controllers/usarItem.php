<?php
session_start();

require_once __DIR__ . '/../autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['idsu'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não está logado.']);
    exit;
}

$iditem = filter_input(INPUT_POST, 'iditem', FILTER_VALIDATE_INT);


$idsu = (int) $_SESSION['idsu'];

$item = new Item_su($idsu, $iditem, 0);

// Consome uma unidade
if ($item->usarItem()) {
    echo json_encode(['mensagem' => 'Item utilizado com sucesso.']);
} else {
    echo json_encode(['erro' => 'Você não possui esse item.']);
}
?>