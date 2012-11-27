<?php session_start(); ?>

<html>
<head>
	<title>Page mois</title>
	<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
	<link href="../../styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<!--<img src="../../Images/logoiutpetit.png" style="margin-left: 5px; margin-top: 5px; float: left;"/>-->

	<?php
		/*include("../miniCalendrier.php");
		include("../menus.php");*/
	?>
	
	<!--<img src="../../Images/phoenix.png" style="position: relative; bottom: 140px; float:right;"/>-->
	
	<?php

	//connexion a la bdd
	include("../../Fonctions_Php/connexion.php");
	
	include("../../Fonctions_Php/diverses_fonctions.php");

	//on défini des valeurs par defaut aux variable année, mois et jour (par défaut : aujourd'hui)
	$annee = date('Y');
	$mois = date('m');
	$jour = date('d');

	//si les variables $_GET existent, on les utilises et au passage, on les stockent dans les variable de session
	if((!empty($_GET['annee'])) && (!empty($_GET['mois'])))
	{
		$annee = $_GET['annee'];
		$mois = $_GET['mois'];
		$jour = 1;

		$_SESSION['annee'] = $_GET['annee'];
		$_SESSION['mois'] = $_GET['mois'];
		$_SESSION['jour'] = 1;

		if($mois == 13)
			$mois = 0;
	}

	//sinon, on utilise les sessions
	if ((!empty($_SESSION['annee'])) && (!empty($_SESSION['mois'])) && (!empty($_SESSION['jour']))) {
		$annee = $_SESSION['annee'];
		$mois = $_SESSION['mois'];
		$jour = $_SESSION['jour'];

		if($mois == 13)
			$mois = 0;
	}

	// Nombre de jours du mois
	$days = retourneJour($annee, $mois);

	//on genere le timestamp de début et de fin de mois
	$dateTimestampDebut = mktime(00, 00, 00, $mois, 01, $annee);
	$dateTimestampFin = mktime(23, 59, 59, $mois, $days, $annee);

	//$dateTimestampDebut = "$annee-$mois-01 00:00:00";
	//$dateTimestampFin = "$annee-$mois-$days 23:59:59";

	//On définit le premier et le dernier jour du mois, ainsi que le nombre de semaines
	$firstDay = date('w',$dateTimestampDebut - 86400);
	$lastDay = date('w',$dateTimestampFin - 86400);

	// Nombre de semaines dans le mois en cours
	$nbWeek = intval(($days + $firstDay + (6-$lastDay))/7);

	// Liste des mois
	$tabMois = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin',
	'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

	$nomMois = $tabMois[$mois - 1];

	//----------
	$idUtil = 1;
	$idSession = 1; //$_SESSION['login'];
	$nomSession = 'Test';
	?>
	
	<?php 
	//Le lien : précédent
	if($mois == 1) {
		$moisPrec = 12;
		$anneePrec = $annee - 1;
	}
	else {
		$moisPrec = $mois - 1;
		$anneePrec = $annee;
	}

	//Le lien : suivant
	if($mois == 12) {
		$moisSuiv = 1;
		$anneeSuiv = $annee + 1;
	}
	else {
		$moisSuiv = $mois + 1;
		$anneeSuiv = $annee;
	}
	?>
	
	<div id="corpsCal" class="mois">
		<!-- Affichage du nom du mois + année et des liens du mois précédent/suivant -->
		<table>
			<colgroup>
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
			</colgroup>
			<th></th>
			<th><a href="mois.php?annee=<?php echo $annee; ?>&mois=1"> |< </a></th>
			<th><a href="mois.php?annee=<?php echo $anneePrec; ?>&mois=<?php echo $moisPrec; ?>"> < </a></th>
			<th></th>
			<th><?php echo $nomMois . ' ' . $annee; ?></th>
			<th></th>
			<th><a href="mois.php?annee=<?php echo $anneeSuiv; ?>&mois=<?php echo $moisSuiv; ?>"> > </a></th>
			<th><a href="mois.php?annee=<?php echo $annee; ?>&mois=12"> >| </a></th>
		</table>
		
		<table>
			<colgroup>
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
				<col width="1*">
			</colgroup>
			<tr>
				<th>n° sem.</th>
				<th>Lundi</th>
				<th>Mardi</th>
				<th>Mercredi</th>
				<th>Jeudi</th>
				<th>Vendredi</th>
				<th>Samedi</th>
				<th>Dimanche</th>
			</tr>

			<?php
			//la requete la plus perfomante (on retire uniquement les événements utiles)
			$sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert FROM aci_evenement
					JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
					JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
					where dateFin >= '$annee-$mois-01 00:00:00'
					and dateDebut <= '$annee-$mois-$days 23:59:59'
					and ((estPublic = 1)
						or ($idUtil = aci_evenement.idUtilisateur))";

			$resultats = $conn->query($sql);

			if ($resultats != null) {
				$cons = 0;
				while ($row = $resultats->fetch()) {
					//on recupère un tableau contenant les date et les titre long)
					$donnees[$cons]["dateDebut"] = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
					$donnees[$cons]["dateFin"] = htmlentities($row["DATEFIN"], ENT_QUOTES);
					$donnees[$cons]["titreCourt"] = stripslashes(htmlentities($row["LIBELLECOURT"], ENT_QUOTES));
					$donnees[$cons]["titreLong"] = stripslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));
					$cons ++;
				}
			}

			$num = 1;

			//tant que notre nombre de semaines n'est pas atteint
			for($i = 1; $i <= $nbWeek; $i++) {   
				echo'<tr>';
				
				// Affichage des n° de semaines + lien avec paramètres transmis à semaine.php
				//-------------------------------------------------------------------------------------------------------------------
				
				$timestamp = mktime(23, 59, 59, $mois, $jour, $annee);
				$jourSemaine = date('N', $timestamp); // indique quel jour se trouve le timestamp (ex : 1 = lundi)
				$numSemaine = date('W', $timestamp); // indique le numéro de semaine

				// Affichage du numéro de la semaine
				$jourDebut = $jour; // correspond au lundi de chaque semaine
				$mois1 = $mois;
				$mois2 = $mois;
				$annee1 = $annee;
				$annee2 = $annee;
				
				// pour une semaine en début de mois
				// Permet d'ajouter à la semaine les jours du mois précédent
				if ($jourDebut == 1 && $jourSemaine != 1) { // si le premier jour du mois n'est pas un lundi
					$jourDebut = (retourneJour($annee, $moisPrec)+1)-($jourSemaine-1);
					$mois1 = $moisPrec;
					
					if ($mois == 1) {
						$annee1 = $anneePrec;
					}
				}         
				
				// Pour une semaine en fin de mois
				$jourFin = jourProchain($mois, $jour, $annee)-1; // correspond au dimanche de chaque semaine
				// fonction permettant de passer du "29 octobre au 35 octobre" à "29 octobre au 4 novembre"
				if ($jourFin > retourneJour($annee, $mois)) {
					$jourEnTrop = $jourFin - retourneJour($annee, $mois);
					$mois2 = $moisSuiv;
					$jourFin = $jourEnTrop;
					if ($mois == 12) {
						$annee2 = $anneeSuiv;
					}
				}
				
				echo '<td class="numSemaine" onclick="document.location.href = \'semaine.php?annee1='.$annee1.'&annee2='.$annee2.'&mois1='.$mois1.'&mois2='.$mois2.'&jourDebut='.$jourDebut.'&jourFin='.$jourFin.'\';"><a href="semaine.php?annee1='.$annee1.'&annee2='.$annee2.'&mois1='.$mois1.'&mois2='.$mois2.'&jourDebut='.$jourDebut.'&jourFin='.$jourFin.'">'. $numSemaine . '</a></td>';
				
				// 1er jour de la prochaine semaine
				$jour = jourProchain($mois, $jour, $annee);

				//-------------------------------------------------------------------------------------------------------------------

				// pour les 7 jours de la semaine
				for($j = 1; $j < 8; $j++) {
					$boucle = 0;

					//on recupere les données du jour
					if(!empty($donnees))
					{
						for($k = 0; $k < count($donnees); $k++)
						{
							$dateCourante = mktime(00,00,00, $mois, $num, $annee);
							
							$dateDebut = explode(' ',$donnees[$k]["dateDebut"]);
							$temp = explode('-',$dateDebut[0]);
							$dateDebut = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);
							
							$dateFin = explode(' ',$donnees[$k]["dateFin"]);
							$temp = explode('-',$dateFin[0]);
							$dateFin = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);

							//On affiche les évènements qui se déroulent dans la journée
							if($dateCourante >= $dateDebut && $dateCourante <= $dateFin) {
								$titreCourt[$boucle] = $donnees[$k]["titreCourt"];
								$titreLong[$boucle] = $donnees[$k]["titreLong"];
								$boucle++;
							}
						}
					}

					//on verifie si la case est du mois ou non
					if((($i == 1) && ($j < $firstDay + 1)) || (($i == $nbWeek) && ($j > $lastDay + 1))) {
						echo '<td class="caseAutreMois"></td>';
					}
					else {
						echo '<td class="caseDuMois" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$num.'&u=2\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$num.'&u=2">';
						echo $num;

						if ($boucle >= 1) {
							echo '<ul>';
							for($l = 0 ; $l < $boucle ; $l++) {
								echo '<li class="info">';
								echo $titreCourt[$l];
								echo '<span>' . $titreLong[$l] . '</span>';
								echo '</li>';
							}
							echo '</ul>';
						}
						echo'</td>';
						$num++;
					}
				}	
				echo'</tr>';
			}
			?>           
		</table>
	</div>
</body>
</html>
