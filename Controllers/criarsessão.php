<?php
session_start();

require_once "../Models/conexao.php";
require_once "../autoload.php";

// Verifica se já existe um token no navegador
if (isset($_COOKIE['monstrobolso_token'])) {

    $usuario = new Usuario();

    if ($usuario->entrar($_COOKIE['monstrobolso_token'])) {

        $_SESSION['idsu'] = $usuario->getIdsu();
        $_SESSION['apelido'] = $usuario->getApelido();

        header("Location: ../Views/Inicio.html");
        exit;
    }
}

// Cria um novo token
$token = bin2hex(random_bytes(32));

// Cria um novo jogador
$usuario = new Usuario();

if ($usuario->criarUsuario($token)) {

    // Guarda o token no navegador por um ano
    setcookie('monstrobolso_token', $token, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]);

    // Guarda os dados do jogador na sessão
    $_SESSION['idsu'] = $usuario->getIdsu();
    $_SESSION['apelido'] = $usuario->getApelido();

    header("Location: ../Views/Inicio.html");
    exit;

} else {
    echo "Não foi possível criar o jogador.";
}
?>