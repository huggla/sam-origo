<?php
	function printLayerForm($layer, $operationTables, $sources, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($layer, 'layer_id', 'textareaMedium', 'Id:');
		printTextarea($layer, 'title', 'textareaMedium', 'Titel:');
		printSourceList($layer, $sources);
		printUpdateSelect($layer, array('type'=>array("WMS", "WFS", "OSM", "GEOJSON", "GROUP", "WMTS")), 'miniSelect', 'Typ:');
		if (isset($layer['layer']['type']))
		{
			if ($layer['layer']['type'] == 'WFS')
			{
				printUpdateSelect($layer, array('editable'=>array("f", "t")), 'miniSelect', 'Redigerbar:');
				if (current($layer)['editable'] == "t")
				{
					printTextarea($layer, 'allowededitoperations', 'textareaMedium', 'Redigeringsalt.:');
					echo '<br>';
				}
			}
			elseif ($layer['layer']['type'] == 'WMS')
			{
				printUpdateSelect($layer, array('tiled'=>array("f", "t")), 'miniSelect', 'Tiled:');
			}
		}
		printUpdateSelect($layer, array('queryable'=>array("f", "t")), 'miniSelect', 'Klickbar:');
		printUpdateSelect($layer, array('visible'=>array("f", "t")), 'miniSelect', 'Synlig:');
		printTextarea($layer, 'opacity', 'textareaSmall', 'Opacitet:');
		echo '<br>';
		if (isset($layer['layer']['service']) && $layer['layer']['service'] == 'restricted')
		{
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'>&nbsp;";
			printTextarea($layer, 'adusers', 'textareaLarge', 'Användare:');
			printTextarea($layer, 'adgroups', 'textareaLarge', 'Grupper:');
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'><br>";
		}
		printTextarea($layer, 'icon', 'textareaLarge', 'Ikon:');
		printTextarea($layer, 'icon_extended', 'textareaLarge', 'Utfälld ikon:');
		echo '<br>';
		if (isset($layer['layer']['type']) && $layer['layer']['type'] == 'WMS')
		{
			printTextarea($layer, 'format', 'textareaMedium', 'Format:');
			printTextarea($layer, 'featureinfolayer', 'textareaMedium', 'FeatureInfo-lager:');
		}
		printTextarea($layer, 'style_filter', 'textareaLarge', 'Stilfilter:');
		echo '<br>';
		printTextarea($layer, 'style_config', 'textareaLarge', 'Stilkonfiguration:');
		printTextarea($layer, 'attributes', 'textareaLarge', 'Attribut:');
		echo '<br>';
		printTextarea($layer, 'abstract', 'textareaLarge', 'Sammanfattning:');
		printTextarea($layer, 'info', 'textareaLarge', 'Info:');
		echo '<br>';
		printTextarea($layer, 'categories', 'textareaLarge', 'Kategorier:');
		printTextarea($layer, 'attribution', 'textareaLarge', 'Tillskrivning:');
		echo '<br>';
		printTextarea($layer, 'maxscale', 'textareaSmall', 'Maxskala:');
		printTextarea($layer, 'minscale', 'textareaSmall', 'Minskala:');
		printUpdateSelect($layer, array('swiper'=>array("f", "t", "under")), 'miniSelect', 'Swiper-lager:');
		if (isset($layer['layer']['type']))
		{
			if ($layer['layer']['type'] == 'GROUP')
			{
				printTextarea($layer, 'layers', 'textareaMedium', 'Lager:');
			}
			elseif ($layer['layer']['type'] == 'WFS')
			{
				printTextarea($layer, 'layertype', 'textareaMedium', 'WFS-typ:');
				if (isset($layer['layer']['layertype']) && $layer['layer']['layertype'] == 'cluster')
				{
					printTextarea($layer, 'clusterstyle', 'textareaLarge', 'Klusterstil:');
					echo '<br>';
					printTextarea($layer, 'clusteroptions', 'textareaLarge', 'Klusteralternativ:');
				}
			}
		}
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('layer');
		$layer['layer']=$layer['layer']['layer_id'];
		printInfoButton($layer);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera lagret ".$layer['layer']."? Referenser till lagret hanteras separat.";
		printDeleteButton($layer, $deleteConfirmStr, 'deleteButton6');
		echo '</div><div style="clear:both">';
		printAddOperation($layer, array('maps'=>array_column($operationTables['maps'], 'map_id')), 'Lägg till i karta', $inheritPosts);
		printRemoveOperation($layer, array('maps'=>$operationTables['maps']), 'Ta bort från karta', $inheritPosts);
		printAddOperation($layer, array('groups'=>array_column($operationTables['groups'], 'group_id')), 'Lägg till i grupp', $inheritPosts);
		printRemoveOperation($layer, array('groups'=>$operationTables['groups']),'Ta bort från grupp', $inheritPosts);
		echo '</div>';
	}
?>
