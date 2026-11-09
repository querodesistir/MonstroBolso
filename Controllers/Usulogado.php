<?php
//tenho que ver se funciona
session_start();
require_once "../Models/conexao.php";
require_once "../autoload.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Se a sessão do usuário existir, logado = true. Se não, false.
if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'logado' => true, 
        'apelido' => $_SESSION['usuario_apelido']
    ]);
} else {
    echo json_encode([
        'logado' => false
    ]);
}
exit;
?>