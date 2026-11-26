
<?php
require_once __DIR__ . "/../Models/conexao.php";
require_once __DIR__ . "/../autoload.php";

header('Content-Type: application/json; charset=utf-8');

try {

    $deckModel = new Deck();
    $deckInimigo = $deckModel->buscarDeckInimigoAleatorio();

    if (!$deckInimigo) {
        http_response_code(404);
        echo json_encode([
            'ok' => false,
            'erro' => 'Nenhum deck inimigo foi encontrado no banco de dados.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'deck_inimigo' => $deckInimigo
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    error_log('Erro no DeckInimigoController: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'ok' => false,
        'erro' => 'Não foi possível carregar o deck inimigo.'
    ], JSON_UNESCAPED_UNICODE);

}

?>