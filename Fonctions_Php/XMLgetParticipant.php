<?php
include("./connexion.php");

$str = "";

$sql="SELECT concat(nom, ' ', prenom) as 'nom' FROM aci_utilisateur WHERE upper(nom) like '".strtoupper($_POST['valeur'])."%';";
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
    $str .= utf8_encode($row['nom']).'|';

$str = substr($str, 0, -1);
echo($str);
?>
