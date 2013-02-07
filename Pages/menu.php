<script type="text/javascript" src="/AgendactIfs/Fonctions_Javascript/miniCalendrier.js"></script>
<link href="../../bootstrap.css" rel="stylesheet" type="text/css">

<?php
$temp = explode("/", $_SERVER['PHP_SELF']);
$nomPage = $temp[sizeof($temp)-1];
?>
<div id="nav">
    <!-- sous-menus -->
    <ul class="nav">
        <li>
            <div class="header">Connexion</div>
            <ul class="menu">
                    <?php if(empty($_SESSION['id'])){ 
                        if(isset($_GET['login']) && $_GET['login'] == 0){
                            echo "<span style=\"color:red\">Connexion echouée.</span>";
                        } ?>
                        <li class="connexion">
                            <form name="connexion" action="../../Fonctions_Php/connexionLDAP.php" method="POST">
                                <input type="text" name="login" placeholder="Identifiant"><br>
                                <input type="password" name="mdp" placeholder="Mot de passe">
                                <input class="btn" type="submit" name="valider_conn" value="Valider">
                            </form>
                        </li>
                    <?php }
                    else {
                        echo "<li onclick =\"document.location.href ='../../Fonctions_Php/deconnexion.php'\"><a href=\"../../Fonctions_Php/deconnexion.php\">".$_SESSION['prenom']." ".ucfirst(strtolower($_SESSION['nom']))." - Déconnexion</a></li>";
                    } ?>
            </ul>
        </li>
        <li>
                <div class="header">Evénement</div>
                <ul class="menu">
                    <?php
                     if(!empty($_SESSION['id'])) {
                     if ($nomPage == "creer.php") {
                         echo '<li class="selected">Créer</li>';
                     }
                     else {
                         echo '<li onclick ="document.location.href =\'../Evenement/creer.php\'"><a href="../Evenement/creer.php">Créer</a></li>';
                     }
                    ?>
                    <!--<li onclick ="document.location.href ='#'"><a href="#">Gérer</a></li>
                    <li onclick ="document.location.href ='#'"><a href="#">Rechercher</a></li>-->

                    <?php } ?>
                    <?php
                    if($nomPage == "semestre.php" or $nomPage == "mois.php" or $nomPage == "semaine.php" or $nomPage == "jour.php") {
                        echo '<li class="priorite">';
                        include("priorite.php");
                        echo '</li>';

                    } ?>
                </ul>
        </li>
        <li>
            <div class="header">Vue</div>
            <ul class="menu">
                <?php
                 if ($nomPage == "semestre.php") {
                     echo '<li class="selected">Semestre</li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/semestre.php\'"><a href="../Calendrier/semestre.php">Semestre</a></li>';
                 }
                 
                 if ($nomPage == "mois.php") {
                     echo '<li class="selected">Mois</li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/mois.php\'"><a href="../Calendrier/mois.php">Mois</a></li>';
                 }
                 
                 if ($nomPage == "semaine.php") {
                     echo '<li class="selected">Semaine</li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/semaine.php\'"><a href="../Calendrier/semaine.php">Semaine</a></li>';
                 }
                 
                 if ($nomPage == "jour.php") {
                     echo '<li class="selected">Jour</li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/jour.php\'"><a href="../Calendrier/jour.php">Jour</a></li>';
                 }
                ?>
            </ul>
        </li>
    </ul>
    
    <?php
    if ($nomPage == "mois.php" or $nomPage == "semaine.php" or $nomPage == "jour.php") { ?>
        <!-- mini-calendrier -->
        <div id="miniCalendrier">
           <script type="text/javascript">miniCalendrier(<?php echo "$annee, $mois-1"; ?>);</script>
        </div>
    <?php } ?>    
</div>
