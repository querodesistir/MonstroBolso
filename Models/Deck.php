
<?php

class Deck {
    private $ideck;
    private $idsu;
    private $idm1;
    private $idm2;
    private $idm3;
    private $titulo;
    private $descartado;

    public function __construct(
        $idsu,
        $idm1 = null,
        $idm2 = null,
        $idm3 = null,
        $titulo = 'FolhaX',
        $descartado = 0
    ) {
        $this->idsu = $idsu;
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
        $this->descartado = $descartado;
    }

    // Cadastra um novo deck
    public function cadasDeck() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO deck
                (idsu, idm1, idm2, idm3, titulo, descartado)
                VALUES
                (:idsu, :idm1, :idm2, :idm3, :titulo, :descartado)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->bindValue(':idm1', $this->idm1);
        $stmt->bindValue(':idm2', $this->idm2);
        $stmt->bindValue(':idm3', $this->idm3);
        $stmt->bindValue(':titulo', $this->titulo);
        $stmt->bindValue(':descartado', $this->descartado);

        if ($stmt->execute()) {
            $this->ideck = $pdo->lastInsertId();
            return true;
        }

        return false;
    }

    // Adiciona um monstro ao primeiro espaço disponível
    public function Addeck($idmonstro) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT idm1, idm2, idm3
                FROM deck
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ideck', $this->ideck);
        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return false;
        }

        if ($dados['idm1'] === null) {
            $campo = 'idm1';
        } elseif ($dados['idm2'] === null) {
            $campo = 'idm2';
        } elseif ($dados['idm3'] === null) {
            $campo = 'idm3';
        } else {
            return false;
        }

        $sql = "UPDATE deck
                SET $campo = :idmonstro
                WHERE ideck = :ideck
                AND idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idmonstro', $idmonstro);
        $stmt->bindValue(':ideck', $this->ideck);
        $stmt->bindValue(':idsu', $this->idsu);

        return $stmt->execute();
    }

    // Consulta um deck específico
    public function VerDeck() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM deck
                WHERE ideck = :ideck
                AND idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ideck', $this->ideck);
        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return false;
        }

        $this->idm1 = $dados['idm1'];
        $this->idm2 = $dados['idm2'];
        $this->idm3 = $dados['idm3'];
        $this->titulo = $dados['titulo'];
        $this->descartado = $dados['descartado'];

        return $dados;
    }

    // Lista os decks de um jogador
    public function ListarDecks() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT *
                FROM deck
                WHERE idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idsu', $this->idsu);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Altera o título do deck
    public function EditarDeck($titulo) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE deck
                SET titulo = :titulo
                WHERE ideck = :ideck
                AND idsu = :idsu
                AND descartado = 0";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':titulo', $titulo);
        $stmt->bindValue(':ideck', $this->ideck);
        $stmt->bindValue(':idsu', $this->idsu);

        if ($stmt->execute()) {
            $this->titulo = $titulo;
            return true;
        }

        return false;
    }

    // Descarta um deck sem excluí-lo do banco
    public function DescartarDeck() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE deck
                SET descartado = 1
                WHERE ideck = :ideck
                AND idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ideck', $this->ideck);
        $stmt->bindValue(':idsu', $this->idsu);

        if ($stmt->execute()) {
            $this->descartado = 1;
            return true;
        }

        return false;
    }

    // Getters
    public function getIdeck() {
        return $this->ideck;
    }

    public function getIdsu() {
        return $this->idsu;
    }

    public function getIdm1() {
        return $this->idm1;
    }

    public function getIdm2() {
        return $this->idm2;
    }

    public function getIdm3() {
        return $this->idm3;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescartado() {
        return $this->descartado;
    }

    // Setters
    public function setIdeck($ideck) {
        $this->ideck = $ideck;
    }

    public function setIdsu($idsu) {
        $this->idsu = $idsu;
    }

    public function setIdm1($idm1) {
        $this->idm1 = $idm1;
    }

    public function setIdm2($idm2) {
        $this->idm2 = $idm2;
    }

    public function setIdm3($idm3) {
        $this->idm3 = $idm3;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDescartado($descartado) {
        $this->descartado = $descartado;
    }
} ?>