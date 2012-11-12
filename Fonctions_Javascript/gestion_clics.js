		/* Cette fonction gere le click sur l'onglet calendrier 
		Elle indique également que le mode d'affichage est désormais le 1 */ 
		function clickCalendrier()
		{ 
			if(affichage != 1)
			{				
				if(affichage == 2)
				{				
					if(recherche)
					{
						enregistrerDonnees();
					}
				}
				
				setCalendrierSelected();
				
				affichage = 1;
				
				document.getElementById('sousMenu').innerHTML = contenuCalendrier;
				
				setDate(Menu_indexAnnee, Menu_indexMois, Menu_indexJour, 'Menu_annee', 'Menu_mois', 'Menu_jour');
				
				if (Menu_indexJour != null && Menu_indexMois != null && Menu_indexAnnee != null)
				{
					if (Menu_indexJour == 0)
					{
						if (Menu_indexMois == 0)
						{
							getSemestre(Menu_indexAnnee, Menu_indexMois);
						}
						
						else
						{
							getMois(Menu_indexAnnee, Menu_indexMois)
						}
					}
					else
					{
						getJour(Menu_indexAnnee, Menu_indexMois, Menu_indexJour, retour);
					}
				}
			}
		}
		
		/* Cette fonction gere le click sur l'onglet Gestion 
		Elle modifie le contenu du menu superieur et mémorise les variables de date si necessaire 
		Elle indique également que le mode d'affichage est désormais le 2 */ 
		function clickGestion()
		{ 
			if(affichage != 2)
			{
				if(affichage == 1)
				{				
					Menu_indexAnnee = document.getElementById('Menu_annee').selectedIndex + 2008;
					Menu_indexMois = document.getElementById('Menu_mois').selectedIndex;
					Menu_indexJour = document.getElementById('Menu_jour').selectedIndex;
				}
				
				setGestionSelected();
				
				affichage = 2;
				
				document.getElementById('sousMenu').innerHTML = contenuGestion;
			}
		}
		
		/* Cette fonction gere le click sur l'onglet connexion 
		Elle modifie le contenu du menu superieur et mémorise les variables de date si necessaire 
		Elle indique également que le mode d'affichage est désormais le 3 */ 
		function clickConnexion()
		{
			if(affichage != 3)
			{
				if(affichage == 1)
				{					
					Menu_indexAnnee =  document.getElementById('Menu_annee').selectedIndex + 2008;
					Menu_indexMois = document.getElementById('Menu_mois').selectedIndex;
					Menu_indexJour = document.getElementById('Menu_jour').selectedIndex;
				}
				
				if(affichage == 2)
				{				
					if(recherche)
					{
						enregistrerDonnees();
					}
				}
				
				setConnexionSelected();

				affichage = 3;
				
				document.getElementById('sousMenu').innerHTML = contenuConnexion;
			}
		}
		
		/* Cette fonction est appellée une fois le boutton "Afficher" utilisé (onglet calendrier)
		Elle nous renvoie sur la page semestre si le mois et le jour ne sont pas définis
		Sur la page mois si le jour uniquement n'est pas défini
		Sur la page jour sinon */
		function submitFormRecherche()
		{
			annee =  document.getElementById('Menu_annee').selectedIndex + 2008;
			mois = 	document.getElementById('Menu_mois').selectedIndex; 
			jour = document.getElementById('Menu_jour').selectedIndex; 
			
			if (jour == 0)
			{
				if (mois == 0)
				{
					getSemestre(annee, mois);
					retour = 1;
				}
				
				else
				{
					getMois(annee, mois)
					retour = 2;
				}
			}
			else
			{
				getJour(annee, mois, jour, retour);
			}
		}
		
		/* Cette fonction est appelé lorsque l'on clique sur le bouton "connexion" (onglet connexion) */
		function submitFormLogin()
		{
			login = document.getElementById('menu_login').value;
			pass = document.getElementById('menu_pass').value;
			
			getLdapConnexion(login, pass);
		}
		
		/* Cette fonction est appelée lorsque l'on valide la creation d'un evenement */
		function clickCreation()
		{	
			var titreLong = trim(document.getElementById('Eve_titreLong').value);
			var titreCourt = trim(document.getElementById('Eve_titreCourt').value);
			var desc = trim(document.getElementById('Eve_description').value);
			var duree = trim(document.getElementById('Eve_duree').value);
			var lieu = trim(document.getElementById('Eve_lieu').value);
			
			var annee = document.getElementById('Eve_Form_annee').options[document.getElementById('Eve_Form_annee').selectedIndex].value;
			var mois = document.getElementById('Eve_Form_mois').options[document.getElementById('Eve_Form_mois').selectedIndex].value;
			var jour = document.getElementById('Eve_Form_jour').options[document.getElementById('Eve_Form_jour').selectedIndex].value;
			
			var type = getType();
			
			verification = verifier(titreLong, titreCourt, desc, duree, lieu, annee, mois, jour);
			
			if(verification == 1)
			{
				CreateEve(titreLong, titreCourt, desc, annee, mois, jour, duree, lieu, type);
				document.getElementById('Eve_Message').innerHTML = '<span class="info">&Eacute;v&eacute;nement cr&eacute;&eacute;</span>';
				clickCalendrier();
				getJour(annee, mois, jour, 2);
			}
		}
		
		/* Cette fonction est appelée lorsque l'on valide la modification d'un evenement */
		function clickModification(numEve)
		{
			var titreLong = trim(document.getElementById('Eve_titreLong').value);
			var titreCourt = trim(document.getElementById('Eve_titreCourt').value);
			var desc = trim(document.getElementById('Eve_description').value);
			var duree = trim(document.getElementById('Eve_duree').value);
			var lieu = trim(document.getElementById('Eve_lieu').value);
			
			var annee = document.getElementById('Eve_Form_annee').options[document.getElementById('Eve_Form_annee').selectedIndex].value;
			var mois = document.getElementById('Eve_Form_mois').options[document.getElementById('Eve_Form_mois').selectedIndex].value;
			var jour = document.getElementById('Eve_Form_jour').options[document.getElementById('Eve_Form_jour').selectedIndex].value;
			
			var type = getType();
			
			verification = verifier(titreLong, titreCourt, desc, duree, lieu, annee, mois, jour);
			
			if(verification == 1)
			{
				modifierEve(numEve, titreLong, titreCourt, desc, annee, mois, jour, duree, lieu, type);
				document.getElementById('Eve_Message').innerHTML = '<span class="info">&Eacute;v&eacute;nement modifi&eacute;</span>';
				clickCalendrier();
				getJour(annee, mois, jour, 2);
			}
		}
