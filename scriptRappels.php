<?php
include("/home/users/info2/jeanbaptiste.louvet/public_html/AgendactIfs/Fonctions_Php/connexion.php");
//require_once('/home/users/info2/jeanbaptiste.louvet/public_html/AgendactIfs/PHPMailer_5.2.2/class.phpmailer.php');

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
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

//Fonctions utiles
function explodeDate($date)
{
	$date = explode(' ', $date);
	$heure = substr($date[1], 0, 5);
	$date = explode('-', $date[0]);
	$date[3] = $heure;

	return $date;
}

// function envoyerMail($adresse, $sujet, $contenu)
// {
	// try{
		// $mail = new PHPMailer(true);
		// $mail->IsSMTP();
		// $mail->SMTPAuth = true;
		// $mail->Host = 'smtp.gmail.com';
		// $mail->Port = '25';
		// $mail->Username = 'airone.masson@gmail.com';
		// $mail->Password = 'p2370_g245gm';
		//$mail->SMTPDebug = 2;
		// $mail->SetFrom('airone.masson@gmail.com', 'AgendactIfs');
		// $mail->Subject = $sujet;
		// $mail->MsgHTML($contenu);
		// $mail->AddAddress($adresse);

		// if(!$mail->Send())
			// return false;
			
		// return true;
	// }
	// catch(Exception $e){
	//	echo $e->getMessage();
	// }
// }

//Création des courriels de rappel à envoyer

//Récupération de tous les rappels à envoyer
$sql = "SELECT idrappel, idevenement, idutilisateur FROM aci_rappel WHERE (daterappel < now() + INTERVAL 1 HOUR) AND (daterappel > now())";

$rappels = $conn->query($sql);
while($rappel = $rappels->fetch())
{
	//Récupération de l'évènement correspondant au rappel
	$sql = "SELECT * FROM aci_evenement WHERE idevenement = ".$rappel['idevenement'];
	
	$evenements = $conn->query($sql);
	
	$evenement = $evenements->fetch();
	
	//Récupération du nom, du prénom et de l'adresse e-mail du rappelé
	$sql2 = "SELECT prenom, nom, adresse_mail FROM aci_utilisateur WHERE idutilisateur = ".$rappel['idutilisateur'];
	
	$util = $conn->query($sql2)->fetch();
	
	//Récupération du nom et du prénom de l'auteur
	$sql3 = "SELECT prenom, nom FROM aci_utilisateur WHERE idutilisateur = ".$evenement['IDUTILISATEUR'];
	
	$auteur = $conn->query($sql3)->fetch();
	
	$dateDebut = explode(' ',$evenement['DATEDEBUT']);
	if(!empty($dateFin))
		$dateFin = explode(' ',$evenement['DATEFIN']);
	
	$contenu = "<h1>".$evenement['LIBELLELONG']."</h1>";
	$contenu = $contenu."Bonjour ".$util[0]." ".$util[1];
	$contenu = $contenu."<br>Nous vous rappelons que l'événement ".$evenement['LIBELLELONG'].", organisé par ".$auteur[0]." ".$auteur[1];
	$contenu = $contenu." se déroulera ";
	
	$contenu_txt = $evenement['LIBELLELONG']."\n";
	$contenu_txt = $contenu_txt."Bonjour ".$util[0]." ".$util[1];
	$contenu_txt = $contenu_txt."\nNous vous rappelons que l'événement ".$evenement['LIBELLELONG'].", organisé par ".$auteur[0]." ".$auteur[1];
	$contenu_txt = $contenu_txt." se déroulera ";
	
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
	
	$contenu = $contenu.".<br><br>L'équipe d'AgendactIfs<br><br><small>Ce courriel est généré automatiquement, veuillez ne pas y répondre</small>";
	$contenu_txt = $contenu_txt.".\n\nL'équipe d'AgendactIfs\nCe courriel est généré automatiquement, veuillez ne pas y répondre";
	echo $contenu_txt;
	
	//Envoi du message
	/*if(envoyerMail($util[2], $evenement['LIBELLELONG'], utf8_decode($contenu)))
		echo $rappel['idutilisateur'].' '.$rappel['idevenement'].'<br>';
	else
		echo 'Message non envoyé<br>';*/
		
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
	if(mail($util[2],utf8_decode("[Agendact'Ifs]".$evenement['LIBELLELONG']),$message,$header))
		echo stripcslashes('\nMessage envoyé avec succès\n');
	else
		echo stripcslashes('\nMessage non envoyé\n');
	//==========
	//Suppression du rappel envoyé de la base de données
	$suppressionRappel = "DELETE FROM aci_rappel WHERE idrappel = ".$rappel['idrappel'];
	
	$delete = $conn->query($suppressionRappel);
}

echo stripcslashes("Script execute avec succes le ".date("d M Y - H:i")."\n\n============\n\n");
?>
