<?php
	function printInfoButton($targetId)
	{
		$target=$GLOBALS['target'];
		echo <<<HERE
			<form></form>
			<form action="info.php" method="get" target="topFrame">
				<input type="hidden" name="type" value="{$target}">
				<button class="updateButton" onclick="toggleTopFrame('info');" type="submit" name="id" value="{$targetId}">
					Info
				</button>
			</form>
		HERE;
	}
?>
