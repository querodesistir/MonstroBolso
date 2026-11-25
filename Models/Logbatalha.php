
<?php

class LogBatalha {
    private $idlog;
    private $idBa;
    private $rodada;
    private $acao;
    private $dano;
    private $itid;
    private $idsu;

    public function __construct(
        $idBa,
        $rodada,
        $acao,
        $idsu,
        $dano = 0,
        $itid = null
    ) {
        $this->idBa = $idBa;
        $this->rodada = $rodada;
        $this->acao = $acao;
        $this->idsu = $idsu;
        $this->dano = $dano;
        $this->itid = $itid;
    }

    // Registra uma ação da batalha
    public function RegistrarAcao() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO logbatalhas
                (idBa, rodada, acao, dano, itid, idsu)
                VALUES
                (:idBa, :rodada, :acao, :dano, :itid, :idsu)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idBa', $this->idBa);
        $stmt->bindValue(':rodada', $this->rodada);
        $stmt->bindValue(':acao', $this->acao);
        $stmt->bindValue(':dano', $this->dano);
        $stmt->bindValue(':itid', $this->itid, 
            $this->itid === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':idsu', $this->idsu);

        if ($stmt->execute()) {
            $this->idlog = $pdo->lastInsertId();
            return true;
        }

        return false;
    }

    // Consulta todas as ações de uma batalha
    public function VerLogBatalha() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM logbatalhas
                WHERE idBa = :idBa
                ORDER BY rodada ASC, idlog ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idBa', $this->idBa);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Consulta o histórico de batalhas de um jogador
    public function VerHistorico() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT
                    l.idlog,
                    l.idBa,
                    l.rodada,
                    l.acao,
                    l.dano,
                    l.itid,
                    b.habitat,
                    b.vencedor,
                    b.data
                FROM logbatalhas l
                INNER JOIN batalha b
                    ON l.idBa = b.idBa
                WHERE l.idsu = :idsu
                ORDER BY b.data DESC, l.rodada ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Consulta uma ação específica
    public function VerAcao() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM logbatalhas
                WHERE idlog = :idlog
                AND idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idlog', $this->idlog);
        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getIdlog() {
        return $this->idlog;
    }

    public function getIdBa() {
        return $this->idBa;
    }

    public function getRodada() {
        return $this->rodada;
    }

    public function getAcao() {
        return $this->acao;
    }

    public function getDano() {
        return $this->dano;
    }

    public function getItid() {
        return $this->itid;
    }

    public function getIdsu() {
        return $this->idsu;
    }

    // Setters
    public function setIdlog($idlog) {
        $this->idlog = $idlog;
    }

    public function setIdBa($idBa) {
        $this->idBa = $idBa;
    }

    public function setRodada($rodada) {
        $this->rodada = $rodada;
    }

    public function setAcao($acao) {
        $this->acao = $acao;
    }

    public function setDano($dano) {
        $this->dano = $dano;
    }

    public function setItid($itid) {
        $this->itid = $itid;
    }

    public function setIdsu($idsu) {
        $this->idsu = $idsu;
    }
} ?>