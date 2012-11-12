		function chargementAjax()
		{
			document.getElementById('corpsCal').innerHTML = '';
		}
		
		function cal_supprimerEve(numEve, annee, mois, jour, urlRetour)
		{
			var confirmation = confirmerSuppression();
	
			if (confirmation)
			{
				supprimerEve(numEve, annee, mois, jour, urlRetour);
			}
		}
		
		function setCalendrierSelected()
		{
			document.getElementById('calendrier').setAttribute("class", "selected");
			document.getElementById('calendrier').setAttribute("className", "selected");
			
			document.getElementById('gestion').setAttribute("class", "deselected");
			document.getElementById('gestion').setAttribute("className", "deselected");
			
			document.getElementById('connexion').setAttribute("class", "deselected");
			document.getElementById('connexion').setAttribute("className", "deselected");
		}
		
		function setGestionSelected()
		{
			document.getElementById('calendrier').setAttribute("class", "deselected");
			document.getElementById('calendrier').setAttribute("className", "deselected");
			
			document.getElementById('gestion').setAttribute("class", "selected");
			document.getElementById('gestion').setAttribute("className", "selected");

			document.getElementById('connexion').setAttribute("class", "deselected");
			document.getElementById('connexion').setAttribute("className", "deselected");
		}
		
		function setConnexionSelected()
		{
			document.getElementById('calendrier').setAttribute("class", "deselected");
			document.getElementById('calendrier').setAttribute("className", "deselected");
			
			document.getElementById('gestion').setAttribute("class", "deselected");
			document.getElementById('gestion').setAttribute("className", "deselected");
			
			document.getElementById('connexion').setAttribute("class", "selected");
			document.getElementById('connexion').setAttribute("className", "selected");
		}
		
		function trim (myString)
		{
			return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
		}

		function getType()
		{
			if(document.getElementById('public').checked)
				return 1;
			else 
				return 0;
		}

		/* fonctions vérifiant la validité d'un formulaire */
		function verifier(titreLong, titreCourt, desc, duree, lieu, annee, mois, jour)
		{	
			//ces variables contiennent des caractères autorisé comme les accents ou les symboles &, °, en base hexadecimale
			var accents = '\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF7\xF8\xF9\xFA\xFB\xFC\xFD\xFE\xFF';
			var symboles = '\xBD\xB0\x26\x2D';
			
			//les expressions regulières verifiant les titres
			var regTitresLong = new RegExp('^[a-z0-9' + accents + ']{1}[a-z0-9 :.\'_' + accents + symboles + ']{0,}[a-z0-9' + accents + ']{1}$','gi');
			var regTitresCourt = new RegExp('^[a-z0-9' + accents + ']{1}[a-z0-9 :.\'_' + accents + symboles + ']{0,}[a-z0-9' + accents + ']{1}$','gi');
			var regLieu = new RegExp('^[a-z0-9' + accents + ']{1}[a-z0-9 :.\'_-' + accents + symboles + ']{0,}[a-z0-9' + accents + ']{1}$','gi');
			var regDuree = new RegExp('^[0-9]+');

			//les expressions regulières verifiant la description
			var regDescDebut = new RegExp('[a-z0-9' + accents + ']{1}','gi');
			var regDescFin = new RegExp('[a-z0-9?!.' + accents + ']{1}','gi');
			var regHtml = new RegExp('\x3C[a-z/]*[ ]*.*\x3E','gi');
			
			/* verification du titre long : 
			 * respecte-t-il la longueur minimal (5 caractères)
			 * respecte-t-il le format d'un titre
			*/
			
			if (titreLong.length >= 5)
			{
				if (!regTitresLong.test(titreLong))
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un titre long correct</span>';
					return 0;	
				}
			}
			else
			{
				document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un titre long d\'au minimum 5 caract&egrave;res</span>';
				return 0;
			}
			
			/* verification du titre court : 
			 * respecte-t-il la longueur minimal (2 caractères)
			 * respecte-t-il le format d'un titre
			*/
			
			if (titreCourt.length >= 2)
			{
				if (!regTitresCourt.test(titreCourt))
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un titre court correct</span>';
					return 0;
				}
			}
			else
			{
				document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un titre court d\'au minimum 2 caract&egrave;res</span>';
				return 0;
			}
			
			/* verification de la description : 
			 * respecte-t-elle la longueur minimal (20 caractères)
			 * respecte-t-il le format d'une description
			 * contient-elle du code pouvant être nuisible à l'application
			*/
			if(desc != '')
			{
				if ( (regDescDebut.test(desc[0])) && (regDescFin.test(desc[desc.length - 1])) )
				{
					if(regHtml.test(desc))
					{
						document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez retirer le code html de la description</span>';
						return 0;
					}
				}
				else
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez respecter le format de la description</span>';
					return 0;
				}

				/*else
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir une description d\'au minimum 20 caract&egrave;res</span>';
					return 0;
				}*/
			}
			/* verification de la date de l'événement :
			 * le mois est-il choisi
			 * le jour est-il choisi
			*/
			
			if (document.getElementById('Eve_Form_mois').selectedIndex == 0)
			{
				document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un mois pour la date de l\'&eacute;v&eacute;nement</span>';
				return 0;
			}
			
			if (document.getElementById('Eve_Form_jour').selectedIndex == 0)
			{
				document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un jour pour la date de l\'&eacute;v&eacute;nement</span>';
				return 0;
			}
			
			/* verification de la durée de l'événement : 
			 * est-ce un nombre
			 * est-ce 0
			*/
			
			if (regDuree.test(duree))
			{
				if(duree == 0)
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir une dur&eacute;e d\'au minimum 1 jour</span>';
					return 0;	
				}
			}
			else
			{
				document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir une dur&eacute;e correcte</span>';
				return 0;
			}
			
			if(lieu != '')
			{
				/*if (!regTitresLong.test(lieu))
				{
					document.getElementById('Eve_Message').innerHTML = '<span class="erreur">Veuillez saisir un lieu</span>';
					return 0;	
				}*/
			}
			return 1;
		}

