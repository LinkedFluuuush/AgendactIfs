<?php session_start(); 
header( 'content-type: text/html; charset=utf-8' ); ?>
<!DOCTYPE html>
<html>
    <head>
	<title>Modifier un évènement</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <link href="../../bootstrap.css" rel="stylesheet" type="text/css">
    </head>
    <body>

<?php
//Connexion a la bdd
include("../../Fonctions_Php/connexion.php");
include_once("../../Fonctions_Php/diverses_fonctions.php");

if ($_SESSION['id'] && !empty($_GET['i'])) {
    $idUtil = $_SESSION['id'];
    $req = "SELECT idUtilisateur FROM aci_evenement WHERE idEvenement = ".$_GET['i'];
    $resultat = $conn->query($req);
    try{
	$row = $resultat->fetch();
	if($row[0] != $idUtil)
		// Redirection vers la page mois.php si la personne n'est pas l'auteur
		header('Location: ../Calendrier/mois.php');
    }
    catch(Exception $e){
	// Redirection vers la page mois.php si l'evenement n'existe pas
	header('Location: ../Calendrier/mois.php');
    }
}
else {
    // Redirection vers la page mois.php si la personne n'est pas connectée
    header('Location: ../Calendrier/mois.php');
}

$insertion = false;

//--------REGEX---------//

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
		
		//Vérifications nécessaires seulement si une date de fin est définie
		if(!empty($_POST['dateFin']))
		{
			if(regexDate($_POST['dateFin']) && comparaisonDate($_POST['dateFin'], date("d/m/Y")))
				$dateFin = $_POST['dateFin'];
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
		}
		else
			$dateFin = null;
		
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
			//$sql = "INSERT INTO `aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `IDLIEU`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
			//VALUES ($idEv[0], $idUtil, $priorite, $idLieu, '$libelleLong', '$libelleCourt', '$description', str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i'), str_to_date('$dateFin $heureFin', '%d/%m/%Y %H:%i'), $public, curdate())";

			$sql = "UPDATE aci_evenement SET IDPRIORITE = ".$priorite.", IDLIEU = ".$idLieu.", LIBELLELONG = ".$libelleLong.", LIBELLECOURT=".$libelleCourt.", DESCRIPTION = ".$description.", 
			DATEDEBUT = str_to_date('".$dateDebut." ".$heureDebut."', '%d/%m/%Y %H:%i'), DATEFIN = str_to_date('".$dateFin." ".$heureFin."', '%d/%m/%Y %H:%i'), ESTPUBLIC = ".$public.", DATEINSERT = curdate() WHERE idEvenement = ".$_GET['i'];
			
			$resultats = $conn->query($sql);
			
			$sqlDelRappel = "DELETE FROM aci_rappel WHERE idEvenement = ".$_GET['i'];
			$sqlDelDest = "DELETE FROM aci_destutilisateur WHERE idEvenement = ".$_GET['i'];
			$sqlDelGroupe = "DELETE FROM aci_destgroupe WHERE idEvenement = ".$_GET['i'];
			
			$resultats = $conn->query($sqlDelRappel);
			$resultats = $conn->query($sqlDelDest);
			$resultats = $conn->query($sqlDelGroupe);

			
			//Préparation de la création des rappels - récupération du premier idrappel utilisable
			$sqlIdRappel= "select max(idrappel)+1 from aci_rappel";
			$temp2 = $conn->query($sqlIdRappel);
			$idRappel = $temp2->fetch();
			
			//Création du rappel à l'auteur de l'événement
			$sqlRappel = "INSERT INTO aci_rappel VALUES($idRappel[0], $idEv[0], $idUtil, str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i') - INTERVAL 1 DAY)";
			$idRappel[0]++;
			$exec = $conn->query($sqlRappel);

			if(!empty($_POST['dest']) && $public == 0)
			{
				$dest[] = $_POST['dest'];
				
				foreach($dest as $cle => $contenu){
					foreach($contenu as $cle2 => $contenu2){
					
						$sqlId = "SELECT idutilisateur FROM aci_utilisateur WHERE adresse_mail='".$contenu2."'";
						
						$temp = $conn->query($sqlId);
						$idDestUtil = $temp->fetch();
						
						//Insertion des utilisateurs destinataires
						$sql = "INSERT INTO aci_destutilisateur VALUES (".$idDestUtil[0];
						$sql.=", ".$idEv[0].", curdate())";

						$insert = $conn->query($sql);

						//Envoi de notifications
						notifications($conn, $idDestUtil[0], $_SESSION['nom'], $_SESSION['prenom'], $dateDebut.' '.$heureDebut, $dateFin.' '.$heureFin, $libelleLong, 'creer');
						
						//Création de rappels
						$sqlRappel = "INSERT INTO aci_rappel VALUES($idRappel[0], $idEv[0], $idDestUtil[0], str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i') - INTERVAL 1 DAY)";
						$idRappel[0]++;
						$exec = $conn->query($sqlRappel);
					}
				}
			}
			
			if(!empty($_POST['groupe']) && $public == 0)
			{
				$groupe[] = $_POST['groupe'];
				
				foreach($groupe as $cle => $contenu){
					foreach($contenu as $cle2 => $contenu2){
					
						//Insertion des utilisateurs destinataires
						$sql = "INSERT INTO aci_destgroupe VALUES (".$idEv[0].", $contenu2";
						$sql.=", curdate())";

						$insert = $conn->query($sql);
						
						$sqlMail = "SELECT idutilisateur FROM aci_composer JOIN aci_groupe USING ( idgroupe ) WHERE idgroupe LIKE '".$contenu2."%'";
						
						$temp = $conn->query($sqlMail);
						
						while($mailGroupe = $temp->fetch())
						{
							//Envoi de notifications
							notifications($conn, $mailGroupe[0], $_SESSION['nom'], $_SESSION['prenom'], $dateDebut.' '.$heureDebut, $dateFin.' '.$heureFin, $libelleLong, 'creer');
							
							$sqlRappel = "INSERT INTO aci_rappel VALUES($idRappel[0], $idEv[0], $mailGroupe[0], str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i') - INTERVAL 1 DAY)";
							
							//Création de rappels
							$idRappel[0]++;
							$exec = $conn->query($sqlRappel);
						}
						
						$temp->closeCursor();
					}
				}
			}
						
			if(!empty($resultats))
				$insertion = true;
		}
	}
}
else {
	$req = "SELECT * from aci_evenement where idevenement =". $_GET["i"];
	$res = $conn->query($req);
	$row = $res->fetch();

	$_POST["libelleLong"] = $row["LIBELLELONG"];
	$_POST["libelleCourt"] = $row["LIBELLECOURT"];
	$_POST["description"] = $row["DESCRIPTION"];
	
	$dateD = formattageDate(explodeDate($row["DATEDEBUT"]));
	
	$_POST["dateDebut"] = $dateD[1];
	$_POST["heureDebut"] = $dateD[0];
	
	$dateF = formattageDate(explodeDate($row["DATEFIN"]));
	
	$_POST["dateFin"] = $dateF[1];
	$_POST["heureFin"] = $dateF[0];
	
	$reqLieu = "SELECT libelle from aci_lieu where idlieu =" .$row["IDLIEU"];
	$resLieu = $conn->query($reqLieu);
	$rowLieu = $resLieu->fetch();
	
	$_POST["lieu"] = $rowLieu["libelle"];

	$_POST["public"] = $row["ESTPUBLIC"];
	
	$reqParticipant = "SELECT adresse_mail from aci_destutilisateur 
	JOIN aci_utilisateur ON aci_destutilisateur.idutilisateur = aci_utilisateur.idutilisateur 
	WHERE idevenement = ".$_GET["i"];
	$resParticipant = $conn->query($reqParticipant);
	
	if ($resParticipant->rowCount() > 0){
		$i = 0;
		while($rowParticipant = $resParticipant->fetch(PDO::FETCH_NUM)){
			$rowParticipants[$i] = $rowParticipant[0];
			$i++;
		}
		
		$_POST["dest"] = $rowParticipants;
	}
	
	$reqGroupe = "SELECT idgroupe from aci_destgroupe
	WHERE idevenement = ".$_GET["i"];
	$resGroupe = $conn->query($reqGroupe);
	
	if ($resGroupe->rowCount() > 0){ 
		$i = 0;
		while($rowGroupe = $resGroupe->fetch(PDO::FETCH_NUM)){
			$rowGroupes[$i] = $rowGroupe[0];
			$i++;
		}
		
		$_POST["groupe"] = $rowGroupes;
	}
}
?>
        <div id="global">
            <?php include('../menu.php'); ?>
            <div id="corpsCal" class="creer">
                <table class="titreCal">
                    <tr class="titreCal">
                        <th>Modifier un évènement</th>
                    </tr>
                </table>
                
                <form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data" id="formCreation">
                    <table cellpadding="4">
                        <tr>
                            <td>
                                <b>Priorité</b> <br>
                                <select name="priorite" id="priorite">
                                    <option value="1">Haute</option>';
                                    <option value="2" selected>Moyenne</option>';
                                    <option value="3">Basse</option>';
                                </select>
                            </td>
                           <!--  <td rowspan="4">
                                <label for="addParticipant"><b>Ajouter un destinataire</b></label><br>
                                <select id="dest" name="dest[]" multiple style="height:200px;width:250px;">
                                </select><br/>
                                <input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/>
                                <div id="resultsParticipant"></div>
                            </td> -->
			    
			    <td rowspan="4" id="tddest">
                                <label for="addParticipant"><b>Ajouter un destinataire</b></label><br>
                                <div id="dest" style="overflow:auto;height:250px;width:250px;border:1px solid #abadb3;padding:5px;background-color:white;">
				<?php saisieFormReq("dest", $conn);?>
                                </div><br/>
                                <input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/>
                                <div id="resultsParticipant"></div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="Eve_titreLong"><b>Titre long</b></label> <br>
                                <input type="text" name="libelleLong" id="Eve_titreLong" value="<?php saisieFormString("libelleLong");?>" class="libelleLong" maxlength=32 />
                                <?php echo "<b id=\"formErreur\"> $erreurLibelleLong </b>"; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="Eve_titreCourt"><b>Titre court</b></label> <br>
                                <input type="text" name="libelleCourt" id="Eve_titreCourt" value="<?php saisieFormString("libelleCourt");?>" class="libelleCourt" maxlength=5 />
                                <?php echo "<b id=\"formErreur\"> $erreurLibelleCourt </b>"; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="Eve_description"><b>Description</b></label> <br>
                                <textarea name="description" rows="5" cols="30" id="Eve_description" class="area"><?php saisieFormString("description");?></textarea>
                                <?php echo "<b id=\"formErreur\"> $erreurDescription </b>"; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="Eve_dateDebut"><b>Date de début</b></label><br>
                                <?php if (!empty($_GET['a']) and !empty($_GET['m']) and !empty($_GET['j'])) { ?>
                                    <input type="text" name="dateDebut" id="Eve_dateDebut" value="<?php echo $_GET['j'].'/'.$_GET['m'].'/'.$_GET['a']; ?>" class="dateDebut" maxlength=10 size=11/>
                                <?php } else { ?>
                                    <input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" value="<?php saisieFormString("dateDebut");?>" class="dateDebut" maxlength=10 size=11/>
                                <?php } ?>
                                    <input type="text" name="heureDebut" id="Eve_heureDebut" placeholder="hh:mm" value="<?php saisieFormString("heureDebut");?>" class="heureDebut" maxlength=5 size=4/>
                                    <?php echo "<b id=\"formErreur\"> $erreurDateDebut $erreurHeureDebut </b>"; ?>
                            </td>
                            <td rowspan="4" id="tdgroupe">
                                <label for="groupe"><b>Ajouter un groupe de participants</b></label><br>
                                <div id="groupe" style="overflow:auto;height:250px;width:250px;border:1px solid #abadb3;padding:5px;background-color:white;">
                                    <?php
                                    $req = "SELECT idgroupe, libelle FROM aci_groupe WHERE idgroupe NOT IN (SELECT idgroupe_1 FROM aci_contenir)";
                                    $resultats = $conn -> query($req);
                                    while($row = $resultats->fetch()){
                                        echo '<img id="'.utf8_encode($row['idgroupe']).'"src="../../Images/arborescencePlus.png" onclick="developper('.utf8_encode($row['idgroupe']).')"/>
					<label for="'.utf8_encode($row['idgroupe']).'" onclick="developper('.utf8_encode($row['idgroupe']).')"> '
					.$row['libelle'].'</label><input type="checkbox" name="groupe[]" value="'.utf8_encode($row['idgroupe']).'" 
					id="'.utf8_encode($row['idgroupe']).'" '.checkAuto(utf8_encode($row['idgroupe'])).'/><br/>';
                                        descGroupe($row['idgroupe'], $conn, 1);
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>

                        <tr>                    
                            <td>
                                <label for="Eve_dateFin"><b>Date de fin</b></label><br>
                                <input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" value="<?php saisieFormString("dateFin");?>"class="dateFin" maxlength=10 size=11/>
                                <input type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" value="<?php saisieFormString("heureFin");?>" class="heureFin" maxlength=5 size=4/>
                                <?php echo "<b id=\"formErreur\"> $erreurDateFin $erreurHeureFin </b>"; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="Eve_lieu"><b>Lieu</b></label> <br>
                                <input type="text" name="lieu" value="<?php saisieFormString("lieu");?>" id="Eve_lieu" autocomplete="off" />
                                <div id="resultsLieu"></div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b>Type</b> <br>
								<?php if(isset($_POST["public"]) && $_POST["public"] == 0) {?>
									<input type="radio" name="public" id="public" value="1" onclick="cacher()"> <label for="public" onclick="cacher()">Public</label>
									<input type="radio" name="public" id="prive" value="0" checked="checked" onclick="cacher()"> <label for="prive" onclick="cacher()">Privé</label>
								<?php } else { ?>
									<input type="radio" name="public" id="public" value="1" checked="checked" onclick="cacher()"> <label for="public" onclick="cacher()">Public</label>
									<input type="radio" name="public" id="prive" value="0" onclick="cacher()"> <label for="prive" onclick="cacher()">Privé</label>
								<?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td cellspan="2">
                                <input class="btn" type="submit" name="submit" value="Valider"/>
                            </td>
                        </tr>

                        <tr>
                            <td cellspan="2">
                                <?php
                                if($insertion)
                                    echo '<div class="alert alert-success"><b>Insertion réalisée avec succès.</b></div>';
                                ?>
                            </td>
                        </tr>
                    </table>
                 </form>
            </div>
        </div>
            
        <script type="text/javascript">
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
                var div = document.createElement('div');
		var hidden = document.createElement('input');
		var img = document.createElement('img');
                var other;
		
		img.src='../../Images/boutonMoinsReduit2.png';
		img.style.cursor='pointer';
		
		hidden.type='hidden';
		hidden.name='dest[]';
                hidden.value=result.innerHTML.split(" ")[2];
		
                div.appendChild(document.createTextNode(result.innerHTML));
		div.appendChild(document.createTextNode("  "));
		div.appendChild(img);
		div.appendChild(hidden);
                div.onclick = function(){
			removeChildSafe(div);	
		}
                selected.appendChild(div);
                searchElement.value = '';
                results.style.display = 'none';
                result.className = '';
                searchElement.focus();
            }

            function removeChildSafe(el) {
            //before deleting el, recursively delete all of its children.
            while(el.childNodes.length > 0) {
                removeChildSafe(el.childNodes[el.childNodes.length-1]);
            }
            el.parentNode.removeChild(el);
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

        function developper(idGroupe){
                var spans = document.getElementsByClassName(idGroupe);
                var i;
                var img = document.getElementById(idGroupe);
                var src = img.src.split('/');

                if(src[src.length-1] == "arborescencePlus.png"){
                        img.src="../../Images/arborescenceMoins.png";
                        for(i=0; i < spans.length; i++){
                                spans[i].style.display="block";
                        }
                }
                else{
                        img.src="../../Images/arborescencePlus.png";
                        for(i=0; i < spans.length; i++){
                                spans[i].style.display="none";
                        }
                }			
        }
	
	function cacher(){
		var radio = document.getElementById("public");
		var divdest = document.getElementById("dest");
		var dest = document.getElementById("addParticipant");
		var groupe = document.getElementsByTagName("input");
		if(radio.checked==true){
			dest.disabled=true;
			for(var i = 0; i < groupe.length; i++){
				if(groupe[i].name=="groupe[]"){
					groupe[i].checked = false;
					groupe[i].disabled=true;
				}
			}
			divdest.innerHTML="";
		}else{
			dest.disabled=false;	
			for(var i = 0; i < groupe.length; i++){
				if(groupe[i].name=="groupe[]"){
					groupe[i].disabled=false;
				}
			}
		}
	}
	
	function changerType(estpublic){
		if(estpublic == 0)
			var publi = document.getElementById("public");
			var prive = document.getElementById("prive");
			publi.checked = false;
			prive.checked = true;
	}
        </script>
    </body>
</html>
