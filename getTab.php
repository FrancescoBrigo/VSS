<?php
include("connect.php");
//prendo l'anno e controllo se è numerico o "Tutti", di conseguenza creo la query corretta
$anno=$_GET['id'];
if($anno=='Tutti'){
	$query="SELECT * FROM segnalazione order by data";
}
else{
	$anno2=$anno+1;
	$query="SELECT * FROM segnalazione where data>'01-01-$anno' and data<'01-01-$anno2' order by data";
}
?>
<!-- replico la struttura della tabella per aggiornarla-->
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
		$result = pg_query($conn,$query);
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
			
			?></td>
			<td><?php echo $row['autore'];?></td>
			<td><?php echo $dataR;?></td>
			<td><button id="apri" onClick="visualizzazione(<?php echo $row['id'].','.$giorno.','.$mese.','.$anno;?>)" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Apri</button></td>
			</tr>
			<?php
			}
			?>
			</tbody>
