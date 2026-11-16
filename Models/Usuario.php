<?php
class Usuario {
    private $idsu;
    private $mail;
    private $senha;
    private $apelido;
    private $dindin;
    private $nivel;

    function __construct($mail, $senha, $apelido = null, $dindin = 0, $nivel = 0) {
        $this->mail = $mail;
        $this->senha = $senha;
        $this->apelido = $apelido;
        $this->dindin = $dindin;
        $this->nivel = $nivel;
    }

    function cadaSu() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO usuario(mail, senha, apelido, dindin, nivel)
                VALUES (:mail, :senha, :apelido, :dindin, :nivel)";

        $stmt = $pdo->prepare($sql);

        $dados[':mail'] = $this->mail;
        $dados[':senha'] = password_hash($this->senha, PASSWORD_DEFAULT);
        $dados[':apelido'] = $this->apelido;
        $dados[':dindin'] = $this->dindin;
        $dados[':nivel'] = $this->nivel;

        $stmt->execute($dados);

        $this->idsu = $pdo->lastInsertId();

        return true;
    } // fim do cadastro

    function Login() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT idsu, mail, senha, apelido, nivel, dindin
                FROM usuario
                WHERE mail = :mail";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':mail' => $this->mail]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($this->senha, $usuario['senha'])) {
            return false;
        }

        $this->idsu = $usuario['idsu'];
        $this->apelido = $usuario['apelido'];
        $this->nivel = $usuario['nivel'];
        $this->dindin = $usuario['dindin'];

        return true;
    } // fim do login

    public function desaida() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header("Location: login.html");
        exit;
    }

    function verPerfil() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT mail, apelido, nivel
                FROM usuario
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':idsu' => $this->idsu]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    } // fim de ler perfil

    function EditPerfil() {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE usuario
     SET mail = :mail, apelido = :apelido WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':mail' => $this->mail,
            ':apelido' => $this->apelido,
            ':idsu' => $this->idsu
        ]);
    } //fim edita perfil

    function mudaSenha($novaSenha) {
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE usuario
                SET senha = :senha
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
            ':idsu' => $this->idsu
        ]);
    } //fim mudasenha

    function apagaConta() {
        $con = new conexao();
        $pdo = $con->pdo;
        try {
            $pdo->beginTransaction();
            $sql = "DELETE FROM usuario WHERE idsu = :idsu";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':idsu' => $this->idsu
            ]);
            if ($stmt->rowCount() === 0) {
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    } // fim de apagar conta

    // SETTERS
    function setMail($mail) {
        $this->mail = $mail;
    }

    function setSen($senha) {
        $this->senha = $senha;
    }

    function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    function setIdsu($idsu) {
        $this->idsu = $idsu;
    }

    function setDin($dindin) {
        $this->dindin = $dindin;
    }

    // GETTERS
    function getMail() {
        return $this->mail;
    }

    function getSen() {
        return $this->senha;
    }

    function getApelido() {
        return $this->apelido;
    }

    function getNivel() {
        return $this->nivel;
    }

    function getIdsu() {
        return $this->idsu;
    }

    function getDin() {
        return $this->dindin;
    }

} // fim da classe Usuario
?>