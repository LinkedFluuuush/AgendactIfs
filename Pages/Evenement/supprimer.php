<?php
session_start();
include("../../Fonctions_Php/connexion.php");
include("../../Fonctions_Php/diverses_fonctions.php");
$suppr = 0;
$idUtil = $_SESSION['id'];
if(!empty($_GET['i'])){
	$req = "SELECT aci_evenement.idUtilisateur, nom, prenom, dateDebut, dateFin, libelleLong FROM aci_evenement JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur WHERE idEvenement = ".$_GET['i'];
	$resultat = $conn->query($req);
	 try{
		$row = $resultat->fetch();
		if($row[0] == $idUtil){
			$req2="SELECT idUtilisateur FROM aci_destutilisateur WHERE idEvenement = ".$_GET['i'];
			$resultat2 = $conn->query($req2);
			while($row2 = $resultat2->fetch()){
				notifications($conn,$row2['idUtilisateur'], $row['nom'], $row['prenom'], $row['dateDebut'], $row['dateFin'], $row['libelleLong'], "supprimer");
			}
			$suppr = supprimer($conn, $_GET['i']);
		}
	}
	catch(Exception $e){
	}
}
header("Location: ../Calendrier/jour.php?a=".$_SESSION['annee']."&m=".$_SESSION['mois']."&j=".$_SESSION['jour']."&d=".$suppr);
exit();
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
			//Suppression des rappels associés
			$sqlDeleteRappels = "DELETE FROM aci_rappel WHERE idevenement = ".$idEv;
			//Suppression de l'événement
			$sqlDeleteEvenement = "DELETE FROM aci_evenement WHERE idevenement = ".$idEv;
			$execution = $conn->query($sqlDeleteDestUtil);
			$execution = $conn->query($sqlDeleteDestGroupe);
			$execution = $conn->query($sqlDeleteRappels);
			$execution = $conn->query($sqlDeleteEvenement);
			if(!empty($execution))
				return 1;
		}
	}
	return 0;
}
?>