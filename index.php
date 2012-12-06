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

<!-- Projet événementiel informatisé - Réalisation :  Guillaume ANNE et Julien DIESNIS (2008-2009) --> 

<!DOCTYPE HTML>
<html>
<head>
	<title>Calendrier Evénementiel : Ifs Campus III</title>
	<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=ISO-8859-1">
</head>

<body>
<script><?php echo('document.location.href =\'Pages/Calendrier/mois.php?a='.$annee.'&m='.$mois.'\''); ?></script>
</body>
</html>

