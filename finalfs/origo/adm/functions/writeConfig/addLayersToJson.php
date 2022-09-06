<?php

	function addLayersToJson($mapLayers, $groupLayer=false)
	{
		GLOBAL $layers, $json, $mapStyles, $mapSources, $sources, $services, $mapStyleLayers;
		$json = $json. '"layers": [ ';
		$firstLayer = true;
		if (!isset($mapSources))
		{
			$mapSources = array();
		}
		if (!isset($mapStyleLayers))
		{
			$mapStyleLayers = array();
		}
		if (!isset($mapStyles))
		{
			$mapStyles = array();
		}

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
				if ($layer['type'] !== 'GROUP')
				{
					$source = array_column_search($layer['source'], 'source_id', $sources);
					if ($layer['type'] == 'WFS')
					{
						$layer['source'] = $layer['source'].'@wfs';
					}
					$service = array_column_search($source['service'], 'service_id', $services);
					if ($layer['attributes'] == '[]' || $layer['attributes'] == '{}' || $layer['attributes'] == '""' || $layer['attributes'] == 'null')
					{
						$layer['attributes'] = '';
					}
				}
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
				if (empty($layer['queryable']) && $layer['type'] !== 'GROUP')
				{
					$layer['queryable'] = 't';
				}
				if (empty($layer['visible']))
				{
					$layer['visible'] = 'f';
				}
				if (empty($layer['legend']) && $layer['type'] !== 'GROUP')
				{
					$layer['legend'] = 'f';
				}
				// Set default values </end>

				$layerName = trim(explode('#', $layer['layer_id'], 2)[0]);
				$json = $json.'{ "name": "'.$layerName.'", "title": "'.$layer['title'].'", "type": "'.$layer['type'].'"';
				if (!$groupLayer)
				{
					$json = $json.', "group": "'.$group.'"';
				}
				if (!empty($layer['format']) && $layer['format'] !== 'image/png')
				{
					$json = $json.', "format": "'.$layer['format'].'"';
				}
				if (!empty($layer['attribution']))
				{
					$json = $json.', "attribution": "'.$layer['attribution'].'"';
				}
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
				elseif ($layer['visible'] == 'f')
				{
					$json = $json.', "visible": false';
				}
				if ($layer['swiper'] == 't')
				{
					$json = $json.', "isSwiperLayer": true';
				}
				elseif ($layer['swiper'] == 'under')
				{
					$json = $json.', "isUnderSwiper": true';
				}
				if (empty($group) && $layer['legend'] == 't')
				{
					$json = $json.', "legend": true';
				}
				if ($layer['opacity'] < 1)
				{
					$json = $json.', "opacity": '.$layer['opacity'];
				}
				if ($layer['type'] != 'OSM')
				{
					$json = $json.', "source": "'.$layer['source'].'"';
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
					elseif ($layer['type'] == 'WFS')
					{
						$json = $json.', "projection": "EPSG:4326"';
						if ($layer['editable'] == 't')
						{
							$json = $json.', "editable": true';
						}
					}
				}
				if (!empty($layer['abstract']))
				{
					$json = $json.', "abstract": "'.$layer['abstract'].'"';
				}
				if (!empty($layer['attributes']) && $layer['type'] !== 'GROUP')
				{
					$json = $json.', "attributes": '.$layer['attributes'];
				}
				if (!empty($layer['maxscale']))
				{
					$json = $json.', "maxScale": '.$layer['maxscale'];
				}
				if (!empty($layer['minscale']))
				{
					$json = $json.', "minScale": '.$layer['minscale'];
				}

				if (!empty($layer['layertype']))
				{
					$json = $json.', "layerType": "'.$layer['layertype'].'"';
					if ($layer['layertype'] == 'cluster')
					{
						$json = $json.', "clusterStyle": "'.$layer['style_layer'].'-cluster"';
						if (!empty($layer['clusteroptions']) && $layer['clusteroptions'] !== '{}')
						{
							$json = $json.', "clusterOptions": '.$layer['clusteroptions'];
						}
					}
				}

				if ($layer['type'] === 'GROUP')
				{
					$json = $json.', ';
					addLayersToJson(array(pgArrayToPhp($layer['layers'])), true);
				}
				$json = $json.'}';

				if (!empty($layer['source']) && !in_array($layer['source'], $mapSources))
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
							if (strpos($styleService['base_url'], '?') === false)
							{
								$paramSeparator='?';
							}
							else
							{
								$paramSeparator='&';
							}
							if (empty($styleLayer['icon']))
							{
								$styleLayer['icon'] = $styleService['base_url'].'/'.$styleSourceProject.$paramSeparator.'SERVICE=WMS&REQUEST=GetLegendGraphic&DPI=96&FORMAT=image/png&ICONLABELSPACE=0&LAYERTITLE=TRUE&RULELABEL=FALSE&ITEMFONTSIZE=1&TRANSPARENT=TRUE&BOXSPACE=1.8&SYMBOLWIDTH=6&SYMBOLHEIGHT=6&LAYERSPACE=5&LAYERTITLESPACE=-6&LAYERFONTSIZE=0.5&LAYERFONTCOLOR=%23FFFFFF&LAYERS='.$styleLayerName;
							}
							if (empty($styleLayer['icon_extended']) && $group != 'background')
							{
								$styleLayer['icon_extended'] = $styleService['base_url'].'/'.$styleSourceProject.$paramSeparator.'SERVICE=WMS&REQUEST=GetLegendGraphic&DPI=96&FORMAT=image/png&ICONLABELSPACE=3&LAYERTITLE=TRUE&RULELABEL=TRUE&TRANSPARENT=TRUE&BOXSPACE=1&SYMBOLWIDTH=6&SYMBOLHEIGHT=4&SYMBOLSPACE=3&LAYERSPACE=5&LAYERTITLESPACE=-5.3&LAYERFONTSIZE=0.5&LAYERFONTCOLOR=%23FFFFFF&LAYERS='.$styleLayerName;
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
					$styleLayerId=$styleLayer['layer_id'];
					if ($group == 'background')
					{
						$styleLayer['layer_id'] = $styleLayerId.'-bg';
					}
					if (!in_array($styleLayer['layer_id'], $mapStyleLayers))
					{
						$mapStyleLayers[] = $styleLayer['layer_id'];
						$mapStyles[] = $styleLayer;
						if (!empty($styleLayer['clusterstyle']) && $styleLayer['clusterstyle'] !== '[]')
						{
							$styleLayer['layer_id'] = $styleLayerId.'-cluster';
							if (!in_array($styleLayer['layer_id'], $mapStyleLayers))
							{
								$mapStyleLayers[] = $styleLayer['layer_id'];
								$styleLayer['style_config']=$styleLayer['clusterstyle'];
								$mapStyles[] = $styleLayer;
							}
						}
					}
				}
			}
		}
		$json = $json.' ]';
		if (!$groupLayer)
		{
			$json = $json.', ';
			addSourcesToJson();
			$json = $json.', ';
			addStylesToJson();
		}
	}

?>
