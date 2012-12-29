//miniCalendrier(2015, 0);//0 -> Janvier, 11 -> Décembre

function miniCalendrier(a, m){
	var date = new Date(a, m, 1, 0,0,0,0);
	var href;
        
	var mainDiv = document.getElementById('miniCalendrier');
	
                var calendrier = document.createElement('table');
		var bandeauLigne = document.createElement('tr');
		var cg = document.createElement('th');
		var cm = document.createElement('th');
                cm.setAttribute("colspan", "5");
		var cd = document.createElement('th');
			var lienMois = document.createElement('a');
			href = "mois.php?annee="+date.getFullYear()+"&mois="+(date.getMonth()+1);
			lienMois.href = href;
			lienMois.appendChild(document.createTextNode(retourneMois(date.getMonth()) + " " + date.getFullYear()));
	
			var suivMois = document.createElement('p');
			suivMois.appendChild(document.createTextNode("►"));
	
			cd.onclick =  function () {
				if(date.getMonth() == 11){
					mainDiv.innerHTML = '';
					miniCalendrier(date.getFullYear()+1, 0);
				}else{
					mainDiv.innerHTML = '';
					miniCalendrier(date.getFullYear(), date.getMonth()+1);
				}
			}
	
			var precMois = document.createElement('p');
			precMois.appendChild(document.createTextNode("◄"));

			cg.onclick =  function () {
				if(date.getMonth() == 0){
					mainDiv.innerHTML = '';
					miniCalendrier(date.getFullYear()-1, 11);
				}else{
					mainDiv.innerHTML = '';
					miniCalendrier(date.getFullYear(), date.getMonth()-1);
				}
			}			
	
		cg.appendChild(precMois);
		cm.appendChild(lienMois);
		cd.appendChild(suivMois);
		
		bandeauLigne.appendChild(cg);
		bandeauLigne.appendChild(cm);
		bandeauLigne.appendChild(cd);
		
                calendrier.appendChild(bandeauLigne);
			
		var corpsLigneTete = document.createElement('tr');
                corpsLigneTete.id = "nomJourSemaine";
		//var cVide = document.createElement('th');
		var cLundi = document.createElement('td');
		var cMardi = document.createElement('td');
		var cMercredi = document.createElement('td');
		var cJeudi = document.createElement('td');
		var cVendredi = document.createElement('td');
		var cSamedi = document.createElement('td');
		var cDimanche = document.createElement('td');
		
		cLundi.appendChild(document.createTextNode("L"));
		cMardi.appendChild(document.createTextNode("M"));
		cMercredi.appendChild(document.createTextNode("M"));
		cJeudi.appendChild(document.createTextNode("J"));
		cVendredi.appendChild(document.createTextNode("V"));
		cSamedi.appendChild(document.createTextNode("S"));
		cDimanche.appendChild(document.createTextNode("D"));
		
	//	corpsLigneTete.appendChild(cVide);
		corpsLigneTete.appendChild(cLundi);
		corpsLigneTete.appendChild(cMardi);
		corpsLigneTete.appendChild(cMercredi);
		corpsLigneTete.appendChild(cJeudi);
		corpsLigneTete.appendChild(cVendredi);
		corpsLigneTete.appendChild(cSamedi);
		corpsLigneTete.appendChild(cDimanche);
		
                calendrier.appendChild(corpsLigneTete);
		
		var cSemaine, c, cLigne, lienJour;
		var nJour = 1, nDebutMois, nSemaine = 0;
		var txtLien;
		
		cLigne = document.createElement('tr');
                cLigne.id = "jourMois";
		c = document.createElement('td');
		c.appendChild(document.createTextNode(""));
		
		nDebutMois = date.getDay()-1;
		if(nDebutMois == -1){
			nDebutMois = 6;
		}
		
		for(var i = 0; i < nDebutMois; i++){
			cLigne.appendChild(document.createElement('td'));
			nSemaine++;
		}
		
		for(var i = 0; i < retourneJourMois(date.getMonth(), date.getFullYear()); i++){
			c = document.createElement('td'); 
			lienJour = document.createElement('a');
			lienJour.href = "jour.php?a="+date.getFullYear()+"&m="+(date.getMonth()+1)+"&j="+nJour;
			lienJour.appendChild(document.createTextNode(nJour));
			c.appendChild(lienJour);
			/*txtLien = "jour.php?a="+date.getFullYear()+"&m="+(date.getMonth()+1)+"&j="+nJour;
			c.onclick = function(){
				document.location.href=txtLien;
			}*/
			nJour++;
			cLigne.appendChild(c);
			nSemaine++;
			if(nSemaine % 7 == 0 || i == retourneJourMois(date.getMonth(), date.getFullYear())-1){
                                calendrier.appendChild(cLigne);
				cLigne = document.createElement('tr');
                                cLigne.id = "jourMois";
			}
		}
	
        mainDiv.appendChild(calendrier);
}

function retourneMois(mois){
	switch(mois){
		case 0 : return "Janvier";
		case 1 : return "Février";
		case 2 : return "Mars";
		case 3 : return "Avril";
		case 4 : return "Mai";
		case 5 : return "Juin";
		case 6 : return "Juillet";
		case 7 : return "Août";
		case 8 : return "Septembre";
		case 9 : return "Octobre";
		case 10 : return "Novembre";
		case 11 : return "Décembre";
		default : return;
	}
}

function retourneJourMois(mois, annee){
	switch(mois){
		case 0 : return 31;
		case 1 : return estBissextile(annee);
		case 2 : return 31;
		case 3 : return 30;
		case 4 : return 31;
		case 5 : return 30;
		case 6 : return 31;
		case 7 : return 31;
		case 8 : return 30;
		case 9 : return 31;
		case 10 : return 30;
		case 11 : return 31;
		default : return 0;
	}
}

function estBissextile(annee){
	if ((annee%4 == 0) && (annee%100 != 0)){
		return 29;
	}
	else
	{
		if (annee%400 == 0){
			return 29;
		}
		else{
			return 28;
		}
	}
}
