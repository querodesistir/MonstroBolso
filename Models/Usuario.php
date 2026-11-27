
<?php

class Usuario {

    private $idsu;
    private $apelido;
    private $dindin;
    private $nivel;

    public function __construct(
        $apelido = 'Jogador',
        $dindin = 0,
        $nivel = 0
    ) {
        $this->apelido = $apelido;
        $this->dindin = $dindin;
        $this->nivel = $nivel;
    }

    public function entrar($token) {

        $con = new conexao();
        $pdo = $con->pdo;

        $tokenHash = hash('sha256', $token);

        $sql = "SELECT idsu, apelido, dindin, nivel
                FROM usuario
                WHERE token_hash = :token_hash";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(
            ':token_hash',
            $tokenHash,
            PDO::PARAM_STR
        );

        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return false;
        }

        $this->idsu = (int) $dados['idsu'];
        $this->apelido = $dados['apelido'];
        $this->dindin = $dados['dindin'];
        $this->nivel = $dados['nivel'];

        return true;
    }

    public function criarUsuario($token) {

        $con = new conexao();
        $pdo = $con->pdo;

        $tokenHash = hash('sha256', $token);

        $sql = "INSERT INTO usuario (
                    token_hash,
                    apelido,
                    dindin,
                    nivel
                ) VALUES (
                    :token_hash,
                    :apelido,
                    :dindin,
                    :nivel
                )";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':token_hash', $tokenHash);
        $stmt->bindValue(':apelido', $this->apelido);
        $stmt->bindValue(':dindin', $this->dindin);
        $stmt->bindValue(':nivel', $this->nivel);

        if ($stmt->execute()) {

            $this->idsu = (int) $pdo->lastInsertId();

            return $this->idsu > 0;
        }

        return false;
    }

    public function VerPerfil() {

        if (!$this->idsu) {
            return false;
        }

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT idsu, apelido, dindin, nivel
                FROM usuario
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $this->idsu);

        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return false;
        }

        $this->apelido = $dados['apelido'];
        $this->dindin = $dados['dindin'];
        $this->nivel = $dados['nivel'];

        return $dados;
    }

    public function EditarPerfil($apelido) {

        $con = new conexao();
        $pdo = $con->pdo;

        if (!$this->idsu) {
            return false;
        }

        $sql = "UPDATE usuario
                SET apelido = :apelido
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':apelido', $apelido);
        $stmt->bindValue(':idsu', $this->idsu);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->apelido = $apelido;
            return true;
        }

        $sql = "SELECT idsu
                FROM usuario
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $this->idsu);

        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->apelido = $apelido;
            return true;
        }

        return false;
    }

    public function getIdsu() {
        return $this->idsu;
    }

    public function getApelido() {
        return $this->apelido;
    }

    public function getDindin() {
        return $this->dindin;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function setIdsu($idsu) {
        $this->idsu = $idsu;
    }

    public function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    public function setDindin($dindin) {
        $this->dindin = $dindin;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }
}

?>