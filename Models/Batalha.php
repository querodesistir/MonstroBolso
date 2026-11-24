<?php

class Batalha
{
    private $idBa;
    private $ideck;
    private $deckMau;
    private $habitat;
    private $vencedor;

    function __construct($ideck, $deckMau, $habitat, $vencedor)
    {
        $this->ideck = $ideck;
        $this->deckMau = $deckMau;
        $this->habitat = $habitat;
        $this->vencedor = $vencedor;
    }


    // SALVAR PARTIDA
    public function SalvarPartida($pdo)
    {
        $sql = "INSERT INTO batalha
                (ideck, deckMau, habitat, vencedor)
                VALUES
                (:ideck, :deckMau, :habitat, :vencedor)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':ideck' => $this->ideck,
            ':deckMau' => $this->deckMau,
            ':habitat' => $this->habitat,
            ':vencedor' => $this->vencedor
        ]);

        // Recupera o ID gerado pelo banco
        $this->idBa = $pdo->lastInsertId();

        return $this->idBa;
    }


    // SETTERS
    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;
    }

    public function setVencedor($vencedor)
    {
        $this->vencedor = $vencedor;
    }


    // GETTERS
    public function getIdBa()
    {
        return $this->idBa;
    }

    public function getIdeck()
    {
        return $this->ideck;
    }

    public function getDeckMau()
    {
        return $this->deckMau;
    }

    public function getHabitat()
    {
        return $this->habitat;
    }
    public function getVencedor()
    {
        return $this->vencedor;
    }

} ?>