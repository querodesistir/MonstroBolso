<?php

class DeckMonstro {
    private $iddeck_monstro;
    private $ideck;
    private $idm;
    private $posicao;
    private $ahp;

    public function __construct(
        $ideck = null,
        $idm = null,
        $posicao = null,
        $ahp = null,
        $iddeck_monstro = null
    ) {
        $this->ideck = $ideck;
        $this->idm = $idm;
        $this->posicao = $posicao;
        $this->ahp = $ahp;
        $this->iddeck_monstro = $iddeck_monstro;
    }

    public function adicionarMonstro($idsu, $idm) {
        $con = new conexao();
        $pdo = $con->pdo;

        try {
            $pdo->beginTransaction();

            $sql = "SELECT ideck
                    FROM deck
                    WHERE ideck = :ideck
                    AND idsu = :idsu
                    AND descartado = 0
                    FOR UPDATE";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ideck' => $this->ideck,
                ':idsu' => $idsu
            ]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return false;
            }

            $sql = "SELECT idm
                    FROM deck_monstro
                    WHERE ideck = :ideck
                    AND idm = :idm";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ideck' => $this->ideck,
                ':idm' => $idm
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return false;
            }

            $sql = "SELECT posicao
                    FROM deck_monstro
                    WHERE ideck = :ideck";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ideck' => $this->ideck
            ]);

            $posicoesOcupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $posicaoLivre = null;

            for ($i = 1; $i <= 3; $i++) {
                if (!in_array($i, array_map('intval', $posicoesOcupadas), true)) {
                    $posicaoLivre = $i;
                    break;
                }
            }

            if ($posicaoLivre === null) {
                $pdo->rollBack();
                return false;
            }

            $sql = "SELECT hp
                    FROM monstro
                    WHERE id = :idm";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':idm' => $idm
            ]);

            $monstro = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$monstro) {
                $pdo->rollBack();
                return false;
            }

            $sql = "INSERT INTO deck_monstro
                    (ideck, idm, posicao, ahp)
                    VALUES
                    (:ideck, :idm, :posicao, :ahp)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ideck' => $this->ideck,
                ':idm' => $idm,
                ':posicao' => $posicaoLivre,
                ':ahp' => $monstro['hp']
            ]);

            $this->iddeck_monstro = $pdo->lastInsertId();
            $this->idm = $idm;
            $this->posicao = $posicaoLivre;
            $this->ahp = $monstro['hp'];

            $pdo->commit();

            return true;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    public function retirarMonstro($idsu, $idm) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "DELETE dm
                FROM deck_monstro dm
                INNER JOIN deck d
                    ON d.ideck = dm.ideck
                WHERE dm.ideck = :ideck
                AND dm.idm = :idm
                AND d.idsu = :idsu
                AND d.descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':idm' => $idm,
            ':idsu' => $idsu
        ]);

        return $stmt->rowCount() > 0;
    }

    public function listarMonstros($idsu) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT
                    m.id,
                    m.nome,
                    m.imagem,
                    m.dano,
                    m.hp,
                    dm.ahp,
                    m.def,
                    m.prio,
                    m.danoEspecial,
                    m.tipid,
                    t.elemento,
                    m.veloz,
                    dm.posicao
                FROM deck_monstro dm
                INNER JOIN deck d
                    ON d.ideck = dm.ideck
                INNER JOIN monstro m
                    ON m.id = dm.idm
                INNER JOIN tipo t
                    ON t.tipid = m.tipid
                WHERE dm.ideck = :ideck
                AND d.idsu = :idsu
                AND d.descartado = 0
                ORDER BY dm.posicao";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':idsu' => $idsu
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarHP($idsu, $idm, $ahp) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT m.hp
                FROM deck_monstro dm
                INNER JOIN deck d
                    ON d.ideck = dm.ideck
                INNER JOIN monstro m
                    ON m.id = dm.idm
                WHERE dm.ideck = :ideck
                AND dm.idm = :idm
                AND d.idsu = :idsu
                AND d.descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':idm' => $idm,
            ':idsu' => $idsu
        ]);

        $monstro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$monstro) {
            return false;
        }

        $ahp = max(0, min((int) $ahp, (int) $monstro['hp']));

        $sql = "UPDATE deck_monstro
                SET ahp = :ahp
                WHERE ideck = :ideck
                AND idm = :idm";

        $stmt = $pdo->prepare($sql);

        $resultado = $stmt->execute([
            ':ahp' => $ahp,
            ':ideck' => $this->ideck,
            ':idm' => $idm
        ]);

        if ($resultado) {
            $this->idm = $idm;
            $this->ahp = $ahp;
        }

        return $resultado;
    }

    public function getIddeckMonstro() {
        return $this->iddeck_monstro;
    }

    public function getIdeck() {
        return $this->ideck;
    }

    public function getIdm() {
        return $this->idm;
    }

    public function getPosicao() {
        return $this->posicao;
    }

    public function getAhp() {
        return $this->ahp;
    }

    public function setIddeckMonstro($iddeck_monstro) {
        $this->iddeck_monstro = $iddeck_monstro;
    }

    public function setIdeck($ideck) {
        $this->ideck = $ideck;
    }

    public function setIdm($idm) {
        $this->idm = $idm;
    }

    public function setPosicao($posicao) {
        $this->posicao = $posicao;
    }

    public function setAhp($ahp) {
        $this->ahp = $ahp;
    }
}
?>