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

public function SalvarPartida($pdo)
{
    $sql = "INSERT INTO batalha (ideck, deckMau, habitat, vencedor)
            VALUES (:ideck, :deckMau, :habitat, :vencedor)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':ideck' => $this->ideck,
        ':deckMau' => $this->deckMau,
        ':habitat' => $this->habitat,
        ':vencedor' => $this->vencedor
    ]);

    $this->idBa = $pdo->lastInsertId();
} //Fim Salvar Batalha

public function setHabitat($habitat)
{
    $this->habitat = $habitat;
}
public function setVence($vencedor)
{
    $this->vencedor = $vencedor;
}

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

public function getData()
{
    return $this->data;
}
}
?>