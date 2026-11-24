<?php
class Item_su {

    private $idsu;
    private $iditem;
    private $quantidade;

    public function __construct($idsu, $iditem, $quantidade) {
        $this->idsu = $idsu;
        $this->iditem = $iditem;
        $this->quantidade = $quantidade;
    }

    // Lista os itens que pertencem ao usuário
    public static function ListarItensUsu($idsu) {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT loja.iditem, loja.nome, item_su.quantidade
                FROM item_su
                INNER JOIN loja
                ON item_su.iditem = loja.iditem
                WHERE item_su.idsu = :idsu
                AND item_su.quantidade > 0";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $idsu, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } // fim ListarItensUsu

public function usarItem() {

    $con = new conexao();
    $pdo = $con->pdo;

    $sql = "UPDATE item_su
            SET quantidade = quantidade - 1
            WHERE idsu = :idsu
            AND iditem = :iditem
            AND quantidade > 0";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':idsu', $this->idsu, PDO::PARAM_INT);
    $stmt->bindValue(':iditem', $this->iditem, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $this->quantidade = (int)$this->quantidade - 1;

        return true;
    }

    return false;

} // fim usarItem



    // Setters
    public function setUsu($idsu) {
        $this->idsu = $idsu;
    }

    public function setItem($iditem) {
        $this->iditem = $iditem;
    }

    public function setQuan($quantidade) {
        $this->quantidade = $quantidade;
    }


    // Getters
    public function getUsu() {
        return $this->idsu;
    }

    public function getItem() {
        return $this->iditem;
    }

    public function getQuant() {
        return $this->quantidade;
    }

} ?>