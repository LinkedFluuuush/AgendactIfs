<?php
	session_start();
	
	include("../connexion.php");
	include("../encode_decode.php");
	
	header('Content-type: text/html; charset=ISO-8859-1');
	
	$numEve = $_POST['numEve'];

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
	$idAuteur = $_SESSION['login'];
	$auteur = $_SESSION['nom_utilisateur'];
	$publicOuPrive = $_POST['Eve_type']; // 0 si privé, 1 si public
	//----------------------------------------------------------------------
	
	$date_eve = mktime(00, 00, 00, $mois, $jour, $annee);

	$date_fin_eve = $date_eve + (86400 * $duree);

	$date_rappel = $date_eve - 86400;

	//update de l'événement
	
	//nouvelle requete -------------------------------------
	$sql = "UPDATE eve_evenement SET dateEvenement = $date_eve, dateFinEvenement =
$date_fin_eve, titreCourt = '$titreCourt', titreLong = '$titreLong', description =
'$description', lieu = '$lieu', estObligatoire = $publicOuPrive, idUtilisateur =
'$idAuteur' where numeroEvenement = $numEve;";
	
	//echo $sql;
	//----------------------------------------------------------
	
	mysql_query($sql) or die ('Erreur :'.mysql_error());
?>
