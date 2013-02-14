<?php
session_start();

if(!empty($_POST['login']) && !empty($_POST['mdp'])){
	$login = $_POST['login'];
	$mdp = $_POST['mdp'];
}else{
	header("location:../Pages/Calendrier/mois.php?login=0");
	exit();
}

include("connexion.php");

$req = "SELECT idutilisateur, nom, prenom, adresse_mail FROM aci_utilisateur WHERE aci_utilisateur.identifiant_de_connexion='".$login."' AND aci_utilisateur.pass='".md5($mdp)."'";

$resultats = $conn->query($req);
if($row = $resultats->fetch()){
    $_SESSION['id'] = $row['idutilisateur'];
    $_SESSION['nom'] = $row['nom'];
    $_SESSION['prenom'] = $row['prenom'];
    $_SESSION['mail'] = $row['adresse_mail'];
    
    header("location:../");
    exit();
}else{
    $baseDN = "dc=iutc3,dc=unicaen,dc=fr";
    $ldapServer = "ruche";
    $ldapServerPort = 389;
    $dn = 'uid='.$login.',ou=People,dc=iutc3,dc=unicaen,dc=fr';

    echo "Connexion au serveur <br />";
    $connLDAP=ldap_connect($ldapServer,$ldapServerPort);
    
    if($connLDAP){
	    if (ldap_set_option($connLDAP, LDAP_OPT_PROTOCOL_VERSION, 3))
	    {
		    echo "Utilisation de LDAP V3 : OK \n";
		    $bindServerLDAP=ldap_bind($connLDAP,$dn,$mdp); // la connexion anonyme ne fonctionne pas
		    if ($bindServerLDAP){
			    echo "Le résultat de connexion est $bindServerLDAP <br />";
			    /* 3ème étape : on effectue une recherche anonyme, avec le dn de base,
			    par exemple, sur tous les noms commençant par B */

			    //$query = "cn=".$recherche[1]." ".$recherche[0];
			    $query = "uid=".$login;
			    echo "Recherche suivant le filtre ($query) <br />";
			    #$query = "sn=*";
			    $result=ldap_search($connLDAP, $baseDN, $query);
			    //echo "Le résultat de la recherche est $result <br />";
			    echo "résultat de la recherche : ";
			    echo "<p> Lecture de ces entrées ....<p />";
			    $info = ldap_get_entries($connLDAP, $result);
			    /* 4ème étape : clôture de la session  */
			    
			    $req = "select max(idutilisateur)+1 from aci_utilisateur";
			    $resultats = $conn->query($req);
			    $row = $resultats->fetch();
			    $idUser = $row['max(idutilisateur)+1'];
			  
			    $_SESSION['id'] = $idUser;
			    $_SESSION['nom'] = $info[0]["sn"][0];
			    $_SESSION['prenom'] = $info[0]["givenname"][0];
			    $_SESSION['mail'] = $info[0]["mail"][0];
			    
			    $req = "INSERT INTO aci_utilisateur VALUES (".$idUser.", '".$info[0]["sn"][0]."', '".$info[0]["givenname"][0]."', '".$info[0]["mail"][0]."', '".$login."', '".md5($mdp)."', 1, 1, curdate())";

			    $resultats = $conn->query($req);
			    			    
			    if(!empty($resultats))
				echo "Inséré";
			    
			    echo $req;
			    echo "Fermeture de la connexion";
			    ldap_close($connLDAP);
			    
			    header("location:../");
		    }
		    else{
			header("location:../Pages/Calendrier/mois.php?login=0");
			exit();
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
}
