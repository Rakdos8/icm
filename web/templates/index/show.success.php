<form method="POST" action="index.php?action=magic">
<?php
	foreach ($valeurs['characters'] as $character) {
?>
	<input type="radio" name="characterID" value="<?php echo $character['characterID']; ?>"><?php echo $character['characterName']; ?>
<?php
	}
?>
	<br>
	<input type="submit" value="!! Show me DAT Magic NOW !!">
</form>
