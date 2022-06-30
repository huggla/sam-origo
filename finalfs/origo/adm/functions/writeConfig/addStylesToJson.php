<?php

	function addStylesToJson()
	{
		GLOBAL $mapStyles, $styles, $json, $mapSources;
		$json = $json.'"styles": {';
		$firstStyle = true;
		foreach ($mapStyles as $style)
		{
			if ($firstStyle)
			{
				$firstStyle = false;
			}
			else
			{
				$json = $json.', ';
			}
			$json = $json.'"'.$style['layer_id'].'": ';
			if (!empty($style['style_config']) && $style['style_config'] != '[]' && $style['style_config'] != '{}' && $style['style_config'] != '""' && $style['style_config'] != 'null')
			{
				$json = $json.$style['style_config'];
			}
			else
			{
				$json = $json.'[ [ {';
				if (!empty($style['label']))
				{
					$json = $json.'"label": "'.$style['label'].'", ';
				}
				if (!empty($style['icon']))
				{
					if (substr($style['layer_id'], -strlen('-bg'))==='-bg' || (empty($style['icon_extended']) && $style['type'] != 'WFS'))
					{
						$json = $json.'"image": { "src": "'.$style['icon'].'" }';
					}
					else
					{
						$json = $json.'"icon": { "src": "'.$style['icon'].'" }';
					}
				}
				if (!empty($style['style_filter']))
				{
					$json = $json.', "filter": "'.$style['style_filter'].'"';
				}
				$json = $json.' }';
				if (!empty($style['icon_extended']))
				{
					$json = $json.',{ "icon": { "src": "'.$style['icon_extended'].'" }, "extendedLegend": true }';
				}
 				$json = $json.']]';
			}
		}
		$json = $json.' }';
	}

?>
