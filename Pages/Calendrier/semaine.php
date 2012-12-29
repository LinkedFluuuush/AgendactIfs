<?php session_start(); ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Page semaine</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <!--[if IE 7]>
            <link href="../../style-ie.css" rel="stylesheet" type="text/css">
        <![endif]-->
    </head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");
	
	$annee = date('Y');
        $mois = date('m');
	$jourDebut = date('d');
	$jourFin = date('d')+7;
        $jour = date('d');
	$mois1 = $mois;
        $mois2 = $mois;
	$annee1 = $annee;
	$annee2 = $annee;
        
        // TEST A EFFACER PLUS TARD
        $idUtil = 1;
                        
        if ((!empty($_GET['jourDebutPrec'])) && (!empty($_GET['jourFinPrec']))) {
            $jourDebut = $_GET['jourDebutPrec'];
            $jourFin = $_GET['jourFinPrec'];
        }
        
        if ((!empty($_GET['jour'])) && (!empty($_GET['annee'])) && (!empty($_GET['mois']))) {
            $jour = $_GET['jour'];
            $mois = $_GET['mois'];
            $annee = $_GET['annee'];
        }
        //sinon, on utilise les session
        else if ((!empty($_SESSION['annee'])) && (!empty($_SESSION['mois'])) && (!empty($_SESSION['jour']))) {
            $annee = $_SESSION['annee'];
            $mois = $_SESSION['mois'];
            $jour = $_SESSION['jour'];
        }
        
        $timestamp = mktime(23, 59, 59, $mois, $jour, $annee);
        $jourSemaine = date('N', $timestamp); // indique quel jour se trouve le timestamp (ex : 1 = lundi)
        
	$moisSuiv = $mois;
	$anneeSuiv = $annee;
	$moisPrec = $mois;
	$anneePrec = $annee;

	if($jour > retourneJour($annee, $mois)-7){
	    if($mois == 12) {
		$moisSuiv = 1;
		$anneeSuiv = $annee + 1;
	    }
	    else {
		$moisSuiv = $mois + 1;
		$anneeSuiv = $annee;
	    }
    	    $jourSuiv = ($jour+7) - retourneJour($annee, $mois);
	}
	else{
	    $jourSuiv = $jour+7;
	}
	
//	echo $jourSuiv.' '.$moisSuiv.' '.$anneeSuiv;
	
	if($jour <= 7){
	    if($mois == 1) {
		$moisPrec = 12;
		$anneePrec = $annee - 1;
	    }
	    else {
		$moisPrec = $mois - 1;
		$anneePrec = $annee;
	    }
	    $jourPrec = retourneJour($anneePrec, $moisPrec) + ($jour-7);
	}
	else{
	    $jourPrec = $jour-7;
	}
	        
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
        
        
/*         $dateTimestampDebut = mktime(00,00,00, $mois1, $jourDebut, $annee1);
        $dateTimestampFin = mktime(23,59,59, $mois2, $jourFin, $annee2); */
        
        // Liste des mois
        $tabMois = array('janv.', 'f&eacute;v.', 'mars', 'avril', 'mai', 'juin',
        'juil.', 'ao&ucirc;t', 'sept.', 'oct.', 'nov.', 'd&eacute;c.');

        $nomMois1 = $tabMois[$mois1 - 1];
        $nomMois2 = $tabMois[$mois2 - 1];
        
        $idSession = 1;
        //$_SESSION['login'];
        ?>
        
        <div id="global">
            <?php include('../menu.php'); ?>
        <div id="corpsCal" class="semaine">
            <table class="titreCal">                
                <tr class="titreCal">
                    <th><?php echo '<a href=\'semaine.php?annee='.$anneePrec.'&mois='.$moisPrec.'&jour='.$jourPrec.'\'> &#9668; </a>';?></th>
                    <th colspan="3"><?php echo "$jourDebut $nomMois1 $annee1 au $jourFin $nomMois2 $annee2"; ?></th>
                    <th><?php echo '<a href=\'semaine.php?annee='.$anneeSuiv.'&mois='.$moisSuiv.'&jour='.$jourSuiv.'\'> &#9658; </a>';?></th>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=3\'>Lundi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+1).'&u=3\'>Mardi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+2).'&u=3\'>Mercredi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+3).'&u=3\'>Jeudi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+4).'&u=3\'>Vendredi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+5).'&u=3\'>Samedi</a>'); ?></th>
                    <th><?php echo('<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jour+6).'&u=3\'>Dimanche</a>'); ?></th>
                </tr>
                
                <?php

                $sql = "SELECT aci_evenement.* , aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert
                        FROM aci_evenement
                        JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
                        JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
                        WHERE dateFin >=  '$annee1-$mois1-$jourDebut 00:00:00'
                        AND dateDebut <=  '$annee2-$mois2-$jourFin 23:59:59'
                        AND ((estPublic =1) OR ( 1 = aci_evenement.idUtilisateur ))";
				
				
                $resultats = $conn->query($sql);
                $resultats->setFetchMode(PDO::FETCH_ASSOC);
                
                if ($resultats != null) {
                    $i=0;
                    while ($row = $resultats->fetch()) {
                        //on recup�re un tableau contenant les date et les titre long)
                        $donnees[$i]["dateDebut"] = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
                        $donnees[$i]["libelleCourt"] = stripslashes(htmlentities($row["LIBELLECOURT"], ENT_QUOTES));
                        $donnees[$i]["libelleLong"] = stripslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));
                        $i++;
                    }
                }
                
                
                for ($i = 0 ; $i <= 23 ; $i++) { //heures de 0 à 23
                    echo '<tr>';
                    echo '<td class="nomHeure">'.$i.':00</td>';
                    
                    $heure = $i.':00';
                    $time = explode(":", $heure);
                    for ($j = 1 ; $j < 8 ; $j++) { //jours de 1 à 7
                        $boucle = 0;
                        
                        if (!empty($donnees)) {
                            for ($k = 0 ; $k < count($donnees) ; $k++) {
                                $heureTestee = date("Y-m-d H:i:s", mktime($time[0], 00, 00, $mois1, $jourDebut, $annee1));
                                //echo $heureTestee."<br>";
                                if($heureTestee == $donnees[$k]["dateDebut"]) {
                                    $libelleCourt[$boucle] = $donnees[$k]["libelleCourt"];
                                    $libelleLong[$boucle] = $donnees[$k]["libelleLong"];
                                    $boucle++;
                                }
                            }
                        }
                        echo '<td onclick="document.location.href =\'jour.php?a='.$annee.'&m='.$mois.'&j='.($jour + $j-1).'&u=3\';">';
                        if ($boucle >= 1) {
                            echo '<ul>';
                            for ($l = 0 ; $l < $boucle ; $l++) {
                                echo '<li class="info">';
                                echo $libelleCourt[$l];
                                echo '<span>' . $libelleLong[$l] . '</span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        echo'</td>';                            
                        $jourDebut++;       
                    }
                    $jourDebut = $jourDebut-7;
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        </div>
    </body>
</html>