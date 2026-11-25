<?php
class Itemsu {

    private $idsu;
    private $itid;
    private $quantidade;

    public function __construct($idsu, $itid, $quantidade = 0) {
        $this->idsu = $idsu;
        $this->itid = $itid;
        $this->quantidade = $quantidade;
    }

    // Lista os itens que pertencem ao jogador
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

    // Utiliza um item do inventário
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

            $this->quantidade = (int)$this->quantidade - 1;

            return true;
        }

        return false;
    }

    // Setters
    public function setUsu($idsu) {
        $this->idsu = $idsu;
    }

    public function setItem($itid) {
        $this->itid = $itid;
    }

    public function setQuan($quantidade) {
        $this->quantidade = $quantidade;
    }

    // Getters
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