<?php

	function multiselectButton($type)
	{
		echo '<form action="multiselect.php" method="get" target="topFrame">';
		echo   "<button onclick=\"toggleTopFrame('$type');\" type=\"submit\" name=\"type\" value=\"$type\">";
		echo     'Flervalsverktyg';
		echo   '</button>';
		echo '</form>';
	}

?>
