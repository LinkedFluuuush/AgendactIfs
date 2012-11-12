		var Menu_indexAnnee;
		var Menu_indexMois;
		var Menu_indexJour;
		
		var connecte = false;
		
		//variables du menu Gestion
		var recherche = true;
		var formulaire = false;
		
		//recherche
		var rech_nom = true;
		var rech_date = false;
		var rech_auteur = false;
		
		var rech_nom_par = 0;
		var contenuTitre = '';
		
		date = new Date();

		var rech_annee_index = date.getFullYear();
		var rech_mois_index = date.getMonth() + 1;
		var rech_jour_index = date.getDate();
		
		var contenuAuteur = '';
		
		var retour = 0;
		
		var affichage = 0;
		
		var contenuCalendrier = '<form name="formRecherche" action="" method="post" enctype="multipart/form-data">'
		+ '<center>'
		+ '<table>'
		+ '<tr>'
		+ '<th></th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th>Ann&eacute;e :</th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th>Mois :</th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th>Jour :</th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th></th>'
		+ '</tr>'
		+ '<tr>'
		+ '<th><input type="button" value="Mini-Calendrier" onclick="ds_sh(this, \'Menu\');" style="width:120px;"/></th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th> <select name="annee" id="Menu_annee" style="width:120px;" onChange="modification(\'Menu\')";></select> </th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th> <select name="mois" id="Menu_mois" style="width:120px;" onChange="modification(\'Menu\')";></select> </th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th> <select name="jour" id="Menu_jour" style="width:120px;"></select> </th>'
		+ '<th style="width: 10px;"></th>'
		+ '<th><input type="button" name="envoie" id="envoie" value="Afficher" style="width:120px;" onclick="submitFormRecherche();"/></th>'
		+ '</tr>'
		+ '</table>'
		+ '</center>'
		+ '</form>';
		
		var contenuGestion = '<center>'
		+ 'G&eacute;rez vos propres &eacute;v&eacute;nements'
		+ '</center>';
		
		var contenuConnexion = '<form name="formLoggin" action="" method="post" enctype="multipart/form-data">'
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
