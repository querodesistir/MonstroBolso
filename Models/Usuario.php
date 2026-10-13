<?php   //Cadastro e login são funçoes
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
 
 header("location: login.html");

   } //fim do cadasu

  function Login(){

  }

   function desaida(){

alert("Até logo...Ou tarde 🥺 ");
session_destroy();
header("location: login.html");
   } //fim do desaida


 }
?>