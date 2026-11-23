<?php
session_start();
require_once "../Models/conexao.php";
require_once "../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {
    $monstro = new Monstro($pdo);

    $acao = $_GET['acao'] ?? null;
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($acao === 'addr' && $id > 0) {
        $model->adicionarAoDeck($id);

    } elseif ($acao === 'TiradoDeck' && $id > 0) {
        $model->removerDoDeck($id);
    }

    echo json_encode([
        'ok' => true,
        'deck' => $model->listarDoDeck(),
        'disponiveis' => $model->listarDisponiveis(),
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao processar a solicitação.',
    ], JSON_UNESCAPED_UNICODE);
}

?>