<?php session_start(); 
header( 'content-type: text/html; charset=utf-8' ); ?>
<!DOCTYPE html>
<html>
    <head>
	<title>Créer un évènement</title>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <link href="../../style.css" rel="stylesheet" type="text/css">
        <link href="../../style-minicalendrier.css" rel="stylesheet" type="text/css">
        <link href="../../bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../../jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
	<script src="../../Fonctions_Javascript/jquery-ui-timepicker-addon.js"></script>
	<script>jQuery(function($){
	   $.datepicker.regional['fr'] = {
	      closeText: 'Fermer',
	      prevText: '<Préc',
	      nextText: 'Suiv>',
	      currentText: 'Courant',
	      monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
	      'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
	      monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
	      'Jul','Aoû','Sep','Oct','Nov','Déc'],
	      dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
	      dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
	      dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
	      weekHeader: 'Sm',
	      //dateFormat: 'dd/mm/yy',
			dateFormat: 'dd/mm/yy',
	      firstDay: 1,
	      isRTL: false,
	      showMonthAfterYear: false,
	      yearSuffix: ''};
	   $.datepicker.setDefaults($.datepicker.regional['fr']);
	});
	</script>
	<script type="text/javascript">
	    jQuery(function($){ $.datepicker.setDefaults($.datepicker.regional['fr']); });
	</script>
	<script>
	$(function() {
	  $( "#Eve_dateDebut" ).datepicker();
	});
	</script>
	<script>
	$(function() {
	  $( "#Eve_dateFin" ).datepicker();
	});
	</script>
	<script>
	$(function() {
	  $('#Eve_heureDebut').timepicker();
	});
	</script>
		<script>
	$(function() {
	  $('#Eve_heureFin').timepicker();
	});
	</script>
    </head>
    <body>

<?php
//Connexion a la bdd
include("../../Fonctions_Php/connexion.php");
include_once("../../Fonctions_Php/diverses_fonctions.php");

if ($_SESSION['id']) {
    $idUtil = $_SESSION['id'];
}
else {
    // Redirection vers la page mois.php si la personne n'est pas connectée
    header('Location: ../Calendrier/mois.php');
}

$insertion = false;

//--------REGEX---------//

