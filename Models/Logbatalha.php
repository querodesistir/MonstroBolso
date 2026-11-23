<?php
class LogBatalha
{
    private $idlog;
    private $idBa;
    private $idsu;
    private $rodada;
    private $acao;
    private $dano;
    private $itid;
    private $dataHora;

    function __construct($idBa, $idsu, $rodada, $acao, $dano, $itid = null)
    {
        $this->idBa = $idBa;
        $this->idsu = $idsu;
        $this->rodada = $rodada;
        $this->acao = $acao;
        $this->dano = $dano;
        $this->itid = $itid;
    }
}
?>