
<?php
header('Content-Type : text/xml');
//R�cup�ration lieu
$sql = "SELECT libelle FROM aci_bdd.aci_contenir JOIN aci_groupe ON (aci_groupe.idgroupe = aci_contenir.idgroupe_1) where aci_contenir.idgroupe = $_POST['valeur'];";
		
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
	echo '<option value="'.utf8_encode($row['libelle']).'"> '.utf8_encode($row['libelle']).'</option>';
?>