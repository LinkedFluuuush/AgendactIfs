<?php session_start(); ?>

<html>
    <head>
        <title>Page semaine</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
        include("../menu.php");
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");
	
	$annee = date('Y');
        $mois = date('m');
	$jourDebut = date('d');
	$jourFin = date('d')+7;
	$mois1 = $mois;
        $mois2 = $mois;
	$annee1 = $annee;
	$annee2 = $annee;
        
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
        
        
        $dateTimestampDebut = mktime(00,00,00, $mois1, $jourDebut, $annee1);
        $dateTimestampFin = mktime(23,59,59, $mois2, $jourFin, $annee2);
        
        // Liste des mois
        $tabMois = array('janv.', 'f&eacute;v.', 'mars', 'avril', 'mai', 'juin',
        'juil.', 'ao&ucirc;t', 'sept.', 'oct.', 'nov.', 'd&eacute;c.');

        $nomMois1 = $tabMois[$mois1 - 1];
        $nomMois2 = $tabMois[$mois2 - 1];
        
        $idSession = 1; //$_SESSION['login'];
        
        ?><!-- 
        
        --><div id="corpsCal" class="semaine">
            <table id="titreCal">
                <!--<colgroup>
                    <col width="1*">
                    <col width="1*">
                    <col width="1*">
                    <col width="1*">
                    <col width="1*">
                    <col width="1*">
                    <col width="1*">
                </colgroup>-->
                
                <tr id="titreCal">
                    <th></th>
                    <th></th>
                    <th><a href="#"> |< </a></th>
                    <th><a href="#"> < </a></th>
                    <th></th>   
                    <th><?php echo "$jourDebut $nomMois1 $annee1 au $jourFin $nomMois2 $annee2"; ?></th>
                    <th></th>
                    <th><a href="#"> > </a></th>
                    <th><a href="#"> >| </a></th>
                    <th></th>
                </tr>
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
                    <th></th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>
                
                <?php
                $sql = "SELECT dateEvenement, titreCourt, titreLong from eve_evenement 
                                where dateEvenement >= ($dateTimestampDebut)
                                and dateEvenement <= ($dateTimestampFin)
                                and (estObligatoire = 1 OR (estObligatoire = 0 and idUtilisateur = '1'))
                                order by titreCourt";

                $query = mysql_query($sql) or die ("Requ�te incorrecte");
                $result = mysql_numrows($query);
                
                if ($result > 0) {
                    $cons = 0;
                    while ($row = mysql_fetch_array($query)) {
                        //on recup�re un tableau contenant les date et les titre long)
                        $donnees[$cons]["dateEvenement"] = htmlentities($row["dateEvenement"], ENT_QUOTES);
                        $donnees[$cons]["titreCourt"] = stripslashes(htmlentities($row["titreCourt"], ENT_QUOTES));
                        $donnees[$cons]["titreLong"] = stripslashes(htmlentities($row["titreLong"], ENT_QUOTES));
                        $cons ++;
                    }
                }
                
                
                for ($i = 0 ; $i <= 23 ; $i++) {
                    echo '<tr>';
                    echo '<td class="nomHeure">'.$i.':00</td>';
                    
                    $heure = $i.':00';
                    $time = explode(":", $heure);
                    
                    for ($j = 1 ; $j < 8 ; $j++) {
                        $boucle = 0;
                        
                            if (!empty($donnees)) {
                                for ($k = 0 ; $k < count($donnees) ; $k++) {
                                    $timestampEnCours = mktime($time[1], 00, 00, $mois1, $jourDebut, $annee1);
                                    
                                    if($timestampEnCours == $donnees[$k]["dateEvenement"]) {
                                        $titreCourt[$boucle] = $donnees[$k]["titreCourt"];
                                        $titreLong[$boucle] = $donnees[$k]["titreLong"];
                                        $boucle++;
                                    }
                                }
                            }
                            echo '<td>';
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
                mysql_close();
                ?>
            </table>
        </div><!--
    --></body>
</html>