<?php

session_start();

echo "Até logo...Ou tarde 🥺 ";
session_destroy();
header("location: login.html");

?>