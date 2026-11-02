<?php
class Deckbattle{
private  $ideck;
private  $idm1; 
private $idm2; 
private  $idm3; 

function __construct($idm1, $idm2, $idm3, $titulo) {
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
    } //fim construct

} //fim classe que antes comi
?>