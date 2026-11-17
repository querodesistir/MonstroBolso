<?php  class DeckController{

    public function mostrar(){
        $monstro =  new Monstro("fabricio", "fa.png", 10, 100, 82, 30, 2, 20, 0);
        $deck = new Deck();

        $MonstrosDeck =  $monstro->MonstrosnoDeck();
        include '../Views/deck.html';
    }
}