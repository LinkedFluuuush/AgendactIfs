<?php
//on utilisera les variable de session
session_start();

//connexion a la bdd
include("../../Fonctions_Php/connexion.php");

//fonction ...
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

$idUtil = 1;

$nomSession = 'Test'; //$_SESSION['login'];

$sql = "SELECT aci_evenement.*, aci_utilisateur.nom, aci_utilisateur.prenom, aci_utilisateur.idUtilisateur, aci_lieu.libelle lieu, aci_evenement.dateinsert FROM aci_evenement
		JOIN aci_utilisateur ON aci_evenement.idUtilisateur = aci_utilisateur.idUtilisateur
		JOIN aci_lieu ON aci_evenement.idLieu = aci_lieu.idLieu
		where dateFin >= '$annee-$mois-$jour 00:00:00'
		and dateDebut <= '$annee-$mois-$jour 23:59:59'
		and ((estPublic = 1)
			or ($idUtil = aci_evenement.idUtilisateur))";

$resultats = $conn->query($sql);
//$resultats->setFetchMode(PDO::FETCH_OBJ);

$dateTimestampDebutMEPJ = mktime(00, 00, 00, $mois, $jour, $annee);
$date = miseEnPageJour($dateTimestampDebutMEPJ);

?>
<div id="titreCal"> <?php 	echo $date; ?> </div>
<div id="corpsCal">
<?php		
	if ($resultats != null)
	{
		$i=1;
		
		while ($row = $resultats->fetch() and $i!=0) 
		{
			$numeroEve = htmlentities($row['IDEVENEMENT'], ENT_QUOTES);	
			$timestamp = htmlentities($row["DATEDEBUT"], ENT_QUOTES);
			$titre = stripcslashes(htmlentities($row["LIBELLELONG"], ENT_QUOTES));
			$desc = stripcslashes(htmlentities($row["DESCRIPTION"], ENT_QUOTES));
			$auteur = stripcslashes(htmlentities($row["prenom"], ENT_QUOTES)).' '.stripcslashes(htmlentities($row["nom"], ENT_QUOTES));
			$idAuteur = stripcslashes(htmlentities($row["idUtilisateur"], ENT_QUOTES));
			
			$lieu = stripcslashes(htmlentities($row["lieu"], ENT_QUOTES));
			
			$dateInsert = substr($row["DATEINSERT"],0,10);
			$tabDateInsert = explode('-', $dateInsert);
			$dateInsert = $tabDateInsert[2].'/'.$tabDateInsert[1].'/'.$tabDateInsert[0];

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
				<?php if(!empty($lieu)) echo '<p>Lieu : ' . $lieu . '</p>'; ?>
				<p><?php echo 'Post&eacute; par ' . $auteur . ' le ' . $dateInsert . '.'; ?></p>
		
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
	?>
</div>