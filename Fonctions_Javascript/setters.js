		/* Cette fonction permet de creer un evenement */
		function CreateEve(titreLong, titreCourt, description, annee, mois, jour, duree, lieu, type)
		{						
			titreLong = encodeURI(titreLong).replace(/&/, "%26");
			titreCourt = encodeURI(titreCourt).replace(/&/, "%26");
			description = encodeURI(description).replace(/&/, "%26");
			lieu = encodeURI(lieu).replace(/&/, "%26");
			
			var xhr = getXMLHttpRequest();
			if(xhr && xhr.readyState != 0)
			{
                xhr.abort();
            }
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						return xhr.responseText;
					}
           		}
			xhr.open("POST", "./Fonctions_Php/Setters/creerEve.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send('titreLong=' + titreLong + '&titreCourt=' + titreCourt + '&description=' + description
            		 + '&annee=' + annee + '&mois=' + mois + '&jour=' + jour + '&duree=' + duree
            		 + '&Eve_lieu=' + lieu + '&Eve_type=' + type);
		}
		
		/* cette fonction permet de modifier un evenement */
		function modifierEve(numEve, titreLong, titreCourt, description, annee, mois, jour, duree, lieu, type)
		{			
			titreLong = encodeURI(titreLong).replace(/&/, "%26");
			titreCourt = encodeURI(titreCourt).replace(/&/, "%26");
			description = encodeURI(description).replace(/&/, "%26");
			lieu = encodeURI(lieu).replace(/&/, "%26");
			
			var xhr = getXMLHttpRequest();
			if(xhr && xhr.readyState != 0)
			{
                xhr.abort();
            }
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						return xhr.responseText;
					}
           		}
			xhr.open("POST", "./Fonctions_Php/Setters/modifierEve.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send('numEve=' + numEve + '&titreLong=' + titreLong + '&titreCourt=' + titreCourt + '&description=' + description
            		 + '&annee=' + annee + '&mois=' + mois + '&jour=' + jour + '&duree=' + duree
            		 + '&Eve_lieu=' + lieu + '&Eve_type=' + type);
		}
		
		/* Cette fonction permet de supprimer un evenement */
		function supprimerEve(numEve, annee, mois, jour, urlRetour)
		{						
			var xhr = getXMLHttpRequest();
				
			if(xhr && xhr.readyState != 0) 
			{
				xhr.abort();
			}

			xhr.onreadystatechange = function()
				{ 
					if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
					{							
						if((annee != null) && (mois != null) && (jour != null))
							getJour(annee, mois, jour, urlRetour);
						return xhr.responseText;
					}
				}
			xhr.open("POST", "./Fonctions_Php/Setters/supEve.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send('numEve=' + numEve);
		}
		
		/* Cette fonction permet de passer en mode "connecte" */
		function switchToConecte(user)
		{
			connecte = user;
			
			contenuConnexion = '<form name="formLoggin" action="" method="post" enctype="multipart/form-data">'
			+ '<center>'
			+ '<table>'
			+ '<tr>'
			+ '<tr><th rowspan="2" style="width: 45%;">Bienvenue ' + user + ' </th>'
			+ '<th style="width: 10%;"></th>'
			+ '<th style="width: 15%;" align="center"></th>'
			+ '<th style="width: 15%;" align="center"></th>'
			+ '<th style="width: 15%;" align="center"></th>'
			+ '</tr>'
			+ '<tr>'
			+ '<td style="width: 10%;"></th>'
			+ '<td style="width: 15%;" align="center"> </td>'
			+ '<th style="width: 15%;" align="center"> </td>'
			+ '<td style="width: 15%;" align="center"> <input type="button" style="width: 100%;" value="D&eacute;connexion" onclick="getLdapDeconnexion();"/> </td>'
			+ '</tr>'
			+ '</table>'
			+ '</center>'
			+ '<input type="hidden" name="deco" value=1 ;/>'
			+ '</form>';
			
			document.getElementById('sousMenu').innerHTML = contenuConnexion;
		}
		
		/* cette fonction permet de passer en mode "deconecte" */
		function switchToDeConecte()
		{
			connecte = false;
			
			contenuConnexion = '<form name="formLoggin" action="" method="post" enctype="multipart/form-data">'
			+ '<center>'
			+ '<table>'
			+ '<tr>'
			+ '<tr><th rowspan="2" style="width: 45%;"> </th>'
			+ '<th style="width: 10%;"></th>'
			+ '<th style="width: 15%;" align="center">Utilisateur</th>'
			+ '<th style="width: 15%;" align="center">Mot de passe</th>'
			+ '<th style="width: 15%;" align="center"></th>'
			+ '</tr>'
			+ '<tr>'
			+ '<td style="width: 10%;"></th>'
			+ '<td style="width: 15%;" align="center"> <input type="text" name="menu_login" id="menu_login" value="" class="texte" /> </td>'
			+ '<th style="width: 15%;" align="center"> <input type="password" name="menu_pass" id="menu_pass" value="" class="texte" /> </td>'
			+ '<td style="width: 15%;" align="center"> <input type="button" style="width: 100%;" value="Connexion" onclick="submitFormLogin();"/> </td>'
			+ '</tr>'
			+ '</table>'
			+ '</center>'
			+ '</form>';
			
			document.getElementById('sousMenu').innerHTML = contenuConnexion;
		}
