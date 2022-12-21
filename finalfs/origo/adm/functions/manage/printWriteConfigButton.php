<?php
	function printWriteConfigButton($mapId)
	{
		$confirmStr="Är du säker att du vill skriva över den befintliga konfigurationen för $mapId?";
		echo <<<HERE
			<form onsubmit='confirmStr="{$confirmStr}"; return confirm(confirmStr);' action="writeConfig.php" method="get" target="hiddenFrame">
				<button class="updateButton" type="submit" name="map" value="{$mapId}">
					Skriv kartkonfiguration
				</button>
			</form>
		HERE;
	}
?>
