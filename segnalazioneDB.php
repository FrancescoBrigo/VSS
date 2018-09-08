<?php
	include("connect.php");
?>
<!DOCTYPE html>
<html>

  <head>

    <title>Segnalazione</title>
	<link rel="icon" href="vss.ico" />


	<script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>

    <link rel="stylesheet" href="https://openlayers.org/en/v5.1.3/css/ol.css" type="text/css">

    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->

    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="font.css" rel="stylesheet">
</head>
<body>
<script>
//creo la funzione logout che mi porta al logout
function logout(){
	location.href="logout.php";
}
</script>
<div class="container-fluid" style="padding-top:15px;">
<div class="row">
    <div id="map" class="map col-lg-9"></div>
	<div id="segnalazione" class="col-lg-3">
	<form action="elabora.php" method="POST" enctype="multipart/form-data">
<div class="form-group">
<h6>SEGNALAZIONE DI MALFORMAZIONI DEL MANTO STRADALE:</h6><p>
Nel caso il GPS non funzionasse:<br>
    <label for="track">

      <input id="track" name="choice-position" value="GPS" type="hidden">

    </label>
	<label for="pointer">

      Punta sulla mappa

      <input id="pointer" name="choice-position" value="POINT" type="radio"/>

	</label>
</div>
<label for="author">Email</label>
<div id="autore" class="form-group">
 <input type="email" class="form-control" id="author" name="author" placeholder="La tua email" value="<?php echo (isset($_SESSION['user'])) ? $_SESSION['user']: ''?>">
</div>
<label for="grandezza">Grandezza della buca</label>
<div id="grandezza" class="form-group">
  <select class="form-control" name="grandezza">
    <option value="0">---</option>	
    <option value="1">Piccola</option>
    <option value="2">Media</option>
    <option value="3">Grande</option>
  </select>
</div>
<!-- Controllo se l'utente loggato è un cantoniere, nel caso mostro la scelta della gravità-->
<?php
if(isset($_SESSION['admin'])&&$_SESSION['admin']==2){?>
<label for="gravita">Gravità</label>
<div id="gravita" class="form-group">
  <select class="form-control" name="gravita">
    <option value="0">---</option>	
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
  </select>
</div>
<?php
}
?>
<div class="form-group"><label>Descrizione:</label><br><textarea name="descrizione" placeholder="Inserisci una descrizione (facoltativa).." rows="5" cols="30"></textarea></div>
<div class="form-group"> Seleziona una foto: <input class="form-control-file" type="file" name="Foto" id="Foto"></div>
<input type="hidden" id="Lat" name="Lat" value="">
<input type="hidden" id="Long" name="Long" value="">
  <input class="btn btn-success" type="submit">
</form>
<div style="margin-top:10px;">
<!-- Controllo se l'utente è loggato, gli mostro il pulsante di logout-->
<?php if(isset($_SESSION['admin'])){?><button onClick="logout()" class="btn btn-primary">Logout</button><?php }?>
  <button class="btn btn-warning" onClick="window.location.reload()">Ricarica</button>
  <button class="btn btn-info" onClick="window.location='index.html'">Torna alla home</button>  
</div>
	</div>
</div>
</div>
<script src="mappa.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>

</html>