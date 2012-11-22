<?php 			
	$host = 'localhost'; //Votre host, souvent localhost
	$user = 'root'; //votre login
	$pass = ''; //Votre mot de passe
	$db = 'mlr2'; // Le nom de la base de donnee

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
