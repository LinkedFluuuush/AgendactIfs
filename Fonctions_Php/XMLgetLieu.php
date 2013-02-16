<?php
include("./connexion.php");

$str = "";

$sql="SELECT libelle FROM aci_lieu WHERE upper(libelle) like '".strtoupper($_POST['valeur'])."%' ORDER BY libelle LIMIT 10;";
$resultats = $conn->query($sql);
while($row = $resultats->fetch())
    $str .= utf8_encode($row['libelle']).'|';

$str = substr($str, 0, -1);
echo($str);
?>
