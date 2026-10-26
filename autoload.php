<?php

spl_autoload_register(function($classe) {

    $pastas = [
        __DIR__ . "/controllers/",
        __DIR__ . "/Models/",
        __DIR__ . "/Views/"
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