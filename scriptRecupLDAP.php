<?php
include("Fonctions_Php/connexion.php");

$req = "DELETE FROM aci_utilisateur WHERE idUtilisateur != 1";
$resultats = $conn->query($req);

if(!empty($resultats)){
	echo("Reinitialisation complète");
}

$login="jeanbaptiste.louvet";
$mdp="300691Link*d";

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
		    $query = "uid=*";
		    echo "Recherche suivant le filtre ($query) <br />";
		    #$query = "sn=*";
		    $result=ldap_search($connLDAP, $baseDN, $query);
		    //echo "Le résultat de la recherche est $result <br />";
		    echo "résultat de la recherche : ";
		    echo "<p> Lecture de ces entrées ....<p />";
		    $info = ldap_get_entries($connLDAP, $result);
		    /* 4ème étape : clôture de la session  */
		    
		    /*$req = "select max(idutilisateur)+1 from aci_utilisateur";
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
		    
		    echo $req;*/

		    foreach($info as $cle => $contenu){
				//echo $cle.' : '.$contenu.'<br/><br/>';
				/*foreach($contenu as $cle2 => $contenu2){
					echo $cle2.' : '.$contenu2[0].'<br/>';
					//echo $contenu2[0].'<br/>';
				}*/
				
				if(!empty($contenu['sn'][0]) && !empty($contenu['givenname'][0]) && !empty($contenu['uid'][0]) && !empty($contenu['mail'][0])){
					echo $contenu['sn'][0].' '.$contenu['givenname'][0].' - '.$contenu['uid'][0].' - '.$contenu['mail'][0].' - '.random(32);
					echo "<br/>";
					
					$req = "select max(idutilisateur)+1 from aci_utilisateur";
					$resultats = $conn->query($req);
					$row = $resultats->fetch();
					$idUser = $row['max(idutilisateur)+1'];
					
					$req = "INSERT INTO aci_utilisateur VALUES (".$idUser.", '".$contenu["sn"][0]."', '".$contenu["givenname"][0]."', '".$contenu["mail"][0]."', '".$contenu['uid'][0]."', '".md5(random(32))."', 1, 1, curdate())";

					$resultats = $conn->query($req);
				}
			}
		    echo "Fermeture de la connexion";
		    ldap_close($connLDAP);
		    
		    //header("location:../");
	    }
	    else{
		//header("location:../Pages/Calendrier/mois.php?login=0");
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

function random($car) {
$string = "";
$chaine = "abcdefghijklmnpqrstuvwxy1234567890";
srand((double)microtime()*1000000);
for($i=0; $i<$car; $i++) {
$string .= $chaine[rand()%strlen($chaine)];
}
return $string;
}
?>
