<?php
	session_start();
	
	include("../connexion.php");
	include("../encode_decode.php");
	
	header('Content-type: text/html; charset=ISO-8859-1');
		
	$titreLong = addslashes(utf8_decode(decodeURI($_POST['titreLong'])));
	
	$titreCourt = addslashes(utf8_decode(decodeURI($_POST['titreCourt'])));
	$description = addslashes(utf8_decode(decodeURI($_POST['description'])));
	
	$duree = $_POST['duree'];
	/*$groupeConcern = $_POST['groupeConc'];
	$groupeRappel = $_POST['groupeRap'];*/
	
	$annee = $_POST['annee'];
	$mois = $_POST['mois'];
	$jour = $_POST['jour'];
	
	//nouveauté : ------------------------------------------------------
	$lieu = addslashes(utf8_decode(decodeURI($_POST['Eve_lieu'])));
	$idAuteur = 1; //$_SESSION['login'];
	$auteur = "Test"; //$_SESSION['nom_utilisateur'];
	$dateSaisie = time();
	$publicOuPrive = $_POST['Eve_type']; // 0 si privé, 1 si public
	//----------------------------------------------------------------------
	
	$date_eve = mktime(00, 00, 00, $mois, $jour, $annee);

	$date_fin_eve = $date_eve + (86400 * $duree);

	$date_rappel = $date_eve - 86400;

	//insertion de l'événement
	
	//nouvelle requete -------------------------------------
	$sql = "INSERT INTO eve_evenement(dateSaisie, dateEvenement, dateFinEvenement, titreCourt, titreLong, description, lieu, estObligatoire, idUtilisateur)
	VALUES($dateSaisie, $date_eve, $date_fin_eve, '$titreCourt', '$titreLong', '$description', '$lieu', $publicOuPrive, '$idAuteur');";
	//----------------------------------------------------------
	
	mysql_query($sql) or die ('Erreur :'.mysql_error());

	//inutile a l'heure actuelle, ces requetes concerne la mise en place des groupes
	
	/*$sql = "SELECT max(numeroEvenement) max from eve_evenement";
	 mysql_query($sql) or die ('Erreur :'.mysql_error(). ' ' . $titreLong);
	$numEve = '';
		
	while ($row = mysql_fetch_array($query))
	{
		//on recupère le numero maximum
		$numEve = htmlentities($row["max"], ENT_QUOTES);
	}

	//les groupes

	$sql = "INSERT INTO eve_etre_public VALUES($groupeConcern, $numEve);";

	mysql_query($sql) or die ('Erreur :'.mysql_error());


	$sql = "INSERT INTO eve_etre_rappele_groupe VALUES($groupeRappel, $numEve);";

	mysql_query($sql) or die ('Erreur :'.mysql_error());

	//les rappels
	
	$sql = "INSERT INTO eve_etre_rappele_date VALUES($numEve, $date_rappel, '1');";

	mysql_query($sql) or die ('Erreur :'.mysql_error());*/
?>
