<?php 			
	$host = 'spartacus.iutc3.unicaen.fr'; //Votre host, souvent localhost
	$user = 'jeanbaptiste_lou'; //votre login
	$pass = 'shoF9uap'; //Votre mot de passe
	$db = 'jeanbaptiste_lou'; // Le nom de la base de donnee
	
	try
	{
		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	}
	catch(Exception $e)
	{
		echo 'Echec de la connexion à la base de donnée';
		exit();
	}
?>
