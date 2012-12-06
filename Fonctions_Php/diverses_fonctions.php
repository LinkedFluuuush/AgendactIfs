<?php 

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
	$date[1] = $dateI[2].'-'.$dateI[1].'-'.$dateI[0];
	
	return $date;
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

?>
