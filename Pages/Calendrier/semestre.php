<?php
//on utilisera les variable de session
session_start();
?>

<html>
    <head>
        <title>Page mois</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!--<img src="../../Images/logoiutpetit.png" style="margin-left: 5px; margin-top: 5px; float: left;"/>-->
	
        <?php
            /*include("../miniCalendrier.php");
            include("../menus.php");*/
        ?>
        
        <!--<img src="../../Images/phoenix.png" style="position: relative; bottom: 140px; float:right;"/>-->

<?php
//connexion a la bdd
include("../../Fonctions_Php/connexion.php");

//fonction ...
include("../../Fonctions_Php/diverses_fonctions.php");

//on dfini des valeurs par defaut aux variable anne, mois et jour (par dfaut : aujourd'hui)
$annee = date('Y');
$mois = date('m');
$jour = date('d');

//si les variables $_POST existent, on les utilises et au passage, on les stockent dans les variable de session
if (!empty($_GET['a']) && !empty($_GET['s']))
{
	$annee = $_GET['a'];
	$mois = 1;
	$jour = 1;
	$semestre = $_GET['s'];
	    
	$_SESSION['annee'] = $_GET['a'];
	$_SESSION['mois'] = 1;
	$_SESSION['jour'] = 1;
}

//sinon, on utilise les session
else if (!empty($_SESSION['annee']) && !empty($_SESSION['mois']) && !empty($_SESSION['jour']))
{
	$annee = $_SESSION['annee'];
	$mois = $_SESSION['mois'];
	$jour = $_SESSION['jour'];
}

//on dfini le semestre :
if(empty($semestre)){
    $semestre = retourneSemestre($mois);
}

//on genere le timestamp de dbut et de fin de semestre
if($semestre == 1)
{
	$dateTimestampDebut = mktime(00, 00, 00, 01, 01, $annee);
	$dateTimestampFin = mktime(23, 59, 59, 06, 31, $annee);
	
	$debutSemestre = 1;
}
else
{
	$dateTimestampDebut = mktime(00, 00, 00, 07, 01, $annee);
	$dateTimestampFin = mktime(23, 59, 59, 12, 31, $annee);
	
	$debutSemestre = 7;
}

$idSession = 1 ;//$_SESSION['login'];

//Le lien : prcdent
if($semestre == 1)
{
        $anneePrec = $annee - 1;
        $semestrePrec = 2;
}
else
{
        $anneePrec = $annee;
        $semestrePrec = 1;
}

//Le lien : suivant
if($semestre == 1)
{
        $anneeSuiv = $annee;
        $semestreSuiv = 2;
}
else
{
        $anneeSuiv = $annee + 1;
        $semestreSuiv = 1;
}

?>
<div id="corpsCal" class="semestre">
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
        <th><a href="semestre.php?a=<?php echo $anneePrec; ?>&s=<?php echo $semestrePrec; ?>">< </a></th>
        <th></th>
        <th></th>
        <th><?php echo $annee . ' Semestre ' . $semestre; ?></th>
        <th></th>
        <th></th>
        <th><a href="semestre.php?a=<?php echo $anneeSuiv; ?>&s=<?php echo $semestreSuiv; ?>"> > </a></th>
    </table>
		<table>
     		<colgroup>
       			<col width="1*">
        		<col width="1*">
        		<col width="1*">
			<col width="1*">
        		<col width="1*">
        		<col width="1*">
     		</colgroup>
     		<?php if ($semestre ==1) { ?>
     		
     		<tr>
     			<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=1" style="cursor: pointer;"> Janvier </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=2" style="cursor: pointer;"> F&eacute;vrier </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=3" style="cursor: pointer;"> Mars </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=4" style="cursor: pointer;"> Avril </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=5" style="cursor: pointer;"> Mai </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=6" style="cursor: pointer;"> Juin </a></th>
			</tr>
     		
     		<?php } else { ?>

    		<tr>
      			<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=7" style="cursor: pointer;"> Juillet </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=8" style="cursor: pointer;"> Ao&ucirc;t </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=9" style="cursor: pointer;"> Septembre </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=10" style="cursor: pointer;"> Octobre </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=11" style="cursor: pointer;"> Novembre </a></th>
				<th><a href="./Pages/Calendrier/mois.php?annee=<?php echo($annee);?>&mois=12" style="cursor: pointer;"> D&eacute;cembre </a></th>
    		</tr>
    		
    		<?php } ?>
			
