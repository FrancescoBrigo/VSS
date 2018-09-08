<?php
//mi connetto al database e faccio partire la session
	$connection="host=localhost port=5432 dbname=vss user=postgres password=postgres";
	$conn=pg_connect($connection) or die("Connessione fallita");
	session_start();
?>