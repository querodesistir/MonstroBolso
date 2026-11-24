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


    function __construct($idBa, $idsu, $rodada, $acao, $dano, $itid = null)
    {
        $this->idBa = $idBa;
        $this->idsu = $idsu;
        $this->rodada = $rodada;
        $this->acao = $acao;
        $this->dano = $dano;
        $this->itid = $itid;
    }


    // SALVAR REGISTRO DA BATALHA
    public function salvarLog($pdo)
    {
        $sql = "INSERT INTO logbatalhas
                (idBa, idsu, rodada, acao, dano, itid)
                VALUES
                (:idBa, :idsu, :rodada, :acao, :dano, :itid)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':idBa' => $this->idBa,
            ':idsu' => $this->idsu,
            ':rodada' => $this->rodada,
            ':acao' => $this->acao,
            ':dano' => $this->dano,
            ':itid' => $this->itid
        ]);

        // Recupera o ID gerado pelo banco
        $this->idlog = $pdo->lastInsertId();

        return $this->idlog;
    }


    // SETTERS
    public function setIdBa($idBa)
    {
        $this->idBa = $idBa;
    }

    public function setIdsu($idsu)
    {
        $this->idsu = $idsu;
    }

    public function setRodada($rodada)
    {
        $this->rodada = $rodada;
    }

    public function setAcao($acao)
    {
        $this->acao = $acao;
    }

    public function setDano($dano)
    {
        $this->dano = $dano;
    }

    public function setItid($itid)
    {
        $this->itid = $itid;
    }


    // GETTERS
    public function getIdlog()
    {
        return $this->idlog;
    }

    public function getIdBa()
    {
        return $this->idBa;
    }

    public function getIdsu()
    {
        return $this->idsu;
    }

    public function getRodada()
    {
        return $this->rodada;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function getDano()
    {
        return $this->dano;
    }

    public function getItid()
    {
        return $this->itid;
    }
} ?>