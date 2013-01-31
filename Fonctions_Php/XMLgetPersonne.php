<?php
include("./connexion.php");

$str = "";

$sql="SELECT CONCAT(nom,' ', prenom,' ', adresse_mail) AS info FROM aci_utilisateur WHERE CONCAT(nom,' ', prenom) like '".strtoupper($_POST['valeur'])."%';";
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
    $str .= utf8_encode($row['info']).'|';

$str = substr($str, 0, -1);
echo($str);
?>