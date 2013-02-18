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
		$option.='<input type="checkbox" name="groupe[]" value="'.utf8_encode($row['idgroupe']).'" id="'.utf8_encode($row['idgroupe']).' '.checkAuto(utf8_encode($row['idgroupe'])).'"/><br/>';

                echo $option;
		descGroupe($row['idgroupe'], $conn, $i+1);
		echo "</div>";
	}
}

function checkAuto($id){
	if(!empty($_POST['groupe'])){
		$groupe[] = $_POST['groupe'];
				
		foreach($groupe as $cle => $contenu){
			foreach($contenu as $cle2 => $contenu2){
				if($contenu2 == $id){
					return "checked";
				}
			}
		}
	}
}

function saisieFormString($chaine)
{
	if(!empty($_POST["$chaine"]))
		echo $_POST["$chaine"];
	else
		echo "";
}

function saisieFormReq($chaine, $conn){
	if(!empty($_POST["$chaine"])){
		$donnee[] = $_POST["$chaine"];
		
		foreach($donnee as $cle => $contenu){
			foreach($contenu as $cle2 => $contenu2){
				$req = "SELECT CONCAT(nom,' ', prenom,' ', adresse_mail) AS info FROM aci_utilisateur WHERE adresse_mail = '".$contenu2."';";
				$resultats = $conn->query($req);
				
				while($row = $resultats->fetch()){
					$mail = explode(" ",$row['info']);
					$div =  "<div onclick =this.parentNode.removeChild(this);>";
					$div .= $row['info'];
					$div .= " <img src=\"../../Images/boutonMoinsReduit2.png\" style=\"cursor:pointer\"/><input type=\"hidden\"name=\"";
					$div .= $chaine;
					$div .= "[]\" value=\"".$mail[2]."\"/></div>";
					echo $div;
				}
			}
		}
	}
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

//Elimine tous les accents non français et echappe les quotes
function accents($texte)
{
	$texte = utf8_decode($texte);
	$texte = strtr($texte,
	utf8_decode("ÀÁÂÃÄÅáâãåÒÓÔÕÖØòóõøÈÉÊËÇÌÍÎÏìíÙÚÛÜúÑñ"),
	utf8_decode("AAAAAAAAAAOOOOOOOOOOEEEEçIIIIIIUUUUUNN"));
	$texte = str_replace("'", "''", $texte);
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
	$date[1] = $dateI[2].'-'.$dateI[1].'-'.$dateI[0];
	
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

function supprimer($conn, $idEv)
{
	//L'utilisateur est bien connecté
	if(!empty($_SESSION['id']))
	{
		//Vérification : l'utilisateur qui veut supprimer l'événement en est bien l'auteur
		$sqlVerif = "SELECT idutilisateur FROM aci_evenement WHERE idevenement = ".$idEv;
	
		$temp = $conn->query($sqlVerif);
		$verif = $temp->fetch();
	
		if(!empty($verif))
		{
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

//require_once('../../PHPMailer_5.2.2/class.phpmailer.php');

function envoyerMail($adresse, $sujet, $contenu, $contenu_txt)
{
/* 	try{
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '25';
		$mail->Username = 'airone.masson@gmail.com';
		$mail->Password = 'p2370_g245gm';
		//$mail->SMTPDebug = 2;
		$mail->SetFrom('airone.masson@gmail.com', 'AgendactIfs');
		$mail->Subject = $sujet;
		$mail->MsgHTML($contenu);
		$mail->AddAddress($adresse);

		if(!$mail->Send())
			return false;
			
		return true;
	}
	catch(Exception $e){
		//echo $e->getMessage();
	} */
	
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $adresse)) // On filtre les serveurs qui rencontrent des bogues.
	{
	    $passage_ligne = "\r\n";
	}
	else
	{
	    $passage_ligne = "\n";
	}
	  
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	  
	//=====Création du header de l'e-mail.
	$header = "From: \"AgendactIfs\"<noreply@agendactifs.com>".$passage_ligne;
	$header.= "Reply-to: \"AgendactIfs\"<noreply@agendactifs.com>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========

	//=====Envoi de l'e-mail.
	if(mail($adresse,utf8_decode("[Agendact'Ifs]".$sujet),$message,$header))
		return true;
	else
		return false;
	//==========
}

//Création des courriels de notification à envoyer
function notifications($conn, $idDest, $nomAuteur, $prenomAuteur, $dateDebut, $dateFin, $libelleLong, $type)
{
	//Récupération du nom, du prénom et de l'adresse e-mail du rappelé
	$sql2 = "SELECT prenom, nom, adresse_mail FROM aci_utilisateur WHERE notificationactive = 1 and idutilisateur = ".$idDest;

	$util = $conn->query($sql2)->fetch();

	//Si les notifications sont activées
	if(!empty($util))
	{
		$dateDebut = explode(' ',$dateDebut);
		if(!empty($dateFin))
			$dateFin = explode(' ',$dateFin);

		$contenu = "<h1>".$libelleLong."</h1>";
		$contenu_txt = $libelleLong."\n";
		
		$contenu = $contenu."Bonjour ".$util[0]." ".$util[1]."<br>";
		$contenu_txt = $contenu_txt."Bonjour ".$util[0]." ".$util[1]."\n";
		
		//Si il s'agit d'une création d'événement
		if($type == "creer")
		{
			$contenu = $contenu."Vous êtes invité(e) à participer à l'événement ".$libelleLong.", organisé par ".$prenomAuteur." ".$nomAuteur;
			$contenu = $contenu.", qui se déroulera ";
			
			$contenu_txt = $contenu_txt."Vous êtes invité(e) à participer à l'événement ".$libelleLong.", organisé par ".$prenomAuteur." ".$nomAuteur;
			$contenu_txt = $contenu_txt.", qui se déroulera ";
		}
		
		//Si il s'agit d'une suppression d'événement
		if($type == "supprimer")
		{
			$contenu = $contenu."L'événement ".$libelleLong." organisé par ".$prenomAuteur." ".$nomAuteur.", initialement prévu ";
			$contenu_txt = $contenu_txt."L'événement ".$libelleLong." organisé par ".$prenomAuteur." ".$nomAuteur.", initialement prévu ";
		}
		
		if(!empty($dateFin[0])){
			$contenu = $contenu."du ";
			$contenu_txt = $contenu_txt."du ";
		}
		else{
			$contenu = $contenu."le ";
			$contenu_txt = $contenu_txt."le ";
		}
			
		$contenu = $contenu.$dateDebut[0];
		$contenu_txt = $contenu_txt.$dateDebut[0];
		
		if($dateDebut[1] != "00:00"){
			$contenu = $contenu." à ".$dateDebut[1];
			$contenu_txt = $contenu_txt." à ".$dateDebut[1];
		}

		if(!empty($dateFin[0]))
		{
			$contenu = $contenu." au ".$dateFin[0];
			$contenu_txt = $contenu_txt." au ".$dateFin[0];
			if($dateFin[1] != "00:00"){
				$contenu = $contenu." à ".$dateFin[1];
				$contenu_txt = $contenu_txt." à ".$dateFin[1];
			}
		}
			
		if($type == "supprimer")
		{
			$contenu = $contenu." a été annulé";
			$contenu_txt = $contenu_txt." a été annulé";
		}
		
		$contenu = $contenu.".<br><br>L'équipe d'AgendactIfs<br><br><small>Ce courriel est généré automatiquement, veuillez ne pas y répondre</small>";
		$contenu_txt = $contenu_txt.".\n\nL'équipe d'AgendactIfs\n\nCe courriel est généré automatiquement, veuillez ne pas y répondre";

		//Envoi du message
		envoyerMail($util[2], $libelleLong, $contenu, $contenu_txt);
	}
}

function calculTailleEve($dateDebut, $dateFin) {
    $dateFinHeure = date('H', strtotime($dateFin));
    $dateFinMinute = date('i', strtotime($dateFin));
    $dateDebutHeure = date('H', strtotime($dateDebut));
    $dateDebutMinute = date('i', strtotime($dateDebut));
    
    $dureeHeure = $dateFinHeure - $dateDebutHeure;
    $dureeMinute = $dateFinMinute - $dateDebutMinute;
    
    if ($dureeMinute != 0) {
        if ($dureeMinute <= 15) {
            return ($dureeHeure.'.25')*40;
        }
        else if ($dureeMinute > 15 and $dureeMinute <= 30) {
            return ($dureeHeure.'.5')*40;
        }
        else if ($dureeMinute > 30 and $dureeMinute <= 45) {
            return ($dureeHeure.'.75')*40;
        }
        elseif ($dureeMinute > 45 and $dureeMinute <= 59) {
            return $dureeHeure*40;
        }
    }    
    return ($dureeHeure.'.'.$dureeMinute)*40;
}
?>
