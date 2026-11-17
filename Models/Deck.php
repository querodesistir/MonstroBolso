<?php
class Deck{
private  $ideck;
private  $idm1; 
private $idm2; 
private  $idm3; 
private $titulo;

function __construct($idm1 = null, $idm2 = null, $idm3 = null, $titulo) {
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
    }

} //fiz nenhum get,set, listar...O listar está em monstro!Tá certo?
function Addeck($ideck, $idsu, $idmonstro) {
    $con = new conexao();
    $pdo = $con->pdo;

    try {
        $pdo->beginTransaction();
        $sql = "SELECT idm1, idm2, idm3
                FROM deckbattle
                WHERE ideck = :ideck AND idsu = :idsu
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
        //  Verifica se o monstro existe e está disponível
        $sql = "SELECT nodeck FROM monstro WHERE id = :id FOR UPDATE";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $idmonstro]);

        $monstro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$monstro || (int)$monstro['nodeck'] === 1) {
            $pdo->rollBack();

            return false;
        }
        //  Encontrar uma posição vazia
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

        // Add monstro ao deck
        $sql = "UPDATE deckbattle
                SET $coluna = :idmonstro
                WHERE ideck = :ideck AND idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idmonstro' => $idmonstro,
            ':ideck' => $ideck,
            ':idsu' => $idsu
        ]);
        $sql = "UPDATE monstro
                SET nodeck = TRUE
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $idmonstro]);
        $pdo->commit();

        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}
