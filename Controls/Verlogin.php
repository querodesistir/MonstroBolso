<?php
session_start();
//verificar se o usuario esta logado 
//senão estiver é enviado para fazer login
//por no autoload
  if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }else{
             header('Location: login.php');
            exit;
        }

?>