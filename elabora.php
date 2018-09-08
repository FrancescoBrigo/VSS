  <head>

    <title>SEGNALAZIONE</title>

    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	


  </head>
<?php
include("connect.php");
//prendo le variabili da inserire
$lat=$_POST['Lat'];
$lon=$_POST['Long'];
$author=$_POST['author'];
$grandezza=$_POST['grandezza'];
$descrizione=$_POST['descrizione'];
//controllo se le variabili necessarie sono state inserite, altrimenti do errore e torno indietro
if (!(isset($autore))||!(isset($lan))||!(isset($lon))){
?> 
<script>
alert("Errore: dati mancanti, controlla la posizione e l'autore");
location.href='segnalazioneDB.php';
</script>
<?php
exit;
}
//controllo se c'è la gravità altrimenti la setto a 0
if(isset($_POST['gravita'])){
$gravita=$_POST['gravita'];
}
else
	$gravita=0;
$id;
//faccio la query e le faccio restituire l'id seriale che prende la segnalazione, se non va a buon fine restituisco l'errore e torno alla segnalazione
$result = pg_query($conn, "INSERT INTO segnalazione(data,tipo,autore,gravita,estensione,descrizione,geom) VALUES (current_date,'1','$author','$gravita','$grandezza','$descrizione',ST_Transform(ST_SetSRID(ST_MakePoint($lon,$lat),4326),3003)) RETURNING id");
if (!$result) {
?> 
<script>
alert("Errore: riprova");
location.href='segnalazioneDB.php';
</script>
<?php
exit;
}
//prendo l'id e ci associo la foto rinominandola come id in modo da collegarla
while($row = pg_fetch_assoc($result)){
	$id=$row['id'];
}
$folder='foto/';
$nomefile;
if(isset($_FILES['Foto']['name'])){
	$path = $_FILES['Foto']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$destination=$folder;
	$origin=$_FILES['Foto']['tmp_name'];
	$nomefile=$id.".".$ext;
    $destination=$destination.basename($nomefile);
    move_uploaded_file($origin, $destination);
}
//controllo se c'è il formato e faccio la query
if(substr($nomefile,-1)!='.'){
$fotoQuery = pg_query($conn, "UPDATE segnalazione SET foto='$nomefile' WHERE id='$id'");
}
//se la segnalazione è andata a buon fine faccio logout e torno alla home
if($result){
unset($_SESSION["user"]);
unset($_SESSION["admin"]);
?> 
<script>
alert("Segnalazione effettuata! Grazie");
location.href='index.html';
</script>
<?php	
}
?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>