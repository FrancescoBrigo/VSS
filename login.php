<?php
	include("connect.php");
?>
<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="vss.ico" />

    <title>VSS-Login</title>

    <!-- Bootstrap core CSS     <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">-->

    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body style="background-image:url('map.png')" class="text-center">
  <?php
  $landingPage=$_GET['landing'];
if(isset($_POST['email'])&&isset($_POST['password'])){
	$email=$_POST['email'];
	$password=sha1($_POST['password']);
	//DEVO FARE LA QUERY, SE L'UTENTE è RICONOSCIUTO E ADMIN=1 ALLORA VADO IN BACKEND ALTRIMENTI VADO IN SEGNALAZIONE MA COMPILO LA MAIL!
	$queryLogin=pg_query($conn,"SELECT * FROM utente WHERE email='$email' AND password='$password'");
	while($row = pg_fetch_assoc($queryLogin)){
		$_SESSION["user"] = $email;
		$_SESSION["admin"] = $row['admin'];
		if($landingPage=="segnalazioniBackend.php"&&$_SESSION["admin"]==0){
		?><script>
		alert("Non sei autorizzato ad accedere a questa pagina");
		location.href = "index.html";
		</script><?php
		}
		?><script>
		var landingPage='<?php echo $landingPage;?>';
		location.href = landingPage;
		</script><?php
	}

}
?>
<!-- Creo la form per fare login -->
    <form class="form-signin" method="POST">
      <img class="mb-4" src="vss.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal" style="background-color: rgba(222, 216, 216, 0.5);">Entra</h1>
      <label for="inputEmail" class="sr-only">Indirizzo Email</label>
      <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Indirizzo Email" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Entra</button>
      <p class="mt-5 mb-3 text-muted">&copy;2018</p>
    </form>
  </body>
</html>
