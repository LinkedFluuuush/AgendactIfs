<?php
include("./connexion.php");

//Récupération groupe
$sql = "SELECT idgroupe_1, libelle FROM aci_bdd.aci_contenir JOIN aci_groupe ON (aci_groupe.idgroupe = aci_contenir.idgroupe_1) where aci_contenir.idgroupe = ".$_POST['valeur'].";";
		
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
	echo '<option value="'.utf8_encode($row['idgroupe_1']).'"> '.utf8_encode($row['libelle']).'</option>';
?>