<?php

	function infoButton($type)
	{
		$idVarStr=$type.'Id';
		echo   '<form></form>';
		echo   '<form action="info.php" method="get" target="topFrame">';
		echo     '<input type="hidden" name="type" value="'.$type.'">';
		echo     "<button class=\"updateButton\" onclick=\"toggleTopFrame('info');\" type=\"submit\" name=\"id\" value=\"".$GLOBALS[$idVarStr].'">';
		echo       'Info';
		echo     '</button>';
		echo   '</form>';
	}

?>
