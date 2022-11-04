<?php
	function printLayerForm($targetId)
	{
		GLOBAL $hiddenInputs, $sources;
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, $target.'_id', $targetTableArr);
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($targetId, 'id', 'textareaMedium');
		printTextarea($targetId, 'title', 'textareaMedium', 'Titel:');
		printSourceList($targetId, 'title', 'textareaMedium', 'Titel:');
		printUpdateSelect($targetId, 'type', 'miniSelect', 'Typ:', array("", "WMS", "WFS", "OSM", "GEOJSON", "GROUP", "WMTS"));
		if ($targetArr['type'] == 'WFS')
		{
			printUpdateSelect($targetId, 'editable', 'miniSelect', 'Redigerbar:', array("", "f", "t"));
			if ($targetArr['editable'] == "t")
			{
				printTextarea($targetId, 'allowededitoperations', 'textareaMedium', 'Redigeringsalt.:');
				echo '<br>';
			}
		}
		elseif ($targetArr['type'] == 'WMS')
		{
			printUpdateSelect($targetId, 'tiled', 'miniSelect', 'Tiled:', array("", "f", "t"));
		}
		printUpdateSelect($targetId, 'queryable', 'miniSelect', 'Klickbar:', array("", "f", "t"));
		printUpdateSelect($targetId, 'visible', 'miniSelect', 'Synlig:', array("", "f", "t"));
		printTextarea($targetId, 'opacity', 'textareaSmall', 'Opacitet:');
		echo '<br>';
		$layerService=array_column_search($targetArr['source'], 'source_id', $sources)['service'];
		if ($layerService == 'restricted')
		{
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'>&nbsp;";
			printTextarea($targetId, 'adusers', 'textareaLarge', 'Användare:');
			printTextarea($targetId, 'adgroups', 'textareaLarge', 'Grupper:');
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'>";
			echo '<br>';
		}
		printTextarea($targetId, 'icon', 'textareaLarge', 'Ikon:');
		printTextarea($targetId, 'icon_extended', 'textareaLarge', 'Utfälld ikon:');
		echo '<br>';
		if ($targetArr['type'] == 'WMS')
		{
			printTextarea($targetId, 'format', 'textareaMedium');
			printTextarea($targetId, 'featureinfolayer', 'textareaMedium', 'FeatureInfo-lager:');
		}
		printTextarea($targetId, 'stylefilter', 'textareaLarge', 'Stilfilter:');
		echo '<br>';
		printTextarea($targetId, 'style_config', 'textareaLarge', 'Stilkonfiguration:');
		printTextarea($targetId, 'attributes', 'textareaLarge', 'Attribut:');
		echo '<br>';
		printTextarea($targetId, 'abstract', 'textareaLarge', 'Sammanfattning:');
		printTextarea($targetId, 'info', 'textareaLarge');
		echo '<br>';
		printTextarea($targetId, 'categories', 'textareaLarge', 'Kategorier:');
		printTextarea($targetId, 'attribution', 'textareaLarge', 'Tillskrivning:');
		echo '<br>';
		printTextarea($targetId, 'maxscale', 'textareaSmall', 'Maxskala:');
		printTextarea($targetId, 'minscale', 'textareaSmall', 'Minskala:');
		printUpdateSelect($targetId, 'swiper', 'miniSelect', 'Swiper-lager:', array("", "f", "t", "under"));
		if ($targetArr['type'] == 'GROUP')
		{
			printTextarea($targetId, 'layers', 'textareaMedium', 'Lager:');
		}
		elseif ($targetArr['type'] == 'WFS')
		{
			printTextarea($targetId, 'layertype', 'textareaMedium', 'WFS-typ:');
			if ($targetArr['layertype'] == 'cluster')
			{
				printTextarea($targetId, 'clusterstyle', 'textareaLarge', 'Klusterstil:');
				echo '<br>';
				printTextarea($targetId, 'clusteroptions', 'textareaLarge', 'Klusteralternativ:');
			}
		}
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($targetId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera lagret $targetId? Referenser till lagret hanteras separat.";
		printDeleteButton($targetId, $deleteConfirmStr, 'deleteButton6');
		echo '</div>';
		printAddOperation('maps', $targetId, 'Lägg till i karta');
		printRemoveOperation('maps', $targetId, 'Ta bort från karta');
		printAddOperation('groups', $targetId, 'Lägg till i grupp');
		printRemoveOperation('groups', $targetId, 'Ta bort från grupp');
	}
?>
