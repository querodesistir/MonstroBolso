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

    $acao = $_GET['acao'] ?? 'listar';

    // Verifica se a ação solicitada é válida
    if (!in_array($acao, ['listar', 'buscar'], true)) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'erro' => 'Ação inválida.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // Cria o objeto Monstro
    $monstroModel = new Monstro();

    if ($acao === 'listar') {

        // Lista todos os monstros cadastrados
        $resultado = $monstroModel->ListarMonstros();

        echo json_encode([
            'ok' => true,
            'monstros' => $resultado
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($acao === 'buscar') {

        // Recebe o nome do monstro
        $nome = trim($_GET['nome'] ?? '');

        if ($nome === '') {

            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'erro' => 'Informe o nome do monstro.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        // Coloca o nome no objeto
        $monstroModel->setNome($nome);

        // Pesquisa o monstro
        $resultado = $monstroModel->buscarMonstro();

        if (!$resultado) {

            http_response_code(404);

            echo json_encode([
                'ok' => false,
                'erro' => 'Monstro não encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        echo json_encode([
            'ok' => true,
            'monstro' => $resultado
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