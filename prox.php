<?php			//Proxy per evitare la CORS
$t="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$u=str_replace("http://localhost/Vss/prox.php?url=","",$t);
//echo $u;
$u=str_replace("%3F","?",$u);
$u=str_replace("&amp","",$u);
//echo $u;
echo file_get_contents( $u );
?>