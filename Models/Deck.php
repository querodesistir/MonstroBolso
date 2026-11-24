<?php
class Deck
{
    private $ideck;
    private $idm1;
    private $idm2;
    private $idm3;
    private $titulo;

    function __construct($idm1 = null, $idm2 = null, $idm3 = null, $titulo = 'FolhaX')
    {
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
    }

function Addeck($ideck, $idsu, $id)
{
    $con = new conexao();
    $pdo = $con->pdo;

    try {
        $pdo->beginTransaction();

        $sql = "SELECT idm1, idm2, idm3
                FROM deck
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        $deck = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            $pdo->rollBack();
            return false;
        }
        $sql = "SELECT id
                FROM monstro
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->rollBack();
            return false;
        }

        // Verifica se o monstro já está nesse deck
        foreach (['idm1', 'idm2', 'idm3'] as $campo) {

            if ($deck[$campo] !== null &&
                (int)$deck[$campo] === (int)$id) {

                $pdo->rollBack();
                return false;
            }
        }

        $coluna = null;

        foreach (['idm1', 'idm2', 'idm3'] as $campo) {

            if ($deck[$campo] === null) {
                $coluna = $campo;
                break;
            }
        }

        if ($coluna === null) {
            $pdo->rollBack();
            return false;
        }

        // 5. Adiciona o monstro na posição encontrada
        $sql = "UPDATE deck
                SET $coluna = :id
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        $pdo->commit();

        return true;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}

function TiradoDeck($ideck, $idsu, $id)
{
    $con = new conexao();
    $pdo = $con->pdo;

    try {
        $pdo->beginTransaction();

        $sql = "SELECT idm1, idm2, idm3
                FROM deck
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        $deck = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            $pdo->rollBack();
            return false;
        }

        $coluna = null;

        foreach (['idm1', 'idm2', 'idm3'] as $campo) {

            if ($deck[$campo] !== null &&
                (int)$deck[$campo] === (int)$id) {

                $coluna = $campo;
                break;
            }
        }

        if ($coluna === null) {
            $pdo->rollBack();
            return false;
        }

        $sql = "UPDATE deck
                SET $coluna = NULL
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);

        $pdo->commit();

        return true;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}


function salvarDeck($idsu, $titulo, $pdo)
{

    $sql = "INSERT INTO deck (idsu, titulo)
            VALUES (:idsu, :titulo)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':idsu' => $idsu,
        ':titulo' => $titulo
    ]);

    $this->ideck = $pdo->lastInsertId();

    return $this->ideck;
}

function renovarDeck($idsuJogador)
{
    $con = new conexao();
    $pdo = $con->pdo;

    try {
        $pdo->beginTransaction();

//Localiza e bloqueia temporáriamente o usuário inimigo para evitar comflitos de transferencias simultaneas
        $sql = "SELECT idsu
                FROM usuario
                WHERE email = :email
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':email' => 'inimigo@inimigo'
        ]);

        $idsuInimigo = $stmt->fetchColumn();

        // Impede transferir o deck para o próprio usuário
        if ((int)$idsuJogador === (int)$idsuInimigo) {
            $pdo->rollBack();
            return false;
        }
        $sql = "SELECT ideck, idm1, idm2, idm3
                FROM deck
                WHERE idsu = :idsu
                AND descartado = 0
                ORDER BY ideck DESC
                LIMIT 1
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $idsuJogador
        ]);

        $deckJogador = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deckJogador) {
            $pdo->rollBack();
            return false;
        }
        $idsJogador = [
            $deckJogador['idm1'],
            $deckJogador['idm2'],
            $deckJogador['idm3']
        ];

        // Verifica se o deck possui três monstros
        if (in_array(null, $idsJogador, true)) {
            $pdo->rollBack();
            return false;
        }

        //Organiza os IDs para comparar sem considerar a ordem
        $idsJogador = array_map('intval', $idsJogador);

        sort($idsJogador, SORT_NUMERIC);
        $sql = "SELECT idm1, idm2, idm3
                FROM deck
                WHERE idsu = :idsu
                AND descartado = 0
                AND idm1 IS NOT NULL
                AND idm2 IS NOT NULL
                AND idm3 IS NOT NULL
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $idsuInimigo
        ]);

        $duplicado = false;

        while ($deckInimigo = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $idsInimigo = [
                (int)$deckInimigo['idm1'],
                (int)$deckInimigo['idm2'],
                (int)$deckInimigo['idm3']
            ];

            sort($idsInimigo, SORT_NUMERIC);

            if ($idsJogador === $idsInimigo) {
                $duplicado = true;
                break;
            }
        }

        if ($duplicado) {

            // Descarta o deck sem apagar o registro
            $sql = "UPDATE deck
                    SET descartado = 1
                    WHERE ideck = :ideck
                    AND idsu = :idsu";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':ideck' => $deckJogador['ideck'],
                ':idsu' => $idsuJogador
            ]);

        } else {
            $sql = "UPDATE deck
                    SET idsu = :idsuInimigo
                    WHERE ideck = :ideck
                    AND idsu = :idsu";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':idsuInimigo' => $idsuInimigo,
                ':ideck' => $deckJogador['ideck'],
                ':idsu' => $idsuJogador
            ]);
        }

        $novoIdeck = $this->salvarDeck(
            $idsuJogador,
            'FolhaX',
            $pdo
        );

        $pdo->commit();

        return $novoIdeck;

    } catch (Throwable $e) {
  
    if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}

function buscarDeckAtivo($idsu)
{
    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "SELECT ideck, idm1, idm2, idm3, titulo
            FROM deck
            WHERE idsu = :idsu
            AND descartado = 0
            ORDER BY ideck DESC
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':idsu' => $idsu
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}  ?> 
