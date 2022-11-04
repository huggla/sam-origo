<?php
	function printExportJsonButton()
	{
		GLOBAL $mapId;
		echo <<<HERE
			<div class="exportButtonDiv">
				<form action="writeConfig.php" method="get" target="hiddenFrame">
					<input type="hidden" name="getJson" value="y">
					<button class="exportButton" type="submit" name="map" value="{$mapId}">
						Exportera JSON
					</button>
				</form>
			</div>
		HERE;
	}
?>
