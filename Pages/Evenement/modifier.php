<?php
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");
include("../../Fonctions_Php/encode_decode.php");

$groupes = "";

$query = mysql_query("SELECT * FROM eve_groupe ORDER BY nomGroupe");
while($back = mysql_fetch_assoc($query)) 
{
	$groupes = $groupes . '<option value="' . $back["numeroGroupe"] . '">' . $back["nomGroupe"] . '</option>';
}

$numEve = $_POST['numEve'];
$sql = "SELECT titreLong, titreCourt, description, dateEvenement, dateFinEvenement, lieu, estObligatoire
FROM eve_evenement
WHERE numeroEvenement = $numEve;";

$query = mysql_query($sql);


while($back = mysql_fetch_assoc($query)) 
{
	$titreLong = str_replace("\"", "'", encodeURI(stripslashes($back['titreLong'])));
	$titreCourt = str_replace("\"", "'", encodeURI(stripslashes($back['titreCourt'])));
	$description = str_replace("\"", "'", encodeURI(stripslashes($back['description'])));
	
	$anneeDebut = date("Y" ,$back['dateEvenement']);
	$moisDebut = date("m" ,$back['dateEvenement']);
	$jourDebut = date("d" ,$back['dateEvenement']);
	
	$duree = ceil(($back['dateFinEvenement'] - $back['dateEvenement']) / 86400);
	
	$lieu = str_replace("\"", "'", encodeURI(stripslashes($back['lieu'])));
	$estObligatoire = $back['estObligatoire'];
}
?>

<div id="titreCal"> Modifier un &eacute;v&eacute;nement :</div>
<div id="corpsCal">
<center>
<form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data">
	<table cellpadding="4">
		<tr>
			<td class="descForm">Titre long : </td>
			<td class="Form"><input type="text" name="Eve_titreLong" id="Eve_titreLong" value="<?php echo $titreLong; ?>" class="titreLong" maxlength=60 /></td>
		</tr>
		<tr>
			<td class="descForm">Titre court : </td>
			<td class="Form"><input type="text" name="Eve_titreCourt" id="Eve_titreCourt" value="<?php echo $titreCourt; ?>" class="titreCourt" maxlength=12 /></td>
		</tr>
		<tr>
			<td class="descForm">Description :</td>
			<td class="Form"><textarea name="Eve_description" rows="5" cols="45" id="Eve_description" class="area"><?php echo $description; ?></textarea></td>
		</tr>
		<tr>
			<td class="descForm"><span onclick="ds_sh(this, 'Eve_Form');">Date : </span></td>
			<td class="Form">
				<select name="Eve_Form_annee" id="Eve_Form_annee" class="date" onChange="modification('Eve_Form');">
				</select>
				<select name="Eve_Form_mois" id="Eve_Form_mois" class="date" onChange="modification('Eve_Form');">
				</select>
				<select name="Eve_Form_jour" id="Eve_Form_jour" class="date">
				</select>
			</td>
		</tr>
		<tr>
			<td class="descForm">Dur&eacute;e (en jour) : </td>
			<td class="Form"><input type="text" name="Eve_duree" id="Eve_duree" value="<?php echo $duree; ?>" class="duree" maxlength=3/></td>
		</tr>
		<tr>
			<td class="descForm">Lieu : </td>
			<td class="Form"><input type="text" name="Eve_lieu" id="Eve_lieu" value="<?php echo $lieu; ?>" class="lieu" maxlength=50/></td>
		</tr>
		<tr>
			<td class="descForm"> Type : </td>
			<td class="Form"> <input type="radio" name="Eve_public" id="public" value="1" <?php if($estObligatoire == 1) echo 'checked="checked"'; ?>> <label for="public">public</label> <input type="radio" name="Eve_public" id="prive" value="0" <?php if($estObligatoire == 0) echo 'checked="checked"'; ?>> <label for="prive">priv&eacute;</label> </td>
		</tr>
		<!--
		<tr>
			<td class="descForm">Groupe concern&eacute; : </td>
			
			<td class="Form">
				<select name="Eve_groupeConcern" id="Eve_groupeConcern" class="groupe">
					<?php echo $groupes; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="descForm">Groupe rappel&eacute; : </td>
			<td class="Form">
				<select name="Eve_groupeRappel" id="Eve_groupeRappel" class="groupe">
					<?php echo $groupes; ?>
				</select>
			</td>
		</tr>
		-->
		
	</table>
	<br>
	<div id="Eve_Message" class="message"></div>
	<input type="button" value="Valider" class="boutonForm" onclick="clickModification(<?php echo $numEve; ?>);"/>
	<input type="reset" value="R&eacute;initialiser" class="boutonForm"/>
	
	<input type="hidden" id="anneeDefaut" value="<?php echo $anneeDebut ?>" />
	<input type="hidden" id="moisDefaut" value="<?php echo $moisDebut ?>" />
	<input type="hidden" id="jourDefaut" value="<?php echo $jourDebut ?>" />
</form>
</center>
</div>
