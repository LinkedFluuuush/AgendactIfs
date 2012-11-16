<?php 			
	$host = 'localhost'; //Votre host, souvent localhost
	$user = 'root'; //votre login
	$pass = '300691'; //Votre mot de passe
	$db = 'mlr2'; // Le nom de la base de donnee
	
	
	$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
	mysql_select_db($db) or die ('Erreur :'.mysql_error());
?>
