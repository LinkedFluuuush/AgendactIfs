<?php session_start(); ?>

<html>
    <head>
        <title>Page semaine</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
        //Connexion a la bdd
        include("../../Fonctions_Php/connexion.php");
        
        $annee = date('Y');
        $mois = date('m');
        
        if ((!empty($_GET['jourDebutPrec'])) && (!empty($_GET['jourFinPrec']))) {
            $jourDebut = $_GET['jourDebutPrec'];
            $jourFin = $_GET['jourFinPrec'];
        }
        
        if ((!empty($_GET['jourDebut'])) && (!empty($_GET['jourFin'])) && (!empty($_GET['annee1'])) && (!empty($_GET['annee2'])) && (!empty($_GET['mois1'])) && (!empty($_GET['mois2']))) {
            $jourDebut = $_GET['jourDebut'];
            $jourFin = $_GET['jourFin'];
            $mois1 = $_GET['mois1'];
            $mois2 = $_GET['mois2'];
            $annee1 = $_GET['annee1'];
            $annee2 = $_GET['annee2']; 
        }
        
        $dateTimestampDebut = mktime(00,00,00, $mois1, $jourDebut, $annee1);
        $dateTimestampFin = mktime(23,59,59, $mois2, $jourFin, $annee2);
        
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
                    echo '<td>'.$i.':00</td>';
                    
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
                mysql_close();
                ?>
        </div>
    </body>
</html>