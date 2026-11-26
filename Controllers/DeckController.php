
<?php
session_start();

require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {

    if (!isset($_SESSION['idsu']) || (int) $_SESSION['idsu'] <= 0) {
        http_response_code(401);

        echo json_encode([
            'ok' => false,
            'erro' => 'Jogador não identificado. Inicie ou continue sua sessão.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $idsu = (int) $_SESSION['idsu'];

    $acao = $_GET['acao'] ?? 'listar';

    if (!in_array($acao, [
        'listar',
        'adicionar',
        'retirar',
        'renovar'
    ], true)) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'listar' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);

        echo json_encode([
            'ok' => false,
            'erro' => 'Método de requisição inválido.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if (
        in_array($acao, ['adicionar', 'retirar', 'renovar'], true) &&
        $_SERVER['REQUEST_METHOD'] !== 'POST'
    ) {
        http_response_code(405);

        echo json_encode([
            'ok' => false,
            'erro' => 'Método de requisição inválido.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $deckModel = new Deck($idsu);

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

    if ($acao === 'renovar') {

        $ideck = $deckModel->renovarDeck();

        if (!$ideck) {
            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Não foi possível renovar o deck.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

    } else {

        $ideck = $deckModel->buscarDeckAtivo();

        if (!$ideck) {
            http_response_code(404);

            echo json_encode([
                'ok' => false,
                'erro' => 'Nenhum deck ativo encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }
    }

    $ideck = (int) $ideck;

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

        $deckMonstroModel = new DeckMonstro($ideck, $id);

        $resultado = $deckMonstroModel->adicionarMonstro($idsu);

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

        $deckMonstroModel = new DeckMonstro($ideck);

        $resultado = $deckMonstroModel->retirarMonstro($idsu, $id);

        if (!$resultado) {
            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Não foi possível retirar o monstro.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }
    }

    $deckMonstroModel = new DeckMonstro($ideck);

    $monstrosDeck = $deckMonstroModel->listarMonstros($idsu);

    $listaMonstros = $monstroModel->ListarMonstroForadoDeck(
        $ideck,
        $idsu
    );

    echo json_encode([
        'ok' => true,
        'ideck' => $ideck,
        'deck' => $monstrosDeck,
        'disponiveis' => $listaMonstros
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    error_log('Erro no DeckController: ' . $e->getMessage());

    echo json_encode([
        'ok' => false,
        'erro' => 'Erro ao processar a solicitação.'
    ], JSON_UNESCAPED_UNICODE);
}
?>