/* Cette fonction permet d'engager une connexion avec l'annuaire ldap. */
function getLdapConnexion(login, pass)
{			
	login = encodeURI(login).replace(/&/, "%26");
	pass = encodeURI(pass).replace(/&/, "%26");
	
	var xhr = getXMLHttpRequest();

	if(xhr && xhr.readyState != 0) 
	{
        xhr.abort();
    }
    chargementAjax();
 
    xhr.onreadystatechange = function()
						    { 
								if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != "") 
						        {
									connecte = xhr.responseText;
									if(connecte != "")
										switchToConecte(connecte);
						        }
						    }
	xhr.open("POST", "./Fonctions_Php/Setters/Ldap_verif.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("menu_login=" + login + "&menu_pass=" + pass);
}

/* Cette fonction permet de détruire la session utilisateur en cours. */
function getLdapDeconnexion()
		{			
			var xhr = getXMLHttpRequest();
			
			
			if(xhr && xhr.readyState != 0) 
			{
                xhr.abort();
            }
            chargementAjax();
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						switchToDeConecte();
                    }
           		}
			xhr.open("POST", "./Fonctions_Php/Setters/Ldap_deco.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(null);
		}

/* cette fonction permet d'obtenir la page jour */
function getJour(annee, mois, jour, retour)
{			
	var xhr = getXMLHttpRequest();
	
	if(xhr && xhr.readyState != 0) 
	{
		xhr.abort();
	}
	chargementAjax();

	xhr.onreadystatechange = function()
    	{ 
			if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
			{
				document.getElementById("contenu").innerHTML = xhr.responseText;
				document.getElementById("corpsCal").setAttribute("class", "jour");
				retour = 0;
				setDate(annee, mois, jour, 'Menu_annee', 'Menu_mois', 'Menu_jour');
			}
		}

	xhr.open("POST", "./Pages/Calendrier/jour.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("annee=" + annee + "&mois=" + mois + "&jour=" + jour + "&urlRetour=" + retour);
}
		
/* cette fonction permet d'obtenir la page mois */
function getMois(annee, mois)
{			
	var xhr = getXMLHttpRequest();

	if(xhr && xhr.readyState != 0) 
	{
		xhr.abort();
    }
    chargementAjax();

	xhr.onreadystatechange = function()
    	{ 
			if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
			{
				document.getElementById("contenu").innerHTML = xhr.responseText;
				document.getElementById("corpsCal").setAttribute("class", "mois");
				retour = 2;getElementById
				setDate(annee, mois, 0, 'Menu_annee', 'Menu_mois', 'Menu_jour');
			}
		}

	xhr.open("POST", "./Pages/Calendrier/mois.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("annee=" + annee + "&mois=" + mois + "&jour=" + 0);
}
		
/* cette fonction permet d'obtenir la page semestre */
function getSemestre(annee, mois)
{			
	var xhr = getXMLHttpRequest();
			
	if(xhr && xhr.readyState != 0) 
	{
		xhr.abort();
	}
	chargementAjax();

	xhr.onreadystatechange = function()
    	{
			if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
            {
				document.getElementById("contenu").innerHTML = xhr.responseText;
				document.getElementById("corpsCal").setAttribute("class", "semestre");
				retour = 1;
				setDate(annee, 0, 0, 'Menu_annee', 'Menu_mois', 'Menu_jour');
			}
		}
		
	xhr.open("POST", "./Pages/Calendrier/semestre.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("annee=" + annee + "&mois=" + mois + "&jour=" + 0);
}

/* Cette fonction permet d'obtenir la page de recherche */
function getEveRecherche()
		{			
			var xhr = getXMLHttpRequest();
			
			if(xhr && xhr.readyState != 0) 
			{
                xhr.abort();
            }
            chargementAjax();
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						document.getElementById("contenu").innerHTML = xhr.responseText;
						document.getElementById("corpsCal").setAttribute("class", "evenement");
						
						setDate(rech_annee_index+2008, rech_mois_index, rech_jour_index, 'Eve_Rech_annee', 'Eve_Rech_mois', 'Eve_Rech_jour');
		
						document.getElementById('rech_Nom_Par').selectedIndex = rech_nom_par;
						document.getElementById('titreSelect').value = contenuTitre;

						document.getElementById('recherche_nom').checked = rech_nom;
						document.getElementById('recherche_date').checked = rech_date;
						document.getElementById('recherche_auteur').checked = rech_auteur;
						
						document.getElementById('auteur_select').value = contenuAuteur;
						rechercher_auteur();
						
						if(!connecte)
							document.getElementById("eve_ajouter").disabled = true;
						if(!rech_nom)
							switchRechNom(document.getElementById('recherche_nom'));
						if(!rech_date)
							switchRechDate(document.getElementById('recherche_date'));
						if(!rech_auteur)
						{
							switchRechAuteur(document.getElementById('recherche_auteur'));
							
						}
						formulaire = false;
						recherche = true;
                    }
           		}
			xhr.open("POST", "./Pages/Evenement/recherche.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
            xhr.send(null);
		}
		
		/* cette fonction permet d'obtenir le formulaire de creation */
		function getEveCrea(annee, mois, jour)
		{			
			var xhr = getXMLHttpRequest();
			
			if(xhr && xhr.readyState != 0) 
			{
                xhr.abort();
            }
            chargementAjax();
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						document.getElementById("contenu").innerHTML = xhr.responseText;
						document.getElementById("corpsCal").setAttribute("class", "evenement");
						
						if(annee != null && mois != null && jour != null)
							setDate(annee, mois, jour, 'Eve_Form_annee', 'Eve_Form_mois', 'Eve_Form_jour');
						else
						{
							date = new Date();
							setDate(date.getFullYear(), date.getMonth() + 1, date.getDate(), 'Eve_Form_annee', 'Eve_Form_mois', 'Eve_Form_jour');
						}
						
						clickGestion();
						recherche = false;
						formulaire = true;
                    }
           		}
			xhr.open("POST", "./Pages/Evenement/creer.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(null);
		}

		/* cette fonction permet d'obtenir le formulaire de modification */
		function getEveModif(numEve)
		{			
			var xhr = getXMLHttpRequest();
			
			if(xhr && xhr.readyState != 0) 
			{
                xhr.abort();
            }
            chargementAjax();

            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						document.getElementById("contenu").innerHTML = decodeURI(xhr.responseText);
						document.getElementById("corpsCal").setAttribute("class", "evenement");
						
						anneeModif = document.getElementById("anneeDefaut").value;
						moisModif = document.getElementById("moisDefaut").value;
						jourModif = document.getElementById("jourDefaut").value;
						
						setDate(anneeModif, moisModif, jourModif, 'Eve_Form_annee', 'Eve_Form_mois', 'Eve_Form_jour');
						
						recherche= false;
						formulaire = true;
						
						clickGestion();
                    }
           		}
			xhr.open("POST", "./Pages/Evenement/modifier.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("numEve=" + numEve);
		}
		
		/* Cette fonction permet d'afficher la page connexion (onglet connexion) */
		function getConnexion()
		{			
			var xhr = getXMLHttpRequest();
			
			if(xhr && xhr.readyState != 0) 
			{
                xhr.abort();
            }
            chargementAjax();
                
            xhr.onreadystatechange = function()
    			{ 
                    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
                    {
						document.getElementById("contenu").innerHTML = xhr.responseText;
						document.getElementById("corpsCal").setAttribute("class", "connexion");
                    }
           		}
			xhr.open("POST", "./Pages/Connexion/profil.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(null);
		}
