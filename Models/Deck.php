<?php
class Deck {

    private $ideck;
    private $idsu;
    private $titulo;
    private $descartado;

    public function __construct(
        $idsu = null,
        $titulo = 'FolhaX',
        $descartado = 0,
        $ideck = null
    ) {
        $this->idsu = $idsu !== null ? (int) $idsu : null;
        $this->titulo = $titulo;
        $this->descartado = (int) $descartado;
        $this->ideck = $ideck !== null ? (int) $ideck : null;
    }

    public function cadasDeck() {

        if (!$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO deck (
                    idsu,
                    idm1,
                    idm2,
                    idm3,
                    titulo,
                    descartado
                ) VALUES (
                    :idsu,
                    NULL,
                    NULL,
                    NULL,
                    :titulo,
                    0
                )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $this->idsu,
            ':titulo' => $this->titulo
        ]);

        $this->ideck = (int) $pdo->lastInsertId();
        $this->descartado = 0;

        return $this->ideck > 0;
    }

    public function buscarDeckAtivo() {

        if (!$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT ideck, idsu, titulo, descartado
                FROM deck
                WHERE idsu = :idsu
                AND descartado = 0
                ORDER BY ideck DESC
                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $this->idsu
        ]);

        $deck = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            return false;
        }

        $this->ideck = (int) $deck['ideck'];
        $this->idsu = (int) $deck['idsu'];
        $this->titulo = $deck['titulo'];
        $this->descartado = (int) $deck['descartado'];

        return $this->ideck;
    }

    public function renovarDeck() {

        if (!$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        try {

            $pdo->beginTransaction();

            $sqlBloquear = "SELECT ideck
                            FROM deck
                            WHERE idsu = :idsu
                            AND descartado = 0
                            FOR UPDATE";

            $stmtBloquear = $pdo->prepare($sqlBloquear);

            $stmtBloquear->execute([
                ':idsu' => $this->idsu
            ]);

            $stmtBloquear->fetchAll(PDO::FETCH_COLUMN);

            $sqlArquivar = "UPDATE deck
                            SET descartado = 1
                            WHERE idsu = :idsu
                            AND descartado = 0";

            $stmtArquivar = $pdo->prepare($sqlArquivar);

            $stmtArquivar->execute([
                ':idsu' => $this->idsu
            ]);

            $sqlCriar = "INSERT INTO deck (
                            idsu,
                            idm1,
                            idm2,
                            idm3,
                            titulo,
                            descartado
                         ) VALUES (
                            :idsu,
                            NULL,
                            NULL,
                            NULL,
                            'FolhaX',
                            0
                         )";

            $stmtCriar = $pdo->prepare($sqlCriar);

            $stmtCriar->execute([
                ':idsu' => $this->idsu
            ]);

            $this->ideck = (int) $pdo->lastInsertId();
            $this->titulo = 'FolhaX';
            $this->descartado = 0;

            $pdo->commit();

            return $this->ideck;

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    public function VerDeck() {

        if (!$this->ideck || !$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT ideck, idsu, titulo, descartado
                FROM deck
                WHERE ideck = :ideck
                AND idsu = :idsu
                LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':idsu' => $this->idsu
        ]);

        $deck = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            return false;
        }

        $this->ideck = (int) $deck['ideck'];
        $this->idsu = (int) $deck['idsu'];
        $this->titulo = $deck['titulo'];
        $this->descartado = (int) $deck['descartado'];

        return true;
    }

    public function ListarDecks() {

        if (!$this->idsu) {
            return [];
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT ideck, idsu, titulo, descartado
                FROM deck
                WHERE idsu = :idsu
                AND descartado = 0
                ORDER BY ideck DESC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $this->idsu
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function EditarDeck($titulo) {

        if (!$this->ideck || !$this->idsu) {
            return false;
        }

        $titulo = trim($titulo);

        if ($titulo === '') {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE deck
                SET titulo = :titulo
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':titulo' => $titulo,
            ':ideck' => $this->ideck,
            ':idsu' => $this->idsu
        ]);

        if ($stmt->rowCount() > 0) {
            $this->titulo = $titulo;
            return true;
        }

        return false;
    }

    public function DescartarDeck() {

        if (!$this->ideck || !$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE deck
                SET descartado = 1
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':idsu' => $this->idsu
        ]);

        if ($stmt->rowCount() > 0) {
            $this->descartado = 1;
            return true;
        }

        return false;
    }

    public function buscarDeckInimigoAleatorio() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT iddeck_inimigo, idm1, idm2, idm3, titulo
                FROM deck_inimigo
                ORDER BY RAND()
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $deck = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            return false;
        }

        $sqlMonstros = "SELECT m.id, m.nome, m.imagem, m.dano, m.hp,
                               m.hp AS ahp, m.def, m.prio, m.danoEspecial,
                               m.tipid, t.elemento, m.veloz
                        FROM monstro m
                        INNER JOIN tipo t
                            ON t.tipid = m.tipid
                        WHERE m.id IN (:idm1, :idm2, :idm3)";

        $stmtMonstros = $pdo->prepare($sqlMonstros);

        $stmtMonstros->execute([
            ':idm1' => $deck['idm1'],
            ':idm2' => $deck['idm2'],
            ':idm3' => $deck['idm3']
        ]);

        $registros = $stmtMonstros->fetchAll(PDO::FETCH_ASSOC);

        $monstrosPorId = [];

        foreach ($registros as $registro) {
            $monstrosPorId[(int) $registro['id']] = $registro;
        }

        $monstros = [];

        foreach (['idm1', 'idm2', 'idm3'] as $campo) {

            $idMonstro = (int) $deck[$campo];

            if (!isset($monstrosPorId[$idMonstro])) {
                return false;
            }

            $monstros[] = $monstrosPorId[$idMonstro];
        }

        return [
            'iddeck_inimigo' => (int) $deck['iddeck_inimigo'],
            'titulo' => $deck['titulo'],
            'monstros' => $monstros
        ];
    }

    public function getIdeck() {
        return $this->ideck;
    }

    public function setIdeck($ideck) {
        $this->ideck = $ideck !== null ? (int) $ideck : null;
    }

    public function getIdsu() {
        return $this->idsu;
    }

    public function setIdsu($idsu) {
        $this->idsu = $idsu !== null ? (int) $idsu : null;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = trim($titulo);
    }

    public function getDescartado() {
        return $this->descartado;
    }

    public function setDescartado($descartado) {
        $this->descartado = (int) $descartado;
    }
}

?>