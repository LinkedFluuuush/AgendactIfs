<?php
	include("../connexion.php");
	include("../encode_decode.php");
	
	$valeur = $_POST['auteur'];
	$valeur = str_replace("%25", "!%", $valeur);
	
	$sql = "SELECT DISTINCT(nomCompletUtilisateur) 
			FROM eve_utilisateur
			WHERE nomCompletUtilisateur like '%$valeur%'
			ORDER BY nomCompletUtilisateur";
		
	$query = mysql_query($sql);

	while($back = mysql_fetch_assoc($query))
	{
		$auteur = str_replace("\"", "'", encodeURI(stripslashes($back["nomCompletUtilisateur"])));
		echo '<option value="' . $auteur . '">' . $auteur . '</option>';
	}
?>
