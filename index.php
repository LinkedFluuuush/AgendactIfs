<?php
session_start();

if (!empty($_SESSION['annee']) && !empty($_SESSION['mois']) && !empty($_SESSION['jour'])) {
    $annee = $_SESSION['annee'];
    $mois = $_SESSION['mois'];
    $jour = $_SESSION['jour'];
}
else {
    $annee = date('Y');
    $mois = date('m');
    $jour = date('d');
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
        <title>Agendact'Ifs</title>
    </head>

    <body>
        <script>
            // Redirection de la page d'index vers la page mois
            <?php echo('document.location.href =\'Pages/Calendrier/mois.php?a='.$annee.'&m='.$mois.'\''); ?>
        </script>
    </body>
</html>

