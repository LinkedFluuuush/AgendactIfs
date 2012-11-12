<?php 
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");

$mesGroupes = "";
$mesDates = "";

$query = mysql_query("SELECT distinct dateEvenement FROM eve_evenement ORDER BY dateEvenement");
while($back = mysql_fetch_assoc($query)) 
{
	$date = date('d/m/Y', $back["dateEvenement"]);
	$mesDates = $mesDates . '<option value="' . $back["dateEvenement"] . '">' .$date. '</option>';
}

$query = mysql_query("SELECT * FROM eve_groupe ORDER BY nomGroupe");
while($back = mysql_fetch_assoc($query)) 
{
	$mesGroupes = $mesGroupes . '<option value="' . $back["numeroGroupe"] . '">' . $back["nomGroupe"] . '</option>';
}
?>

	
	<div id="titreCal"> Recherche d'&eacute;v&eacute;nements </div>
	<div id="corpsCal">
	<center>
	<div id="rechercheEvenement">
		
		<!-- premiere ligne : recherche par titre -->
		<p>		
			<input type="checkbox" id="recherche_nom" name="recherche_nom" onChange="switchRechNom(this);" checked>
			
			<select name="rech_Nom_Par" id="rech_Nom_Par" class="rechNom" onchange="rechercher();">
				<!-- cet espace corespond à la liste d'options pour la recherche par nom -->
				<option value="commence">Commen&ccedil;ant par</option>
				<option value="contient">Contenant</option>
				<option value="fini">Se terminant par</option>
			</select>
			
			<input type="text" id="titreSelect" name="titre" class="rechNom" onKeyup="modifTitre();"/>
		</p>
		<!-- deuxieme ligne : recherche par date -->
		<p>
			<input type="checkbox" id="recherche_date" name="recherche" onChange="switchRechDate(this);">
			
			<select name="annee" id="Eve_Rech_annee" class="rechDate" onChange="modification('Eve_Rech');rechercher();">
				<!-- cet espace corespond à la liste d'année générées automatiquement avec des fonctions javascript -->
			</select>
			<select name="mois" id="Eve_Rech_mois" class="rechDate" onChange="modification('Eve_Rech');rechercher();">
				<!-- cet espace corespond à la liste de mois générés automatiquement avec des fonctions javascript -->
			</select>
			<select name="jour" id="Eve_Rech_jour" class="rechDate" onchange="rechercher();">
				<!-- cet espace corespond à la liste de jours générés automatiquement avec des fonctions javascript -->
			</select>
		</p>
		<!-- troisieme ligne : recherche par groupe -->
		<p>
			<input type="checkbox" id="recherche_auteur" name="recherche_auteur" onChange="switchRechAuteur(this);">
			<input type="text" id="auteur_select" name="titre" class="rechNom" onKeyup="modifAuteur();" />
			<select name="liste_auteur" id="liste_auteur" class="rechNom" onChange="rechercher();" ></select>
		</p>
	</div>
	<div id="resultatEvenement">
		<select name="Eve_evenement" id="affichageSelect" size="5" class="listeRes" onChange="eve_selected()">
			<!-- cet espace corespond à la liste des resultats de la recherche -->
		</select>
		
		<input type="button" id="eve_ajouter" value="Ajouter" class="bouton" onclick="getEveCrea(null, null, null);" /><br/>
		<input type="button" id="eve_modifier" value="Modifier" class="bouton" onclick="rech_click_Modifier();" disabled="disabled" /><br/>
		<input type="button" id="eve_supprimer" value="Supprimer" class="bouton" onclick="rech_click_supprimerEve()" disabled="disabled"/>
	</div>
	<br/>
	<div id="selected_description">
	</div>
</center>
</div>
