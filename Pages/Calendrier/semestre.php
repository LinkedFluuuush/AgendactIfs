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
        <title>Page semestre</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../favicon.ico" rel="icon" type="image/x-icon" />
    </head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");

        //on definit des valeurs par defaut aux variables annee, mois et jour (par defaut : aujourd'hui)
        $annee = date('Y');
        $mois = date('m');
        $jour = date('d');

        //si les variables $_POST existent, on les utilise et au passage, on les stockent dans les variable de session
        if (!empty($_GET['a']) && !empty($_GET['s'])) {
            $annee = $_GET['a'];
            $mois = 1;
            $jour = 1;
            $semestre = $_GET['s'];

            $_SESSION['annee'] = $_GET['a'];
            $_SESSION['mois'] = 1;
            $_SESSION['jour'] = 1;
        }
        else if (!empty($_SESSION['annee']) && !empty($_SESSION['mois']) && !empty($_SESSION['jour'])){ //sinon, on utilise les session
            $annee = $_SESSION['annee'];
            $mois = $_SESSION['mois'];
            $jour = $_SESSION['jour'];
        }

        // on définit le semestre
        if(empty($semestre)){
            $semestre = retourneSemestre($mois);
        }

        if($semestre == 1) {
            $moisDebut = 01;
            $moisFin = 06;
            $debutSemestre = 1;
        }
        else {
            $moisDebut = 07;
            $moisFin = 12;
            $debutSemestre = 7;
        }
        
        $days = retourneJour($annee, $moisFin);

        if(!empty($_SESSION['id']))
            $idUtil = $_SESSION['id'];
        else
            $idUtil = 0;
			        
        //Le lien : semestre précédent
        if($semestre == 1){
            $anneePrec = $annee - 1;
            $semestrePrec = 2;
        } else {
            $anneePrec = $annee;
            $semestrePrec = 1;
        }

        //Le lien : semestre suivant
        if($semestre == 1){
            $anneeSuiv = $annee;
            $semestreSuiv = 2;
        } else {
            $anneeSuiv = $annee + 1;
            $semestreSuiv = 1;
        }
        ?>
        
        
        <div id="global">
	        <?php include('../menu.php'); ?>
	<div id="corpsCal" class="semestre">            
            <table class="titreCal">
                <tr class="titreCal">
                    <!-- Lien du semestre précédent -->
                    <th><a href="semestre.php?a=<?php echo $anneePrec; ?>&s=<?php echo $semestrePrec; ?>"> &#9668; </a></th>
                    <!-- Nom du semestre -->
                    <th width="500px"><?php echo $annee . ' Semestre ' . $semestre; ?></th>
                    <!-- Lien du semestre suivant -->
                    <th><a href="semestre.php?a=<?php echo $anneeSuiv; ?>&s=<?php echo $semestreSuiv; ?>"> &#9658; </a></th>
                </tr>
            </table>
            
            <table>                
     		<?php if ($semestre ==1) { ?> <!-- on définit les mois affichés pour le semestre 1 -->
     		<tr>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=1" style="cursor: pointer;"> Janvier </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=2" style="cursor: pointer;"> F&eacute;vrier </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=3" style="cursor: pointer;"> Mars </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=4" style="cursor: pointer;"> Avril </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=5" style="cursor: pointer;"> Mai </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=6" style="cursor: pointer;"> Juin </a></th>
                </tr>
     		<?php } else { ?> <!-- on définit les mois affichés pour le semestre 2 -->
    		<tr>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=7" style="cursor: pointer;"> Juillet </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=8" style="cursor: pointer;"> Ao&ucirc;t </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=9" style="cursor: pointer;"> Septembre </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=10" style="cursor: pointer;"> Octobre </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=11" style="cursor: pointer;"> Novembre </a></th>
                    <th><a href="mois.php?annee=<?php echo($annee);?>&mois=12" style="cursor: pointer;"> D&eacute;cembre </a></th>
    		</tr>
    		<?php } ?>
			
                <?php 
                $sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert FROM aci_evenement
			JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
			LEFT JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
			where (dateFin >= '$annee-$moisDebut-01 00:00:00' or dateFin is null)
			and dateDebut <= '$annee-$moisFin-$days 23:59:59'
                        and idpriorite <= $priorite
			and ((estPublic = 1)
				or ($idUtil = aci_evenement.idUtilisateur)
				or $idUtil in (SELECT idutilisateur FROM aci_destutilisateur WHERE aci_destutilisateur.idevenement = aci_evenement.idevenement)
				or $idUtil in (SELECT idutilisateur FROM aci_composer JOIN aci_destgroupe USING (idgroupe) WHERE aci_destgroupe.idevenement = aci_evenement.idevenement))";

                $resultats = $conn->query($sql);
	
                if ($resultats != null) {
                    $cons = 0;
                    while ($row = $resultats->fetch()) {
                        //on recupère un tableau contenant les dates et les libellés de tous les évènements du semestre
                        $donnees[$cons]["dateDebut"] = $row["DATEDEBUT"];
                        $donnees[$cons]["dateFin"] = $row["DATEFIN"];
                        $donnees[$cons]["titreCourt"] = stripslashes($row["LIBELLECOURT"]);
                        $donnees[$cons]["titreLong"] = stripslashes($row["LIBELLELONG"]);
                        $cons ++;
                    }
                }
	
                $num = 1;

                $evenement = '';
	
                for($jour = 1; $jour < 32; $jour++) { /* on parcourt tous les jours d'un mois */
                    echo'<tr>';
		
                    for($mois = $debutSemestre; $mois < ($debutSemestre + 6) ; $mois++) {			
                        $boucle = 0;
			
			//on recupere les données du jour
			if(!empty($donnees)) {
                            for($k = 0; $k < count($donnees); $k++) {
                                $dateCourante = mktime(00,00,00, $mois, $jour, $annee);

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
			 
                        $weekend = date('N', mktime(00, 00, 00, $mois, $jour, $annee));
                        $numJour = date('N', mktime(00, 00, 00, $mois, $jour, $annee));
                        
			// CAS 0 : le jour n'existe pas (31 fevrier)
			if($jour > retourneJour($annee, $mois)) {
                            echo '<th></th>'; //un peu sale, a modifier avec des styles
			}
                        
			// Cas 1 : aucun événement
			else if ($boucle == 0) {
                            if ($weekend == 6 or $weekend == 7) {
                                echo '<td id="weekend" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">'.$jour.'</a></td>';
                            }
                            else {
                                echo '<td onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">'.$jour.'</a></td>';
                            }
			}
			
			// Cas 2 : plusieurs evenements -> on affiche le nombre d'évènements et une infobulle indique tous les libellés des évènements 
			else if ($boucle > 1) {
                            // Affichage des cases du week-end en gris plus foncé
                            if ($weekend == 6 or $weekend == 7) {
				echo '<td class="info" id="weekend" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
				echo $jour . '<i> Evénements : ' . $boucle . '</i>';

				echo '<span>';
				for ($i=0 ; $i<$boucle ; $i++) {
					echo '<div>';
					echo ($i + 1) . ': ' .$titreLong[$i]; 
					echo '</div>';
				}
				echo '</span>';
				echo '</td>';
                            }
                            else { // Affichage des cases des autres jour de la semaine
                                echo '<td class="info" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
				echo $jour . '<i> Evénements : ' . $boucle . '</i>';

				echo '<span>';
				for ($i=0 ; $i<$boucle ; $i++) {
					echo '<div>';
					echo ($i + 1) . ': ' .$titreLong[$i]; 
					echo '</div>';
				}
				echo '</span>';
				echo '</td>';
                            }
			}
			
			// Cas 3 : 1 seul evenement
			else {
                            // Affichage des cases du week-end en gris plus foncé
                            if ($weekend == 6 or $weekend == 7) {
                                echo '<td class="info" id="weekend" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
                                echo $jour . ' ' . $titreCourt[0] . '<span>' . $titreLong[0] . '</span>';
                                echo'</td>';
                            }
                            else { // Affichage des cases des autres jour de la semaine
                                echo '<td class="info" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
                                echo $jour . ' ' . $titreCourt[0] . '<span>' . $titreLong[0] . '</span>';
                                echo'</td>';
                            }
			}
                    }
                    echo'</tr>';
                } ?>	
            </table>
        </div>
        </div>
    </body>
</html>
