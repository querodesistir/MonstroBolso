<?php
class Itens {
private $itid;
private $nomi;
private $nefeito;
private $preco;
private $descri;

function function __construct($nomi, $nefeito, $preco, $descri) {
    $this->nomi = $nomi;
    $this->nefeito = $nefeito;
    $this->preco = $preco;
    $this->descri = $descri;
}

funtion Oloja(){

} //fim oloja

function setItid($itid){
    $this->itid = $itid;
}
function getItid(){
    return $this->itid;
}

} ?>