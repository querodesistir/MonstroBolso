<?php

class Deck_Inimigo {
    private $iddeck_inimigo;
    private $idm1;
    private $idm2;
    private $idm3;
    private $titulo;

    public function __construct($idm1, $idm2, $idm3, $titulo) {
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
    }

    public function getIddeckInimigo() {
        return $this->iddeck_inimigo;
    }

    public function getIdm1() {
        return $this->idm1;
    }

    public function getIdm2() {
        return $this->idm2;
    }

    public function getIdm3() {
        return $this->idm3;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setIddeckInimigo($iddeck_inimigo) {
        $this->iddeck_inimigo = $iddeck_inimigo;
    }

    public function setIdm1($idm1) {
        $this->idm1 = $idm1;
    }

    public function setIdm2($idm2) {
        $this->idm2 = $idm2;
    }

    public function setIdm3($idm3) {
        $this->idm3 = $idm3;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
} ?>