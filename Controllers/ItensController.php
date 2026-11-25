
<?php

session_start();

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {

    // Verifica se existe um jogador identificado na sessão
    if (!isset($_SESSION['idsu'])) {

        http_response_code(401);

        echo json_encode([
            'ok' => false,
            'erro' => 'Jogador não identificado. Inicie ou continue sua sessão.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $idsu = (int) $_SESSION['idsu'];

    $acao = $_GET['acao'] ?? 'listarLoja';

    // Verifica a ação solicitada
    if (!in_array($acao, ['listarLoja', 'listar'], true)) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $itemModel = new Itens('', 0, 0, '');

    if ($acao === 'listarLoja') {

        // Busca todos os itens disponíveis na loja
        $resultado = $itemModel->ListarItens();

        echo json_encode([
            'ok' => true,
            'loja' => $resultado
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'listar') {

        // Busca os itens pertencentes ao jogador
        $resultado = Item_su::ListarItensUsu($idsu);

        echo json_encode([
            'ok' => true,
            'itens' => $resultado
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

} catch (Throwable $e) {

    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao processar a solicitação.'
    ], JSON_UNESCAPED_UNICODE);
}
?>