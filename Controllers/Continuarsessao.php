<?php
session_start();

require_once "../Models/conexao.php";
require_once "../autoload.php";

// Verifica se existe um token salvo no navegador
if (!isset($_COOKIE['monstrobolso_token'])) {
    header("Location: criarSessao.php");
    exit;
}

$token = $_COOKIE['monstrobolso_token'];

// Procura o jogador pelo token
$usuario = new Usuario();

if ($usuario->entrar($token)) {

    // Recupera os dados do jogador
    $_SESSION['idsu'] = $usuario->getIdsu();
    $_SESSION['apelido'] = $usuario->getApelido();

    header("Location: ../Views/Inicio.html");
    exit;

} else {

    // Token inválido: inicia a criação de uma nova sessão
    header("Location: criarSessao.php");
    exit;
}
?>