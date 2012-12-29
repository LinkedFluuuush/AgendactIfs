<script type="text/javascript" src="../../Fonctions_Javascript/miniCalendrier.js"></script>
<div id="nav">
    <!-- sous-menus -->
    <ul class="nav">
         <li>
             <div class="header">Evénement</div>
             <ul class="menu">
                 <li onclick ="document.location.href ='../Evenement/creer.php'"><a href="../Evenement/creer.php">Ajouter</a></li>
                 <li onclick ="document.location.href ='#'"><a href="#">Rechercher</a></li>
             </ul>
         </li>
         <li>
             <div class="header">Vue</div>
             <ul class="menu">
                 <li onclick ="document.location.href ='semestre.php'"><a href="semestre.php">Semestre</a></li>
                 <li onclick ="document.location.href ='mois.php'"><a href="mois.php">Mois</a></li>
                 <li onclick ="document.location.href ='semaine.php'"><a href="semaine.php">Semaine</a></li>
                 <li onclick ="document.location.href ='jour.php'"><a href="jour.php">Jour</a></li>
             </ul>
         </li>
     </ul>
    <!-- mini-calendrier -->
     <div id="miniCalendrier">
        <script type="text/javascript">miniCalendrier(<?php echo "$annee, $mois-1"; ?>);</script>
     </div>
</div>


