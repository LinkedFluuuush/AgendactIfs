<?php header( 'content-type: text/html; charset=utf-8' ); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Créer un évènement</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <link href="../../bootstrap.css" rel="stylesheet" type="text/css">
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
$erreurDate ="";
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
		$lieu = $_POST['lieu'];
	
		//Remise à zéro des variables pour tests par expressions régulières
		$valide = true;
		$erreurLibelleCourt = "";
		$erreurLibelleLong = "";
		$erreurDescription = "";
		$erreurDateDebut = "";
		$erreurDateFin = "";
		$erreurDate = "";
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

		if(regexDate($_POST['dateDebut']) && comparaisonDate($_POST['dateDebut'], date("d/m/Y")))
			$dateDebut = $_POST['dateDebut'];
		else
		{
			$valide = false;
			$erreurDateDebut = "La date saisie est invalide";
		}
		
		if(regexDate($_POST['dateFin']) && comparaisonDate($_POST['dateFin'], date("d/m/Y")))
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
		
		if(comparaisonDate($_POST['dateFin'], $_POST['dateDebut'])){}
		else
		{
			$valide = false;
			$erreurDate = "Un évènement ne peut pas se terminer avant de commencer";
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
			$reqIdEv = "select ifnull(max(idevenement),0)+1 from aci_evenement";
			$temp = $conn->query($reqIdEv);
			$idEv = $temp->fetch();
			
			//Récupération de l'idlieu du lieu à ajouter à l'événement
			if(!empty($lieu))
			{
				$sqlRecupId = "SELECT idlieu FROM aci_lieu WHERE libelle = '$lieu'";

				$temp = $conn->query($sqlRecupId);
				$idLieu = $temp->fetch();
				$idLieu =  $idLieu['idlieu'];
			}
			else
				$idLieu = null;
			
			//Insertion de l'événement
			//echo "$idEv[0], $idUtil, $priorite, 1, $libelleLong, $libelleCourt, $description, $dateDebut $heureDebut, $dateFin $heureFin, $public";
			$sql = "INSERT INTO `aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `IDLIEU`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
			VALUES ($idEv[0], $idUtil, $priorite, $idLieu, '$libelleLong', '$libelleCourt', '$description', str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i'), str_to_date('$dateFin $heureFin', '%d/%m/%Y %H:%i'), $public, curdate())";
			
			$resultats = $conn->query($sql);
			
			if(!empty($_POST['addParticipant0']) && $public == 0)
			{
				$i = 0;

				while(!empty($_POST["addParticipant$i"]))
				{
					$dest = $_POST["addParticipant$i"];

					//Récupération de l'idutilisateur du participant à ajouter à l'événement
					$sqlRecupId = "SELECT idutilisateur FROM aci_utilisateur WHERE adresse_mail = '$dest'";

					$temp = $conn->query($sqlRecupId);
					$id = $temp->fetch();
					$id =  $id['idutilisateur'];

					$sqlDestUtilisateur = "INSERT INTO `aci_bdd`.`aci_destutilisateur` (`IDUTILISATEUR`, `IDEVENEMENT`, `DATEINSERT`) 
					VALUES ($id, $idEv[0], curdate())";
					
					$insertionUtil = $conn->query($sqlDestUtilisateur);
					
					$i++;
				}
			}
			
			//TODO
			/* for($i=0;$i<$destGroupe.length;$i++)
			{
				$sqlDestGroupe = "INSERT INTO `aci_bdd`.`aci_destgroupe` (`IDEVENEMENT`, `IDGROUPE`, `DATEINSERT`) 
				VALUES ($idEv[0], $destGroupe[$i], curdate())";
			
				$insertionGroupe = $conn->query($sqlDestGroupe);
			}*/
			
			if(!empty($resultats))
				$insertion = true;
		}
	}
}
?>
<div id="global">
            <?php include('../menu.php'); ?>
        <div id="corpsCal" class="creer">
            <table class="titreCal">
                <tr class="titreCal">
                    <th>créer un évènement</th>
                </tr>
            </table>
