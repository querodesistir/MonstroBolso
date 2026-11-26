<?php
class Item_su {

    private $idsu;
    private $itid;
    private $quantidade;

    public function __construct($idsu, $itid, $quantidade = 0) {
        $this->idsu = (int) $idsu;
        $this->itid = (int) $itid;
        $this->quantidade = (int) $quantidade;
    }

    public static function ListarItensUsu($idsu) {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT itens.itid, itens.nomi, itens.nefeito,
                       itens.preco, itens.descri, item_su.quantidade
                FROM item_su
                INNER JOIN itens
                    ON item_su.itid = itens.itid
                WHERE item_su.idsu = :idsu
                AND item_su.quantidade > 0";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $idsu, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usarItem() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "UPDATE item_su
                SET quantidade = quantidade - 1
                WHERE idsu = :idsu
                AND itid = :itid
                AND quantidade > 0";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $this->idsu, PDO::PARAM_INT);
        $stmt->bindValue(':itid', $this->itid, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $sql = "SELECT quantidade
                    FROM item_su
                    WHERE idsu = :idsu
                    AND itid = :itid";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':idsu' => $this->idsu,
                ':itid' => $this->itid
            ]);

            $this->quantidade = (int) $stmt->fetchColumn();

            return true;
        }

        return false;
    }

    public function comprarItem() {

    $con = new conexao();
    $pdo = $con->pdo;

    try {

        $pdo->beginTransaction();

        $sql = "SELECT dindin
                FROM usuario
                WHERE idsu = :idsu
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $this->idsu
        ]);

        $saldo = $stmt->fetchColumn();

        if ($saldo === false) {
            $pdo->rollBack();
            return false;
        }

        $sql = "SELECT preco
                FROM itens
                WHERE itid = :itid";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':itid' => $this->itid
        ]);

        $preco = $stmt->fetchColumn();

        if ($preco === false || $saldo < $preco) {
            $pdo->rollBack();
            return false;
        }

        $sql = "UPDATE usuario
                SET dindin = dindin - :preco
                WHERE idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':preco' => $preco,
            ':idsu' => $this->idsu
        ]);

        $sql = "SELECT quantidade
                FROM item_su
                WHERE idsu = :idsu
                AND itid = :itid
                FOR UPDATE";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idsu' => $this->idsu,
            ':itid' => $this->itid
        ]);

        $quantidadeAtual = $stmt->fetchColumn();

        if ($quantidadeAtual === false) {

            $sql = "INSERT INTO item_su
                    (idsu, itid, quantidade)
                    VALUES
                    (:idsu, :itid, 1)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':idsu' => $this->idsu,
                ':itid' => $this->itid
            ]);

            $this->quantidade = 1;

        } else {

            $sql = "UPDATE item_su
                    SET quantidade = quantidade + 1
                    WHERE idsu = :idsu
                    AND itid = :itid";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':idsu' => $this->idsu,
                ':itid' => $this->itid
            ]);

            $this->quantidade = (int) $quantidadeAtual + 1;
        }

        $pdo->commit();

        return true;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}

    public function setUsu($idsu) {
        $this->idsu = (int) $idsu;
    }

    public function setItem($itid) {
        $this->itid = (int) $itid;
    }

    public function setQuan($quantidade) {
        $this->quantidade = (int) $quantidade;
    }

    public function getUsu() {
        return $this->idsu;
    }

    public function getItem() {
        return $this->itid;
    }

    public function getQuant() {
        return $this->quantidade;
    }
}
?>