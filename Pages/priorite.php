<!-- formulaire avec choix des priorités -->
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="priorite">
    <input type="radio" name="priorite" value="1" id="1" <?php if($priorite == 1) echo "checked";?> onclick="document.forms['priorite'].submit();"><label for="1"> Haute</label><br>
    <input type="radio" name="priorite" value="2" id="2" <?php if($priorite == 2) echo "checked";?> onclick="document.forms['priorite'].submit();"><label for="2"> Moyenne</label><br>
    <input type="radio" name="priorite" value="3" id="3" <?php if(empty($priorite) || $priorite == 3) echo "checked";?> onclick="document.forms['priorite'].submit();"><label for="3"> Tout</label>
</form>