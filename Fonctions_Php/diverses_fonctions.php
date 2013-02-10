<?php header( 'content-type: text/html; charset=utf-8' );

function descGroupe($idGroupe, $conn, $i){
	$req = "SELECT idgroupe, libelle FROM aci_groupe WHERE idgroupe IN (SELECT idgroupe_1 FROM aci_contenir WHERE idgroupe = ".$idGroupe.")";
	$resultats = $conn->query($req);
	while($row = $resultats->fetch()){		
		$option='<div class="'.$idGroupe.'" style="display:none;">';
		for($j = 0; $j < $i; $j++){
			$option.="&nbsp &nbsp &nbsp";
		}
		$option.='<img id="'.utf8_encode($row['idgroupe']).'" src="../../Images/arborescencePlus.png" onclick="developper('.utf8_encode($row['idgroupe']).')"/>';
		$option.='<label for="'.utf8_encode($row['idgroupe']).'" onclick="developper('.utf8_encode($row['idgroupe']).')"> '.$row['libelle'].'</label>';
		$option.='<input type="checkbox" name="groupe[]" value="'.utf8_encode($row['idgroupe']).'" id="'.utf8_encode($row['idgroupe']).'"/><br/>';
		echo $option;
		descGroupe($row['idgroupe'], $conn, $i+1);
		echo "</div>";
	}
}

function saisieFormString($chaine)
{
	if(!empty($_POST["$chaine"]))
		echo $_POST["$chaine"];
	else
		echo "";
}

function regexChaine($chaine, $limiteTaille)
{
	/* Gestion des accents */
	$chaine = utf8_decode($chaine);
	$chaine = strtr($chaine,
	utf8_decode("ÀÁÂÃÄÅáãåÒÓÔÕÖØòóôõøÈÉÊËÇÌÍÎÏìíÙÚÛÜùúûÿÑñ"),
	utf8_decode("AAAAAAaaaOOOOOOoooooEEEECIIIIiiUUUUuuuyNn"));
	$chaine = utf8_encode($chaine);

	$regex = "#^[A-Za-z0-9\'\" /_()[],;\.:!?%£°=+*€$#éèàöôäâçùïî-]{1,$limiteTaille}$#";
	
	if(!preg_match($regex, $chaine))
		return false;
	else
		return true;
}

