 <?php
class Tipo{
   public $tipid;
   public $especie;
   private $elemento;


  function   __construct($especie, $elemento){
    $this->especie = $especie;
    $this->elemento = $elemento;
  }
 
  function meuLugar(){
  $con = new conecxao();
  }

}
 ?>