$valide = true; //Permet de savoir si au moins un élément saisi dans le formulaire est invalide
$public = 1;
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
			$erreurDateDebut = "La date saisie est invalide.";
		}
		
		//Vérifications nécessaires seulement si une date de fin est définie
		if(!empty($_POST['dateFin']))
		{
			if(regexDate($_POST['dateFin']) && comparaisonDate($_POST['dateFin'], date("d/m/Y")))
				$dateFin = $_POST['dateFin'];
			else
			{
				$valide = false;
				$erreurDateFin = "La date saisie est invalide.";
			}
			
			if(comparaisonDate($_POST['dateFin'], $_POST['dateDebut'])){}
			else
			{
				$valide = false;
				$erreurDate = "Un évènement ne peut pas se terminer avant de commencer.";
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
			$erreurHeureDebut = "L'heure saisie est invalide.";
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
			$erreurHeureFin = "L'heure saisie est invalide.";
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
				//Insertion de l'événement
				//echo "$idEv[0], $idUtil, $priorite, 1, $libelleLong, $libelleCourt, $description, $dateDebut $heureDebut, $dateFin $heureFin, $public";
				$sql = "INSERT INTO `aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `IDLIEU`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
				VALUES ($idEv[0], $idUtil, $priorite, '$lieu', '$libelleLong', '$libelleCourt', '$description', str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i'), str_to_date('$dateFin $heureFin', '%d/%m/%Y %H:%i'), $public, curdate())";
			}
			//Si il n'y a pas de lieu défini
			else
			{
				$sql = "INSERT INTO `aci_evenement` (`IDEVENEMENT`, `IDUTILISATEUR`, `IDPRIORITE`, `LIBELLELONG`, `LIBELLECOURT`, `DESCRIPTION`, `DATEDEBUT`, `DATEFIN`, `ESTPUBLIC`, `DATEINSERT`) 
				VALUES ($idEv[0], $idUtil, $priorite, '$libelleLong', '$libelleCourt', '$description', str_to_date('$dateDebut $heureDebut', '%d/%m/%Y %H:%i'), str_to_date('$dateFin $heureFin', '%d/%m/%Y %H:%i'), $public, curdate())";
			}
			$resultats = $conn->query($sql);

			//Préparation de la création des rappels - récupération du premier idrappel utilisable
			$sqlIdRappel= "SELECT ifnull(max(idrappel), 0)+1 FROM aci_rappel";
			$temp2 = $conn->query($sqlIdRappel);
			$idRappel = $temp2->fetch();
			
			//Création du rappel à l'auteur de l'événement
			
			$sqlInfosUtil = "SELECT rappelhaute, rappelmoyenne, rappelbasse FROM aci_utilisateur WHERE idutilisateur = ".$idUtil;
			$temp2 = $conn->query($sqlInfosUtil);
			$rappelUtil = $temp2->fetch();

			creationRappel($conn, $idEv[0], $priorite, $idUtil, $idRappel[0], $rappelUtil['rappelhaute'], $rappelUtil['rappelmoyenne'], $rappelUtil['rappelbasse'], $dateDebut.' '.$heureDebut);
			$idRappel[0]++;

			if(!empty($_POST['dest']) && $public == 0)
			{
				$dest[] = $_POST['dest'];
				
				foreach($dest as $cle => $contenu){
					foreach($contenu as $cle2 => $contenu2){
					
						$sqlId = "SELECT idutilisateur, rappelhaute, rappelmoyenne, rappelbasse FROM aci_utilisateur WHERE adresse_mail='".$contenu2."'";
						
						$temp = $conn->query($sqlId);
						$idDestUtil = $temp->fetch();
						
						//Insertion des utilisateurs destinataires
						$sql = "INSERT INTO aci_destutilisateur VALUES (".$idDestUtil[0];
						$sql.=", ".$idEv[0].", curdate())";

						$insert = $conn->query($sql);

						//Envoi de notifications
						notifications($conn, $idDestUtil[0], $_SESSION['nom'], $_SESSION['prenom'], $dateDebut.' '.$heureDebut, $dateFin.' '.$heureFin, $libelleLong, 'creer');
						
						//Création de rappels						
						creationRappel($conn, $idEv[0], $priorite, $idDestUtil[0], $idRappel[0], $idDestUtil['rappelhaute'], $idDestUtil['rappelmoyenne'], $idDestUtil['rappelbasse'], $dateDebut.' '.$heureDebut);
						$idRappel[0]++;
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
						
						$sqlMail = "SELECT idutilisateur, rappelhaute, rappelmoyenne, rappelbasse FROM aci_composer 
						JOIN aci_groupe USING ( idgroupe ) 
						JOIN aci_utilisateur USING (idutilisateur)
						WHERE idgroupe LIKE '".$contenu2."%'";
						
						$temp = $conn->query($sqlMail);
						
						while($mailGroupe = $temp->fetch())
						{
							//Envoi de notifications
							notifications($conn, $mailGroupe[0], $_SESSION['nom'], $_SESSION['prenom'], $dateDebut.' '.$heureDebut, $dateFin.' '.$heureFin, $libelleLong, 'creer');
							
							//Création de rappels						
							creationRappel($conn, $idEv[0], $priorite, $mailGroupe[0], $idRappel[0], $mailGroupe['rappelhaute'], $mailGroupe['rappelmoyenne'], $mailGroupe['rappelbasse'], $dateDebut.' '.$heureDebut);
							$idRappel[0]++;
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
?>
        <div id="global">
            <?php include('../menu.php'); ?>
            <div id="corpsCal" class="creer">
                <table class="titreCal">
                    <tr class="titreCal">
                        <th>Créer un évènement</th>
                    </tr>
                </table>
                
                <?php
                if($insertion)
                    echo '<div class="alert alert-success"><b>Insertion réalisée avec succès.</b></div>';
                ?>
                <form action="" name="FormCreaEvenement" method="post" enctype="multipart/form-data" id="formCreation">
                    <table>
                        <tr>
                            <td valign="top">
                                <table cellpadding="4">
                                    <tr>
                                        <td>
                                            <label for="priorite"><b>Priorité*</b></label><br>
                                            <select name="priorite" id="priorite">
                                                <option value="1">Haute</option>';
                                                <option value="2" selected>Moyenne</option>';
                                                <option value="3">Basse</option>';
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            if (!empty($erreurLibelleLong)) { ?>
                                                <label style="color: #b94947;" for="Eve_titreLong"><b>Titre long*</b></label> <br>
                                                <input style="border: 1px solid #b94947;" type="text" name="libelleLong" id="Eve_titreLong" value="<?php if(!$insertion){saisieFormString("libelleLong");}?>" class="libelleLong" maxlength=32 />
                                                <?php
                                                echo "<b style=\"color: #b94947;\" id=\"formErreur\">$erreurLibelleLong</b>";
                                            }
                                            else { ?>
                                                <label for="Eve_titreLong"><b>Titre long*</b></label> <br>
                                                <input type="text" name="libelleLong" id="Eve_titreLong" value="<?php if(!$insertion){saisieFormString("libelleLong");}?>" class="libelleLong" maxlength=32 />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            if (!empty($erreurLibelleCourt)) { ?>
                                                <label style="color: #b94947;" for="Eve_titreCourt"><b>Titre court*</b></label> <br>
                                                <input style="border: 1px solid #b94947;" type="text" name="libelleCourt" id="Eve_titreCourt" value="<?php if(!$insertion){saisieFormString("libelleCourt");}?>" class="libelleCourt" maxlength=10 />
                                                <?php echo "<b style=\"color: #b94947;\" id=\"formErreur\">$erreurLibelleCourt </b>";
                                            }
                                            else { ?>
                                                <label for="Eve_titreCourt"><b>Titre court*</b></label> <br>
                                                <input type="text" name="libelleCourt" id="Eve_titreCourt" value="<?php if(!$insertion){saisieFormString("libelleCourt");}?>" class="libelleCourt" maxlength=10 />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            if ($erreurDescription) { ?>
                                                <label style="color: #b94947;" for="Eve_description"><b>Description*</b></label> <br>
                                                <textarea style="border: 1px solid #b94947;" name="description" rows="5" cols="30" id="Eve_description" class="area"><?php if(!$insertion){saisieFormString("description");}?></textarea>
                                                <?php echo "<b style=\"color: #b94947;\" id=\"formErreur\">$erreurDescription </b>";
                                            }
                                            else { ?>
                                                <label for="Eve_description"><b>Description*</b></label> <br>
                                                <textarea name="description" rows="5" cols="30" id="Eve_description" class="area"><?php if(!$insertion){saisieFormString("description");}?></textarea>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            if(!empty($erreurDateDebut) or !empty($erreurHeureDebut)) { ?>
                                                <label style="color: #b94947;" for="Eve_dateDebut"><b>Date de début*</b></label><br>
                                                <?php if (!empty($_GET['a']) and !empty($_GET['m']) and !empty($_GET['j'])) { ?>
                                                    <input style="border: 1px solid #b94947;" type="text" name="dateDebut" id="Eve_dateDebut" value="<?php if(!$insertion){echo $_GET['j'].'/'.$_GET['m'].'/'.$_GET['a']; }?>" class="dateDebut" maxlength=10 size=11/>
                                                <?php } else { ?>
                                                    <input style="border: 1px solid #b94947;" type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" value="<?php if(!$insertion){saisieFormString("dateDebut");}?>" class="dateDebut" maxlength=10 size=11/>
                                                <?php } ?>
                                                    <input style="border: 1px solid #b94947;" type="text" name="heureDebut" id="Eve_heureDebut" placeholder="hh:mm" value="<?php if(!$insertion){saisieFormString("heureDebut");}?>" class="heureDebut" maxlength=5 size=4/>
                                                <?php echo "<b style=\"color: #b94947;\" id=\"formErreur\"><br>$erreurDateDebut<br>$erreurHeureDebut</b>";
                                            }
                                            else { ?>
                                                <label for="Eve_dateDebut"><b>Date de début*</b></label><br>
                                                <?php if (!empty($_GET['a']) and !empty($_GET['m']) and !empty($_GET['j'])) { ?>
                                                    <input type="text" name="dateDebut" id="Eve_dateDebut" value="<?php if(!$insertion){echo $_GET['j'].'/'.$_GET['m'].'/'.$_GET['a'];} ?>" class="dateDebut" maxlength=10 size=11/>
                                                <?php } else { ?>
                                                    <input type="text" name="dateDebut" id="Eve_dateDebut" placeholder="JJ/MM/YYYY" value="<?php if(!$insertion){saisieFormString("dateDebut");}?>" class="dateDebut" maxlength=10 size=11/>
                                                <?php } ?>
                                                    <input type="text" name="heureDebut" id="Eve_heureDebut" placeholder="hh:mm" value="<?php if(!$insertion){saisieFormString("heureDebut");}?>" class="heureDebut" maxlength=5 size=4/>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>                    
                                        <td>
					<?php if (!empty($_GET['a']) and !empty($_GET['m']) and !empty($_GET['j'])) { ?>
                                                <label for="Eve_dateFin"><b>Date de fin</b></label><br>
                                                    <input type="text" name="dateFin" id="Eve_dateFin" value="<?php if(!$insertion){echo $_GET['j'].'/'.$_GET['m'].'/'.$_GET['a'];} ?>" class="dateDebut" maxlength=10 size=11/>
                                                    <input type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" value="<?php if(!$insertion){saisieFormString("heureFin");}?>" class="heureFin" maxlength=5 size=4/>
                                                <?php } else {
                                            if(!empty($erreurDateFin) or !empty($erreurHeureFin)) { ?>
                                                <label style="color: #b94947;" for="Eve_dateFin"><b>Date de fin*</b></label><br>
                                                <input style="border: 1px solid #b94947;" type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" value="<?php if(!$insertion){saisieFormString("dateFin");}?>"class="dateFin" maxlength=10 size=11/>
                                                <input style="border: 1px solid #b94947;" type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" value="<?php if(!$insertion){saisieFormString("heureFin");}?>" class="heureFin" maxlength=5 size=4/>
                                                <?php echo "<b id=\"formErreur\" style=\"color: #b94947;\"><br>$erreurDateFin<br>$erreurHeureFin</b>";
                                            }
                                            else { ?>
                                                <label for="Eve_dateFin"><b>Date de fin</b></label><br>
                                                <input type="text" name="dateFin" id="Eve_dateFin" placeholder="JJ/MM/YYYY" value="<?php if(!$insertion){saisieFormString("dateFin");}?>"class="dateFin" maxlength=10 size=11/>
                                                <input type="text" name="heureFin" id="Eve_heureFin" placeholder="hh:mm" value="<?php if(!$insertion){saisieFormString("heureFin");}?>" class="heureFin" maxlength=5 size=4/>
                                            <?php }} ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="Eve_lieu"><b>Lieu</b></label> <br>
                                            <input type="text" name="lieu" value="<?php if(!$insertion){saisieFormString("lieu");}?>" id="Eve_lieu" autocomplete="off" />
                                            <div id="resultsLieu"></div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label><b>Type*</b></label><br>
                                            <input type="radio" name="public" id="public" value="1" onclick="cacher()"> <label for="public" onclick="cacher()">Public</label>
                                            <input type="radio" name="public" id="prive" value="0" checked onclick="cacher()"> <label for="prive" onclick="cacher()">Privé</label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td valign="top">
                                <table cellpadding="4">
                                    <tr>
                                        <td id="tddest" valign="top">
                                            <label for="addParticipant"><b>Destinataires</b></label><br>
                                            <div id="dest">
                                                <?php if(!$insertion){saisieFormReq("dest", $conn);}?>
                                            </div>
                                            <label for="addParticipant"><b>Rechercher un destinataire</b></label><br><input type="text" name="addParticipant" id="addParticipant" class="boutonForm"/>
                                            <div id="resultsParticipant"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="tdgroupe">
                                            <label for="groupe"><b>Ajouter un groupe de participants</b></label><br>
                                            <div id="groupe">
                                                <?php
                                                $req = "SELECT idgroupe, libelle FROM aci_groupe WHERE idgroupe NOT IN (SELECT idgroupe_1 FROM aci_contenir)";
                                                $resultats = $conn -> query($req);
                                                while($row = $resultats->fetch()){
                                                    echo '<img id="'.utf8_encode($row['idgroupe']).'"src="../../Images/arborescencePlus.png" onclick="developper('.utf8_encode($row['idgroupe']).')"/>
                                                                                            <label for="'.utf8_encode($row['idgroupe']).'" onclick="developper('.utf8_encode($row['idgroupe']).')"> '
                                                                                            .$row['libelle'].'</label><input type="checkbox" name="groupe[]" value="'.utf8_encode($row['idgroupe']).'" 
                                                                                            id="'.utf8_encode($row['idgroupe']).'" ';
						    if(!$insertion){echo checkAuto(utf8_encode($row['idgroupe']));}
							echo '/><br/>';
                                                    descGroupe($row['idgroupe'], $conn, 1, $insertion);
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="btn" type="submit" name="submit" value="Valider" style="margin: 0;"/>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                 </form>
            </div>
        </div>
            
        <script type="text/javascript">
		cacher();
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
        </script>
    </body>
</html>

<?php
function creationRappel($conn, $idEvenement, $priorite, $idUtilisateur, $idRappel, $rappelHaute, $rappelMoyenne, $rappelBasse, $date)
{
	if($priorite == 1)
	{
		$rappel = explode(' ', $rappelHaute);
		
		insertionRappel($conn, $idEvenement, $idUtilisateur, $idRappel, $date, $rappel[0], $rappel[1], $rappel[2], $rappel[3], $rappel[4]);
	}
	elseif($priorite == 2)
	{
		$rappel = explode(' ', $rappelMoyenne);
		
		insertionRappel($conn, $idEvenement, $idUtilisateur, $idRappel, $date, $rappel[0], $rappel[1], $rappel[2], $rappel[3], $rappel[4]);
	}
	elseif($priorite == 3 && $rappelBasse != '00 00 00 00 00')
	{
		$rappel = explode(' ', $rappelBasse);
		
		insertionRappel($conn, $idEvenement, $idUtilisateur, $idRappel, $date, $rappel[0], $rappel[1], $rappel[2], $rappel[3], $rappel[4]);
	}
}

function insertionRappel($conn, $idEvenement, $idUtilisateur, $idRappel, $date, $jour, $mois, $annee, $heure, $minute)
{
	//Insertion du rappel dans la base
	$sqlRappel = "INSERT INTO aci_rappel VALUES($idRappel, $idEvenement, $idUtilisateur, str_to_date('$date', '%d/%m/%Y %H:%i')";

	if(!empty($jour))
		$sqlRappel .= " - INTERVAL $jour DAY";
	if(!empty($mois))
		$sqlRappel .= " - INTERVAL $mois MONTH";
	if(!empty($annee))
		$sqlRappel .= " - INTERVAL $annee YEAR";
	if(!empty($heure))
		$sqlRappel .= " - INTERVAL $heure HOUR";
	if(!empty($minute))
		$sqlRappel .= " - INTERVAL $minute MINUTE";

	$sqlRappel .= ")";
	
	$exec = $conn->query($sqlRappel);
}
?>