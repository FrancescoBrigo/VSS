<!DOCTYPE html>
<html>

  <head>

    <title>Gestione Segnalazioni</title>
    <link rel="icon" href="vss.ico" />

	<script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>

    <link rel="stylesheet" href="https://openlayers.org/en/v5.1.3/css/ol.css" type="text/css">

    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->

    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
//creo la funzione visualizzazione che mi permette di vedere nel modal la singola segnalazione
function visualizzazione(id,giorno,mese,anno,riparata){
	//modifico il titolo del modal
    document.getElementById('titolo').innerHTML =  "Segnalazione n."+id+" del "+giorno+"-"+mese+"-"+anno;
	if(riparata==1)
		//controllo se è riparata per non far vedre il bottone per segnarla riparata
		    document.getElementById('bottoni').innerHTML = "<button type='button' class='btn btn-danger' data-dismiss='modal'>Chiudi</button>"
	else
    document.getElementById('bottoni').innerHTML = "<form><button type='submit' name='riparata' value="+id+" class='btn btn-success'>Riparata</button></form><button type='button' class='btn btn-danger' data-dismiss='modal'>Chiudi</button>"
//faccio la chiamata ajax per prendermi le info da Postgres e la mappa
$.ajax({

   url: "getInfo.php?id="+id,

   success: function(data){

     $("#testo").html(data);

   }

 });
}
//funzione per visualizzare l'anno
function visualAnno(anno){
		if(anno=='Tutti'){
				    document.getElementById('MAtitolo').innerHTML =  "Mappa delle segnalazioni";
		}
		else
	    document.getElementById('MAtitolo').innerHTML =  "Mappa delle segnalazioni anno "+anno;
		
		
		document.getElementById('MAtesto').innerHTML = "<div id='map' style='width:500xp; height:450px;'><iframe  style='width:100%; height:450px;' src='mappaAnno.html?id="+anno+"'></iframe></div>";		
		
//modifico la tabella sottostante
	$.ajax({

   url: "getTab.php?id="+anno,

   success: function(data){

     $("#tabella").html(data);

   }
   });
		
		
		}
//filtro per anno in base all'anno selezionato tramite js e ajax
function filtroAnno(anno){
	$.ajax({

   url: "getTab.php?id="+anno,

   success: function(data){

     $("#tabella").html(data);

   }
   });
}
</script>
  </head>

  <body>
<?php
	include("connect.php");
	//controllo se è stata segnata come riparata una buca, nel caso aggiorno il db, se la query non va a buon fine segnalo
	if(isset($_GET['riparata'])){
		$value=$_GET['riparata'];
		$riparo_query="UPDATE segnalazione SET datariparazione=current_date, riparata=TRUE WHERE id='$value'";
		$riparo=pg_query($conn,$riparo_query);
		if(!$riparo){
			?>
			<script>
			alert("Errore: riprova");
			location.href='segnalazioniBackend.php';
			</script>
			<?php
		}
	}
?>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow" style="margin-bottom: 0px !important;">
      <img src="vss.png" width="38px"></img>
	  <h5 class="my-0 mr-md-auto font-weight-normal" style="margin-left:20px;">VSS - Gestione segnalazioni</h5>
	  <a>Ciao, <?php echo $_SESSION['user']?>&nbsp;</a>
      <a class="btn btn-outline-danger" href="logout.php">Esci</a>
	  </div>
<div class="container-fluid" style="padding-top:15px;">
  <!-- Modal per la singola segnalazione -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal per la singola segnalazione contenuto-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="titolo" class="modal-title"> Segnalazione n. del </h4>
        </div>
        <div id="testo" class="modal-body">
        </div>

        <div class="modal-footer" id="bottoni">
        </div>
      </div>
      
    </div>
  </div>
  <!-- Modal per la mappa dell'anno-->
  <div class="modal fade" id="MappaAnno" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal per la mappa dell'anno contenuto -->
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="MAtitolo" class="modal-title">Mappa Segnalazioni Anno </h4>
        </div>
        <div id="MAtesto" class="modal-body">
        </div>

        <div class="modal-footer" id="MAbottoni">
			<button type='button' class='btn btn-danger' data-dismiss='modal'>Chiudi</button>
		</div>
      </div>
      
    </div>
  </div>
<div class="row" style="margin-left:0px;">
<div style="line-height: 2.0em;">Anno:</div>
<select class="form-control" onChange="filtroAnno(document.getElementById('Anno').value)" style="margin-left:10px; width:auto;" name="Anno" id="Anno">
<option>Tutti</option>
<option>2018</option>
<option>2017</option>
</select>
<button  style="margin-left:10px;" onClick="visualAnno(document.getElementById('Anno').value)" class="btn btn-info" data-toggle="modal" data-target="#MappaAnno" type="button">Visualizza su mappa</button>
</div>
<div id="tabella" style="margin-top:10px;">
		<table class="table table-striped table-bordered table-hover table-condensed">

		<thead>

      			<tr>
			<th>Data</th>
			<th>Numero</th>
			<th>Tipo</th>
			<th>Autore</th>
			<th>Data Riparazione</th>
			<th>Dettagli</th>
			</tr>
		</thead>	
		<tbody>
		<?php
		//lancio la query per prenderre le segnalazioni ordinate per data e divise per anno
		$result = pg_query($conn,"SELECT * FROM segnalazione order by data");
		while($row = pg_fetch_assoc($result)){
				$data=$row['data'];
				$giorno=substr($data,8);
				$mese=substr($data,5,2);
				$anno=substr($data,0,4);
				$dataS = date("d-m-Y", strtotime($data));
				$dataR = $row['datariparazione'];
				if($dataR==''){
					$dataR='';
				}
				else{
					$dataR = date("d-m-Y", strtotime($dataR));
				}
		?>
			<tr>
			<td><?php echo $dataS;?></td>
			<td><?php echo $row['id'];?></td>
			<td><?php 
			if($row['tipo']==1)
				echo "Manto stradale";
			else if ($row['tipo']==2)
				echo "Segnaletica";
			else
				echo "Dispositivi di sicurezza";
			$riparata=false;
			if($row['riparata'])
				$riparata=true;
			?></td>
			<td><?php echo $row['autore'];?></td>
			<td><?php echo $dataR;?></td>
			<td><button id="apri" onClick="visualizzazione(<?php echo $row['id'].','.$giorno.','.$mese.','.$anno.','.$riparata;?>)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Apri</button></td>
			</tr>
			<?php
			}
			?>
			</tbody>
</div>
</div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>