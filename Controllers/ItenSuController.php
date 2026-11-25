
<?php

session_start();

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {

    // Verifica se existe um jogador identificado
    if (!isset($_SESSION['idsu'])) {

        http_response_code(401);

        echo json_encode([
            'ok' => false,
            'erro' => 'Jogador não identificado. Inicie ou continue sua sessão.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $idsu = (int) $_SESSION['idsu'];

    $acao = $_GET['acao'] ?? 'listar';

    if (!in_array($acao, ['listar', 'usar'], true)) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
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

    if ($acao === 'usar') {

        $itid = isset($_POST['itid']) ? (int) $_POST['itid'] : 0;

        if ($itid <= 0) {

            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Item inválido.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $itemModel = new Item_su($idsu, $itid, 0);

        $resultado = $itemModel->usarItem();

        if (!$resultado) {

            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Não foi possível utilizar o item.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // Atualiza o inventário após utilizar o item
        $itensAtualizados = Item_su::ListarItensUsu($idsu);

        echo json_encode([
            'ok' => true,
            'mensagem' => 'Item utilizado com sucesso.',
            'itens' => $itensAtualizados
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