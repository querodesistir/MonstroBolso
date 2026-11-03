<?php
class Deck_usu {
    private $ideck;
    private $idsu;

    function __construct($ideck, $idsu) {
        $this->ideck = $ideck;
        $this->idsu = $idsu;
    }
     function associaUser() {  //recomendação do chat, achei inutil
        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "INSERT INTO deck_usu(ideck, idsu) VALUES (:ideck, :idsu)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':ideck', $this->ideck, PDO::PARAM_INT);
        $stmt->bindValue(':idsu', $this->idsu, PDO::PARAM_INT);

        return $stmt->execute();
    }

    function setDeck($ideck) {
        $this->ideck = $ideck;
    }
    function setUsuario($idsu) {
        $this->idsu = $idsu;
    }
    function getDeck() {
        return $this->ideck;
    }
    function getUsuario() {
        return $this->idsu;
    }

   
}
?>