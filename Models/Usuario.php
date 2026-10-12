<?php
 class Usuario{
    $idsu,
    $mail,
    $senha,
    $nível,

 function __construct($mail, $senha, $nivel){
    $this->mail = $mail;
    $this->senha = $senha;
    $this->nivel = $nivel;
 }   
 //ids são auto_incremente ou fazemos uma distribuição de numeros 
 
 function cadasSu(){

   $sql = "INSERT INTO clientes() VALUES (:apelido, :mail, :nivel)"

 $usuario = new Usuario();
   }


 }
?>