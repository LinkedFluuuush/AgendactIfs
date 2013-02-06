<?php session_start();
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
	</head>
    <body>
        <?php
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");

        //on définit des valeurs par defaut aux variable année, mois et jour (par défaut : aujourd'hui)
        $annee = date('Y');
        $mois = date('m');
        $jour = date('d');

        //si les variables $_GET existent, on les utilises et au passage, on les stockent dans les variable de session
        if((!empty($_GET['a'])) && (!empty($_GET['m'])) && (!empty($_GET['j']))) {
            $annee = $_GET['a'];
            $mois = $_GET['m'];
            $jour = $_GET['j'];

            $_SESSION['annee'] = $_GET['a'];
            $_SESSION['mois'] = $_GET['m'];
            $_SESSION['jour'] = $_GET['j'];

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
	

        $idUtil = 1;
        
        $nomSession = 'Test'; //$_SESSION['login'];

        $sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert FROM aci_evenement
		JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
		JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
		where dateFin >= '$annee-$mois-$jour 00:00:00'
		and dateDebut <= '$annee-$mois-$jour 23:59:59'
        and idpriorite <= $priorite
		and ((estPublic = 1)
			or ($idUtil = aci_evenement.idUtilisateur))";
        
        $resultats = $conn->query($sql);
        //$resultats->setFetchMode(PDO::FETCH_OBJ);
        
        $dateTimestampDebutMEPJ = mktime(00, 00, 00, $mois, $jour, $annee);
        $date = miseEnPageJour($dateTimestampDebutMEPJ);

        ?>
        
        <?php include('../menu.php'); ?>
        <div id="global">
            <table class="titreCal"><tr class="titreCal"><th><?php echo $date; ?></th></tr></table>
        <div id="corpsCal" class="jour">
            <?php		
            if ($resultats != null) {
                $i=1;
                while ($row = $resultats->fetch() and $i != 0) {
                    $numeroEve = htmlentities($row['IDEVENEMENT'], ENT_QUOTES);	
                    $dateDebut = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
                    $dateFin = htmlentities($row["DATEFIN"], ENT_QUOTES);
                    $titre = stripcslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));
                    $desc = stripcslashes(htmlentities($row["DESCRIPTION"], ENT_QUOTES));
                    $auteur = stripcslashes(htmlentities($row["prenom"], ENT_QUOTES)).' '.stripcslashes(htmlentities($row["nom"], ENT_QUOTES));
                    $idAuteur = stripcslashes(htmlentities($row["idUtilisateur"], ENT_QUOTES));
                    $lieu = stripcslashes(htmlentities($row["lieu"], ENT_QUOTES));

                    $dateInsert = substr($row["DATEINSERT"],0,10);
                    $tabDateInsert = explode('-', $dateInsert);
                    $dateInsert = $tabDateInsert[2].'/'.$tabDateInsert[1].'/'.$tabDateInsert[0];
					
                    $dateDebut = formattageDate(explodeDate($dateDebut));
                    $dateFin = formattageDate(explodeDate($dateFin));
                    ?>
            
                    <br><span class="titre"><?php echo $dateDebut[0]; ?></span>
                    <?php if(!empty($dateFin))
                              echo '<h5>jusqu\'à '.$dateFin[0].' le '.$dateFin[1].'</h5>'; ?>
                    
                    
                    
                    <?php if($nomSession == $auteur) { ?>
                        <a href="javascript:getEveModif(<?php echo $numeroEve; ?>);">Modifier</a>
                        <a href="javascript:cal_supprimerEve(<?php echo $numeroEve; ?>, <?php echo $annee; ?>, <?php echo $mois; ?>, <?php echo $jour; ?>, <?php echo $_GET['u']; ?>);">Supprimer</a>
                    <?php } ?>
                    
                    <p>
                    <?php
                        echo "<b>".trim($titre)."</b>";
                        echo '<br>'.$desc.'<br>';
                        if(!empty($lieu))
                            echo 'Lieu : ' . $lieu . '<br>'; 
                        echo 'Post&eacute; par ' . $auteur . ' le ' . $dateInsert . '<br>'; ?>
                    </p>
                    <?php $i++;
                }
            }
            else {
                echo "Il n'y a aucun &eacute;v&eacute;nement à cette date.";
            }
                if(!empty($nomSession))
                    echo '<a class="btn" href="javascript:getEveCrea(' . $annee . ', ' . $mois . ', ' . $jour .');">Ajouter</a>';


                if(!empty($_GET['u'])){
                    if ($_GET['u'] == 1) {
                        echo '<a class="btn" href="semestre.php?a=' . $annee . '&m=' . $mois . '">Retour</a>';
                    }

                    if ($_GET['u'] == 2) {
                        echo '<a class="btn" href="mois.php?annee=' . $annee . '&mois=' . $mois . '">Retour</a>';
                    } 

                    if ($_GET['u'] == 3) {
                        //echo '<a href="semaine.php?annee=' . $annee . '&mois=' . $mois . '&jour='. $jour .'">Retour</a>';
                        $ts = mktime(0,0,0,$mois,$jour,$annee);
                        $jourDebut = date('N', $ts);
                        echo '<a class="btn" href="semaine.php?annee=' . $annee . '&mois=' . $mois . '&jour='. ($jour-$jourDebut+1) .'">Retour</a>';
                    }
                }
            ?>
        </div>
        </div>
    </body>
</html>
