<?php
	//connexion a la bdd
	include("../connexion.php");
	
	$numEve = $_POST['numEve'];

	$sql = "DELETE FROM eve_etre_public WHERE numeroEvenement = $numEve;";

	mysql_query($sql) or die ("Requête incorrecte2");


	$sql = "DELETE FROM eve_etre_rappele_groupe WHERE numeroEvenement = $numEve;";

	mysql_query($sql) or die ("Requête incorrecte3");


	$sql = "DELETE FROM eve_etre_rappele_date WHERE numeroEvenement = $numEve;";

	mysql_query($sql) or die ("Requête incorrecte4");

	$sql = "DELETE FROM eve_evenement WHERE numeroEvenement = $numEve;";

	mysql_query($sql) or die ("Requête incorrecte");
?>
