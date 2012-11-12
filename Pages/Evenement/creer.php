<?php
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");

$groupes = "";

$query = mysql_query("SELECT * FROM eve_groupe ORDER BY nomGroupe");
while($back = mysql_fetch_assoc($query)) 
{
	$groupes = $groupes . '<option value="' . $back["numeroGroupe"] . '">' . $back["nomGroupe"] . '</option>';
}
?>

<div id="titreCal"> Cr&eacute;er un &eacute;v&eacute;nement : </div>
<div id="corpsCal">
<center>
<form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data">
	<table cellpadding="4">
		<tr>
			<td class="descForm">Titre long : </td>
			<td class="Form"><input type="text" name="Eve_titreLong" id="Eve_titreLong" value="" class="titreLong" maxlength=60 /></td>
		</tr>
		<tr>
			<td class="descForm">Titre court : </td>
			<td class="Form"><input type="text" name="Eve_titreCourt" id="Eve_titreCourt" value="" class="titreCourt" maxlength=12 /></td>
		</tr>
		<tr>
			<td class="descForm">Description :</td>
			<td class="Form"><textarea name="Eve_description" rows="5" cols="45" id="Eve_description" class="area"></textarea></td>
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
			<td class="Form"><input type="text" name="Eve_duree" id="Eve_duree" value="" class="duree" maxlength=3/></td>
		</tr>
		<tr>
			<td class="descForm">Lieu : </td>
			<td class="Form"><input type="text" name="Eve_lieu" id="Eve_lieu" value="" class="lieu" maxlength=50/></td>
		</tr>
		<tr>
			<td class="descForm"> Type : </td>
			<td class="Form"> <input type="radio" name="Eve_public" id="public" value="1" checked="checked"> <label for="public">public</label> <input type="radio" name="Eve_public" id="prive" value="0"> <label for="prive">priv&eacute;</label> </td>
		</tr>
		<!--
		<tr>
			<td class="descForm">Groupe concern&eacute; : </td>
			
			<td class="Form">
				<select name="Eve_groupeConcern" id="Eve_groupeConcern" class="groupe">
					<?php //echo $groupes; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="descForm">Groupe rappel&eacute; : </td>
			<td class="Form">
				<select name="Eve_groupeRappel" id="Eve_groupeRappel" class="groupe">
					<?php //echo $groupes; ?>
				</select>
			</td>
		</tr>
		-->
		
	</table>
	<br>
	<div id="Eve_Message" class="message"></div>
	<input type="button" value="Valider" class="boutonForm" onclick="clickCreation();"/>
	<input type="reset" value="R&eacute;initialiser" class="boutonForm"/>
</form>
</center>
</div>