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

    <title>VSS-Registrati</title>

    <!-- Bootstrap core CSS     <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">-->

    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body style="background-image:url('map.png')"class="text-center">
    <?php
if(isset($_POST['email'])&&isset($_POST['password'])&&isset($_POST['repassword'])&&isset($_POST['cf'])&&($_POST['password']==$_POST['repassword'])){
	$cf=$_POST['cf'];
	$email=$_POST['email'];
	$password=sha1($_POST['password']);
	$queryRegistrazione=pg_query($conn,"INSERT INTO utente(cf,email,password,admin) VALUES ('$cf','$email','$password',0)");
	if($queryRegistrazione){?>
	<script>
		alert("Registrazione effettuata!");
		location.href="index.html";
	</script>
<?php
	}
}
?>
    <form class="form-signin" method="POST">
      <img class="mb-4" src="vss.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal" style="background-color: rgba(222, 216, 216, 0.5);">Registrati</h1>
	  <label for="inputcf" class="sr-only">Codice Fiscale</label>
      <input type="cf" id="inputcf" class="form-control" name="cf" placeholder="Codice Fiscale" required autofocus>
      <label for="inputEmail" class="sr-only">Indirizzo Email</label>
      <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Indirizzo Email" required>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="password" style="margin-bottom:0px;" class="form-control" placeholder="Password" required>
	  <label for="inputRepassword" class="sr-only">Ridigita password</label>
      <input type="password" id="inputRepassword" name="repassword" class="form-control" placeholder="Ridigita password" required>
	<!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>-->
      <button class="btn btn-lg btn-primary btn-block" type="submit">Registrati</button>
      <p class="mt-5 mb-3 text-muted">&copy;2018</p>
    </form>
  </body>
</html>
