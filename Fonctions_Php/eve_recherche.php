<?php
	session_start();
	
	include("../connexion.php");
	include("../encode_decode.php");
	include("../diverses_fonctions.php");
	
	header('Content-type: text/html; charset=ISO-8859-1');
	
	$idUtilisateur = $_SESSION['login'];

	$prefixe = "SELECT eve_evenement.*, eve_utilisateur.nomCompletUtilisateur FROM eve_evenement
				INNER JOIN eve_utilisateur ON eve_evenement.idUtilisateur = eve_utilisateur.idUtilisateur
				WHERE (estObligatoire = 1 OR (estObligatoire = 0 and eve_utilisateur.idUtilisateur = '$idUtilisateur')) AND ";
	
	$type_rech_nom = $_POST['rech_Nom_Par'];
	$val_rech_nom = utf8_decode(decodeURI($_POST['titreSelect']));
	$val_rech_nom = str_replace("%25", "!%", $val_rech_nom);

	$jour = $_POST['jour'];
	$mois = $_POST['mois'];
	$annee = $_POST['annee'];
	
	$sql = "";

	if(($type_rech_nom != null) && ($val_rech_nom != null) && (strlen($val_rech_nom) > 1))
	{
		if ($type_rech_nom == "commence")
		{
			$sql = $sql . $prefixe . "titreLong like '$val_rech_nom%' ";
			$prefixe = "AND ";
		}
		else if ( $type_rech_nom == "fini" )
		{
			$sql = $sql . $prefixe . "titreLong like '%$val_rech_nom' ";
			$prefixe = "AND ";
		}
		else
		{
			$sql = $sql . $prefixe . "titreLong like '%$val_rech_nom%' ";
			$prefixe = "AND ";
		}
	}

	if(($jour != null) && ($mois != null) && ($annee != null))
	{
		if($jour == 0)
		{
			if($mois== 0)
			{
				$timestamp_debut = mktime(00, 00, 00, 01, 01, $annee);
				$timestamp_fin = mktime(23, 59, 59, 12, 31, $annee);
			}
			else
			{
				$timestamp_debut = mktime(00, 00, 00, $mois, 01, $annee);
				$timestamp_fin = mktime(23, 59, 59, $mois, retourneJour($annee, $mois), $annee);
			}
		}
		else
		{
			$timestamp_debut = mktime(00, 00, 00, $mois, $jour, $annee);
			$timestamp_fin = mktime(23, 59, 59, $mois, $jour, $annee);
		}
		$sql = $sql . $prefixe . "dateEvenement >= $timestamp_debut and dateEvenement <= $timestamp_fin ";
		$prefixe = "AND ";
	}
	
	if ($sql != "")
	{
		$sql = $sql . "ORDER BY titreLong";
		
		$query = mysql_query($sql);

		while($back = mysql_fetch_assoc($query))
		{			
			$titre = '<span class=\'titre\'>' . str_replace("\"", "'", encodeURI(stripslashes($back["titreLong"]))) . '</span>';
			$lieu = '<span class=\'lieu\'>' . str_replace("\"", "'", encodeURI(stripslashes($back["lieu"]))) . '</span>';
			$dateEve = '<span class=\'date\'>' .  date('d/m/Y', $back["dateEvenement"]) . '</span>';
			
			$description = '<span class=\'description\'>' .  str_replace("\"", "'", encodeURI(stripslashes($back["description"]))) . '</span>';
			
			$datePost = '<span class=\'datePost\'>' . date('d/m/Y',$back["dateSaisie"]) . '</span>';
			$auteur = str_replace("\"", "'", encodeURI(stripslashes($back["nomCompletUtilisateur"])));
			
			echo '<option label="' . $titre . '<br/><br/>' . $lieu . ' le ' . $dateEve . '<br/><br/>';
			echo $description . '<br/><br/>';
			echo 'Post&eacute; le ' . $datePost . ' par ' . $auteur;
			
			echo '" id="' . $auteur . '" value="' . $back["numeroEvenement"] . '">' .$back["titreLong"] . '</option>';
	    }
	}
?>
