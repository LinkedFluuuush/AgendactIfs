<?php
include("./connexion.php");

$str = "";

$sql="SELECT libelle FROM aci_lieu WHERE upper(libelle) like '".strtoupper($_POST['valeur'])."%';";
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
    $str .= utf8_encode($row['libelle']).'|';

$str = substr($str, 0, -1);
echo($str);
?>
