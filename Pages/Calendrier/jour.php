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
        <title>Page jour</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <link href="../../bootstrap.css" rel="stylesheet" type="text/css">
	<link href="../../favicon.ico" rel="icon" type="image/x-icon" />
    </head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");
	
        //on définit des valeurs par defaut aux variables année, mois et jour (par défaut : aujourd'hui)
        $annee = date('Y');
        $mois = date('m');
        $jour = date('d');

        //si les variables $_GET existent, on les utilise et au passage, on les stocke dans les variables de session
        if((!empty($_GET['a'])) && (!empty($_GET['m'])) && (!empty($_GET['j']))) {
            $timestamp = mktime(00, 00, 00, $_GET['m'], $_GET['j'], $_GET['a']);
            $jour = date("d", $timestamp);
            $mois = date("m", $timestamp);
            $annee = date("Y", $timestamp);
            
            $_SESSION['annee'] = $annee;
            $_SESSION['mois'] = $mois;
            $_SESSION['jour'] = $jour;

            if($mois == 13)
                    $mois = 0;
        }

        //sinon, on utilise les sessions
        else if ((!empty($_SESSION['annee'])) && (!empty($_SESSION['mois'])) && (!empty($_SESSION['jour']))) {
            $annee = $_SESSION['annee'];
            $mois = $_SESSION['mois'];
            $jour = $_SESSION['jour'];

            if($mois == 13)
                    $mois = 0;
        }
	

        if(!empty($_SESSION['id']))
            $idUtil = $_SESSION['id'];
        else
            $idUtil = 0;
        

         $sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_evenement.dateinsert FROM aci_evenement
                JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
                WHERE (dateFin >= '$annee-$mois-$jour 00:00:00' and dateDebut <= '$annee-$mois-$jour 23:59:59')
                or (dateFin is null and dateDebut <= '$annee-$mois-$jour 23:59:59' and dateDebut >= '$annee-$mois-$jour 00:00:00')
                and idpriorite <= $priorite
                and ((estPublic = 1)
                    or ($idUtil = aci_evenement.idUtilisateur)
                    or $idUtil in (SELECT idutilisateur FROM aci_destutilisateur WHERE aci_destutilisateur.idevenement = aci_evenement.idevenement)
                    or $idUtil in (SELECT idutilisateur FROM aci_composer JOIN aci_destgroupe USING (idgroupe) WHERE aci_destgroupe.idevenement = aci_evenement.idevenement))";
        
        $resultats = $conn->query($sql);
        
        $dateTimestampDebutMEPJ = mktime(00, 00, 00, $mois, $jour, $annee);
        $date = miseEnPageJour($dateTimestampDebutMEPJ);

        ?>
        
        <div id="global">
            <?php include('../menu.php'); ?>
            <div id="corpsCal" class="jour">
                <table class="titreCal"><tr class="titreCal"><th><?php echo $date; ?></th></tr></table>
                
                <?php
                //Suppression d'un événement
                if(isset($_GET['d']) && $_GET['d'] == 1){
                    echo '<div class="alert alert-success"><b>Suppression réalisée avec succès.</b></div>';
                }
                ?>
                
                <p class="ajouter_retour">
                    <?php
                    // Affichage du bouton ajouter si l'utilisateur est connecté
                    if(!empty($idUtil)) {
                        echo '<a class="btn" href="../Evenement/creer.php?a='.date("Y", $dateTimestampDebutMEPJ).'&m='.
                            date("m", $dateTimestampDebutMEPJ).'&j='.date("d", $dateTimestampDebutMEPJ).'">Ajouter</a>';
                    }

                    // Comportement du bouton retour selon si on vient de la vue semestre, mois ou semaine
                    if(!empty($_GET['u'])){
                        if ($_GET['u'] == 1) {
                            echo '<a class="btn" href="semestre.php?a=' . $annee . '&m=' . $mois . '">Retour</a>';
                        }

                        if ($_GET['u'] == 2) {
                            echo '<a class="btn" href="mois.php?annee=' . $annee . '&mois=' . $mois . '">Retour</a>';
                        } 

                        if ($_GET['u'] == 3) {
                            $ts = mktime(0, 0, 0, $_GET['m'], $_GET['j'], $_GET['a']);
                            $jourDebut = date('N', $ts);
                            echo '<a class="btn" href="semaine.php?annee=' . $_GET['a'] . '&mois=' . $_GET['m'] . '&jour='. ($_GET['j'] - $jourDebut+1) .'">Retour</a>';
                        }
                    }
                    ?>
                </p>
                
                <?php               
                if ($resultats != null) {
                    $i=1;
                    // on récupère toutes les informations sur tous les évènements du jour
                    while ($row = $resultats->fetch() and $i != 0) {
                        $numeroEve = $row['IDEVENEMENT'];	
                        $dateDebut = $row["DATEDEBUT"];
                        $dateFin = $row["DATEFIN"];
                        $titre = stripcslashes($row["LIBELLELONG"]);
                        $desc = stripcslashes($row["DESCRIPTION"]);
                        $auteur = stripcslashes($row["prenom"]).' '.stripcslashes(ucfirst(strtolower($row["nom"])));
                        $idAuteur = stripcslashes($row["idUtilisateur"]);
                        $lieu = stripcslashes($row["IDLIEU"]);

                        $dateInsert = substr($row["DATEINSERT"],0,10);
                        $tabDateInsert = explode('-', $dateInsert);
                        $dateInsert = $tabDateInsert[2].'/'.$tabDateInsert[1].'/'.$tabDateInsert[0];

                        $dateDebut = formattageDate(explodeDate($dateDebut));
                        if(!empty($dateFin))
                            $dateFin = formattageDate(explodeDate($dateFin));
                        ?>

                        <p class="affichage_details">
                            <!-- Affichage de la date et de l'heure de début -->
                            <span style="font-size: 1.5em"><b><?php echo $dateDebut[0]; ?></b></span>
                            <?php
                            // Affichage de la date et l'heure de fin si renseignée
                            if(!empty($dateFin))
                                echo '<span style="font-size: 0.88em"><b>jusqu\'à '.$dateFin[0].' le '.$dateFin[1].'</b></span>';
                            ?>
                        </p>

                        <p class="affichage_details">
                            <?php
                            // Affichage libellé long
                            echo '<span style="font-size:1.1em"><b>'.trim($titre).'</b></span>';
                            // Affichage description
                            echo '<br>'.$desc.'<br>';
                            // Affichage lieu si renseigné
                            if(!empty($lieu))
                                echo '<b>Lieu : </b>' . $lieu . '<br>';
                            // Affichage auteur + date d'insertion de l'évènement
                            echo 'Post&eacute; par <b>' . $auteur . '</b> le <b>' . $dateInsert . '</b>';
                            
                            
                            if(!empty($idUtil)) { ?>
                                <!-- Affichage des boutons modifier et supprimer si la personne est l'auteur de l'évènement et si elle est connectée -->
                                <div class="modifier_suppr">
                                    <?php if($idUtil == $idAuteur) { ?>
                                    <form name="modifier" action="../Evenement/modifier.php?i=<?php echo $numeroEve;?>" method="POST">
                                        <input type="hidden" name="idEve" value="<?php echo $numeroEve; ?>" />
                                        <input class="btn" type="submit" name="modifier_eve" value="Modifier" />
                                    </form>
                                    <form>
                                        <input type="button" class="btn" name="supprimer_eve" value="Supprimer" onclick="if(confirm('Voulez-vous vraiment supprimer cet &eacute;v&egrave;nement ?')){
                                                document.location.href = '../Evenement/supprimer.php?i=<?php echo $numeroEve;?>';
                                        }"/>
                                    </form>
                                    <?php } ?>
                                </div>
                            
                            <?php } ?>
                        </p>
                        <?php $i++;
                    }
                }
                else {
                    echo '<div class="alert alert-info"><b>Il n\'y a aucun &eacute;v&egrave;nement à cette date.</b></div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>
