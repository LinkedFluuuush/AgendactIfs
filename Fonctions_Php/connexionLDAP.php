<?php

if(!empty($_POST['login']) && !empty($_POST['mdp'])){
	$login = $_POST['login'];
	$mdp = $_POST['mdp'];
}else{
	header("location:../Pages/Calendrier/mois.php?login=0");
	exit();
}

$baseDN = "dc=iutc3,dc=unicaen,dc=fr";
$ldapServer = "ruche";
$ldapServerPort = 389;
$dn = 'uid='.$login.',ou=People,dc=iutc3,dc=unicaen,dc=fr';

echo "Connexion au serveur <br />";
$conn=ldap_connect($ldapServer,$ldapServerPort);

if($conn){
	if (ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3))
	{
		echo "Utilisation de LDAP V3 : OK \n";
		$bindServerLDAP=ldap_bind($conn,$dn,$mdp); // la connexion anonyme ne fonctionne pas
		if ($bindServerLDAP){
			echo "Le résultat de connexion est $bindServerLDAP <br />";
			/* 3ème étape : on effectue une recherche anonyme, avec le dn de base,
			par exemple, sur tous les noms commençant par B */

			//$query = "cn=".$recherche[1]." ".$recherche[0];
			$query = "uid=".$login;
			echo "Recherche suivant le filtre ($query) <br />";
			#$query = "sn=*";
			$result=ldap_search($conn, $baseDN, $query);
			//echo "Le résultat de la recherche est $result <br />";
			echo "résultat de la recherche : ";

			echo "Le nombre d'entrées retournées est ";
			//$nbentrees=ldap_count_entries($conn,$result*1);
			$nbentrees=ldap_count_entries($conn,$result);
			echo $nbentrees."<p />";
			//echo $nbentrees;


			echo "<p> Lecture de ces entrées ....<p />";
			$info = ldap_get_entries($conn, $result);
			echo "Données pour ".$info["count"]." entrées:<p />";

			//echo "<hr>"; 
			//print_r($info);
			//echo "<hr>"; 

			for ($i=0; $i < $info["count"]; $i++) 
			{
				foreach($info[$i] as $cle => $contenu){
					echo $cle." : ".$contenu[0]."<br/>";
				}
				echo($info[$i]["homedirectory"][0]);
				/*echo "dn est : ". $info[$i]["cn"] ."<br />";
				echo "premiere entree cn : ". $info[$i]["cn"][0] ."<br />";
				echo "premier email : ". $info[$i]["mail"][0] ."<p />";*/
			}
			/* 4ème étape : clôture de la session  */
			echo "Fermeture de la connexion";
			ldap_close($conn);
		}
		else{
			die("Liaison impossible au serveur ldap ...");
		}
	}
	else 
	{
		echo "Impossible d'utiliser LDAP V3\n";
		exit();  
	}
} else {
	echo 'Echec de la connexion au serveur LDAP.';
	exit();
}
