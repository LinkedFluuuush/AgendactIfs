<?php

session_start();

if (!empty($_SESSION['annee']) && !empty($_SESSION['mois']) && !empty($_SESSION['jour']))
{
	$annee = $_SESSION['annee'];
	$mois = $_SESSION['mois'];
	$jour = $_SESSION['jour'];
}
else
{
	$annee = date('Y');
	$mois = date('m');
	$jour = date('d');
}
?>

<!-- AAAProjet événementiel informatisé - Réalisation :  Guillaume ANNE et Julien DIESNIS (2008-2009)AAA --> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Calendrier Evénementiel : Ifs Campus III</title>
	<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=ISO-8859-1">
	
	<link href="menus.css" rel="stylesheet" type="text/css">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<link href="miniCalendrier.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" charset="iso-8859-1">		
		<?php include("./Fonctions_Javascript/autre.js"); ?>
		<?php include("./Fonctions_Javascript/eve_recherche.js"); ?>
		<?php include("./Fonctions_Javascript/gestion_clics.js"); ?>
		<?php include("./Fonctions_Javascript/getters.js"); ?>
		<?php include("./Fonctions_Javascript/setters.js"); ?>
		<?php include("./Fonctions_Javascript/variables.js"); ?>
		
		/* lors du chargement de la page, on simule un clic sur l'onglet calendrier et on genere la date */
		function chargement()
		{
			<?php if (!empty($_SESSION['nom_utilisateur'])) { ?>
			
			clickConnexion();
			switchToConecte('<?php echo $_SESSION['nom_utilisateur'] ?>');
			
			<?php } ?>
			
			clickCalendrier();
			getSemestre(<?php echo $annee; ?>, <?php echo $mois; ?>);
		}
		
		/* cette fonction renvoie l'objet javascript utilise pour faire une requete au serveur */
		function getXMLHttpRequest()
		{
			var xhr = null;
			if(window.XMLHttpRequest || window.ActiveXObject)
			{
				if(window.ActiveXObject)
				{
					try
					{
						xhr = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch(e)
					{
						xhr = new ActiveXObject("Microsoft.XMLHTTP");
					}
				}

				else
				{
					xhr = new XMLHttpRequest();
				}
			}

			else
			{
				alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
				return null;
			} 

			return xhr;
		}
		
		function confirmerSuppression()
		{
			return confirm('Vous êtes sur le point de retirer définitivement un événement du calendrier, êtes vous sur de vouloir supprimer cet événement ?');
		}
		
		
		</script>
	
</head>

<body onload="chargement();">
	<img src="./Images/logoiutpetit.png" style="margin-left: 5px; margin-top: 5px; float: left;"/>

	<div id="page">
		<?php
			include("./Pages/miniCalendrier.php");
			include("./Pages/menus.php");
		?>
		<div id="contenu">
			<div id="titreCal">
			</div>
			<div id="corpsCal">
			</div>
		</div>
	</div>
	
	<img src="./Images/phoenix.png" style="position: relative; bottom: 140px; float:right;"/>
</body>
</html>
