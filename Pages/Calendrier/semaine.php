<?php session_start(); ?>

<html>
    <head>
        <title>Page semaine</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");
        
        //$annee = date('Y');
        //$mois = date('m');
        //$jour = date('d');
        
		// TEST A EFFACER PLUS TARD
			$idUtil = 1;
		
			$jourDebut = 12;
            $jourFin = 18;
            $mois1 = 11;
            $mois2 = 11;
            $annee1 = 2012;
            $annee2 = 2012; 
		
        if ((!empty($_GET['jourDebutPrec'])) && (!empty($_GET['jourFinPrec']))) {
            $jourDebut = $_GET['jourDebutPrec'];
            $jourFin = $_GET['jourFinPrec'];
        }
        
        if ((!empty($_GET['jour'])) && (!empty($_GET['annee'])) && (!empty($_GET['mois']))) {
            $jour = $_GET['jour'];
            $mois = $_GET['mois'];
            $annee = $_GET['annee'];
        }
        
        $timestamp = mktime(23, 59, 59, $mois, $jour, $annee);
        $jourSemaine = date('N', $timestamp); // indique quel jour se trouve le timestamp (ex : 1 = lundi)
        
        if($mois == 1) {
            $moisPrec = 12;
            $anneePrec = $annee - 1;
        }
        else {
            $moisPrec = $mois - 1;
            $anneePrec = $annee;
        }

        if($mois == 12) {
            $moisSuiv = 1;
            $anneeSuiv = $annee + 1;
        }
        else {
            $moisSuiv = $mois + 1;
            $anneeSuiv = $annee;
        }
        
        
        $jourDebut = $jour; // correspond au lundi de chaque semaine
        $mois1 = $mois;
        $mois2 = $mois;
        $annee1 = $annee;
        $annee2 = $annee;
        
        // pour une semaine en dÃ©but de mois
        // Permet d'ajouter Ã  la semaine les jours du mois prÃ©cÃ©dent
        if ($jourDebut == 1 && $jourSemaine != 1) { // si le premier jour du mois n'est pas un lundi
            $jourDebut = (retourneJour($annee, $moisPrec)+1)-($jourSemaine-1);
            $mois1 = $moisPrec;

            if ($mois == 1) {
                $annee1 = $anneePrec;
            }
        }         

        // Pour une semaine en fin de mois
        $jourFin = jourProchain($mois, $jour, $annee)-1; // correspond au dimanche de chaque semaine
        // fonction permettant de passer du "29 octobre au 35 octobre" Ã  "29 octobre au 4 novembre"
        if ($jourFin > retourneJour($annee, $mois)) {
            $jourEnTrop = $jourFin - retourneJour($annee, $mois);
            $mois2 = $moisSuiv;
            $jourFin = $jourEnTrop;
            if ($mois == 12) {
                $annee2 = $anneeSuiv;
            }
        }     
        
        
/*         $dateTimestampDebut = mktime(00,00,00, $mois1, $jourDebut, $annee1);
        $dateTimestampFin = mktime(23,59,59, $mois2, $jourFin, $annee2); */
        
        // Liste des mois
        $tabMois = array('janv.', 'f&eacute;v.', 'mars', 'avril', 'mai', 'juin',
        'juil.', 'ao&ucirc;t', 'sept.', 'oct.', 'nov.', 'd&eacute;c.');

        $nomMois1 = $tabMois[$mois1 - 1];
        $nomMois2 = $tabMois[$mois2 - 1];
        
        $idSession = 1; //$_SESSION['login'];
        
        ?>       
        
        <div id="corpsCal" class="semaine">
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
                <th><a href="#"> |< </a></th>
                <th><a href="#"> < </a></th>
                <th></th>   
                <th><?php echo "$jourDebut $nomMois1 $annee1 au $jourFin $nomMois2 $annee2"; ?></th>
                <th></th>
                <th><a href="#"> > </a></th>
                <th><a href="#"> >| </a></th>
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
                    <th>Heure</th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>
                
                <?php

                $sql = "SELECT aci_evenement . * , aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert
						FROM aci_evenement
						JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
						JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
						WHERE dateFin >=  '2012-11-12 00:00:00'
						AND dateDebut <=  '2012-11-18 23:59:59'
						AND ((estPublic =1) OR ( 1 = aci_evenement.idUtilisateur ))";
				
				
                $resultats = $conn->query($sql);
                $resultats->setFetchMode(PDO::FETCH_ASSOC);
                if ($resultats != null) {
                    $i=0;
					
                    while ($row = $resultats->fetch()) {
                        //on recupère un tableau contenant les date et les titre long)
                        $donnees[$i]["dateDebut"] = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
                        $donnees[$i]["libelleCourt"] = stripslashes(htmlentities($row["LIBELLECOURT"], ENT_QUOTES));
                        $donnees[$i]["libelleLong"] = stripslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));
                        $i++;
                    }
                }
                
                
                for ($i = 0 ; $i <= 23 ; $i++) { //heures de 0 à 23
                    echo '<tr>';
                    echo '<td>'.$i.':00</td>';
                    
                    $heure = $i.':00';
                    $time = explode(":", $heure);
                    for ($j = 1 ; $j < 8 ; $j++) { //jours de 1 à 7
                        $boucle = 0;
                        
						if (!empty($donnees)) {
							for ($k = 0 ; $k < count($donnees) ; $k++) {
								$heureTestee = date("Y-m-d H:i:s", mktime($time[0], 00, 00, $mois1, $jourDebut, $annee1));
								echo $heureTestee."<br>";
								if($heureTestee == $donnees[$k]["dateDebut"]) {
									$libelleCourt[$boucle] = $donnees[$k]["libelleCourt"];
									$libelleLong[$boucle] = $donnees[$k]["libelleLong"];
									$boucle++;
								}
							}
						}
						echo '<td class="caseDuMois">';
						if ($boucle >= 1) {
							echo '<ul>';
							for ($l = 0 ; $l < $boucle ; $l++) {
								echo '<li class="info">';
								echo $titreCourt[$l];
								echo '<span>' . $titreLong[$l] . '</span>';
								echo '</li>';
							}
							echo '</ul>';
						}
						echo'</td>';                            
						$jourDebut++;
                    }
                    echo '</tr>';
                }
                ?>
        </div>
    </body>
</html>