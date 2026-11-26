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
            'erro' => 'Usuário não identificado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $idsu = (int) $_SESSION['idsu'];
    $acao = $_GET['acao'] ?? '';

    if (!in_array($acao, [
        'listarLoja',
        'listar',
        'comprar',
        'usar'
    ], true)) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'listarLoja') {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT itid, nomi, nefeito, preco, descri
                FROM itens
                ORDER BY itid";

        $stmt = $pdo->query($sql);
        $loja = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'ok' => true,
            'loja' => $loja
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'listar') {

        $itens = Item_su::ListarItensUsu($idsu);

        echo json_encode([
            'ok' => true,
            'itens' => $itens
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $entrada = json_decode(
        file_get_contents('php://input'),
        true
    );

    if (!is_array($entrada)) {
        $entrada = [];
    }

    $itid = (int) ($entrada['itid'] ?? $_POST['itid'] ?? 0);

    if ($itid <= 0) {
        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Item não informado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $itemModel = new Item_su($idsu, $itid, 0);

    if ($acao === 'comprar') {
        $resultado = $itemModel->comprarItem();
    } else {
        $resultado = $itemModel->usarItem();
    }

    if (!$resultado) {
        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Não foi possível realizar a operação.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'usar' && (
        isset($entrada['idBa']) || isset($entrada['rodada'])
    )) {

        $idBa = (int) ($entrada['idBa'] ?? 0);
        $rodada = (int) ($entrada['rodada'] ?? 0);

        if ($idBa <= 0 || $rodada <= 0) {
            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Dados da batalha inválidos.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $logModel = new LogBatalha(
            $idBa,
            $rodada,
            'item',
            $idsu,
            0,
            $itid
        );

        if (!$logModel->RegistrarAcao()) {
            throw new Exception('Não foi possível registrar o uso do item.');
        }
    }

    echo json_encode([
        'ok' => true,
        'mensagem' => $acao === 'comprar'
            ? 'Item comprado com sucesso.'
            : 'Item utilizado com sucesso.',
        'itens' => Item_su::ListarItensUsu($idsu)
    ], JSON_UNESCAPED_UNICODE);

    exit;

} catch (Throwable $e) {

    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao processar a operação dos itens.'
    ], JSON_UNESCAPED_UNICODE);
}
?>