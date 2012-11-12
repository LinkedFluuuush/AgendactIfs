<?php 			
	include("../connexion.php");
	include("../encode_decode.php");
	
	error_reporting(0);
	
	session_start();
	
	/* cette fonction recupere le login et pass du menu et tente de se connecter a l'annuaire LDAP du campus III
	   avec. */
	
	if(!empty($_POST['menu_login']) &&  !empty($_POST['menu_pass']))
	{
		$baseDN = "dc=iutc3,dc=unicaen,dc=fr";
		$ldapServer = "ruche.iutc3.unicaen.fr";
		$ldapServerPort = 389;
		
		$login = utf8_decode(decodeURI($_POST['menu_login']));
		$mdp = utf8_decode(decodeURI($_POST['menu_pass']));
		
		$dn = 'uid=' . $login . ',ou=People,dc=iutc3,dc=unicaen,dc=fr';

		$conn = ldap_connect($ldapServer,$ldapServerPort);
		
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		
		$bindServerLDAP = @ldap_bind($conn,$dn,$mdp);
		
		if($bindServerLDAP)
		{
			$_SESSION['login'] = $login;
			
			$query = 'uid= '. $login;
			
			$result=ldap_search($conn, $baseDN, $query);
			$nbentrees=ldap_count_entries($conn,$result);
			$info = ldap_get_entries($conn, $result);

			//possibilité d'utiliser la fonction corrge_texte du site du tour de france dans coureur_recup.php
			$nom_utilisateur = @ucwords(strtolower($info[0]["description"][0]));
			$_SESSION['nom_utilisateur'] = $nom_utilisateur;
			
			
			$sql = "SELECT count(*) existe from eve_utilisateur WHERE idUtilisateur = '$login';";
			$query = mysql_query($sql) or die ('Erreur :'.mysql_error());
			
			while ($row = mysql_fetch_array($query))
			{
				//on vérifie si l'utilisateur existe déja
				$existe = htmlentities($row["existe"], ENT_QUOTES);
			}
			
			if($existe < 1)
			{
				$sql = "INSERT INTO eve_utilisateur(idUtilisateur, nomCompletUtilisateur, recevoirMinRappel, numeroRole) VALUES('$login', '$nom_utilisateur', '0', '2');";
				mysql_query($sql) or die ('Erreur :'.mysql_error());
				
				$_SESSION['role'] = 2;
			}
			else
			{
				$sql = "SELECT numeroRole FROM eve_utilisateur WHERE idUtilisateur = '$login'";
				$query = mysql_query($sql) or die ('Erreur :'.mysql_error());
			
				while ($row = mysql_fetch_array($query))
				{
					//on vérifie si l'utilisateur existe déja
					$_SESSION['role'] = htmlentities($row["numeroRole"], ENT_QUOTES);
				}
			}				
			echo $nom_utilisateur;
		}
		else
		{
			//a voir.			
		}
		
		ldap_close($conn);
	}
?>