<form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data" id="formCreation">
	<table cellpadding="4" align="center">
		<tr>
			<td class="descForm">Priorité : </td>
			<td class="Form">
			<select name="priorite" id ="priorite">
				<option value="1">Haute</option>';
				<option value="2" selected>Moyenne</option>';
				<option value="3">Basse</option>';
			</select>
			</td>
		<tr>
			<td class="descForm">Titre long : </td>
			<td class="Form"><input type="text" name="libelleLong" id="Eve_titreLong" value="<?php saisieFormString("libelleLong");?>" class="libelleLong" maxlength=32 />
			<?php echo "<b id=\"formErreur\"> $erreurLibelleLong </b>"; ?></td>
		</tr>
		<tr>
			<td class="descForm">Titre court : </td>
			<td class="Form"><input type="text" name="libelleCourt" id="Eve_titreCourt" value="<?php saisieFormString("libelleCourt");?>" class="libelleCourt" maxlength=5 />
			<?php echo "<b id=\"formErreur\"> $erreurLibelleCourt </b>"; ?></td>
		</tr>
		<tr>
			<td class="descForm">Description :</td>
			<td class="Form"><textarea name="description" rows="5" cols="30" id="Eve_description" class="area"><?php saisieFormString("description");?></textarea>
			<?php echo "<b id=\"formErreur\"> $erreurDescription </b>"; ?></td>
		</tr>
		<tr>
			<td class="descForm">Date de début :</td>
			<td class="Form">
				<input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" value="<?php saisieFormString("dateDebut");?>" class="dateDebut" maxlength=10 size=11/>
				<input type="text" name="heureDebut" id="Eve_heureDebut" placeholder="hh:mm" value="<?php saisieFormString("heureDebut");?>" class="heureDebut" maxlength=5 size=4/>
				<?php echo "<b id=\"formErreur\"> $erreurDateDebut $erreurHeureDebut </b>"; ?>
			</td>
		</tr>
		<tr>
			<td class="descForm">Date de fin :</td>
			<td class="Form">
				<input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" value="<?php saisieFormString("dateFin");?>"class="dateFin" maxlength=10 size=11/>
				<input type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" value="<?php saisieFormString("heureFin");?>" class="heureFin" maxlength=5 size=4/>
				<?php echo "<b id=\"formErreur\"> $erreurDateFin $erreurHeureFin </b>"; ?>
			</td>
		</tr>
		<tr>
			<td class="descForm">Lieu : </td>
			<td class="Form">
				<input type="text" name="lieu" value="<?php saisieFormString("lieu");?>" id="Eve_lieu" autocomplete="off" />
				<div id="resultsLieu"></div>
			</td>
		</tr>
		<tr>
			<td class="descForm"> Type : </td>
			<td class="Form">
			<input type="radio" name="public" id="public" value="1" checked="checked"> <label for="public">public</label>
			<input type="radio" name="public" id="prive" value="0"> <label for="prive">privé</label>
			</td>
		</tr>
		<tr>
			<td class="descForm"> Ajouter un destinataire : </td>
			<td class="Form"> 
			<select id="dest" multiple style="width:250px;">
			</select><br/>
			<input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/>
			<div id="resultsParticipant"></div></td>
		</tr>
		<tr><td class="descForm"> Ajouter un groupe de participants : </td>
		<td class="Form">
			<div id="groupe" style="overflow:auto;height:250px;width:250px;border:1px solid;border-radius:5px;padding:5px;">
				<?php
					$req = "SELECT idgroupe, libelle FROM aci_groupe WHERE idgroupe NOT IN (SELECT idgroupe_1 FROM aci_contenir)";
					$resultats = $conn -> query($req);
					while($row = $resultats->fetch()){
						echo '<img id="'.utf8_encode($row['idgroupe']).'"src="../../Images/arborescencePlus.png" onclick="developper('.utf8_encode($row['idgroupe']).')"/> <label for="'.utf8_encode($row['idgroupe']).'">'.utf8_encode($row['libelle']).'</label><input type="checkbox" name="groupe[]" value="'.utf8_encode($row['idgroupe']).'" id="'.utf8_encode($row['idgroupe']).'"/><br/>';
						descGroupe($row['idgroupe'], $conn, 1);
					}
				?>
			</div>
		</td></tr>
		<tr><td>
			<input class="btn" type="submit" name="submit" value="Valider" class="boutonForm"/>
			<input class="btn" type="reset" value="R&eacute;initialiser" class="boutonForm" onclick="reset()"/>
		</td></tr>
	</table>
</form>

<?php if($insertion) echo '<h3 align="center">Insertion réalisée avec succés</h3>'; ?>
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

(function(){

    var searchElement = document.getElementById('addParticipant');
    var results = document.getElementById('resultsParticipant');
    var selected = document.getElementById('dest');
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
	
	xhr.open('POST', '../../Fonctions_Php/XMLgetPersonne.php');
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
	var div = document.createElement('option');
	div.selected = "true";
	div.value=result.innerHTML.split(" ")[2];
	div.appendChild(document.createTextNode(result.innerHTML));
	div.onclick = function(){
		div.parentNode.removeChild(div);
	}	
	selected.appendChild(div);
	searchElement.value = '';
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

function developper(idGroupe, close){
	var spans = document.getElementsByClassName(idGroupe);
	var i;
	var img = document.getElementById(idGroupe);
	
	if(img.src.lastIndexOf("arborescencePlus.png") !=-1 && close != 1){
		img.src="../../Images/arborescenceMoins.png";
		for(i=0; i < spans.length; i++){
			spans[i].style.display="inline";
		}
	}
	else{
		img.src="../../Images/arborescencePlus.png";
		for(i=0; i < spans.length; i++){
			spans[i].style.display="none";
		}
	}			
}
</script>
