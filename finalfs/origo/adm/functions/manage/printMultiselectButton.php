<?php
	function printMultiselectButton($table)
	{
		echo <<<HERE
			<form action="multiselect.php" method="get" target="topFrame">
				<button onclick="toggleTopFrame('{$table}');" type="submit" name="table" value="{$table}">
					Flervalsverktyg
				</button>
			</form>
		HERE;
	}
?>
