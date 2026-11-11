<?php  class DeckController{

    public function mostrar(){
        $monstro =  new Monstro();
        $deck = new Deck();

        $MonstrosDeck =  $monstro->MonstrosnoDeck();
        include '../Views/deck.html';
    }
}