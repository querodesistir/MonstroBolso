
<?php
session_start();
require_once "../Models/conexao.php";
require_once "../autoload.php";

$mail = trim($_POST['mail'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($mail === '' || $senha === '') {
    header("Location: ../Views/login.html?erro=campos");
    exit;
}


if ($usuario->Login()) {
    $_SESSION['logado'] = true;
    $_SESSION['idsu'] = $usuario->getIdsu();
    $_SESSION['apelido'] = $usuario->getApelido();

    header("Location: ../Views/Inicio.html");
    exit;
}

header("Location: ../Views/login.html?erro=credenciais");
exit;
?>