<?php
session_start();
require_once "../Models/conexao.php";
require_once "../autoload.php";

$deck = new Deck();

$esimdodeck = $deck->Addeck($ideck, 
$_SESSION['idsu'],
 $id);
?>