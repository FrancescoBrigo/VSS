<?php
include("connect.php");
//cancello le variabili di sessione
unset($_SESSION["user"]);
unset($_SESSION["admin"]);
//rimando alla homepage
header("Location: index.html");
?>