<?php
session_start();

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {
    $nomeCookie = 'monstrob_token';

    // Verifica se o navegador já possui um token
    $token = $_COOKIE[$nomeCookie] ?? null;

    $usuario = new Usuario();

    $novoUsuario = false;

    // Tenta reconhecer um jogador existente
    if (is_string($token) && $token !== '') {

        $reconheceu = $usuario->entrar($token);

    } else {

        $reconheceu = false;

    }

    // Se não encontrou um jogador, cria um novo
    if (!$reconheceu) {

        // Gera um token exclusivo e aleatório
        $token = bin2hex(random_bytes(32));

        $usuario = new Usuario('Jogador', 0, 0);

        // Cadastra o jogador no banco
        if (!$usuario->criarUsuario($token)) {
            throw new RuntimeException(
                'Não foi possível criar o jogador.'
            );
        }

        $novoUsuario = true;
    }

    // Recupera a identificação do jogador
    $idsu = (int) $usuario->getIdsu();

    if ($idsu <= 0) {
        throw new RuntimeException(
            'Não foi possível identificar o jogador.'
        );
    }

    // Renova o identificador da sessão quando necessário
    if (
        !isset($_SESSION['idsu']) ||
        (int) $_SESSION['idsu'] !== $idsu
    ) {
        session_regenerate_id(true);
    }

    // Guarda os dados do jogador na sessão PHP
    $_SESSION['idsu'] = $idsu;
    $_SESSION['apelido'] = $usuario->getApelido();

    // Configura o cookie para durar um ano
    $opcoesCookie = [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'secure' => (
            isset($_SERVER['HTTPS']) &&
            $_SERVER['HTTPS'] !== 'off'
        ),
        'httponly' => true,
        'samesite' => 'Lax'
    ];

    // Salva ou renova o token no navegador
    if (!setcookie($nomeCookie, $token, $opcoesCookie)) {
        throw new RuntimeException(
            'Não foi possível salvar o token no navegador.'
        );
    }

    // Retorna os dados necessários para o JavaScript
    echo json_encode([
        'ok' => true,
        'novoUsuario' => $novoUsuario,
        'usuario' => [
            'idsu' => $idsu,
            'apelido' => $usuario->getApelido(),
            'dindin' => $usuario->getDindin(),
            'nivel' => $usuario->getNivel()
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    error_log('Erro no UserController: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'erro' => 'Não foi possível iniciar o jogador.'
    ], JSON_UNESCAPED_UNICODE);

}

?>