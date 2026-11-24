<?php

class Itens {

    private $itid;
    private $nomi;
    private $nefeito;
    private $preco;
    private $descri;


    public function __construct($nomi, $nefeito, $preco, $descri) {

        $this->nomi = $nomi;
        $this->nefeito = $nefeito;
        $this->preco = $preco;
        $this->descri = $descri;

    }


    // Lista todos os itens disponíveis na loja
    public static function Listaloja() {

        $con = new conexao();
        $pdo = $con->pdo;

        $sql = "SELECT * FROM loja ORDER BY nome";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } // fim Listaloja


    // Setters
    public function setItid($itid) {
        $this->itid = $itid;
    }

    public function setNomi($nomi) {
        $this->nomi = $nomi;
    }

    public function setNefeito($nefeito) {
        $this->nefeito = $nefeito;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function setDescri($descri) {
        $this->descri = $descri;
    }


    // Getters
    public function getItid() {
        return $this->itid;
    }

    public function getNomi() {
        return $this->nomi;
    }

    public function getNefeito() {
        return $this->nefeito;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getDescri() {
        return $this->descri;
    }

} ?>