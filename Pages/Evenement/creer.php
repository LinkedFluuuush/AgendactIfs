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
			<td class="Form"><input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YY" class="dateDebut" maxlength=8 /></td>
		</tr>
		<tr>
			<td class="descForm">Date de fin :</td>
			<td class="Form"><input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YY" class="dateFin" maxlength=8 /></td>
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