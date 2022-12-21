<?php
	function printInfoButton($target)
	{
		$type=key($target);
		$id=current($target);
		echo <<<HERE
			<form></form>
			<form action="info.php" method="get" target="topFrame">
				<input type="hidden" name="type" value="{$type}">
				<button class="updateButton" onclick="toggleTopFrame('info');" type="submit" name="id" value="{$id}">
					Info
				</button>
			</form>
		HERE;
	}
?>
