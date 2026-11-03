<?php
class Item_su {
    private $idsu;
    private $iditem;
    private $quantidade;

    function __construct($idsu, $iditem, $quantidade) {
        $this->idsu = $idsu;
        $this->iditem = $iditem;
        $this->quantidade = $quantidade;
    }

    function ListarItensUsu($idsu) {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT loja.iditem, loja.nome, item_su.quantidade
                FROM item_su INNER JOIN loja 
                ON item_su.iditem = loja.iditem
                WHERE item_su.idsu = :idsu";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':idsu', $idsu, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  // fim do itens do usuario

    function usarItem(){

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

    return $stmt->rowCount() > 0;
} //fim usaritem

   function setUsu($idsu) {
        $this->idsu = $idsu;
    }

    function setItem($iditem) {
        $this->iditem = $iditem;
    }

    function setQuan($quantidade) {
        $this->quantidade = $quantidade;
    }

    function getUsu() {
        return $this->idsu;
    }

    function getItem() {
        return $this->iditem;
    }

    function getQuant() {
        return $this->quantidade;
    }

}
?>