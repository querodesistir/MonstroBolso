<?php
class Batalha
{
    private $idBa;
    private $ideck;
    private $deckMau;
    private $habitat;
    private $vencedor;
    private $data;

    function __construct($ideck, $deckMau, $habitat, $vencedor)
    {
        $this->ideck = $ideck;
        $this->deckMau = $deckMau;
        $this->habitat = $habitat;
        $this->vencedor = $vencedor;
    }

    
}
?>