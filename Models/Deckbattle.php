<?php
class Deckbattle{
private  $ideck;
private  $idm1; 
private $idm2; 
private  $idm3; 
private $titulo;

function __construct($idm1, $idm2, $idm3, $titulo) {
        $this->idm1 = $idm1;
        $this->idm2 = $idm2;
        $this->idm3 = $idm3;
        $this->titulo = $titulo;
    } //fim construct

} 
 function cadasDeck(){ //apagar anter de enviar
   $con = new conexao();
   $pdo = $con->pdo;
   try {
     $sql = "INSERT INTO Deck(mail,senha, apelido, nivel) VALUES (:mail,:senha, :apelido, :nivel)";
  
     
    $stmt = $pdo->prepare($sql);

    $dados[':mail'] = $this->mail; 
    $dados[':senha'] = password_hash($this->senha, PASSWORD_DEFAULT); //$dados[':senha'] = $this->senha; ia aconselhou trocar
    $dados[':apelido'] = $this->apelido;
    $dados[':nivel'] = $this->nivel;

    $stmt->execute($dados);

    $this->idsu = $pdo->lastInsertID(); 

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
   } //fim do cadasu

function Addeck(){ //ESSA FUNÇÃO É PARA ESTAR ONDE?
    $con = new conexao();
    $pdo = $con->pdo;
    try {
        $sql = "UPDATE DECKBATTLE SET ";
}


?>