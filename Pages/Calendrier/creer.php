<?php header( 'content-type: text/html; charset=utf-8' ); ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Page jour</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
	</head>
    <body>
        <?php
        //Connexion a la bdd
        include("../../Fonctions_Php/connexion.php");
        $idUtil = 1;
        $insertion = false;

        //--------REGEX---------//
        include_once("../../Fonctions_Php/diverses_fonctions.php");

        $valide = true; //Permet de savoir si au moins un élément saisi dans le formulaire est invalide
        $erreurLibelleCourt = "";
        $erreurLibelleLong = "";
        $erreurDescription = "";
        $erreurDateDebut = "";
        $erreurDateFin = "";
        $erreurHeureDebut = "";
        $erreurHeureFin = "";

        if(!empty($_POST['submit']))
        {
                //Vérification de la saisie des champs nécessaires
                if(empty($_POST['libelleCourt']))
                {
                        $valide = false;
                        $erreurLibelleCourt = "Obligatoire";
                }

                if(empty($_POST['libelleLong']))
                {
                        $valide = false;
                        $erreurLibelleLong = "Obligatoire";
                }

                if(empty($_POST['description']))
                {
                        $valide = false;
                        $erreurDescription = "Obligatoire";
                }

                if(empty($_POST['dateDebut']))
                {
                        $valide = false;
                        $erreurDateDebut = "Obligatoire";
                }

                if($valide)
                {
                        $priorite = $_POST['priorite'];
                        $public = $_POST['public'];
                        $libelleCourt = $_POST['libelleCourt'];
                        $libelleLong = $_POST['libelleLong'];
                        $description = $_POST['description'];

                        //Remise à zéro des variables pour tests par expressions régulières
                        $valide = true;
                        $erreurLibelleCourt = "";
                        $erreurLibelleLong = "";
                        $erreurDescription = "";
                        $erreurDateDebut = "";
                        $erreurDateFin = "";
                        $erreurHeureDebut = "";
                        $erreurHeureFin = "";

                        //Libelle court
                        $libelleCourt = accents($libelleCourt);

                        $libelleCourt = htmlspecialchars($libelleCourt);

                        //Libelle long
                        $libelleLong = accents($libelleLong);

                        $libelleLong = htmlspecialchars($libelleLong);

                        //Description
                        $description = accents($description);

                        $description = htmlspecialchars($description);

                        //Date
                        if(regexDate($_POST['dateDebut']))
                                $dateDebut = $_POST['dateDebut'];
                        else
                        {
                                $valide = false;
                                $erreurDateDebut = "La date saisie est invalide";
                        }

                        if(regexDate($_POST['dateFin']))
                                $dateFin = $_POST['dateFin'];
                        else if (empty($_POST['dateFin']))
                        {
                                $dateFin = null;
                        }
                        else
                        {
                                $valide = false;
                                $erreurDateFin = "La date saisie est invalide";
                        }

                        //Heure
                        if(regexHeure($_POST['heureDebut']))
                                $heureDebut = $_POST['heureDebut'];
                        else if (empty($_POST['heureDebut']))
                        {
                                $heureDebut = "00:00";
                        }
                        else
                        {
                                $valide = false;
                                $erreurHeureDebut = "L'heure saisie est invalide";
                        }

                        if(regexHeure($_POST['heureFin']))
                                $heureFin = $_POST['heureFin'];
                        else if (empty($_POST['heureFin']))
                        {
                                $heureFin = "00:00";
                        }
                        else
                        {
                                $valide = false;
                                $erreurHeureFin = "L'heure saisie est invalide";
                        }

                        if($valide)
                        {
                                //Récupération du prochain numéro d'événement attribuable
                                $reqIdEv = "select max(idevenement)+1 from aci_evenement";
                                $temp = $conn->query($reqIdEv);
                                $idEv = $temp->fetch();
                                echo "$idEv[0], $idUtil, $priorite, 1, $libelleLong, $libelleCourt, $description, $dateDebut $heureDebut, $dateFin $heureFin, $public";
                                $sql = "INSERT INTO `aci_bdd`.`aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `IDLIEU`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
                                VALUES ($idEv[0], $idUtil, $priorite, 1, '$libelleLong', '$libelleCourt', '$description', str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i'), str_to_date('$dateFin $heureFin', '%d/%m/%Y %H:%i'), $public, curdate());";

                                $resultats = $conn->query($sql);

                                //TODO
                                $sqlDestUtilisateur = "INSERT INTO `aci_bdd`.`aci_destutilisateur` (`IDUTILISATEUR`, `IDEVENEMENT`, `DATEINSERT`) 
                                VALUES (, $idEv[0], curdate());";

                                //TODO
                                $sqlDestGroupe = "INSERT INTO `aci_bdd`.`aci_destgroupe` (`IDEVENEMENT`, `IDGROUPE`, `DATEINSERT`) 
                                VALUES ($idEv[0], , curdate());";

                                if(!empty($resultats))
                                        $insertion = true;
                        }
                }
        }
        ?>
        <div id="global">
            <?php include('../menu.php'); ?>
        <div id="corpsCal" class="jour">
            <table class="titreCal"><tr class="titreCal"><th>créer un évènement</th></tr></table>
        <form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data" id="formCreation">
                <table cellpadding="4" align="center">
                        <tr>
                                <td class="descForm">Priorité : </td>
                                <td class="Form">
                                <select name="priorite" id ="priorite">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected>3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                </select>
                                </td>
                        <tr>
                                <td class="descForm">Titre long : </td>
                                <td class="Form"><input type="text" name="libelleLong" id="Eve_titreLong" value="" class="libelleLong" maxlength=32 />
                                <?php echo "<br><b id=\"formErreur\"> $erreurLibelleLong </b>"; ?></td>
                        </tr>
                        <tr>
                                <td class="descForm">Titre court : </td>
                                <td class="Form"><input type="text" name="libelleCourt" id="Eve_titreCourt" value="" class="libelleCourt" maxlength=5 />
                                <?php echo "<br><b id=\"formErreur\"> $erreurLibelleCourt </b>"; ?></td>
                        </tr>
                        <tr>
                                <td class="descForm">Description :</td>
                                <td class="Form"><textarea name="description" rows="5" cols="30" id="Eve_description" class="area"></textarea>
                                <?php echo "<br><b id=\"formErreur\"> $erreurDescription </b>"; ?></td>
                        </tr>
                        <tr>
                                <td class="descForm">Date de début :</td>
                                <td class="Form">
                                        <input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" class="dateDebut" maxlength=10 size=10/>
                                        <input type="text" name="heureDebut" id="Eve_heureDebut" placeholder="hh:mm" class="heureDebut" maxlength=5 size=3/>
                                        <?php echo "<br><b id=\"formErreur\"> $erreurDateDebut $erreurHeureDebut </b>"; ?>
                                </td>
                        </tr>
                        <tr>
                                <td class="descForm">Date de fin :</td>
                                <td class="Form">
                                        <input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" class="dateFin" maxlength=10 size=10/>
                                        <input type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" class="heureFin" maxlength=5 size=3/>
                                        <?php echo "<br><b id=\"formErreur\"> $erreurDateFin $erreurHeureFin </b>"; ?>
                                </td>
                        </tr>
                        <tr>
                                <td class="descForm">Lieu : </td>
                                <td class="Form">
                                        <select name="lieu">
                                        <?php
                                        //Récupération lieu
                                        $sql = "SELECT libelle FROM aci_lieu";

                                        $resultats = $conn->query($sql);
                                        while($row = $resultats->fetch())
                                                echo '<option value="'.$row['libelle'].'"> '.$row['libelle'].'</option>';
                                        ?>
                                </select>
                                <input type="text" name="lieu" id="Eve_lieu" autocomplete="off" />
                                <div id="resultsLieu"></div>

                        </tr>
                        <tr>
                                <td class="descForm"> Type : </td>
                                <td class="Form">
                                <input type="radio" name="public" id="public" value="1" checked="checked"> <label for="public">public</label>
                                <input type="radio" name="public" id="prive" value="0"> <label for="prive">priv&eacute;</label>
                                </td>
                        </tr>
                        <tr>
                                <td class="descForm"> Ajouter un participant : </td>
                                <td class="Form"> <input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/> <a id="plusParticipant" href=""> <img src="../../Images/boutonPlusReduit.png"> </a></td>
                        </tr>
                        <tr><td class="descForm"> Ajouter un groupe de participants : </td>
                        <td class="Form">
                                <select name="groupe1" id ="groupe1" onchange="selectGroupe2()">
                                        <option value="0"></option>
                                        <?php
                                        //Récupération lieu
                                        $sql = "SELECT idgroupe, libelle FROM aci_groupe where length(idgroupe) = 1";

                                        $resultats = $conn->query($sql);
                                        while($row = $resultats->fetch())
                                                echo '<option value="'.utf8_encode($row['idgroupe']).'"> '.utf8_encode($row['libelle']).'</option>';
                                        ?>
                                </select>
                                <select name="groupe2" id ="groupe2" onchange="selectGroupe3()">
                                        <option value="0"></option>
                                </select>
                                <select name="groupe3" id ="groupe3">
                                        <option value="0"></option>
                                </select>
                        </td></tr>
                        <tr><td>
                                <input type="submit" name="submit" value="Valider" class="boutonForm"/>
                                <input type="reset" value="R&eacute;initialiser" class="boutonForm" onclick="reset()"/>
                        </td></tr>
                </table>
        </form>
        <?php if($insertion) echo '<h3 align="center">Insertion réalisée avec succés</h2>'; ?>
        </div>
        </div>
        </body>
</html>

<script>
function selectGroupe2(){
	var list = document.getElementById('groupe1');
	var selectionne = list.value;
	
	var xhr = new XMLHttpRequest();
	
	xhr.onreadystatechange = function (){
	    if(xhr.readyState == 4){
		    if(xhr.status == 200 || xhr.status == 0){
			/*var response = xhr.responseXML;
			alert(xhr.getAllResponseHeaders());
			var options = response.getElementsByTagName('option');
			var i;
			var select = document.getElementById('groupe2');
			for(i = 0; i < options.length; i++){
				select.appendChild(options[i]);
			}*/
			document.getElementById('groupe2').innerHTML = "<option value=0></option>" + xhr.responseText;
			document.getElementById('groupe3').innerHTML = "<option value=0></option>";
		    }
	    }
	}
	
	xhr.open('POST','../../Fonctions_Php/XMLSelectEvent.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send('valeur='+selectionne);
}

function selectGroupe3(){
	var list = document.getElementById('groupe2');
	var selectionne = list.value;
	
	var xhr = new XMLHttpRequest();
	
	xhr.onreadystatechange = function (){
	    if(xhr.readyState == 4){
		    if(xhr.status == 200 || xhr.status == 0){
			/*var response = xhr.responseXML;
			alert(xhr.getAllResponseHeaders());
			var options = response.getElementsByTagName('option');
			var i;
			var select = document.getElementById('groupe3');
			for(i = 0; i < options.length; i++){
				select.appendChild(options[i]);
			}*/
			document.getElementById('groupe3').innerHTML = "<option value=0></option>" + xhr.responseText;
		    }
	    }
	}
	
	xhr.open('POST','../../Fonctions_Php/XMLSelectEvent.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send('valeur='+selectionne);
}

function reset(){
    document.getElementById('groupe2').innerHTML = "<option value=0></option>";
    document.getElementById('groupe3').innerHTML = "<option value=0></option>";   
}

(function(){

    var searchElement = document.getElementById('Eve_lieu');
    var results = document.getElementById('resultsLieu');
    var value = searchElement.value;
    var selectedResult = -1;
    var previousRequest;
    var previousValue = searchElement.value;
    
    function getLieu(value){
	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
	    if(xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)){
		afficheLieu(xhr.responseText);
	    }
	}
	
	xhr.open('POST', '../../Fonctions_Php/XMLgetLieu.php');
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send('valeur='+value);
	
	return xhr;
    }

    function afficheLieu(response){
	results.style.display = response.length ? 'block' : 'none';

	if(response.length){
	    var lieux = response.split('|');
	    
	    results.innerHTML = '';

	    for(var i = 0, div; i < lieux.length ; i++){
		div = results.appendChild(document.createElement('div'));
		div.innerHTML = lieux[i];

		div.onclick = function(){
		    chooseResult(this);
		}
	    }
	}
    }

    function chooseResult(result){
	searchElement.value = result.innerHTML;
	results.style.display = 'none';
	result.className = '';
	searchElement.focus();
    }
    
    searchElement.onkeyup = function(e){
	e = e || window.event;
	    
	var divs = results.getElementsByTagName('div');
	if(e.keyCode == 38 && selectedResult > -1){
		divs[selectedResult--].className = '';
		if(selectedResult > -1){
			divs[selectedResult].className = 'result_focus';
		}
	}
	else if(e.keyCode == 40 && selectedResult < divs.length-1){
		results.style.display = 'block';
		if(selectedResult > -1){
			divs[selectedResult].className = 'result_focus';
		}
		divs[++selectedResult].className = '';
	}
	else if(e.keyCode == 13 && selectedResult > -1){
		chooseResult(divs[selectedResult]);
	}
	else if(searchElement.value == ''){
	    results.innerHTML = '';
	}
	else if(searchElement.value != previousValue){
		previousValue = searchElement.value;
		
		if(previousRequest && previousRequest.readyState < 4){
			previousRequest.abort();
		}
		
		previousRequest = getLieu(previousValue);
		selectedResult = -1;
	}
    }
})();
</script>