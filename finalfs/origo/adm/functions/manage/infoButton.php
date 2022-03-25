<?php

	function infoButton($type)
	{
		GLOBAL $mapId, $groupId, $controlId, $layerId, $sourceId, $serviceId, $footerId, $tilegridId;
		$evalStr="\$id=\$".$type."Id;";
		eval($evalStr);
		echo   '<form></form>';
		echo   '<form action="info.php" method="get" target="topFrame">';
		echo     '<input type="hidden" name="type" value="'.$type.'">';
		echo     "<button class=\"updateButton\" onclick=\"toggleTopFrame('info');\" type=\"submit\" name=\"id\" value=\"$id\">";
		echo       'Info';
		echo     '</button>';
		echo   '</form>';
	}

?>
