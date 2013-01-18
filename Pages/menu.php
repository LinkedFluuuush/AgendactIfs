<script type="text/javascript" src="../../Fonctions_Javascript/miniCalendrier.js"></script>
<div id="nav">
    <!-- sous-menus -->
    <ul class="nav">
        <li>
            <div class="header">Connexion</div>
            <ul class="menu">
                <?php if(empty($_SESSION['id'])){ ?>
		<form name="connexion" action="../../Fonctions_Php/connexionLDAP.php" method="POST">
                    <input type="text" name="login" placeholder="Identifiant"><br>
                    <input type="password" name="mdp" placeholder="Mot de passe">
                    <input type="submit" name="valider_conn" value="Valider">
                </form>
		<?php }else{
		    echo "<li>".$_SESSION['prenom']." ".$_SESSION['nom']."</li>";
		    echo "<li onclick =\"document.location.href ='../../Fonctions_Php/deconnexion.php'\"><a href=\"../../Fonctions_Php/deconnexion.php\">Déconnexion</a></li>";
		} ?>
            </ul>
        </li>
        <li>
            <div class="header">Evénement</div>
            <ul class="menu">
                <li onclick ="document.location.href ='..\\Evenement\\creer.php'"><a href="..\Evenement\creer.php">Créer</a></li>
                <li onclick ="document.location.href ='#'"><a href="#">Gérer</a></li>
                <li onclick ="document.location.href ='#'"><a href="#">Rechercher</a></li>
            </ul>
        </li>
        <form>
            <select name="changement_priorite">
                <option value="1">Haute</option>
                <option value="2">Moyennne</option>
                <option value="3">Basse</option>
            </select>
            <input type="submit" name="valider" value="Valider">
        </form>
    </ul><br>
    
    <!-- mini-calendrier -->
     <div id="miniCalendrier">
        <script type="text/javascript">miniCalendrier(<?php echo "$annee, $mois-1"; ?>);</script>
     </div>
    <br>
    <ul class="nav">
        <li>
            <div class="header">Vue</div>
            <ul class="menu">
                <li onclick ="document.location.href ='..\\Calendrier\\semestre.php'"><a href="..\Calendrier\semestre.php">Semestre</a></li>
                <li onclick ="document.location.href ='..\\Calendrier\\mois.php'"><a href="..\Calendrier\mois.php">Mois</a></li>
                <li onclick ="document.location.href ='..\\Calendrier\\semaine.php'"><a href="..\Calendrier\semaine.php">Semaine</a></li>
                <li onclick ="document.location.href ='..\\Calendrier\\jour.php'"><a href="..\Calendrier\jour.php">Jour</a></li>
            </ul>
        </li>
     </ul>
</div>


