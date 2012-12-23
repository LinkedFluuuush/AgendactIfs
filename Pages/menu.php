<div id="nav"><!--
    --><script type="text/javascript" src="../../Fonctions_Javascript/miniCalendrier.js"></script>
    <div class="titreCal">Menu</div>
    <div class="menu">
        <table>
            <tr>
                <td><a href="#">Ajouter</a></td>
            </tr>
            <tr>
                <td><a href="#">Rechercher</a></td>
            </tr>
        </table>
    </div>
    <br>
    
    <div class="titreCal">Mini calendrier</div>
    <div class="menu" id="Calendrier">
	<script type="text/javascript">miniCalendrier(<?php echo "$annee, $mois-1"; ?>);</script>
    </div>
    <br>
    
    <div class="titreCal">Vue</div>
    <div class="menu">
        <table>
            <tr>
                <td onclick ="document.location.href ='semestre.php'"><a href="semestre.php">Semestre</a></td>
            </tr>
            <tr>
                <td onclick ="document.location.href ='mois.php'"><a href="mois.php">Mois</a></td>
            </tr>
            <tr>
                <td onclick ="document.location.href ='semaine.php'"><a href="semaine.php">Semaine</a></td>
            </tr>
            <tr>
                <td onclick ="document.location.href ='jour.php'"><a href="jour.php">Jour</a></td>
            </tr>
        </table>
    </div><!--
--></div>