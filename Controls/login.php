
<?php
session_start();
require_once "../Models/conexao.php";
$login = [ 
  "mail" => $mail,
  "senha" => $senha
];

//aqui é referencia, não está totalmente serto e condizente
$loginForm = $_POST['login'] ?? '';
$senha = $_POST['senha'] ?? '';
var_dump($login[$loginForm]);

if(isset($login[$loginForm]) && $login[$loginForm] == $senha ){
 
  $_SESSION['logado'] = true;
  header('location: fablica.php');
}
else{
alert("logar para jogar");

header("location: cadastro.html");
}

?>