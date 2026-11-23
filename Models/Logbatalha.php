<?php
class LogDeBatalha
    private $idBatalha;
    private $idBatalha;
    private $rodada;
    private $acao;
    private $dano;
    private $dataHora;

    function __construct($idBatalha, $rodada, $acao, $dano, $dataHora)
    {
        $this->idBatalha = $idBatalha;
        $this->rodada = $rodada;
        $this->acao = $acao;
        $this->dano = $dano;
        $this->dataHora = $dataHora;
    }
}
?>