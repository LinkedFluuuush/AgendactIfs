<?php
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");
?>

<div id="titreCal"> Cr&eacute;er un &eacute;v&eacute;nement : </div>
<div id="corpsCal">
<center>



<form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data">
	<table cellpadding="4">
		<tr>
			<td class="descForm">Titre long : </td>
			<td class="Form"><input type="text" name="Eve_titreLong" id="Eve_titreLong" value="" class="titreLong" maxlength=32 /></td>
		</tr>
		<tr>
			<td class="descForm">Titre court : </td>
			<td class="Form"><input type="text" name="Eve_titreCourt" id="Eve_titreCourt" value="" class="titreCourt" maxlength=5 /></td>
		</tr>
		<tr>
			<td class="descForm">Description :</td>
			<td class="Form"><textarea name="Eve_description" rows="5" cols="45" id="Eve_description" class="area"></textarea></td>
		</tr>
		<tr>
			<td class="descForm">Date de début (format JJ/MM/YY) :</td>
			<td class="Form"><input type="text" name="Eve_dateDebut" id="Eve_dateDebut" value="" class="dateDebut" maxlength=8 /></td>
		</tr>
		<tr>
			<td class="descForm">Date de fin (format JJ/MM/YY) :</td>
			<td class="Form"><input type="text" name="Eve_dateFin" id="Eve_dateFin" value="" class="dateFin" maxlength=8 /></td>
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
			<td class="Form"> <input type="radio" name="Eve_public" id="public" value="1" checked="checked"> <label for="public">public</label> <input type="radio" name="Eve_public" id="prive" value="0"> <label for="prive">priv&eacute;</label> </td>
		</tr>
		
	</table>
	<br>
	<div id="Eve_Message" class="message"></div>
	<input type="button" value="Valider" class="boutonForm" onclick="clickCreation();"/>
	<input type="reset" value="R&eacute;initialiser" class="boutonForm"/>
</form>
</center>
</div>