    <!-- Le menu Superieur -->
    <div id="menuSuperieur">
		<div id="surMenu">
			<table cellpadding="0" cellspacing="0">
     			<colgroup>
       				<col width="1*">
        			<col width="1*">
        			<col width="1*">

     			</colgroup>
    			<tr>
      				<th id="calendrier" class="selected" onclick="clickCalendrier();">Calendrier</th>
      				<th id="gestion" class="deselected" onclick="clickGestion();getEveRecherche();">&Eacute;v&eacute;nement</th>
					<th id="connexion" class="deselected" onclick="clickConnexion();getConnexion();">Connexion</th>
    			</tr>
			</table>
		</div>
		
		<div id="sousMenu">

		</div>
		
		<form name="formSubRecherche" id="formSubRecherche" action="" method="post" enctype="multipart/form-data">
		</form>
    </div>
