
<?php
session_start();
require_once "../Models/conexao.php";
require_once "../autoload.php";

$mail = trim($_POST['mail'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($mail === '' || $senha === '') {
    header("Location: ../login.html?erro=campos");
    exit;
}

$usuario = new Usuario($mail, $senha);

if ($usuario->Login()) {
    $_SESSION['logado'] = true;
    $_SESSION['idsu'] = $usuario->getIdsu();
    $_SESSION['apelido'] = $usuario->getApelido();

    header("Location: fablica.php");
    exit;
}

header("Location: ../login.html?erro=credenciais");
exit;
?>