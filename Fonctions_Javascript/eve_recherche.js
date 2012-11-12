function switchRechNom(maCase)
{
	if (maCase.checked)
	{
		document.getElementById("rech_Nom_Par").disabled = false;
		document.getElementById("titreSelect").disabled = false;
	}
	else
	{
		document.getElementById("rech_Nom_Par").disabled = true;
		document.getElementById("titreSelect").disabled = true;
	}
	rechercher();
}
		
function switchRechDate(maCase)
{
	if (maCase.checked)
	{
		document.getElementById("Eve_Rech_annee").disabled = false;
		document.getElementById("Eve_Rech_mois").disabled = false;
		document.getElementById("Eve_Rech_jour").disabled = false;
	}
	else
	{
		document.getElementById("Eve_Rech_annee").disabled = true;
		document.getElementById("Eve_Rech_mois").disabled = true;
		document.getElementById("Eve_Rech_jour").disabled = true;
	}
	rechercher();
}

function switchRechAuteur(maCase)
{
	if (maCase.checked)
	{
		document.getElementById("liste_auteur").disabled = false;
		document.getElementById("auteur_select").disabled = false;
	}
	else
	{
		document.getElementById("liste_auteur").disabled = true;
		document.getElementById("auteur_select").disabled = true;
	}
	rechercher();
}

function eve_selected()
{
	//on degrise les boutons modifier / supprimer
	liste = document.getElementById('affichageSelect');
	auteurName = liste.options[liste.selectedIndex].id;
	
	if(auteurName == connecte)
	{
		document.getElementById("eve_modifier").disabled = false;
		document.getElementById("eve_supprimer").disabled = false;
	}
	else
	{
		document.getElementById("eve_modifier").disabled = true;
		document.getElementById("eve_supprimer").disabled = true;
	}
	
	document.getElementById("selected_description").innerHTML = liste.options[liste.selectedIndex].label;
}

function modifTitre()
{
	if (contenuTitre != document.getElementById("titreSelect").value)
	{
		contenuTitre = document.getElementById("titreSelect").value;
		rechercher();
	}
}

function modifAuteur()
{
	document.getElementById('liste_auteur').innerHTML = "";
	
	if (contenuAuteur != document.getElementById("auteur_select").value)
	{
		contenuAuteur = document.getElementById("auteur_select").value;
		rechercher_auteur();
	}
}

function griserModifierSupprimer()
{
	document.getElementById("eve_modifier").disabled = true;
	document.getElementById("eve_supprimer").disabled = true;
}

function rech_click_supprimerEve()
{						
	var confirmation = confirmerSuppression();
	
	if (confirmation)
	{
		listeSelection = document.getElementById('affichageSelect');
		
		supprimerEve(listeSelection.options[listeSelection.selectedIndex].value, null, null, null);
		rechercher();
	}
}

function rechercher() 
{
	prefixe = "";
	mesPosts = "";
			
	griserModifierSupprimer();
	document.getElementById("selected_description").innerHTML = "";
			
	document.getElementById("affichageSelect").innerHTML = "";
			
	if(document.getElementById("recherche_nom").checked)
	{
		if (contenuTitre.length < 2)
			return -1;
	
		typeNoms = document.getElementById('rech_Nom_Par');
	
		mesPosts = "rech_Nom_Par=" + typeNoms.options[typeNoms.selectedIndex].value;
		mesPosts = mesPosts + "&titreSelect=" + encodeURI(contenuTitre).replace(/&/, "%26");
	
		prefixe = "&";
	}
	if(document.getElementById("recherche_date").checked)
	{
		date_Annee = document.getElementById('Eve_Rech_annee');
		date_Mois = document.getElementById('Eve_Rech_mois');
		date_Jour = document.getElementById('Eve_Rech_jour');
	
		mesPosts = mesPosts + prefixe + "annee=" + date_Annee.options[date_Annee.selectedIndex].value;
		mesPosts = mesPosts + "&mois=" + date_Mois.options[date_Mois.selectedIndex].value;
		mesPosts = mesPosts + "&jour=" + date_Jour.options[date_Jour.selectedIndex].value;
	
		prefixe = "&";
	}
	if(document.getElementById("recherche_auteur").checked)
	{
		listeAuteur = document.getElementById('liste_auteur');
				
		if (listeAuteur.innerHTML.length < 2)
			return -1;
			
		mesPosts = mesPosts + prefixe + "auteur=" + encodeURI(listeAuteur.options[listeAuteur.selectedIndex].value).replace(/&/, "%26");	
	}
	if (mesPosts != "")
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
					document.getElementById("affichageSelect").innerHTML = decodeURI(xhr.responseText);
				}
			}

		xhr.open("POST", "./Fonctions_Php/Setters/eve_recherche.php", true);
	    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=iso-8859-1');
	    xhr.send(mesPosts);
	}
}

function rechercher_auteur()
{
	if (contenuAuteur.length < 2)
	{
		document.getElementById("recherche_auteur").innerHTML = "";
		document.getElementById("affichageSelect").innerHTML = "";
		return 0;
	}	
	else
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
					document.getElementById("liste_auteur").innerHTML = decodeURI(xhr.responseText);
					rechercher();
				}
			}

		xhr.open("POST", "./Fonctions_Php/Setters/recherche_auteur.php", true);
	    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=iso-8859-1');
	    xhr.send("auteur=" + encodeURI(contenuAuteur).replace(/&/, "%26"));
	}
}

function rech_click_Modifier()
{
	listeEve = document.getElementById('affichageSelect');
	
	numEve = listeEve.options[listeEve.selectedIndex].value;
	
	getEveModif(numEve);
}

function enregistrerDonnees()
{
	rech_nom = document.getElementById('recherche_nom').checked;
	rech_date = document.getElementById('recherche_date').checked;
			
	rech_nom_par = document.getElementById('rech_Nom_Par').selectedIndex;
	rech_nom_titre = document.getElementById('titreSelect').value;
			
	rech_annee_index = document.getElementById('Eve_Rech_annee').selectedIndex;
	rech_mois_index = document.getElementById('Eve_Rech_mois').selectedIndex;
	rech_jour_index = document.getElementById('Eve_Rech_jour').selectedIndex;
						
	rech_auteur = document.getElementById('recherche_auteur').checked;
	contenuAuteur = document.getElementById('auteur_select').value;
}