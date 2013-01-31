<script type="text/javascript" src="../../Fonctions_Javascript/miniCalendrier.js"></script>
<div id="nav">
    <!-- sous-menus -->
    <ul class="nav">
        <li>
            <div class="header">Connexion</div>
            <ul class="menu">
                <form name="connexion">
                    <input type="text" name="id" placeholder="Identifiant"><br>
                    <input type="password" name="motdepasse" placeholder="Mot de passe">
                </form>
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
		<?php 
		$temp = explode("/", $_SERVER['PHP_SELF']);
		$nomPage = $temp[sizeof($temp)-1];
		
		if($nomPage == "semestre.php" || $nomPage == "mois.php" ||$nomPage == "semaine.php" ||$nomPage == "jour.php")
			include("priorite.php"); ?>
    </ul>
	
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


