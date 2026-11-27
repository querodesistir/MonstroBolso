
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

function responderJson(array $dados, int $status = 200): void {
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

try {

    $token = $_COOKIE['monstrob_token'] ?? '';

    if ($token === '') {
        $token = bin2hex(random_bytes(32));
    }

    $usuario = new Usuario();

    if (!$usuario->entrar($token)) {

        $usuario = new Usuario('Jogador', 0, 0);

        if (!$usuario->criarUsuario($token)) {
            responderJson([
                'ok' => false,
                'erro' => 'Não foi possível criar o jogador.'
            ], 500);
        }
    }

    $idsu = $usuario->getIdsu();

    if (!$idsu) {
        responderJson([
            'ok' => false,
            'erro' => 'Não foi possível identificar o jogador.'
        ], 500);
    }

    session_regenerate_id(true);

    $_SESSION['idsu'] = $idsu;
    $_SESSION['apelido'] = $usuario->getApelido();

    setcookie('monstrob_token', $token, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);

    responderJson([
        'ok' => true,
        'idsu' => $idsu,
        'apelido' => $usuario->getApelido(),
        'dindin' => $usuario->getDindin(),
        'nivel' => $usuario->getNivel()
    ]);

} catch (Throwable $e) {

    error_log($e->getMessage());

    responderJson([
        'ok' => false,
        'erro' => 'Não foi possível iniciar a sessão.',
        'detalhe' => $e->getMessage()
    ], 500);
}
?>