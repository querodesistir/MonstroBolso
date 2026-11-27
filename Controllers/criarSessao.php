
<?php

header('Content-Type: application/json; charset=utf-8');

try {

    session_start();

    require_once __DIR__ . "/../Models/conexao.php";
    require_once __DIR__ . "/../autoload.php";

    $nomeCookie = 'monstrob_token';

    $token = $_COOKIE[$nomeCookie] ?? null;

    $usuario = new Usuario();

    $novoUsuario = false;

    if (is_string($token) && $token !== '') {
        $reconheceu = $usuario->entrar($token);
    } else {
        $reconheceu = false;
    }

    if (!$reconheceu) {

        $token = bin2hex(random_bytes(32));

        $usuario = new Usuario('Jogador', 0, 0);

        if (!$usuario->criarUsuario($token)) {
            throw new RuntimeException(
                'Não foi possível criar o jogador.'
            );
        }

        $novoUsuario = true;
    }

    $idsu = (int) $usuario->getIdsu();

    if ($idsu <= 0) {
        throw new RuntimeException(
            'Não foi possível identificar o jogador.'
        );
    }

    if (
        !isset($_SESSION['idsu']) ||
        (int) $_SESSION['idsu'] !== $idsu
    ) {
        session_regenerate_id(true);
    }

    $_SESSION['idsu'] = $idsu;
    $_SESSION['apelido'] = $usuario->getApelido();

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

    if (!setcookie($nomeCookie, $token, $opcoesCookie)) {
        throw new RuntimeException(
            'Não foi possível salvar o token no navegador.'
        );
    }

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

    error_log('Erro no criarSessao: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'erro' => 'Não foi possível iniciar o jogador.',
        'detalhe' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

?>