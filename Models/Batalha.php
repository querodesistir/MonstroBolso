<?php
class Batalha
{
    private $idba; 
    private $ideck;
    private $ideckMau;
    private $habitat;
    private $datah;

    function __construct($iddeck, $deckInimigo, $habitat, $vencedor, $data)
    {
        $this->deckJogador = $iddeck;
        $this->deckInimigo = $deckInimigo;
        $this->habitat = $habitat;
        $this->vencedor = $vencedor;
        $this->data = $data;
    }
}
?>