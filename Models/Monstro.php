<?php
 class Monstro extends Tipo{
    private $id;
    private $nome;
    private $dano;
    private $hp;
    private $ahp;
    private $def;


     function __construct($id, $nome, $dano,$hp, $def) {
        $this->nome = $nome;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
    }

function setId($id){
    $this->id= $id;
}
function setNome($nome){
    $this->nome = $nome;
}
function setDano($dano){
    $this->dano= $dano;
}
function setHp($hp){
    $this->hp = $hp;
}
function setAhp($ahp){
    $this->ahp= $ahp;
}
function setDef($def){
    $this->def = $def;
}

function getId(){
  return $this->id;
}
function getNome(){
  return $this->nome;
}
function getDano(){
  return $this->dano;
}
function getHp(){
  return $this->Hp;
}
function getAhp(){
  return $this->ahp;
}
function getDef(){
  return $this->def;
}
 }
?>