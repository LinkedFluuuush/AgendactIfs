<?php
include("./Fonctions_Php/connexion.php");

$req= "SELECT nom, prenom, dateDebut, dateFin, libelleLong FROM aci_evenement JOIN aci_utilisateur ON aci_evenement.idUtilisateur= aci_utilisateur.idUtilisateur WHERE idEvenement = 15";
$resultat = $conn->query($req);
try{
	$row = $resultat->fetch();
	$req2 = "SELECT idUtilisateur FROM aci_destutilisateur WHERE idEvenement = 15";
	$resultat2 = $conn->query($req2);
	while($row2 = $resultat2->fetch()){
		notifications($conn, $row2['idUtilisateur'], $row['nom'], $row['prenom'], $row['dateDebut'], $row['dateFin'], $row['libelleLong']);
	}
}catch(Exception $e){}


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
function notifications($conn, $idDest, $nomAuteur, $prenomAuteur, $dateDebut, $dateFin, $libelleLong)
{
	//Récupération du nom, du prénom et de l'adresse e-mail du rappelé
	$sql2 = "SELECT prenom, nom, adresse_mail FROM aci_utilisateur WHERE notificationactive = 1 and idutilisateur = ".$idDest;
	
	$dateDebut = explode(' ',$dateDebut);
		if(!empty($dateFin))
			$dateFin = explode(' ',$dateFin);

	$util = $conn->query($sql2)->fetch();
		$contenu = "<h1>".$libelleLong."</h1>";
		$contenu .= "Une erreur est survenue à propos de l'évènement ".$libelleLong.". Cet évènement, signalé comme annulé, aura bel et bien lieu ";
	
		$contenu_txt = $libelleLong."\n";
		$contenu .= "Une erreur est survenue à propos de l'évènement ".$libelleLong.". Cet évènement, signalé comme annulé, aura bel et bien lieu ";
	
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
		
		$contenu.=".<br/><br/>Toute l'équipe d'Agendact'Ifs s'excuse pour le désagrément.";
		$contenu_txt.=".\n\nToute l'équipe d'Agendact'Ifs s'excuse pour le désagrément.";
		
		$dateEx = explode('/', $dateDebut[0]);
		$contenu = $contenu.".<br/><br/>Vous pouvez visualiser cet évènement ici : <a href=http://spartacus.iutc3.unicaen.fr/~jeanbaptiste.louvet/AgendactIfs/Pages/Calendrier/jour.php?a=".$dateEx[2]."&m=".$dateEx[1]."&j=".$dateEx[0].">".$libelleLong."</a>";
		$contenu_txt = $contenu_txt.".\n\nVous pouvez visualiser cet évènement ici : http://spartacus.iutc3.unicaen.fr/~jeanbaptiste.louvet/AgendactIfs/Pages/Calendrier/jour.php?a=".$dateEx[2]."&m=".$dateEx[1]."&j=".$dateEx[0];
		
		
		$contenu = $contenu.".<br><br>L'équipe d'AgendactIfs<br><br><small>Ce courriel est généré automatiquement, veuillez ne pas y répondre</small>";
		$contenu_txt = $contenu_txt.".\n\nL'équipe d'AgendactIfs\n\nCe courriel est généré automatiquement, veuillez ne pas y répondre";

		//Envoi du message
		envoyerMail($util[2], $libelleLong, $contenu, $contenu_txt);
}?>