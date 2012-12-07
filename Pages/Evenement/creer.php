<?php
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");
?>

<div id="titreCal"> Cr&eacute;er un &eacute;v&eacute;nement : </div>
<div id="corpsCal">
<center>



<form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data" id="formCreation">
	<table cellpadding="4">
		<tr>
			<td class="descForm">Titre long : </td>
			<td class="Form"><input type="text" name="titreLong" id="Eve_titreLong" value="" class="titreLong" maxlength=32 /></td>
		</tr>
		<tr>
			<td class="descForm">Titre court : </td>
			<td class="Form"><input type="text" name="titreCourt" id="Eve_titreCourt" value="" class="titreCourt" maxlength=5 /></td>
		</tr>
		<tr>
			<td class="descForm">Description :</td>
			<td class="Form"><textarea name="description" rows="5" cols="45" id="Eve_description" class="area"></textarea></td>
		</tr>
		<tr>
			<td class="descForm">Date de début :</td>
			<td class="Form"><input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" class="dateDebut" maxlength=10 /></td>
		</tr>
		<tr>
			<td class="descForm">Date de fin :</td>
			<td class="Form"><input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" class="dateFin" maxlength=10 /></td>
		</tr>
		<tr>
			<td class="descForm">Lieu : </td>
			<td class="Form">
			<select name="lieu">
				<?php
				//Récupération lieu
				$sql = "SELECT libelle FROM aci_lieu";
						
				$resultats = $conn->query($sql);
				while($row = $resultats->fetch())
					echo '<option value="'.$row['libelle'].'"> '.$row['libelle'].'</option>';
				?>
			</select>
			
		</tr>
		<tr>
			<td class="descForm"> Type : </td>
			<td class="Form">
			<input type="radio" name="public" id="public" value="1" checked="checked"> <label for="public">public</label>
			<input type="radio" name="public" id="prive" value="0"> <label for="prive">priv&eacute;</label>
			</td>
		</tr>
		<tr>
			<td class="descForm"> Ajouter un participant : </td>
			<td class="Form"> <input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/> <a id="plusParticipant" href=""> <img src="../../Images/boutonPlusReduit.png"> </a></td>
		</tr>
		<tr>
			<td class="descForm"> Ajouter un groupe de participants : </td>
			<td class="Form"> <input type="text" name="addGroupeParticipant" id="addGroupeParticipant" class="boutonForm"/> <a id="plusGroupeParticipant" href=""> <img src="../../Images/boutonPlusReduit.png"> </a></td>
		</tr>
	</table>
	<br>
	<tr><td>
	<select name="groupe1" id ="groupe1" onchange="selectGroupe2()">
		<?php
		//Récupération lieu
		$sql = "SELECT idgroupe, libelle FROM aci_groupe where length(idgroupe) = 1";
				
		$resultats = $conn->query($sql);
		while($row = $resultats->fetch())
			echo '<option value="'.utf8_encode($row['idgroupe']).'"> '.utf8_encode($row['libelle']).'</option>';
		?>
	</select>
	<select name="groupe2" id ="groupe2">
	</select>
	<select name="groupe3" id ="groupe3">
	</select>
	</td></tr>
	<div id="Eve_Message" class="message"></div>
	<input type="submit" value="Valider" class="boutonForm"/>
	<input type="reset" value="R&eacute;initialiser" class="boutonForm"/>
</form>
</center>
</div>

<?php
if(!empty($_POST['titreCourt']))
{
	$sql = "INSERT INTO `aci_bdd`.`aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `IDLIEU`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
	VALUES ((select max(idevenement) from aci_evenement), '1', '1', '1', 'Test Insertion', 'test', 'Test d''insertion d''événement', '2012-12-16 21:00:00', '2012-12-17 03:00:00', '1', '2012-12-01 00:00:00');";
						
	$resultats = $conn->query($sql);
}
?>

<script>
function selectGroupe2(){
	var list = document.getElementById('groupe1');
	var selectionne = list.value;
	
	var xhr = new XMLHttpRequest();
	
	xhr.onreadystatechange = function(){
	    if(xhr.readystate == 4 && (xhr.status == 200 || xhr.status == 0)){
		    alert('test');
		    var options = xhr.responseXML.getElementsByTagName('option');
		    var i;
		    var select = document.getElementById('groupe2');
		    for(i = 0; i < options.length; i++){
			    select.appendChild(options[i]);
		    }
	    }
	}
	
	xhr.open('POST','../../Fonctions_Php/XMLSelectEvent.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send('valeur='+selectionne);
}
</script>