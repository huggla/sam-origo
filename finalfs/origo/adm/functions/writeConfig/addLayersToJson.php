<?php

	function addLayersToJson()
	{
		GLOBAL $mapLayers, $layers, $json, $mapStyles, $mapSources, $sources, $services;
		$json = $json. '"layers": [ ';
		$firstLayer = true;
		$mapSources = array();
		$mapStyleLayers = array();
		$mapStyles = array();
		foreach ($mapLayers as $group => $groupLayers)
		{
			foreach ($groupLayers as $layer)
			{
				if ($firstLayer)
				{
					$firstLayer = false;
				}
				else
				{
					$json = $json.', ';
				}
				$layer = array_column_search($layer, 'layer_id', $layers);
				$source = array_column_search($layer['source'], 'source_id', $sources);
				if ($layer['type'] == 'WFS')
				{
					$layer['source'] = $layer['source'].'@wfs';
				}
				$service = array_column_search($source['service'], 'service_id', $services);
				if ($layer['style_config'] == '[]' || $layer['style_config'] == '{}' || $layer['style_config'] == '""' || $layer['style_config'] == 'null')
				{
					$layer['style_config'] = '';
				}
				// Set default values <start>
				if (empty($layer['type']))
				{
					$layer['type'] = 'WMS';
				}
				if (empty($layer['style_layer']) && ($layer['type'] == 'WMS' || (!empty($layer['icon']) || !empty($layer['style_config']))))
				{
					$layer['style_layer'] = $layer['layer_id'];
				}
				if (empty($layer['queryable']))
				{
					$layer['queryable'] = 't';
				}
				if (empty($layer['visible']))
				{
					$layer['visible'] = 'f';
				}
				if (empty($layer['legend']))
				{
					$layer['legend'] = 'f';
				}
				// Set default values </end>

				$layerName = trim(explode('#', $layer['layer_id'], 2)[0]);
				$json = $json.'{ "name": "'.$layerName.'", "source": "'.$layer['source'].'", "title": "'.$layer['title'].'", "group": "'.$group.'", "type": "'.$layer['type'].'"';
				if (!empty($layer['style_layer']))
				{
					$styleLayerName = trim(explode('#', $layer['style_layer'], 2)[0]);
					$styleLayer = array_column_search($layer['style_layer'], 'layer_id', $layers);
					if ($group == 'background')
					{
						$layer['style_layer'] = $layer['style_layer'].'-bg';
					}
					$json = $json.', "style": "'.$layer['style_layer'].'"';
				}
				if ($layer['queryable'] == 'f')
				{
					$json = $json.', "queryable": false';
				}
				if ($layer['visible'] == 't')
				{
					$json = $json.', "visible": true';
				}
				if (empty($group) && $layer['legend'] == 't')
				{
					$json = $json.', "legend": true';
				}
				if ($layer['type'] == 'WMS')
				{
					if (!empty($layer['gutter']))
					{
						$json = $json.', "gutter": '.$layer['gutter'];
					}
					if ($layer['tiled'] == 'f')
					{
						$json = $json.', "renderMode": "image"';
					}
					if (!empty($layer['featureinfolayer']))
					{
						$json = $json.', "featureinfoLayer": "'.$layer['featureinfolayer'].'"';
					}
				}
				if (!empty($layer['abstract']))
				{
					$json = $json.', "abstract": "'.$layer['abstract'].'"';
				}
				if ($layer['type'] == 'WFS')
				{
					$json = $json.', "projection": "EPSG:4326"';
					if ($layer['editable'] == 't')
					{
						$json = $json.', "editable": true';
					}
				}
				if (!empty($layer['attributes']))
				{
					$json = $json.', "attributes": '.$layer['attributes'];
				}
				$json = $json.'}';
				if (!in_array($layer['source'], $mapSources))
				{
					$mapSources[] = $layer['source'];
				}
				if (!empty($layer['style_layer']))
				{
					if ($styleLayer['style_config'] == '[]' || $styleLayer['style_config'] == '{}' || $styleLayer['style_config'] == '""' || $styleLayer['style_config'] == 'null')
					{
						$styleLayer['style_config'] = '';
					}
					if (empty($styleLayer['style_config']))
					{
						$styleSource = array_column_search($styleLayer['source'], 'source_id', $sources);
						$styleService = array_column_search($styleSource['service'], 'service_id', $services);
						if (empty($styleLayer['type']))
						{
							$styleLayer['type'] = 'WMS';
						}
						if (strtoupper($styleLayer['type']) == 'WMS')
						{
							$styleSourceProject = trim(explode('#', $styleSource['source_id'], 2)[0]);
							if (empty($styleLayer['icon']))
							{
								$styleLayer['icon'] = $styleService['base_url'].'/'.$styleSourceProject.'?SERVICE=WMS&REQUEST=GetLegendGraphic&DPI=96&FORMAT=image/png&ICONLABELSPACE=0&LAYERTITLE=TRUE&RULELABEL=FALSE&ITEMFONTSIZE=1&TRANSPARENT=TRUE&BOXSPACE=1.8&SYMBOLWIDTH=6&SYMBOLHEIGHT=6&LAYERSPACE=5&LAYERTITLESPACE=-6&LAYERFONTSIZE=0.5&LAYERFONTCOLOR=%23FFFFFF&LAYERS='.$styleLayerName;
							}
							if (empty($styleLayer['icon_extended']) && $group != 'background')
							{
								$styleLayer['icon_extended'] = $styleService['base_url'].'/'.$styleSourceProject.'?SERVICE=WMS&REQUEST=GetLegendGraphic&DPI=96&FORMAT=image/png&ICONLABELSPACE=3&LAYERTITLE=TRUE&RULELABEL=TRUE&TRANSPARENT=TRUE&BOXSPACE=1&SYMBOLWIDTH=6&SYMBOLHEIGHT=4&SYMBOLSPACE=3&LAYERSPACE=5&LAYERTITLESPACE=-5.3&LAYERFONTSIZE=0.5&LAYERFONTCOLOR=%23FFFFFF&LAYERS='.$styleLayerName;
							}
						}
						elseif (strtoupper($styleLayer['type']) == 'WFS')
						{
							$styleLayer['label'] = $styleLayer['title'];
						}
						if (!empty($styleLayer['icon']))
						{
							if (strpos($styleLayer['icon'], '?') === false)
							{
								$styleLayer['icon'] = $styleLayer['icon'].'?';
							}
							else
							{
								$styleLayer['icon'] = $styleLayer['icon'].'&';
							}
							$styleLayer['icon'] = $styleLayer['icon'].'ttl=36000';
						}
						if (!empty($styleLayer['icon_extended']))
						{
							if (strpos($styleLayer['icon_extended'], '?') === false)
							{
								$styleLayer['icon_extended'] = $styleLayer['icon_extended'].'?';
							}
							else
							{
								$styleLayer['icon_extended'] = $styleLayer['icon_extended'].'&';
							}
							$styleLayer['icon_extended'] = $styleLayer['icon_extended'].'ttl=36000';
						}
					}
					if ($group == 'background')
					{
						$styleLayer['layer_id'] = $styleLayer['layer_id'].'-bg';
					}
					if (!in_array($styleLayer['layer_id'], $mapStyleLayers))
					{
						$mapStyleLayers[] = $styleLayer['layer_id'];
						$mapStyles[] = $styleLayer;
					}
				}
			}
		}
		$json = $json.' ], ';
		addSourcesToJson();
		$json = $json.', ';
		addStylesToJson();
	}

?>