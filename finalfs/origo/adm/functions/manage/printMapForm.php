<?php
	function printMapForm($mapId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($mapId, 'id', 'textareaMedium');
		printTextarea($mapId, 'layers', 'textareaLarge', 'Lager:');
		printTextarea($mapId, 'groups', 'textareaLarge', 'Grupper:');
		echo 			'<br>';
		printUpdateSelect($mapId, 'footers', 'bodySelect', 'Sidfot:');
		printTextarea($mapId, 'controls', 'textareaLarge', 'Kontroller:');
		printTextarea($mapId, 'proj4defs', 'textareaMedium');
		printTextarea($mapId, 'featureinfooptions', 'textareaMedium', 'FeatureInfoOptions:');
		echo 			'<br>';
		printTextarea($mapId, 'center', 'textareaMedium', 'Mittpunkt:');
		printTextarea($mapId, 'zoom', 'textareaXSmall');
		printUpdateSelect($mapId, 'tilegrids', 'bodySelect', 'Tilegrid:');
		printTextarea($mapId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($mapId);
		printWriteConfigButton();
		echo 			'</div>';
		printExportJsonButton();
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker på att du vill radera kartan $mapId? Ingående kontroller, grupper och lager påverkas ej.";
		printDeleteButton($mapId, $deleteConfirmStr, 'deleteButton3b');
		echo '</div>';
	}
?>
