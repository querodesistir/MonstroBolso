<?php
 class Monstro extends Tipo{
    public $id;
    public $nome;
    public $dano;
    public $hp;
    public $ahp;
    public $def;


     function __construct(id, nome, dano,hp, def) {
        $this->nome = $nome;
        $this->dano = $dano;
        $this->hp = $hp;
        $this->ahp = $ahp;
        $this->def = $def;
    }

    function Atacada(){
        $con = new conexao();
        $pdo = $con->pdo;


    }

 }

?>