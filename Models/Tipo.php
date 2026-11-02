 <?php
class Tipo{
   private $tipid;
   private $elemento;


  function   __construct($especie, $elemento){
    $this->especie = $especie;
    $this->elemento = $elemento;
  }
  //não deveria ter uma tabela habitat? ou um onclick com a informação do jogo tá bom?
 //espaço favoravel para o monstro (de acorde com seu tipo) da vantagem;
 //como verifica
 
 
 function setElemento($elemento){
  $this->elemento = $elemento;
   }
function setTipo($tipid){
  $this->tipid = $tipid;
}

function getElemento(){
  return $this->elemento;
}
function getTipo(){
  return $this->tipid;
}

} ?>