<?php

//la requete la plus perfomante (on retire uniquement les vnements utiles)
	$sql = "SELECT dateEvenement, titreCourt, titreLong FROM eve_evenement
			WHERE dateEvenement >= ($dateTimestampDebut)
			AND dateEvenement <= ($dateTimestampFin)
			AND (estObligatoire =1 OR (estObligatoire =0 AND idUtilisateur = '1')) ORDER BY titreLong";
	
	$query = mysql_query($sql) or die ("Requête incorrecte");
	$result = mysql_numrows($query);
	
	if ($result>0) 
	{
		$cons = 0;
		while ($row = mysql_fetch_array($query)) 
		{
			//on recupre un tableau contenant les date et les titre long)
			
			$donnees[$cons]["dateEvenement"] = htmlentities($row["dateEvenement"], ENT_QUOTES);
			$donnees[$cons]["titreCourt"] = stripslashes(htmlentities($row["titreCourt"], ENT_QUOTES));
			$donnees[$cons]["titreLong"] = stripslashes(htmlentities($row["titreLong"], ENT_QUOTES));

			$cons ++;
		}
	}
	
	$evenement ='';
	
	for($jour=1; $jour<32; $jour++)
	{
		echo'<tr>';
		
		for($mois = $debutSemestre; $mois < ($debutSemestre + 6) ; $mois++)
		{			
			$boucle = 0;
			
			if(!empty($donnees)){
			    for($k=0; $k<count($donnees); $k++)
			    {
				    $vieux_timestamp = mktime(00, 00, 00, $mois, $jour, $annee);

				    if($vieux_timestamp == $donnees[$k]["dateEvenement"])
				    {
					    $titreCourt[$boucle] = $donnees[$k]["titreCourt"];
					    $titreLong[$boucle] = $donnees[$k]["titreLong"];

					    $boucle++;
				    }
			    }
			}
			    
			// CAS 0 : le jour n'existe pas (31 fevrier)
			
			if($jour > retourneJour($annee, $mois))
			{
				echo '<th></th>'; //un peu sale, a modifier avec des styles
			}
			
			// Cas 1 : aucun vnement
			
			else if ($boucle == 0)
			{
				echo'<td onClick="getJour(' . $annee . ', ' . $mois .', ' . $jour . ', 1);">' . $jour . '</td>';
			}
			
			// Cas 2 : plusieurs evenements
			
			else if ($boucle > 1)
			{
				echo '<td class="info" onClick="getJour(' . $annee . ', ' . $mois .', ' . $jour . ', 1);">';
				echo $jour . '<img STYLE="vertical-align: -3px; margin-left: 5px; margin-right: 2px;" src="./Images/warning_exclamation.png" height="15" width="15">' . ' Evenements : ' . $boucle;
				
				echo '<span>';
				for ($i=0 ; $i<$boucle ; $i++)
				{
					echo '<div>';
					echo ($i + 1) . ': ' .$titreLong[$i]; 
					echo '</div>';
				}
				echo '</span>';
				
				echo '</td>';
			}
			
			// Cas 3 : 1 seul evenement
			
			else
			{
				echo'<td class="info" onClick="getJour(' . $annee . ', ' . $mois .', ' . $jour . ', 1);">';
				echo $jour . ' ' . $titreCourt[0] . '<span>' . $titreLong[0] . '</span>';
				echo'</td>';
			}
		}
		echo'</tr>';
	}
?>

<?php
mysql_close();
?>
	
			
		</table>
        </div>
    </body>
</html>
