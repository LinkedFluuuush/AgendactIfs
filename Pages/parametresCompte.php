<?php session_start();
header( 'content-type: text/html; charset=utf-8' ); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Paramètres du compte</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../style.css" rel="stylesheet" type="text/css">
        <link href="../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap.css" rel="stylesheet" type="text/css">
    </head>
<body>

<?php
//Connexion a la bdd
include("../Fonctions_Php/connexion.php");
include_once("../Fonctions_Php/diverses_fonctions.php");

if(!empty($_SESSION['id']))
{
	$idUtil = $_SESSION['id'];
	$envoye = false;
	
	$sqlUtil = "SELECT * FROM aci_utilisateur WHERE idutilisateur = ".$idUtil;
	
	$temp = $conn->query($sqlUtil);
	
	$infoUtil = $temp->fetch();
	
	$rappelHaute = explode(' ', $infoUtil['RAPPELHAUTE']);
	$rappelMoyenne = explode(' ', $infoUtil['RAPPELMOYENNE']);
	$rappelBasse = explode(' ', $infoUtil['RAPPELBASSE']);
	
	//Si un formulaire a été envoyé
	if(isset($_POST['notif']) && isset($_POST['rappel']))
	{
		//Récupération du jour et de l'heure pour chaque priorité
		if(preg_match("#^[0-9]{1,2}$#", $_POST['hHaute']))
			$hHaute = $_POST['hHaute'];
		else
			$hHaute = $rappelHaute[3];
		if(preg_match("#^[0-9]{1,2}$#", $_POST['jHaute']))
			$jHaute = $_POST['jHaute'];
		else
			$jHaute = $rappelHaute[0];
			
		if(preg_match("#^[0-9]{1,2}$#", $_POST['hMoyenne']))
			$hMoyenne = $_POST['hMoyenne'];
		else
			$hMoyenne = $rappelMoyenne[3];
		if(preg_match("#^[0-9]{1,2}$#", $_POST['jMoyenne']))
			$jMoyenne = $_POST['jMoyenne'];
		else
			$jMoyenne = $rappelMoyenne[0];
			
		if(preg_match("#^[0-9]{1,2}$#", $_POST['hBasse']))
			$hBasse = $_POST['hBasse'];
		else
			$hBasse = $rappelBasse[3];
		if(preg_match("#^[0-9]{1,2}$#", $_POST['jBasse']))
			$jBasse = $_POST['jBasse'];
		else
			$jBasse = $rappelBasse[0];
			
		//Création de la suite de chiffres permettant de savoir combien de temps avant l'événement dois avoir lieu le rappel
		$rHaute = $jHaute." 00 00 ".$hHaute." 00";
		$rMoyenne = $jMoyenne." 00 00 ".$hMoyenne." 00";
		$rBasse = $jBasse." 00 00 ".$hBasse." 00";
			
		$sqlUpdate = "UPDATE aci_utilisateur SET rappelactive = '".$_POST['rappel']."', notificationactive = '".$_POST['notif']."',
					rappelhaute = '".$rHaute."', rappelmoyenne = '".$rMoyenne."', rappelbasse = '".$rBasse."' WHERE idutilisateur = ".$idUtil;

		$temp = $conn->query($sqlUpdate);
		
		if(isset($temp))
			$envoye = true;
			
		$sqlUtil = "SELECT * FROM aci_utilisateur WHERE idutilisateur = ".$idUtil;
	
		$temp = $conn->query($sqlUtil);
		
		$infoUtil = $temp->fetch();
		
		$rappelHaute = explode(' ', $infoUtil['RAPPELHAUTE']);
		$rappelMoyenne = explode(' ', $infoUtil['RAPPELMOYENNE']);
		$rappelBasse = explode(' ', $infoUtil['RAPPELBASSE']);
	}
	?>
<div id="global">
    <?php include('./menu.php'); ?>
    <div id="corpsCal" class="parametresCompte">
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" name="FormParametres" method="post" enctype="multipart/form-data" id="formParametres">
			<table align="center">
				<tr>
					<td>Notifications </td>
					<td><input type="radio" name="notif" id="notifA" value="1" <?php checked(1, "NOTIFICATION", $infoUtil);?>> <label for="notifA">Activé</label>
						</br>
						<input type="radio" name="notif" id="notifD" value="0" <?php checked(0, "NOTIFICATION", $infoUtil);?>> <label for="notifD">Désactivé</label>
					</td>
				</tr>
				<tr>
					<td>Rappels : </td>
					<td><input type="radio" name="rappel" id="rappelA" value="1" <?php checked(1, "RAPPEL", $infoUtil);?>> <label for="rappelA">Activé</label>
						</br>
						<input type="radio" name="rappel" id="rappelD" value="0" <?php checked(0, "RAPPEL", $infoUtil);?>> <label for="rappelD">Désactivé</label>
					</td>
				</tr>
				<tr>
					<td>Temps avant rappels d'événements : </td>
					<td>Heure Jour
				</tr>
				<tr>
					<td>Priorité haute : </td>
					<td><input type="text" name="hHaute" size=1 maxlength=2 value="<?php echo $rappelHaute[3]; ?>"> &nbsp;
						<input type="text" name="jHaute" size=1 maxlength=2 value="<?php echo $rappelHaute[0]; ?>">
					</td>
				</tr>
				</tr>
				<tr>
					<td>Priorité moyenne : </td>
					<td><input type="text" name="hMoyenne" size=1 maxlength=2 value="<?php echo $rappelMoyenne[3]; ?>"> &nbsp;
						<input type="text" name="jMoyenne" size=1 maxlength=2 value="<?php echo $rappelMoyenne[0]; ?>">
					</td>
				</tr>
				</tr>
				<tr>
					<td>Priorité basse : </td>
					<td><input type="text" name="hBasse" size=1 maxlength=2 value="<?php echo $rappelBasse[3]; ?>"> &nbsp;
						<input type="text" name="jBasse" size=1 maxlength=2 value="<?php echo $rappelBasse[0]; ?>">
					</td>
				</tr>
			</table>
			<p align="center"><input type="submit" value="Enregistrer"></p>
		</form>
		<?php
		
		if($envoye)
			echo '<h2 align="center">Mise à jour des paramètres effectuée</h2>';
		?>
	</div>
</div>
<?php
}

function checked($boolean, $type, $infoUtil)
{
	if(isset($infoUtil["$type"."ACTIVE"]))
	{
		if($infoUtil["$type"."ACTIVE"] == $boolean)
			echo "checked";
	}
}
?>