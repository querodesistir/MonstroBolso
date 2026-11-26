<?php
session_start();

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($_SESSION['idsu'])) {
        http_response_code(401);

        echo json_encode([
            'ok' => false,
            'erro' => 'Jogador não identificado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $idsu = (int) $_SESSION['idsu'];
    $acao = $_POST['acao'] ?? '';

    if ($acao !== 'salvarHP') {
        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $ideck = isset($_POST['ideck']) ? (int) $_POST['ideck'] : 0;
    $idm = isset($_POST['idm']) ? (int) $_POST['idm'] : 0;

    if (
        $ideck <= 0 ||
        $idm <= 0 ||
        !isset($_POST['ahp']) ||
        !is_numeric($_POST['ahp']) ||
        (int) $_POST['ahp'] < 0
    ) {
        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Dados de HP inválidos.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $ahp = (int) $_POST['ahp'];

    $deckMonstro = new DeckMonstro($ideck);

    $resultado = $deckMonstro->atualizarHP(
        $idsu,
        $idm,
        $ahp
    );

    if (!$resultado) {
        http_response_code(404);

        echo json_encode([
            'ok' => false,
            'erro' => 'Não foi possível atualizar o HP desse monstro.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    echo json_encode([
        'ok' => true,
        'mensagem' => 'HP atualizado com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao salvar o HP.'
    ], JSON_UNESCAPED_UNICODE);
}
?>