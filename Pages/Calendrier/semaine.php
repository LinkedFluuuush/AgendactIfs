<?php
    session_start();
    
    // Gestion des priorités dans la vue semaine
    if (!empty($_POST['priorite']))
        $_SESSION['priorite'] = $_POST['priorite'];

    if (!empty($_SESSION['priorite']))
        $priorite = $_SESSION['priorite'];
    else
        $priorite = 3;
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Page semaine</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
    </head>
    
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php"); // connexion à la base de données
        include("../../Fonctions_Php/diverses_fonctions.php");
        
        // INITIALISATION DES VARIABLES $jour, $mois, $annee --------------------------------------------------
        
        if ((!empty($_GET['jour'])) && (!empty($_GET['mois'])) && (!empty($_GET['annee']))) {
            $timestamp = mktime(00, 00, 00, $_GET['mois'], $_GET['jour'], $_GET['annee']);
            $jour = date("d", $timestamp);
            $mois = date("m", $timestamp);
            $annee = date("Y", $timestamp);
            
            // TEST
            //echo "GET : $jour $mois $annee<br>";
        }
        else if ((!empty($_SESSION['annee'])) && (!empty($_SESSION['mois'])) && (!empty($_SESSION['jour']))) {    
            $timestamp = mktime(00, 00, 00, $_SESSION['mois'], $_SESSION['jour'], $_SESSION['annee']);
            $jour = date("d", $timestamp);
            $mois = date("m", $timestamp);
            $annee = date("Y", $timestamp);
            
            // TEST
            //echo "SESSION : $jour $mois $annee<br>";
        }
        else {
            $annee = date('Y');
            $mois = date('m');
            $jour = date('d');
            
            // TEST
            //echo "INITIALISATION : $jour $mois $annee";
        }
             
	
        // GESTION DES LIENS PRECEDENT ET SUIVANT -------------------------------------------------------------
        
        // Pour passer à la semaine précédente, on prend le jour choisi par l'utilisateur et on enlève 7
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
	else {
	    $jourPrec = $jour-7;
            $moisPrec = $mois;
            $anneePrec = $annee;
	}
        
        // Pour passer à la semaine suivante, on prend le jour choisi par l'utilisateur et on ajoute 7
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
	else {
	    $jourSuiv = $jour+7;
            $moisSuiv = $mois;
            $anneeSuiv = $annee;
        }
        
        $mois1 = $mois;
        $mois2 = $mois;
        $annee1 = $annee;
        $annee2 = $annee;
        
        
        // DEFINIR LE 1ER JOUR DE LA SEMAINE (AVEC MOIS ET ANNEE) -------------------------------------------------------
        
        // indique sur quel jour de la semaine on est (ex : lundi = 1)
        $jourSemaine = date('N', mktime(23, 59, 59, $mois, $jour, $annee));
        
        // pour une semaine en début de mois
        // Permet d'ajouter à la semaine les jours du mois précédent
        switch ($jourSemaine) {
            case 1: // lundi
                $jourDebut = $jour;
                break;
            case 2: // mardi
                $jourDebut = $jour-1;
                break;
            case 3: // mercredi
                $jourDebut = $jour-2;
                break;
            case 4: // jeudi
                $jourDebut = $jour-3;
                break;
            case 5: // vendredi
                $jourDebut = $jour-4;
                break;
            case 6: // samedi
                $jourDebut = $jour-5;
                break;
            case 7: // dimanche
                $jourDebut = $jour-6;
                break;
            default:
                echo 'Erreur';
                break;
        }
        
        if ($jourDebut <= 0) {
            $jourDebutTmp = $jourDebut;
            $jourDebut = retourneJour($anneePrec, $moisPrec) + $jourDebutTmp;
            $mois1 = $moisPrec;
            // Pour la première semaine de l'année
            if ($mois == 1) {
                $annee1 = $anneePrec;
            }
        }
        
        
        // DEFINIR LE DERNIER JOUR DE LA SEMAINE (AVEC MOIS ET ANNEE) ---------------------------------------------------

        $jourFin = jourProchain($mois, $jourDebut, $annee)-1; // correspond au dimanche de chaque semaine (lundi - 1)
        // Pour une semaine en fin de mois
        // fonction permettant de passer du "29 octobre au 35 octobre" à "29 octobre au 4 novembre"
        if ($jourFin > retourneJour($annee, $moisPrec)) {
            $jourEnTrop = $jourFin - retourneJour($annee, $moisPrec);
            $mois2 = $moisSuiv;
            $jourFin = $jourEnTrop;
            // Pour la dernière semaine de l'année
            if ($mois == 12) {
                $annee2 = $anneeSuiv;
            }
        }
        
        // Liste des mois
        $tabMois = array('janv.', 'f&eacute;v.', 'mars', 'avril', 'mai', 'juin',
        'juil.', 'ao&ucirc;t', 'sept.', 'oct.', 'nov.', 'd&eacute;c.');

        $nomMois1 = $tabMois[$mois1 - 1];
        $nomMois2 = $tabMois[$mois2 - 1];
        
        $idUtil = 1; //$_SESSION['id'];
        $idSession = 1; //$_SESSION['login'];
        
        ?>
        
        <?php include('../menu.php'); ?>
        <div id="global">
            <table class="titreCal">                
                <tr class="titreCal">
                    <th><?php echo '<a href=\'semaine.php?annee='.$anneePrec.'&mois='.$moisPrec.'&jour='.$jourPrec.'\'> &#9668; </a>'; ?></th>
                    <th width="500px"><?php echo "$jourDebut $nomMois1 $annee1 au $jourFin $nomMois2 $annee2"; ?></th>
                    <th><?php echo '<a href=\'semaine.php?annee='.$anneeSuiv.'&mois='.$moisSuiv.'&jour='.$jourSuiv.'\'> &#9658; </a>'; ?></th>
                </tr>
            </table>
        <div id="corpsCal" class="semaine">          
            <table>
                <tr>
                    <th></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.$jourDebut.'&u=3\'>Lundi</a> '; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+1).'&u=3\'>Mardi</a>'; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+2).'&u=3\'>Mercredi</a>'; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+3).'&u=3\'>Jeudi</a>'; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+4).'&u=3\'>Vendredi</a>'; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+5).'&u=3\'>Samedi</a>'; ?></th>
                    <th><?php echo '<a href=\'./jour.php?a='.$annee.'&m='.$mois.'&j='.($jourDebut+6).'&u=3\'>Dimanche</a>'; ?></th>
                </tr>
                
                <?php
                $sql = "SELECT aci_evenement.* , aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert
                        FROM aci_evenement
                        JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
                        JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
                        WHERE dateFin >=  '$annee1-$mois1-$jourDebut 00:00:00'
                        AND dateDebut <=  '$annee2-$mois2-$jourFin 23:59:59'
                        and idpriorite <= $priorite
                        AND ((estPublic =1) OR ( 1 = aci_evenement.idUtilisateur ))";
                
                $resultats = $conn->query($sql);
                $resultats->setFetchMode(PDO::FETCH_ASSOC);
                
				
                if ($resultats != null) {
                    $i=0;
                    while ($row = $resultats->fetch()) {
                        //on recupère un tableau contenant les dates et les titres longs)
                        $donnees[$i]["dateDebut"] = $row["DATEDEBUT"];
                        $donnees[$i]["libelleCourt"] = stripslashes($row["LIBELLECOURT"]);
                        $donnees[$i]["libelleLong"] = stripslashes($row["LIBELLELONG"]);
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
                                for ($min = 0 ; $min < 60 ; $min++){
                                    $heureTestee = date("Y-m-d H:i:s", mktime($time[0], $min, 00, $mois1, $jourDebut, $annee1));
                                    //echo $heureTestee;
                                    if($heureTestee == $donnees[$k]["dateDebut"]) {
                                            $libelleCourt[$boucle] = $donnees[$k]["libelleCourt"];
                                            $libelleLong[$boucle] = $donnees[$k]["libelleLong"];
                                            $boucle++;
                                    }
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