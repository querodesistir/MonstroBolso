 <?php
class Tipo{
   public $tipid;
   public $especie;
   private $elemento;


  function   __construct($especie, $elemento){
    $this->especie = $especie;
    $this->elemento = $elemento;
  }
  //não deveria ter uma tabela habitat? ou um onclick com a informação do jogo tá bom?
 //espaço favoravel para o monstro (de acorde com seu tipo) da vantagem;
 //como verifica
  function meuLugar(){ 
  $con = new conecxao();
  $pdo = $con->pdo;
 //essa função não tem que ficar na união deles?
}//fim do meu lugar
 
 function setEspec($especie){
  $this->especie = $especie;
 }
 function setElemento($elemento){
  $this->elemento = $elemento;
   }
function setTipo($tipid){
  $this->tipid = $tipid;
}

function getEspec(){
  return $this->especie;
}
function getElemento(){
  return $this->elemento;
}
function getTipo(){
  return $this->tipid;
}

} ?>