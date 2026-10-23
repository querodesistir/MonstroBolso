<?php

spl_autoload_register(function($classe) {

    $pastas = [
        __DIR__ . "/controlls/",
        __DIR__ . "/Models/"
    ];

    foreach($pastas as $pasta) {
        $pagina = $pasta . $classe . ".php";

        if(file_exists($pagina)) {
            require_once $pagina;
            return;
        }
    }

});

?>