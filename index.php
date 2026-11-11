<?php
require 'autoload.php';




// $us = new Usuario('teste@email.com','senha123', 'JogadorTeste',0);

// $deckinico = new Deckbattle(1, 2, 3, 'Deck Inicial');

$controlador =  new DeckController();
$controlador->mostrar();