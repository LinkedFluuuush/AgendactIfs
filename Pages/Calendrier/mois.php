<?php session_start();
// Gestion des priorités
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
        <title>Page mois</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../favicon.ico" rel="icon" type="image/x-icon" />
    </head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");

        //on définit des valeurs par defaut aux variables année, mois et jour (par défaut : aujourd'hui)
        $annee = date('Y');
        $mois = date('m');
        $jour = 1;

        //si les variables $_POST existent, on les utilise et au passage, on les stocke dans les variables de session
        if((!empty($_GET['annee'])) && (!empty($_GET['mois']))) {
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
            $jour = 1;

            if($mois == 13)
                $mois = 0;
        }

        // Nombre de jours du mois
        $days = retourneJour($annee, $mois);

        //on genere le timestamp de début et de fin de mois
        $dateTimestampDebut = mktime(00, 00, 00, $mois, 01, $annee);
        $dateTimestampFin = mktime(23, 59, 59, $mois, $days, $annee);

        //On définit le premier et le dernier jour du mois, ainsi que le nombre de semaines
        $firstDay = date('w',$dateTimestampDebut - 86400);
        $lastDay = date('w',$dateTimestampFin - 86400);
		
        // Nombre de semaines dans le mois en cours
        $nbWeek = intval(($days + $firstDay + (6-$lastDay))/7);

        // Gestion de l'erreur inexplicable due au mois d'avril 2013
        if($mois == 4 && $annee == 2013) {
            $firstDay = 0;
            $lastDay = 1;
        }
        // Liste des mois
        $tabMois = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

        $nomMois = $tabMois[$mois - 1];

        //----------

        if(!empty($_SESSION['id']))
            $idUtil = $_SESSION['id'];
        else
            $idUtil = 0;

        //Le lien : mois précédent
        if($mois == 1) {
            $moisPrec = 12;
            $anneePrec = $annee - 1;
        }
        else {
            $moisPrec = $mois - 1;
            $anneePrec = $annee;
        }

        //Le lien : mois suivant
        if($mois == 12) {
            $moisSuiv = 1;
            $anneeSuiv = $annee + 1;
        }
        else {
            $moisSuiv = $mois + 1;
            $anneeSuiv = $annee;
        }
        ?>
        
        <div id="global">
		<?php include('../menu.php'); ?>
        <div id="corpsCal" class="mois">
            <table class="titreCal">
                <tr class="titreCal">
                    <!-- Lien amenant au mois à l'année précédente -->
                    <th><a href="mois.php?annee=<?php echo $annee-1; ?>&amp;mois=<?php echo $mois; ?>">&#9668;&#9668; </a></th>
                    <!-- Lien du mois précédent -->
                    <th><a href="mois.php?annee=<?php echo $anneePrec; ?>&amp;mois=<?php echo $moisPrec; ?>"> &#9668; </a></th>
                    <!-- Nom du mois -->
                    <th width="500px"><?php echo $nomMois . ' ' . $annee; ?></th>
                    <!-- Lien du mois suivant -->
                    <th><a href="mois.php?annee=<?php echo $anneeSuiv; ?>&amp;mois=<?php echo $moisSuiv; ?>"> &#9658; </a></th>
                    <!-- Lien amenant au mois à l'année suivante -->
                    <th><a href="mois.php?annee=<?php echo $annee+1; ?>&amp;mois=<?php echo $mois; ?>"> &#9658;&#9658; </a></th>
                </tr>
            </table>
            
            <!-- Affichage du nom du mois + année et des liens du mois précédent/suivant -->
            <table>
                <tr>
                    <th class="numSemaine"></th>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>

                <?php
                $sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert FROM aci_evenement
                        JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
                        LEFT JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
                        where (dateFin >= '$annee-$mois-01 00:00:00' or dateFin is null)
                        and dateDebut <= '$annee-$mois-$days 23:59:59'
                        and idpriorite <= $priorite
                        and ((estPublic = 1)
                                or ($idUtil = aci_evenement.idUtilisateur)
                                or $idUtil in (SELECT idutilisateur FROM aci_destutilisateur WHERE aci_destutilisateur.idevenement = aci_evenement.idevenement)
                                or $idUtil in (SELECT idutilisateur FROM aci_composer JOIN aci_destgroupe USING (idgroupe) WHERE aci_destgroupe.idevenement = aci_evenement.idevenement))";

                $resultats = $conn->query($sql);

                if ($resultats != null) {
                    $cons = 0;
                    while ($row = $resultats->fetch()) {
                        //on recupère un tableau contenant les date et les libellés de tous les évènements du mois
                        $donnees[$cons]["dateDebut"] = $row["DATEDEBUT"];
                        $donnees[$cons]["dateFin"] = $row["DATEFIN"];
                        $donnees[$cons]["titreCourt"] = stripslashes($row["LIBELLECOURT"]);
                        $donnees[$cons]["titreLong"] = stripslashes($row["LIBELLELONG"]);
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
                    $numSemaine = date('W', $timestamp); // indique le numéro de semaine

                    echo '<td class="numSemaine" onclick="document.location.href = \'semaine.php?annee='.$annee.'&amp;mois='.$mois.'&amp;jour='.$jour.'\';"><a href="semaine.php?annee='.$annee.'&amp;mois='.$mois.'&amp;jour='.$jour.'">'. $numSemaine . '</a></td>';

                    // 1er jour de la prochaine semaine
                    $jour = jourProchain($mois, $jour, $annee);

                    //-------------------------------------------------------------------------------------------------------------------

                    // pour les 7 jours de la semaine
                    for($j = 1; $j < 8; $j++) {			
                        $boucle = 0;

                        //on recupere les données du jour
                        if(!empty($donnees)) {
                            for($k = 0; $k < count($donnees); $k++) {
                                $dateCourante = mktime(00,00,00, $mois, $num, $annee);

                                $dateDebut = explode(' ',$donnees[$k]["dateDebut"]);
                                $temp = explode('-',$dateDebut[0]);
                                $dateDebut = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);

                                if(!empty($donnees[$k]["dateFin"])) {
                                    $dateFin = explode(' ',$donnees[$k]["dateFin"]);
                                    $temp = explode('-',$dateFin[0]);
                                    $dateFin = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);
                                }
                                else
                                    $dateFin = null;

                                //On affiche les évènements qui se déroulent dans la journée (gestion des événements sans date de fin)
                                if(($dateCourante >= $dateDebut && $dateCourante <= $dateFin) or ($dateCourante == $dateDebut && empty($dateFin))) {
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
                        else { // on affiche la case du jour avec évènements ou non
                            echo '<td class="caseDuMois" onclick="document.location.href = \'jour.php?a='.$annee.'&amp;m='.$mois.'&amp;j='.$num.'&amp;u=2\';"><a href="jour.php?a='.$annee.'&amp;m='.$mois.'&amp;j='.$num.'&amp;u=2">';
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
                            echo'</a></td>';
                            $num++;
                        }
                    }	
                    echo'</tr>';
                }
                ?>
            </table>
        </div>
        </div>
    </body>
</html>