<?php
include("connect.php");
//prendo l'id e mi estraggo tutti i dati stampandoli
$id=$_GET['id'];
$result = pg_query($conn,"SELECT * FROM segnalazione Where id='$id'");
while($row = pg_fetch_assoc($result)){
		//controllo tipologia ed estensione del problema
			if($row['estensione']==1){
				$grandezza='Piccola';
			}
			else if($row['estensione']==2){
				$grandezza='Media';
			}
			else
					$grandezza='Grande';
			if($row['tipo']==1){
				$tipo="Malformazione manto stradale";
			}
			else if($row['tipo']==2){
				$tipo="Danni alla segnaletica verticale";
			}
			else
				$tipo="Danni ai dispositivi di sicurezza";
			//stampo i dati e mostro la foto
			echo "<b>Tipo di Segnalazione:</b>".$tipo."<br>";
			echo "<b>Autore:</b>".$row['autore']."<br>";
			echo "<b>Grandezza:</b>".$grandezza."<br>";
			echo "<b>Gravità:</b>".$row['gravita']."/3<br>";			
			echo "<b>Descrizione:</b>".$row['descrizione']."<br>";	
			if($row['foto']!=''){
			echo "<img style='max-width:700px;' src='foto/".$row['foto']."' alt='Foto non disponibile'/>";
			}
			else
				echo "<b>Foto non disponibile</b>";
			//stampo la mappa come iframe passandogli l'id
			echo "<div id='map' style='width:500xp; height:auto;'><iframe  style='width:100%; height:auto;' src='mappa.html?id=$id'></iframe></div>";
}
?>
