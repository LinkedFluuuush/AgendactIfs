<script type="text/javascript" src="../../Fonctions_Javascript/miniCalendrier.js"></script>
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
                <?php if(empty($_SESSION['id'])){ ?>
                    <li class="connexion">
                        <?php
                        if(isset($_GET['login']) && $_GET['login'] == 0){
                            echo '<div class="alert alert-error" style="padding:2px;margin-bottom:8px;"><b>Connexion échouée.</b></div>';
                        } ?>
                        <form name="connexion" action="../../Fonctions_Php/connexionLDAP.php" method="POST">
                            <input class="zoneDeSaisie" type="text" name="login" placeholder="Identifiant"><br>
                            <input class="zoneDeSaisie" type="password" name="mdp" placeholder="Mot de passe">
                            <input type="submit" class="btn" style="padding: 2px 8px;" name="valider_conn" value="Valider">
                        </form>
                    </li>
                <?php }
                else {
                    if($nomPage == "parametresCompte.php") {
                        echo "<li onclick =\"document.location.href ='../Fonctions_Php/deconnexion.php'\"><a href=\"../Fonctions_Php/deconnexion.php\">".$_SESSION['prenom']." ".ucfirst(strtolower($_SESSION['nom']))." - Déconnexion</a></li>";
                    }
                    else {
                        echo "<li onclick =\"document.location.href ='../../Fonctions_Php/deconnexion.php'\"><a href=\"../../Fonctions_Php/deconnexion.php\">".$_SESSION['prenom']." ".ucfirst(strtolower($_SESSION['nom']))." - Déconnexion</a></li>";
                    }
                    
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
                    else if ($nomPage == "parametresCompte.php") {
                        echo '<li onclick ="document.location.href =\'Evenement/creer.php\'"><a href="../Evenement/creer.php">Créer</a></li>';
                    }
                    else {
                        echo '<li onclick ="document.location.href =\'../Evenement/creer.php\'"><a href="../Evenement/creer.php">Créer</a></li>';
                    }
                }
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
                 else if ($nomPage == "parametresCompte.php") {
                     echo '<li onclick ="document.location.href =\'Calendrier/semestre.php\'"><a href="../Calendrier/semestre.php">Semestre</a></li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/semestre.php\'"><a href="../Calendrier/semestre.php">Semestre</a></li>';
                 }
                 //-------------------------------------------------------------------------------------------
                 if ($nomPage == "mois.php") {
                     echo '<li class="selected">Mois</li>';
                 }
                 else if ($nomPage == "parametresCompte.php") {
                     echo '<li onclick ="document.location.href =\'Calendrier/mois.php\'"><a href="../Calendrier/mois.php">Mois</a></li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/mois.php\'"><a href="../Calendrier/mois.php">Mois</a></li>';
                 }
                 //-------------------------------------------------------------------------------------------
                 if ($nomPage == "semaine.php") {
                     echo '<li class="selected">Semaine</li>';
                 }
                 else if ($nomPage == "parametresCompte.php") {
                     echo '<li onclick ="document.location.href =\'Calendrier/semaine.php\'"><a href="../Calendrier/semaine.php">Semaine</a></li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/semaine.php\'"><a href="../Calendrier/semaine.php">Semaine</a></li>';
                 }
                 //--------------------------------------------------------------------------------------------
                 if ($nomPage == "jour.php") {
                     echo '<li class="selected">Jour</li>';
                 }
                 else if ($nomPage == "parametresCompte.php") {
                     echo '<li onclick ="document.location.href =\'Calendrier/jour.php\'"><a href="../Calendrier/jour.php">Jour</a></li>';
                 }
                 else {
                     echo '<li onclick ="document.location.href =\'../Calendrier/jour.php\'"><a href="../Calendrier/jour.php">Jour</a></li>';
                 }
                ?>
            </ul>
        </li>
        <li>
            <?php if(!empty($_SESSION['id'])) { ?>
                <div class="header">Rappels/notifs</div>
                <ul class="menu">
                    <?php  if($nomPage == "parametresCompte.php") {
                        echo '<li class="selected">Paramètres</li>';
                     }
                     else {
                         echo '<li onclick ="document.location.href =\'../parametresCompte.php\'"><a href="../parametresCompte.php">Paramètres</a></li>';
                     } ?>
                </ul>
           <?php } ?>
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
