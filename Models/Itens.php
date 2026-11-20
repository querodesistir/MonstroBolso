<?php
class Itens {
private $itid;
private $nomi;
private $nefeito;
private $preco;
private $descri;

function __construct($nomi, $nefeito, $preco, $descri) {
    $this->nomi = $nomi;
    $this->nefeito = $nefeito;
    $this->preco = $preco;
    $this->descri = $descri;
}

function Listaloja(){

} //fim oloja
function Meusitens(){}

function setItid($itid){
    $this->itid = $itid;
}
function getItid(){
    return $this->itid;
}

} ?>