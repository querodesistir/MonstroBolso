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

    $deckModel = new Deck();

    $monstroModel = new Monstro(
        '',
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null
    );

    $acao = $_GET['acao'] ?? 'listar';

    // Verifica se a ação solicitada é válida
   if (!in_array($acao, [
    'listar',
    'adicionar',
    'retirar',
    'renovar',
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
// Ações relacionadas aos itens
if ($acao === 'comprar' || $acao === 'usar') {

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

    if ($acao === 'comprar') {
        $resultado = $itemModel->comprarItem();
    } else {
        $resultado = $itemModel->usarItem();
    }

    if (!$resultado) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'erro' => $acao === 'comprar'
                ? 'Não foi possível comprar o item. Verifique seu saldo.'
                : 'Você não possui esse item no inventário.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'mensagem' => $acao === 'comprar'
            ? 'Item comprado com sucesso!'
            : 'Item utilizado com sucesso!',
        'itens' => Item_su::ListarItensUsu($idsu)
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
    if ($acao === 'renovar') {

        $resultado = $deckModel->renovarDeck($idsu);

        if ($resultado === false) {

            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Não foi possível renovar o deck.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

    } else {

        // Busca o deck ativo do jogador
        $deckAtual = $deckModel->buscarDeckAtivo($idsu);

        if (!$deckAtual) {

            http_response_code(404);

            echo json_encode([
                'ok' => false,
                'erro' => 'Nenhum deck ativo encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $ideck = (int) $deckAtual['ideck'];

        if ($acao === 'adicionar') {

            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

            if ($id <= 0) {

                http_response_code(400);

                echo json_encode([
                    'ok' => false,
                    'erro' => 'Monstro inválido.'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            $resultado = $deckModel->Addeck($ideck, $idsu, $id);

            if (!$resultado) {

                http_response_code(400);

                echo json_encode([
                    'ok' => false,
                    'erro' => 'Não foi possível adicionar o monstro.'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

        } elseif ($acao === 'retirar') {

            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

            if ($id <= 0) {

                http_response_code(400);

                echo json_encode([
                    'ok' => false,
                    'erro' => 'Monstro inválido.'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            $resultado = $deckModel->TiradoDeck($ideck, $idsu, $id);

            if (!$resultado) {

                http_response_code(400);

                echo json_encode([
                    'ok' => false,
                    'erro' => 'Não foi possível retirar o monstro.'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }
        }
    }

    // Busca novamente o deck ativo
    $deckAtual = $deckModel->buscarDeckAtivo($idsu);

    if (!$deckAtual) {

        http_response_code(404);

        echo json_encode([
            'ok' => false,
            'erro' => 'Não foi possível localizar o deck atualizado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $ideck = (int) $deckAtual['ideck'];

    // Busca os monstros que estão no deck
    $MonstrosDeck = $monstroModel->MonstrosnoDeck(
        $ideck,
        $idsu
    );

    // Busca os monstros disponíveis para adicionar
    $listaMonstros = $monstroModel->ListarMonstroForadoDeck(
        $ideck,
        $idsu
    );

    echo json_encode([
        'ok' => true,
        'ideck' => $ideck,
        'deck' => $MonstrosDeck,
        'disponiveis' => $listaMonstros
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao processar a solicitação.'
    ], JSON_UNESCAPED_UNICODE);
}
?>