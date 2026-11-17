<?php
session_start();
require_once "../autoload.php";

$mail = trim($_POST['mail'] ?? '');
$apelido = trim($_POST['apelido'] ?? '');
$senha = $_POST['senha'] ?? '';

try {
    $usuario = new Usuario($mail, $senha, $apelido, 0, 0);

    if ($usuario->cadaSu()) {
        header("Location: ../Views/login.html");
        exit;
    }
} catch (PDOException $e) {
    switch ($e->getCode()) {
        case '23000':
            echo "Este e-mail já está cadastrado.";
            break;
        default:
            error_log($e->getMessage());
            echo "Ocorreu um erro inesperado no cadastro.";
            break;
    }
}
?>