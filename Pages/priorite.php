<li>
	<div class="header">Définir évènements visibles</div>
	<ul class="menu"><li>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<select name="priorite">
			<option value="1" <?php if($priorite == 1) echo "selected";?>>Haute</option>
			<option value="2" <?php if($priorite == 2) echo "selected";?>>Moyenne</option>
			<option value="3" <?php if(empty($priorite) || $priorite == 3) echo "selected";?>>Tout</option>
		</select>
		<input type="submit" name="valider" value="Sélectionner">
	</form>
	</li></ul>
</li>