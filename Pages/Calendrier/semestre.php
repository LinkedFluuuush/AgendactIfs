<?php
//on utilisera les variable de session
session_start();
?>

<html>
    <head>
        <title>Page semestre</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>

        <div class="nav">
            <?php include("../miniCalendrier.php");?>
        </div>

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

//on défini le semestre :
if(empty($semestre)){
    $semestre = retourneSemestre($mois);
}

//on gère le semestre
if($semestre == 1)
{
	$moisDebut = 01;
	$moisFin = 06;
	
	$debutSemestre = 1;
}
else
{
	$moisDebut = 07;
	$moisFin = 12;
	
	$debutSemestre = 7;
}

$days = retourneJour($annee, $moisFin);

$idUtil = 1;
$idSession = 1 ;//$_SESSION['login'];

//Le lien : précédent
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
     			<th><a href="mois.php?annee=<?php echo($annee);?>&mois=1" style="cursor: pointer;"> Janvier </a></th>
				<th><a href="mois.php?annee=<?php echo($annee);?>&mois=2" style="cursor: pointer;"> F&eacute;vrier </a></th>
				<th><a href="mois.php?annee=<?php echo($annee);?>&mois=3" style="cursor: pointer;"> Mars </a></th>
				<th><a href="mois.php?annee=<?php echo($annee);?>&mois=4" style="cursor: pointer;"> Avril </a></th>
				<th><a href="mois.php?annee=<?php echo($annee);?>&mois=5" style="cursor: pointer;"> Mai </a></th>
				<th><a href="mois.php?annee=<?php echo($annee);?>&mois=6" style="cursor: pointer;"> Juin </a></th>
			</tr>
     		
     		<?php } else { ?>

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
			JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
			where dateFin >= '$annee-$moisDebut-01 00:00:00'
			and dateDebut <= '$annee-$moisFin-$days 23:59:59'
			and ((estPublic = 1)
				or ($idUtil = aci_evenement.idUtilisateur))";

	$resultats = $conn->query($sql);
	
	if ($resultats != null) 
	{
		$cons = 0;
		while ($row = $resultats->fetch()) 
		{
			//on recupère un tableau contenant les dates et les titres longs
			$donnees[$cons]["dateDebut"] = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
			$donnees[$cons]["dateFin"] = htmlentities($row["DATEFIN"], ENT_QUOTES);
			$donnees[$cons]["titreCourt"] = stripslashes(htmlentities($row["LIBELLECOURT"], ENT_QUOTES));
			$donnees[$cons]["titreLong"] = stripslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));

			$cons ++;
		}
	}
	
	$num = 1;
	
	$evenement ='';
	
	for($jour=1; $jour<32; $jour++)
	{
		echo'<tr>';
		
		for($mois = $debutSemestre; $mois < ($debutSemestre + 6) ; $mois++)
		{			
			$boucle = 0;
			
			//on recupere les données du jour
			if(!empty($donnees))
			{
				for($k = 0; $k < count($donnees); $k++)
				{
					$dateCourante = mktime(00,00,00, $mois, $jour, $annee);
		
					$dateDebut = explode(' ',$donnees[$k]["dateDebut"]);
					$temp = explode('-',$dateDebut[0]);
					$dateDebut = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);
				
					$dateFin = explode(' ',$donnees[$k]["dateFin"]);
					$temp = explode('-',$dateFin[0]);
					$dateFin = mktime(00,00,00, $temp[1],$temp[2],$temp[0]);

					//On affiche les évènements qui se déroulent dans la journée
					if($dateCourante >= $dateDebut && $dateCourante <= $dateFin) {
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
			
			// Cas 1 : aucun événement
			
			else if ($boucle == 0)
			{
				echo '<td onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">'.$jour.'</a></td>';
			}
			
			// Cas 2 : plusieurs evenements
			
			else if ($boucle > 1)
			{
				echo '<td class="info" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
				echo $jour . /*'<img STYLE="vertical-align: -3px; margin-left: 5px; margin-right: 2px;" src="./Images/warning_exclamation.png" height="15" width="15">' . */' Evenements : ' . $boucle;
				
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
				echo '<td class="info" onclick="document.location.href = \'jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1\';"><a href="jour.php?a='.$annee.'&m='.$mois.'&j='.$jour.'&u=1">';
				echo $jour . ' ' . $titreCourt[0] . '<span>' . $titreLong[0] . '</span>';
				echo'</td>';
			}
		}
		echo'</tr>';
	}
?>	
		</table>
        </div>
    </body>
</html>
