<?php session_start(); ?>

<html>
    <head>
        <title>Page jour</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
        include("../menu.php");
        include("../../Fonctions_Php/connexion.php");
        include("../../Fonctions_Php/diverses_fonctions.php");

        //on défini des valeurs par defaut aux variable année, mois et jour (par défaut : aujourd'hui)
        $annee = date('Y');
        $mois = date('m');
        $jour = date('d');

        //si les variables $_GET existent, on les utilises et au passage, on les stockent dans les variable de session
        if((!empty($_GET['a'])) && (!empty($_GET['m'])) && (!empty($_GET['j'])))
        {
                $annee = $_GET['a'];
                $mois = $_GET['m'];
                $jour = $_GET['j'];

                $_SESSION['annee'] = $_GET['a'];
                $_SESSION['mois'] = $_GET['m'];
                $_SESSION['jour'] = $_GET['j'];

                if($mois == 13)
                        $mois = 0;
        }

        //sinon, on utilise les session
        else if ((!empty($_SESSION['annee'])) && (!empty($_SESSION['mois'])) && (!empty($_SESSION['jour'])))
        {
                $annee = $_SESSION['annee'];
                $mois = $_SESSION['mois'];
                $jour = $_SESSION['jour'];

                if($mois == 13)
                        $mois = 0;
        }

        $nomSession = 'Test'; //$_SESSION['login'];

        $dateTimestampDebut = mktime(00, 00, 00, $mois, $jour, $annee);
        $dateTimestampFin = mktime(23, 59, 59, $mois, $jour, $annee);

        //$dateTimestampDebut = "$annee-$mois-$jour 00:00:00";
        //$dateTimestampFin = "$annee-$mois-$jour 23:59:59";

        $sql = "SELECT eve_evenement.*, eve_utilisateur.nomCompletUtilisateur FROM eve_evenement
                        INNER JOIN eve_utilisateur ON eve_evenement.idUtilisateur = eve_utilisateur.idUtilisateur
                        where dateEvenement >= $dateTimestampDebut
                        and dateEvenement <= $dateTimestampFin
                        and (estObligatoire = 1 OR (estObligatoire = 0 and eve_utilisateur.nomCompletUtilisateur = '$nomSession'))";

        $query = mysql_query($sql) or die ('Erreur :'.mysql_error());
        $result = mysql_numrows($query);

        $dateTimestampDebutMEPJ = mktime(00, 00, 00, $mois, $jour, $annee);
        $date = miseEnPageJour($dateTimestampDebutMEPJ);

        ?><!--
        <div id="titreCal"><?php echo $date; ?></div>--><!--
        --><div id="corpsCal">
        <?php		
                if ($result>0)
                {
                        $i=1;

                        while ($row = mysql_fetch_array($query) and $i!=0) 
                        {
                                $numeroEve = htmlentities($row["numeroevenement"], ENT_QUOTES);	
                                $timestamp = htmlentities($row["dateevenement"], ENT_QUOTES);
                                $titre = stripcslashes(htmlentities($row["titrelong"], ENT_QUOTES));
                                $desc = stripcslashes(htmlentities($row["description"], ENT_QUOTES));
                                $auteur = stripcslashes(htmlentities($row["nomCompletUtilisateur"], ENT_QUOTES));

                                $lieu = stripcslashes(htmlentities($row["lieu"], ENT_QUOTES));

                                $dateSaisie = date('d/m/Y',$row["datesaisie"]);

                                ?>

                                        <br><span class="titre"><?php echo trim($titre); ?></span>
                                <?php 
                                        if($nomSession == $auteur)
                                        {
                                ?>
                                        <a href="javascript:getEveModif(<?php echo $numeroEve; ?>);">Modifier</a><a href="javascript:cal_supprimerEve(<?php echo $numeroEve; ?>, <?php echo $annee; ?>, <?php echo $mois; ?>, <?php echo $jour; ?>, <?php echo $_GET['u']; ?>);">Supprimer</a>
                                <?php 
                                        }
                                ?>
                                        <p><?php echo $desc; ?></p>
                                        <?php if(!empty($lieu)) echo '<p>Lieu : ' . $lieu . '.</p>'; ?>
                                        <p><?php echo 'Post&eacute; par ' . $auteur . ' le ' . $dateSaisie . '.'; ?></p>

                                <?php
                                $i++;
                        }
                }
                else
                {
                        ?>

                                <p>Il n'y a aucun &eacute;v&eacute;nement pour cette date</p>

                <?php
                }
                if(!empty($nomSession))
                        echo '<a href="javascript:getEveCrea(' . $annee . ', ' . $mois . ', ' . $jour .');">Ajouter</a>';

                if ($_GET['u'] == 1)
                {
                        echo '<a href="semestre.php?a=' . $annee . '&m=' . $mois . '">Retour</a>';
                }
                if ($_GET['u'] == 2)
                {
                        echo '<a href="mois.php?a=' . $annee . '&m=' . $mois . '">Retour</a>';
                }
                
                mysql_close();
                ?>
        </div><!--
    --></body>
</html>