function regexDate($date)
{
	if(empty($date))
		return false;
		
	if(!preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#", $date))
		return false;
	else
	{
		$date = explode('/', $date);
		if($date[0] < 1 || $date[0] > retourneJour($date[2], $date[1]))
			return false;
		if($date[1] < 1 || $date[1] > 12)
			return false;
		if($date[2] < date("Y"))
			return false;
			
		return true;
	}
}

function regexHeure($heure)
{
	if(empty($heure))
		return false;
		
	if(!preg_match("#^[0-9]{2}:[0-9]{2}$#", $heure))
		return false;
	else
	{
		$heure = explode(':', $heure);
		if($heure[0] < 0 || $heure[0] > 23)
			return false;
		if($heure[1] < 0 || $heure[1] > 59)
			return false;
			
		return true;
	}
}

//Elimine tous les accents non français
function accents($texte)
{
	$texte = utf8_decode($texte);
	$texte = strtr($texte,
	utf8_decode("ÀÁÂÃÄÅáâãåÒÓÔÕÖØòóõøÈÉÊËÇÌÍÎÏìíÙÚÛÜúÑñ"),
	utf8_decode("AAAAAAAAAAOOOOOOOOOOEEEEçIIIIIIUUUUUNN"));
	$texte = utf8_encode($texte);
	
	return $texte;
}

/* cette fonction retourne l'evenement correspondant au numero passe en parametre */
function retourneEve($numEvenement)
{	
    $sql = "SELECT titreLong, titreCourt, description, dateEvenement, dateFinEvenement from eve_evenement 
            where numeroEvenement = $numEvenement";

    $query = mysql_query($sql) or die ("Requête incorrecte ");

    while ($row = mysql_fetch_array($query)) 
    {
            $Eve['titreLong'] = htmlentities($row['titreLong'], ENT_QUOTES);	
            $Eve['titreCourt'] = htmlentities($row['titreCourt'], ENT_QUOTES);
            $Eve['description'] = htmlentities($row['description'], ENT_QUOTES);
    }
    return $Eve;
}

function explodeDate($date)
{
	$date = explode(' ', $date);
	$heure = substr($date[1], 0, 5);
	$date = explode('-', $date[0]);
	$date[3] = $heure;
	
	return $date;
}

function formattageDate($dateI)
{
	$date[0] = $dateI[3];
	$date[1] = $dateI[2].'/'.$dateI[1].'/'.$dateI[0];
	
	return $date;
}

function comparaisonDate($date, $date2)
{
	$date = explode('/', $date);
	$date2 = explode('/', $date2);
	if($date[2] < $date2[2])
		return false;
	else if($date[2] == $date2[2])
	{
		if($date[1] < $date2[1])
			return false;
		else if($date[1] == $date2[1])
		{
			if($date[0] < $date2[0])
				return false;
		}
	}
	return true;
}

/* cette fonction sert à verifier si une connection ldap est valide */
function sessionValide($utilisateur, $pass)
{
    //certe c'est brutal de n'afficher aucune erreur, mais c'est plus propre pour le client
    //error_reporting(0);
    if (($utilisateur == null) or ($pass == null))
    {
            return false;
    }
    return (oci_connect($utilisateur,$pass,"info"));
}

/* cette fonction permet de retourner le nombre de jour pour un mois et une annee donnee */
function retourneJour($annee, $mois)
{
    //on défini le nombre de jour du mois
    if ($mois == 1 || $mois == 3 || $mois == 5 || $mois == 7 || $mois == 8 || $mois == 10 || $mois == 12) 
    {
        $days = 31;
    } 
    else if ($mois == 4 || $mois == 6 || $mois == 9 || $mois == 11) 
    {
        $days = 30;
    } 
    else 
    {
        $days = ($annee % 4 == 0) ? 29 : 28;
    }
    return $days;
}

/* cette fonction renvoie le numero du semestre (1 ou 2) */
function retourneSemestre($mois)
{
    if($mois < 7)
    {
            return 1;	
    }
    return 2;
}

function miseEnPageJour($timestamp)
{
    $jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"); 
    $mois = array("", "Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre", "Décembre");


    return $jour[date("w", $timestamp)]." ".date("d", $timestamp)." ".$mois[date("n", $timestamp)]." ".date("Y", $timestamp);
}

function jourProchain ($mois, $jour, $annee) {
    $timestamp = mktime(23, 59, 59, $mois, $jour, $annee);
    $jourSemaine = date('N', $timestamp); // indique quel jour se trouve le timestamp (ex : 1 = lundi)
    
    // Permet de connaitre le 1er jour de la prochaine semaine
    if ($jour == 1) { // si c'est le premier jour du mois
        switch ($jourSemaine) {
            case 1: $jour += 7;
                break;
            case 2: $jour += 6;
                break;
            case 3: $jour += 5;
                break;
            case 4: $jour += 4;
                break;
            case 5: $jour += 3;
                break;
            case 6: $jour += 2;
                break;
            case 7: $jour += 1;
                break;
        }
    } else {
        $jour += 7;
    }
    return $jour;
}

function supprimer($conn, $idEv) {
    //L'utilisateur est bien connecté
    if(!empty($_SESSION['id'])) {
        /*echo $idEv;*/
        
        //Vérification : l'utilisateur qui veut supprimer l'événement en est bien l'auteur
        $sqlVerif = "SELECT idutilisateur FROM aci_evenement WHERE idevenement = ".$idEv;

        $temp = $conn->query($sqlVerif);
        $verif = $temp->fetch();

        if(!empty($verif)) {
            //Suppression des participants
            $sqlDeleteDestUtil = "DELETE FROM aci_destutilisateur WHERE idevenement = ".$idEv;

            //Suppression des groupes de participants
            $sqlDeleteDestGroupe = "DELETE FROM aci_destgroupe WHERE idevenement = ".$idEv;

            //Suppression de l'événement
            $sqlDeleteEvenement = "DELETE FROM aci_evenement WHERE idevenement = ".$idEv;

            $execution = $conn->query($sqlDeleteDestUtil);
            $execution = $conn->query($sqlDeleteDestGroupe);
            $execution = $conn->query($sqlDeleteEvenement);

            if(!empty($execution))
                return true;
        }
    }
    return false;
}

?>
