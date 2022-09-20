<!DOCTYPE html>
<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");

	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/pgArrayToPhp.php");
	include_once("./functions/array_column_search.php");
	include_once("./functions/all_from_table.php");
	include_once("./functions/findParents.php");
	include_once("./functions/setLayers.php");
	$functionFiles = array_diff(scandir('./functions/manage'), array('.', '..'));
	foreach ($functionFiles as $functionFile)
	{
		include_once("./functions/manage/$functionFile");
	}
	$dbh=dbh(CONNECTION_STRING);
	if (!empty($_POST['mapId']))
	{ 
		$mapId=$_POST['mapId'];
	}
	$controlId=$_POST['controlId'];
	$layerId=$_POST['layerId'];
	$sourceId=$_POST['sourceId'];
	$serviceId=$_POST['serviceId'];
	$footerId=$_POST['footerId'];
	$tilegridId=$_POST['tilegridId'];
	$proj4defId=$_POST['proj4defId'];
	$groupId=$_POST['groupId'];
	if (isset($_POST['groupIds']))
	{
		$groupIds=explode(',', $_POST['groupIds']);
	}
	else
	{
		$groupIds=array();
	}
	if (empty($groupId) && !isset($mapId))
	{
		$groupId=$groupIds[0];
	}

	$toGroupId=$_POST['toGroupId'];
	$toMapId=$_POST['toMapId'];

	$fromGroupId=$_POST['fromGroupId'];
	$fromMapId=$_POST['fromMapId'];

	$newmapId=$_POST['newmapId'];
	$newcontrolId=$_POST['newcontrolId'];
	$newlayerId=$_POST['newlayerId'];
	$newsourceId=$_POST['newsourceId'];
	$newserviceId=$_POST['newserviceId'];
	$newgroupId=$_POST['newgroupId'];
	$newfooterId=$_POST['newfooterId'];
	$newtilegridId=$_POST['newtilegridId'];
	$newproj4defId=$_POST['newproj4defId'];

	$mapButton=$_POST['mapButton'];
	$groupButton=$_POST['groupButton'];
	$layerButton=$_POST['layerButton'];
	$controlButton=$_POST['controlButton'];
	$sourceButton=$_POST['sourceButton'];
	$serviceButton=$_POST['serviceButton'];
	$footerButton=$_POST['footerButton'];
	$tilegridButton=$_POST['tilegridButton'];
	$proj4defButton=$_POST['proj4defButton'];

	$hiddenInputs="";
	if (isset($footerId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="footerId" value="'.$footerId.'">';
	}
	if (isset($mapId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="mapId" value="'.$mapId.'">';
	}
	if (isset($sourceId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="sourceId" value="'.$sourceId.'">';
	}
	if (isset($controlId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="controlId" value="'.$controlId.'">';
	}
	if (isset($tilegridId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="tilegridId" value="'.$tilegridId.'">';
	}
	if (isset($proj4defId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="proj4defId" value="'.$proj4defId.'">';
	}
	if (isset($serviceId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="serviceId" value="'.$serviceId.'">';
	}
	if (!empty($groupIds))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="groupIds" value="'.implode(',', $groupIds).'">';
	}
	if (isset($layerId))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="layerId" value="'.$layerId.'">';
	}

	$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3Focus";
	if (!isset($mapId) && (isset($_POST['groupIds']) || isset($layerId) || isset($controlId) || isset($sourceId) || isset($serviceId) || isset($footerId) || isset($tilegridId) || isset($proj4defId)))
	{
		if (isset($_POST['groupIds']) || isset($layerId) || isset($sourceId) || isset($serviceId) || isset($footerId) || isset($tilegridId) || isset($proj4defId))
		{
			if ((empty($groupIds) && isset($layerId)) || isset($controlId) || (empty($groupIds) && isset($sourceId)) || (empty($groupIds) && isset($serviceId)) || isset($footerId) || isset($tilegridId) || isset($proj4defId))
			{
				if (isset($_POST['groupIds']) || isset($controlId) || (!isset($layerId) && isset($sourceId)) || isset($serviceId) || isset($footerId) || isset($tilegridId) || isset($proj4defId))
				{
					if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || (!isset($sourceId) && isset($serviceId)) || isset($footerId) || (!isset($sourceId) && isset($tilegridId)) || isset($proj4defId))
					{
						if (isset($_POST['groupIds']) || isset($layerId) || isset($sourceId) || isset($serviceId) || isset($controlId) || isset($tilegridId) || isset($proj4defId))
						{
							if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || isset($sourceId) || isset($footerId) || isset($tilegridId) || isset($proj4defId))
							{
								if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || isset($sourceId) || isset($footerId) || isset($serviceId) || isset($proj4defId))
								{
									if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || isset($sourceId) || isset($footerId) || isset($serviceId) || isset($tilegridId))
									{
									}
									else
									{
										$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class="h3NoFocus";
									}
								}
								else
								{
									$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$proj4defh3class="h3NoFocus";
								}
							}
							else
							{
								$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
							}
						}
						else
						{
							$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
						}
					}
					else
					{
						$maph3class=$controlh3class=$grouph3class=$layerh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
					}
				}
				else
				{
					$maph3class=$controlh3class=$grouph3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
				}
			}
			else
			{
				$maph3class=$controlh3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
			}
		}
		else
		{
			$maph3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
		}
	}
	elseif (isset($mapId))
	{
		$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class=$tilegridh3class=$proj4defh3class="h3NoFocus";
	}

/*
 *************************
 *  DATABAS-OPERATIONER  *
 *************************
*/
	$maps=all_from_table('map_configs.maps');
	$groups=all_from_table('map_configs.groups');
	$controls=all_from_table('map_configs.controls');
	$sources=all_from_table('map_configs.sources');
	$services=all_from_table('map_configs.services');
	$footers=all_from_table('map_configs.footers');
	$tilegrids=all_from_table('map_configs.tilegrids');
	$proj4defs=all_from_table('map_configs.proj4defs');
	setLayers();

	// Map button pressed
	if (isset($mapButton) && $mapButton != 'get')
	{
		if ($mapButton == 'create')
		{
			if (!empty($newmapId) && !in_array($newmapId, array_column($maps, 'map_id')))
			{
				$sql="INSERT INTO map_configs.maps(map_id) VALUES ('$newmapId')";
			}
		}
		elseif ($mapButton == 'delete')
		{
			$sql="DELETE FROM map_configs.maps WHERE map_id = '$mapId'";
		}
		elseif ($mapButton == 'update')
		{
			$sql="UPDATE map_configs.maps SET layers = '{".$_POST['updateLayers']."}', groups = '{".$_POST['updateGroups']."}', controls = '{".$_POST['updateControls']."}', proj4defs = '{".$_POST['updateProj4defs']."}', featureinfooptions = '".$_POST['updateFeatureinfooptions']."', center = '".$_POST['updateCenter']."', zoom = '".$_POST['updateZoom']."', footer = '".$_POST['updateFooter']."', info = '".$_POST['updateInfo']."', map_id = '".$_POST['updateId']."', tilegrid = '".$_POST['updateTilegrid']."' WHERE map_id = '$mapId'";
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			$maps=all_from_table('map_configs.maps');
		}
	}

	// Control button pressed
	elseif (isset($controlButton) && $controlButton != 'get')
	{
		if ($controlButton == 'create')
		{
			if (!empty($newcontrolId) && !in_array($newcontrolId, array_column($controls, 'control_id')))
			{
				$sql="INSERT INTO map_configs.controls(control_id) VALUES ('$newcontrolId')";
			}
		}
		elseif ($controlButton == 'delete')
		{
			$sql="DELETE FROM map_configs.controls WHERE control_id = '$controlId'";
		}
		elseif ($controlButton == 'update')
		{
			$sql="UPDATE map_configs.controls SET options = '".$_POST['updateOptions']."', info = '".$_POST['updateInfo']."', control_id = '".$_POST['updateId']."' WHERE control_id = '$controlId'";
		}
		elseif ($controlButton == 'add')
		{
			$toMap=array_column_search($toMapId, 'map_id', $maps);
			$toMapControls=pgArrayToPhp($toMap['controls']);
			if (empty($toMapControls[0]))
			{
				$toMapControls=array();
			}
			if (!in_array($controlId, $toMapControls))
			{
				$toMapControls[]=$controlId;
				$sql="UPDATE map_configs.maps SET controls = '{".implode(',', $toMapControls)."}' WHERE map_id = '$toMapId'";
			}
		}
		elseif ($controlButton == 'remove' && isset($fromMapId))
		{
			$fromMap=array_column_search($fromMapId, 'map_id', $maps);
			$fromMapControls=pgArrayToPhp($fromMap['controls']);
			$controlKey=array_search($controlId, $fromMapControls);
			unset($fromMapControls[$controlKey]);
			$sql="UPDATE map_configs.maps SET controls = '{".implode(',', $fromMapControls)."}' WHERE map_id = '$fromMapId'";
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if ($controlButton == 'add' || $controlButton == 'remove')
			{
				$maps=all_from_table('map_configs.maps');
			}
			else
			{
				$controls=all_from_table('map_configs.controls');
			}
		}
	}

	// Footer button pressed
	elseif (isset($footerButton) && $footerButton != 'get')
	{
		if ($footerButton == 'create')
		{
			if (!empty($newfooterId) && !in_array($newfooterId, array_column($footers, 'footer_id')))
			{
				$sql="INSERT INTO map_configs.footers(footer_id) VALUES ('$newfooterId')";
			}
		}
		elseif ($footerButton == 'delete')
		{
			$sql="DELETE FROM map_configs.footers WHERE footer_id = '$footerId'";
		}
		elseif ($footerButton == 'update')
		{
			$sql="UPDATE map_configs.footers SET img = '".$_POST['updateImg']."', url = '".$_POST['updateUrl']."', text = '".$_POST['updateText']."', info = '".$_POST['updateInfo']."', footer_id = '".$_POST['updateId']."' WHERE footer_id = '$footerId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$footers=all_from_table('map_configs.footers');
	}

	// Tilegrid button pressed
	elseif (isset($tilegridButton) && $tilegridButton != 'get')
	{
		if ($tilegridButton == 'create')
		{
			if (!empty($newtilegridId) && !in_array($newtilegridId, array_column($tilegrids, 'tilegrid_id')))
			{
				$sql="INSERT INTO map_configs.tilegrids(tilegrid_id) VALUES ('$newtilegridId')";
			}
		}
		elseif ($tilegridButton == 'delete')
		{
			$sql="DELETE FROM map_configs.tilegrids WHERE tilegrid_id = '$tilegridId'";
		}
		elseif ($tilegridButton == 'update')
		{
			$sql="UPDATE map_configs.tilegrids SET tilesize = '".$_POST['updateTilesize']."', info = '".$_POST['updateInfo']."', tilegrid_id = '".$_POST['updateId']."' WHERE tilegrid_id = '$tilegridId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$tilegrids=all_from_table('map_configs.tilegrids');
	}

	// Proj4def button pressed
	elseif (isset($proj4defButton) && $proj4defButton != 'get')
	{
		if ($proj4defButton == 'create')
		{
			if (!empty($newproj4defId) && !in_array($newproj4defId, array_column($proj4defs, 'code')))
			{
				$sql="INSERT INTO map_configs.proj4defs(code) VALUES ('$newproj4defId')";
			}
		}
		elseif ($proj4defButton == 'delete')
		{
			$sql="DELETE FROM map_configs.proj4defs WHERE code = '$proj4defId'";
		}
		elseif ($proj4defButton == 'update')
		{
			$sql="UPDATE map_configs.proj4defs SET code = '".$_POST['updateCode']."', projection = '".$_POST['updateProjection']."', alias = '".$_POST['updateAlias']."', info = '".$_POST['updateInfo']."' WHERE code = '$proj4defId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$proj4defs=all_from_table('map_configs.proj4defs');
	}

	// Source button pressed
	elseif (isset($sourceButton) && $sourceButton != 'get')
	{
		if ($sourceButton == 'create')
		{
			if (!empty($newsourceId) && !in_array($newsourceId, array_column($sources, 'source_id')))
			{
				$sql="INSERT INTO map_configs.sources(source_id) VALUES ('$newsourceId')";
			}
		}
		elseif ($sourceButton == 'delete')
		{
			$sql="DELETE FROM map_configs.sources WHERE source_id = '$sourceId'";
		}
		elseif ($sourceButton == 'update')
		{
			if (!empty($_POST['updateWithgeometry']))
			{
				$withgeometry="'".$_POST['updateWithgeometry']."'";
			}
			else
			{
				$withgeometry="null";
			}
			if (!empty($_POST['updateFipointtolerance']))
			{
				$fipointtolerance="'".$_POST['updateFipointtolerance']."'";
			}
			else
			{
				$fipointtolerance="null";
			}
			if (!empty($_POST['updateTtl']))
			{
				$ttl="'".$_POST['updateTtl']."'";
			}
			else
			{
				$ttl="null";
			}
			$sql="UPDATE map_configs.sources SET service = '".$_POST['updateService']."', with_geometry = $withgeometry, fi_point_tolerance = $fipointtolerance, ttl = $ttl, info = '".$_POST['updateInfo']."', source_id = '".$_POST['updateId']."', tilegrid = '".$_POST['updateTilegrid']."' WHERE source_id = '$sourceId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$sources=all_from_table('map_configs.sources');
	}

	// Service button pressed
	elseif (isset($serviceButton) && $serviceButton != 'get')
	{
		if ($serviceButton == 'create')
		{
			if (!empty($newserviceId) && !in_array($newserviceId, array_column($services, 'service_id')))
			{
				$sql="INSERT INTO map_configs.services(service_id) VALUES ('$newserviceId')";
			}
		}
		elseif ($serviceButton == 'delete')
		{
			$sql="DELETE FROM map_configs.services WHERE service_id = '$serviceId'";
		}
		elseif ($serviceButton == 'update')
		{
			$sql="UPDATE map_configs.services SET base_url = '".$_POST['updateBaseurl']."', info = '".$_POST['updateInfo']."', service_id = '".$_POST['updateId']."' WHERE service_id = '$serviceId'";
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			$services=all_from_table('map_configs.services');
		}
	}

	// Group button pressed
	elseif (isset($groupButton) && $groupButton != 'get')
	{
		if ($groupButton == 'create')
		{
			if (!empty($newgroupId) && !in_array($newgroupId, array_column($groups, 'group_id')))
			{
				$sql="INSERT INTO map_configs.groups(group_id) VALUES ('$newgroupId')";
			}
		}
		elseif ($groupButton == 'delete')
		{
			$sql="DELETE FROM map_configs.groups WHERE group_id = '".$_POST['groupIds']."'";
		}
		elseif ($groupButton == 'update')
		{
			$sql="UPDATE map_configs.groups SET layers = '{".$_POST['updateLayers']."}', groups = '{".$_POST['updateGroups']."}', title = '".$_POST['updateTitle']."', abstract = ".pg_escape_literal($_POST['updateAbstract']).", expanded = '".$_POST['updateExpanded']."', group_id = '".$_POST['updateId']."', info = '".$_POST['updateInfo']."' WHERE group_id = '".end($groupIds)."'";
		}
		elseif ($groupButton == 'add' && isset($toMapId))
		{
			$toMap=array_column_search($toMapId, 'map_id', $maps);
			$toMapGroups=explode(',', trim($toMap['groups'], '{}'));
			if (empty($toMapGroups[0]))
			{
				$toMapGroups=array();
			}
			if (!in_array($groupId, $toMapGroups))
			{
				$toMapGroups[]=$groupId;
				$sql="UPDATE map_configs.maps SET groups = '{".implode(',', $toMapGroups)."}' WHERE map_id = '$toMapId'";
			}
		}
		elseif ($groupButton == 'add' && isset($toGroupId))
		{
			$toGroup=array_column_search($toGroupId, 'group_id', $groups);
			$toGroupGroups=explode(',', trim($toGroup['groups'], '{}'));
			if (empty($toGroupGroups[0]))
			{
				$toGroupGroups=array();
			}
			if (!in_array($groupId, $toGroupGroups))
			{
				$toGroupGroups[]=$groupId;
				$sql="UPDATE map_configs.groups SET groups = '{".implode(',', $toGroupGroups)."}' WHERE group_id = '$toGroupId'";
			}
		}
		elseif ($groupButton == 'remove' && isset($fromMapId))
		{
			$fromMap=array_column_search($fromMapId, 'map_id', $maps);
			$fromMapGroups=pgArrayToPhp($fromMap['groups']);
			$groupKey=array_search($groupId, $fromMapGroups);
			unset($fromMapGroups[$groupKey]);
			$sql="UPDATE map_configs.maps SET groups = '{".implode(',', $fromMapGroups)."}' WHERE map_id = '$fromMapId'";
		}
		elseif ($groupButton == 'remove' && isset($fromGroupId))
		{
			$fromGroup=array_column_search($fromGroupId, 'group_id', $groups);
			$fromGroupGroups=pgArrayToPhp($fromGroup['groups']);
			$groupKey=array_search($groupId, $fromGroupGroups);
			unset($fromGroupGroups[$groupKey]);
			$sql="UPDATE map_configs.groups SET groups = '{".implode(',', $fromGroupGroups)."}' WHERE group_id = '$fromGroupId'";
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if (($groupButton == 'add' && isset($toMapId)) || ($groupButton == 'remove' && isset($fromMapId)))
			{
				$maps=all_from_table('map_configs.maps');
			}
			else
			{
				$groups=all_from_table('map_configs.groups');
			}
		}
	}

	// Layer button pressed
	elseif (isset($layerButton) && $layerButton != 'get')
	{
		if ($layerButton == 'create')
		{
			if (!empty($newlayerId) && !in_array($newlayerId, array_column($layers, 'layer_id')))
			{
				$sql="INSERT INTO map_configs.layers(layer_id) VALUES ('$newlayerId')";
			}
		}
		elseif ($layerButton == 'delete')
		{
			$sql="DELETE FROM map_configs.layers WHERE layer_id = '$layerId'";
		}
		elseif ($layerButton == 'update')
		{
			if (!empty($_POST['updateAttributes']))
			{
				$attributesStr=", attributes = '".pg_escape_string($_POST['updateAttributes'])."'";
			}
			else
			{
				$attributesStr="";
			}
			if (!empty($_POST['updateEditable']))
			{
				$editableStr=", editable = '".$_POST['updateEditable']."'";
			}
			else
			{
				$editableStr="";
			}
			if (!empty($_POST['updateTiled']))
			{
				$tiledStr=", tiled = '".$_POST['updateTiled']."'";
			}
			else
			{
				$tiledStr="";
			}
			if (!empty($_POST['updateStyle_config']))
			{
				$styleConfigStr=", style_config = '".$_POST['updateStyle_config']."'";
			}
			else
			{
				$styleConfigStr=", style_config = '[]'";
			}
			if (!empty($_POST['updateMaxscale']))
			{
				$maxscaleStr=", maxscale = '".$_POST['updateMaxscale']."'";
			}
			else
			{
				$maxscaleStr=", maxscale = null";
			}
			if (!empty($_POST['updateMinscale']))
			{
				$minscaleStr=", minscale = '".$_POST['updateMinscale']."'";
			}
			else
			{
				$minscaleStr=", minscale = null";
			}
			if (!empty($_POST['updateClusterstyle']))
			{
				$clusterstyleStr=", clusterstyle = '".$_POST['updateClusterstyle']."'";
			}
			else
			{
				$clusterstyleStr=", clusterstyle = '[]'";
			}
			if (!empty($_POST['updateClusteroptions']))
			{
				$clusteroptionsStr=", clusteroptions = '".$_POST['updateClusteroptions']."'";
			}
			else
			{
				$clusteroptionsStr=", clusteroptions = '[]'";
			}
			$sql="UPDATE map_configs.layers SET title = '".$_POST['updateTitle']."', abstract = ".pg_escape_literal($_POST['updateAbstract']).", source = '".$_POST['updateSource']."', type = '".$_POST['updateType']."', queryable ='".$_POST['updateQueryable']."', visible = '".$_POST['updateVisible']."', icon = '".$_POST['updateIcon']."', icon_extended = '".$_POST['updateIcon_extended']."', style_filter = ".pg_escape_literal($_POST['updateStylefilter']).", layer_id = '".$_POST['updateId']."', opacity = '".$_POST['updateOpacity']."', info = '".$_POST['updateInfo']."', featureinfolayer = '".$_POST['updateFeatureinfolayer']."', categories = '{".$_POST['updateCategories']."}', format = '".$_POST['updateFormat']."', attribution = '".$_POST['updateAttribution']."', layertype = '".$_POST['updateLayertype']."', layers = '{".$_POST['updateLayers']."}', adusers = '{".$_POST['updateAdusers']."}', adgroups = '{".$_POST['updateAdgroups']."}', swiper = '".$_POST['updateSwiper']."' $editableStr $tiledStr $attributesStr $styleConfigStr $maxscaleStr $minscaleStr $clusterstyleStr $clusteroptionsStr WHERE layer_id = '$layerId'";
		}
		elseif ($layerButton == 'add' && isset($toGroupId))
		{
			$toGroup=array_column_search($toGroupId, 'group_id', $groups);
			$toGroupLayers=explode(',', trim($toGroup['layers'], '{}'));
			if (empty($toGroupLayers[0]))
			{
				$toGroupLayers=array();
			}
			if (!in_array($layerId, $toGroupLayers))
			{
				$toGroupLayers[]=$layerId;
				$sql="UPDATE map_configs.groups SET layers = '{".implode(',', $toGroupLayers)."}' WHERE group_id = '$toGroupId'";
			}
		}
		elseif ($layerButton == 'add' && isset($toMapId))
		{
			$toMap=array_column_search($toMapId, 'map_id', $maps);
			$toMapLayers=explode(',', trim($toMap['layers'], '{}'));
			if (empty($toMapLayers[0]))
			{
				$toMapLayers=array();
			}
			if (!in_array($layerId, $toMapLayers))
			{
				$toMapLayers[]=$layerId;
				$sql="UPDATE map_configs.maps SET layers = '{".implode(',', $toMapLayers)."}' WHERE map_id = '$toMapId'";
			}
		}
		elseif ($layerButton == 'remove' && isset($fromMapId))
		{
			$fromMap=array_column_search($fromMapId, 'map_id', $maps);
			$fromMapLayers=pgArrayToPhp($fromMap['layers']);
			$layerKey=array_search($layerId, $fromMapLayers);
			unset($fromMapLayers[$layerKey]);
			$sql="UPDATE map_configs.maps SET layers = '{".implode(',', $fromMapLayers)."}' WHERE map_id = '$fromMapId'";
		}
		elseif ($layerButton == 'remove' && isset($fromGroupId))
		{
			$fromGroup=array_column_search($fromGroupId, 'group_id', $groups);
			$fromGroupLayers=pgArrayToPhp($fromGroup['layers']);
			$layerKey=array_search($layerId, $fromGroupLayers);
			unset($fromGroupLayers[$layerKey]);
			$sql="UPDATE map_configs.groups SET layers = '{".implode(',', $fromGroupLayers)."}' WHERE group_id = '$fromGroupId'";
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if ($layerButton == 'add' || $layerButton == 'remove')
			{
				if (isset($toGroupId) || isset($fromGroupId))
				{
					$groups=all_from_table('map_configs.groups');
				}
				elseif (isset($toMapId) || isset($fromMapId))
				{
					$maps=all_from_table('map_configs.maps');
				}
			}
			else
			{
				setLayers();
			}
		}
	}

?>

<html style="width:100%;height:100%;font-size:0.9vw;line-height:2">
<head>
	<meta charset="utf-8"/>
	<script>
		var topFrame="";
		function toggleTopFrame(type)
		{

			var x = document.getElementById("topFrame");

			if (x.style.display === "none")
			{

				x.style.display = "block";

			}
			else if (topFrame === type)
			{

				x.style.display = "none";

			}
			topFrame = type;

		}

		function updateSelect(id, array)
		{
			var select = document.getElementById(id);
			if (select.options != null)
			{
				var length = select.options.length;

				for (i = length-1; i >= 0; i--)
				{

					select.options[i] = null;

				}
			}
			array.forEach(function(item)

			{

				var newOption = document.createElement("option");

				newOption.text = item.toString();

				select.add(newOption);

			});
		}
		<?php 
			echo "var categories = ".json_encode(array_keys($layerCategories)).";\n";
			foreach ($layerCategories as $category => $catLayers)
			{
				echo "var $category = ".json_encode($catLayers).";\n";
			}
		?>
	</script>
	<style>
		<?php include("./styles/manage.css"); ?>
	</style>

</head>
<body>
	<form action="read_json.php">

		<input class="topInput" type="submit" value="Importera JSON" />

	</form>
	<form action="help.php" target="topFrame">
		<input class="topInput" onclick="toggleTopFrame('help');" type="submit" value="Hjälp" />

	</form>
	<iframe id="topFrame" name="topFrame" style="display:none"></iframe>
	<iframe id="hiddenFrame" name="hiddenFrame" style="display:none"></iframe>


<!--  REDIGERA KARTA  -->
<div style="width:calc( 100vw - 15px ); overflow-x:auto; margin-bottom: 5px">
	<table style="border-bottom:dashed 1px lightgray; margin-bottom: 2px; border-top:dashed 1px lightgray;">
		<tr>
			<th class="thLeft">
				<h3 class="<?php echo $maph3class; ?>">
					Redigera karta
				</h3>
				<?php headForm('map'); ?>
			</th>

<!--  REDIGERA KONTROLLER  -->
			<th class="thMiddle">
				<h3 class="<?php echo $controlh3class; ?>">
					Redigera kontroll
				</h3>
				<?php headForm('control'); ?>
				<?php multiselectButton('controls'); ?>
			</th>

<!--  REDIGERA GRUPP  -->
			<th class="thMiddle">
				<h3 class="<?php echo $grouph3class; ?>">
					Redigera grupp
				</h3>
				<?php headForm('group'); ?>
				<?php multiselectButton('groups'); ?>
			</th>

<!--  REDIGERA LAGER  -->
			<th class="thMiddle">
				<h3 class="<?php echo $layerh3class; ?>">
					Redigera lager
				</h3>
				<?php headForm('layer'); ?>
				<?php multiselectButton('layers'); ?>
			</th>

<!--  REDIGERA KÄLLOR  -->
			<th class="thMiddle">
				<h3 class="<?php echo $sourceh3class; ?>">
					Redigera källa
				</h3>
				<?php headForm('source'); ?>
			</th>

<!--  REDIGERA SIDFÖTTER  -->
			<th class="thMiddle">
				<h3 class="<?php echo $footerh3class; ?>">
					Redigera sidfot
				</h3>
				<?php headForm('footer'); ?>
			</th>

<!--  REDIGERA TJÄNSTER  -->
			<th class="thMiddle">
				<h3 class="<?php echo $serviceh3class; ?>">
					Redigera tjänst
				</h3>
				<?php headForm('service'); ?>
			</th>
<!--  REDIGERA TILEGRIDS  -->
			<th class="thMiddle">
				<h3 class="<?php echo $tilegridh3class; ?>">
					Redigera tilegrid
				</h3>
				<?php headForm('tilegrid'); ?>
			</th>
<!--  REDIGERA PROJ4DEFS  -->
			<th class="thRight">
				<h3 class="<?php echo $proj4defh3class; ?>">
					Redigera proj4defs
				</h3>
				<?php headForm('proj4def'); ?>
				<?php multiselectButton('proj4defs'); ?>
			</th>
		</tr>
	</table>
</div>
<?php
/*
 ************************
 *  DYNAMISKT INNEHÅLL  *
 ************************
*/
	$level=0;

	//  Om grupp eller karta vald

	$tmpGroupIds=$groupIds;
	if (isset($mapId) && !isset($_POST['groupIds']))
	{
		$tmpGroupIds[]=$mapId;
	}
	foreach ($tmpGroupIds as $key => $groupId)
	{
		if ($groupId == $mapId)
		{
			$level++;
			$group=array_column_search($groupId, 'map_id', $maps);
			$updateButtonName='mapButton';
		}
		else
		{
			$group=array_column_search($groupId, 'group_id', $groups);
			$updateButtonName='groupButton';
		}
		if (in_array($groupId, array_column($groups, 'group_id')) || in_array($groupId, array_column($maps, 'map_id')))
		{
			echo '<div>';
			echo '<div style="float:left;">';
			echo   '<form method="post" style="line-height:2">';
			echo      '<label for="'.$groupId.'Id">Id:</label>';
			echo      '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Id" name="updateId">'.$groupId.'</textarea>&nbsp;';
			
			echo      '<label for="'.$groupId.'Layers">Lager:</label>';
			echo      '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Layers" name="updateLayers">'.trim($group['layers'], '{}').'</textarea>&nbsp;';
			echo      '<label for="'.$groupId.'Groups">Grupper:</label>';
			echo      '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Groups" name="updateGroups">'.trim($group['groups'], '{}').'</textarea>&nbsp;';
			if ($groupId != $mapId)
			{
				echo '<br>';
				echo '<label for="'.$groupId.'Title">Titel:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Title" name="updateTitle">'.$group['title'].'</textarea>&nbsp;';

				echo '<label for="'.$groupId.'Expanded">Expanderad:</label>';
				echo '<select class="miniSelect" id="'.$groupId.'Expanded" name="updateExpanded">';
				printSelectOptions(array("", "f", "t"), $group['expanded']);
				echo '</select>&nbsp;';
				echo '<label for="'.$groupId.'Abstract">Sammanfattning:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Abstract" name="updateAbstract">'.$group['abstract'].'</textarea>&nbsp;';
				$childType="group";
				$deleteButtonClass="deleteButton2";
			}
			else
			{
				echo '<br>';
				echo '<label for="'.$groupId.'Footer">Sidfot:</label>';
				echo '<select class="bodySelect" id="'.$groupId.'Footer" name="updateFooter">';
				printSelectOptions(array_merge(array(""), array_column($footers, 'footer_id')), $group['footer']);
				echo '</select>&nbsp;';

				echo '<label for="'.$groupId.'Controls">Kontroller:</label>';
				echo '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Controls" name="updateControls">'.trim($group['controls'], '{}').'</textarea>&nbsp;';

				echo '<label for="'.$groupId.'Proj4defs">Proj4defs:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Proj4defs" name="updateProj4defs">'.trim($group['proj4defs'], '{}').'</textarea>&nbsp;';

				echo '<label for="'.$groupId.'Featureinfooptions">FeatureInfoOptions:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Featureinfooptions" name="updateFeatureinfooptions">'.$group['featureinfooptions'].'</textarea>&nbsp;';
				echo '<br>';
				echo '<label for="'.$groupId.'Center">Mittpunkt:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Center" name="updateCenter">'.$group['center'].'</textarea>&nbsp;';
				echo '<label for="'.$groupId.'Zoom">Zoom:</label>';
				echo '<textarea rows="1" class="textareaXSmall" id="'.$groupId.'Zoom" name="updateZoom">'.$group['zoom'].'</textarea>&nbsp;';
				echo '<label for="'.$groupId.'Tilegrid">Tilegrid:</label>';
				echo '<select class="bodySelect" id="'.$groupId.'Tilegrid" name="updateTilegrid">';
				printSelectOptions(array_merge(array(""), array_column($tilegrids, 'tilegrid_id')), $group['tilegrid']);
				echo         '</select>&nbsp;';
				$childType="map";
				$deleteButtonClass="deleteButton3b";
			}
			echo '<label for="'.$groupId.'Info">Info:</label>';
			echo '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Info" name="updateInfo">'.$group['info'].'</textarea>&nbsp;';
			if (isset($mapId))
			{
				echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
				$confirmStr="Är du säker på att du vill radera kartan $mapId? Ingående kontroller, grupper och lager påverkas ej.";
			}
			if (isset($_POST['groupIds']))
			{
				$groupIdsValue=$tmpGroupIds;
				$confirmStr="Är du säker på att du vill radera gruppen $groupId? Ingående lager påverkas ej och referenser till gruppen hanteras separat.";
				if ($groupId == $mapId)
				{
					array_splice($groupIdsValue, 1);
					$confirmStr="Är du säker på att du vill radera kartan $mapId? Ingående kontroller, grupper och lager påverkas ej.";
				}
				elseif (isset($mapId))
				{
					array_splice($groupIdsValue, $level);
				}
				else
				{
					array_splice($groupIdsValue, $level+1);
				}
				$groupIdsValue=implode(',', $groupIdsValue);
				echo '<input type="hidden" name="groupIds" value="'.$groupIdsValue.'">';
			}
			echo     '<div class="buttonDiv">';
  			echo       '<button class="updateButton" type="submit" name="'.$updateButtonName.'" value="update">Uppdatera</button>';
                        infoButton($childType);
			if (isset($mapId) && $level == 1)
			{
				echo   "<form onsubmit='confirmStr=\"Är du säker att du vill skriva över den befintliga konfigurationen för $mapId?\"; return confirm(confirmStr);' action=\"writeConfig.php\" method=\"get\" target=\"hiddenFrame\">";
				echo     "<button class=\"updateButton\" type=\"submit\" name=\"map\" value=\"$mapId\">";
				echo       'Skriv kartkonfiguration';
				echo     '</button>';
				echo   '</form>';
				echo '</div><div style="margin-top:-0.5vw;margin-bottom:-2vw;margin-right:4vw;float:right">';
				echo   "<form action=\"writeConfig.php\" method=\"get\" target=\"hiddenFrame\">";
				echo     '<input type="hidden" name="getJson" value="y">';
				echo     "<button class=\"exportButton\" type=\"submit\" name=\"map\" value=\"$mapId\">";
				echo       'Exportera JSON';
				echo     '</button>';
				echo   '</form>';
			}
			echo     '</div>';
			echo   '</form>';
			echo '</div>';
			if ($groupId != $mapId)
			{
				echo '<form class="addForm" method="post">';
				echo '<select class="headSelect" name="toMapId">';
				selectOptions('maps', false);
				echo '</select>';
				echo '<input type="hidden" name="groupId" value="'.$groupId.'">';
				echo $hiddenInputs;
				echo '<button type="submit" name="groupButton" value="add">Lägg till i karta</button>';
				echo '</form>';
				echo '<form class="addForm" method="post">';
				echo '<select class="headSelect" name="toGroupId">';
				selectOptions('groups', false);
				echo '</select>';
				echo '<input type="hidden" name="groupId" value="'.$groupId.'">';
				echo $hiddenInputs;
				echo '<button type="submit" name="groupButton" value="add">Lägg till i grupp</button>';
				echo '</form>';
				$mapParents=findParents('map', 'group', $groupId);
				if (!empty($mapParents))
				{
					echo '<form class="addForm" method="post">';
					echo   '<select class="headSelect" name="fromMapId">';
					printSelectOptions($mapParents);
					echo   '</select>';
					echo   '<input type="hidden" name="groupId" value="'.$groupId.'">';
					echo $hiddenInputs;
					echo   '<button type="submit" name="groupButton" value="remove">Ta bort från karta</button>';
					echo '</form>';
				}
				$groupParents=findParents('group', 'group', $groupId);
				if (!empty($groupParents))
				{
					echo '<form class="addForm" method="post">';
					echo   '<select class="headSelect" name="fromGroupId">';
					printSelectOptions($groupParents);
					echo   '</select>';
					echo   '<input type="hidden" name="groupId" value="'.$groupId.'">';
					echo $hiddenInputs;
					echo   '<button type="submit" name="groupButton" value="remove">Ta bort från grupp</button>';
					echo '</form>';
				}
			}
			echo '<div style="white-space:nowrap;margin-bottom:-2vw">';
			echo   "<form method='post' onsubmit='confirmStr=\"$confirmStr\"; return confirm(confirmStr);' style='line-height:2'>";
			if (isset($mapId))
			{
				echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
			}
			if (isset($_POST['groupIds']))
			{
				echo '<input type="hidden" name="groupIds" value="'.$groupIdsValue.'">';
			}
  			echo     "<button class='$deleteButtonClass' type='submit' name='$updateButtonName' value='delete'>Radera</button>";
			echo   '</form>';
			echo '</div>';
			echo '</div>';
			echo '<table>';
			echo   '<tr>';
			if (!empty(trim($group['controls'], '{}')))
			{
				echo '<th style="padding-right:0.3vw">';
				if ($groupId != end($tmpGroupIds) || ((isset($layerId) || isset($footerId)) && $groupId == end($tmpGroupIds)))
				{
					echo '<h3 style="color:lightgray">Redigera kontroll</h3>';
				}
				else
				{
					echo '<h3>Redigera kontroll</h3>';
				}
				echo '<div style="display:flex;">';
				echo   '<form class="headForm" method="post">';
				echo     '<select onchange="this.form.submit()" class="headSelect" name="controlId">';
				printSelectOptions(pgArrayToPhp($group['controls']), $controlId);
				echo     '</select>';
				if (isset($mapId))
				{
					echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
				}
				if (!empty($tmpGroupIds))
				{
					$splicedTmpGroupIds=$tmpGroupIds;
					if (isset($mapId))
					{
						array_splice($splicedTmpGroupIds, $level);
					}
					else
					{
						array_splice($splicedTmpGroupIds, $level+1);
					}
					echo '<input type="hidden" name="groupIds" value="'.implode(',', $splicedTmpGroupIds).'">';
				}
			  	echo     '<button type="submit" class="headButton" name="controlButton" value="get">Hämta</button>';
				echo   '</form>';
				echo '</div>';
				echo '</th>';
				$style="border-left:dashed 1px lightgray; padding-left:0.3vw";
			}
			else
			{
				$style="";
			}

			if (!empty(trim($group['groups'], '{}')))
			{
				echo '<th style="padding-right:0.3vw;'.$style.'">';
				if (isset($controlId) || isset($footerId) || (isset($layerId) && $groupId == end($tmpGroupIds)))
				{
					echo '<h3 style="color:lightgray">Redigera grupp</h3>';
				}
				else
				{
					echo '<h3>Redigera grupp</h3>';
				}
				echo '<div style="display:flex;">';
				echo   '<form class="headForm" method="post">';
				echo     '<select onchange="this.form.submit()" class="headSelect" name="groupIds">';
				foreach (pgArrayToPhp($group['groups']) as $subGroupId)
				{
					$groupOptionValue=$tmpGroupIds;
					if (!in_array($subGroupId, $groupOptionValue))
					{
						if (isset($mapId))
						{
							$groupOptionValue[$level]=$subGroupId;
							array_splice($groupOptionValue, $level+1);
						}
						else
						{
							$groupOptionValue[$level+1]=$subGroupId;
							array_splice($groupOptionValue, $level+2);
						}
					}
					$groupOptions='<option value="'.implode(',', $groupOptionValue).'"';
					if ($subGroupId == $tmpGroupIds[$key+1])
					{
						$groupOptions="$groupOptions selected";
					}
					$groupOptions=$groupOptions.">".$subGroupId."</option>";
					echo $groupOptions;
				}
				echo     '</select>';
				if (isset($mapId))
				{
					echo     '<input type="hidden" name="mapId" value="'.$mapId.'">';
				}
				echo     '<button type="submit" class="headButton" name="groupButton" value="get">Hämta</button>';
				echo   '</form>';
				echo '</div>';
				echo '</th>';
				$style="border-left:dashed 1px lightgray; padding-left:0.3vw";
			}
			
			if (!empty(trim($group['layers'], '{}')))
			{
				echo '<th style="'.$style.'">';
				if (isset($controlId) || isset($footerId) || $groupId != end($tmpGroupIds))
				{
					echo '<h3 style="color:lightgray">Redigera lager</h3>';
				}
				else
				{
					echo '<h3>Redigera lager</h3>';
				}
				echo '<div style="display:flex;">';
				echo   '<form class="headForm" method="post">';
				echo     '<select onchange="this.form.submit()" class="headSelect" name="layerId">';
				printSelectOptions(pgArrayToPhp($group['layers']), $layerId);
				echo     '</select>';
				if (isset($mapId))
				{
					echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
				}
				if (!empty($tmpGroupIds))
				{
					$splicedTmpGroupIds=$tmpGroupIds;
					if (isset($mapId))
					{
						array_splice($splicedTmpGroupIds, $level);
					}
					else
					{
						array_splice($splicedTmpGroupIds, $level+1);
					}
					echo '<input type="hidden" name="groupIds" value="'.implode(',', $splicedTmpGroupIds).'">';
				}
			  	echo     '<button type="submit" class="headButton" name="layerButton" value="get">Hämta</button>';
				echo   '</form>';
				echo '</div>';
				echo '</th>';
				$style="border-left:dashed 1px lightgray; padding-left:0.3vw";
			}
			if (!empty($group['footer']))
			{
				echo '<th style="'.$style.'">';
				if ($groupId != end($tmpGroupIds) || ((isset($layerId) || isset($controlId)) && $groupId == end($tmpGroupIds)))
				{
					echo '<h3 style="color:lightgray">Redigera sidfot</h3>';
				}
				else
				{
					echo '<h3>Redigera sidfot</h3>';
				}
				echo '<div style="display:flex;">';
				echo   '<form class="headForm" method="post">';
				echo     '<select onchange="this.form.submit()" class="headSelect" name="footerId">';
				$controlOptions="<option value='".$group['footer']."'";
				$controlOptions=$controlOptions.">".$group['footer']."</option>";
				echo $controlOptions;
				echo     '</select>';
				if (isset($mapId))
				{
					echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
				}
				if (!empty($tmpGroupIds))
				{
					$splicedTmpGroupIds=$tmpGroupIds;
					if (isset($mapId))
					{
						array_splice($splicedTmpGroupIds, $level);
					}
					else
					{
						array_splice($splicedTmpGroupIds, $level+1);
					}
					echo '<input type="hidden" name="groupIds" value="'.implode(',', $splicedTmpGroupIds).'">';
				}
			  	echo     '<button type="submit" class="headButton" name="footerButton" value="get">Hämta</button>';
				echo   '</form>';
				echo '</div>';
				echo '</th>';
			}
			echo   '</tr>';
			echo '</table>';
			echo '<hr>';
			$level++;
		}
	}

	//  Om kontroll vald

	if (isset($controlId) && in_array($controlId, array_column($controls, 'control_id')))
	{
		$control=array_column_search($controlId, 'control_id', $controls);
		echo '<div style="display:flex">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$controlId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$controlId.'Id" name="updateId">'.$controlId.'</textarea>&nbsp;';
		echo      '<label for="'.$controlId.'Options">Inställningar:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$controlId.'Options" name="updateOptions">'.$control['options'].'</textarea>&nbsp;';
		echo	  '</br>';
		echo      '<label for="'.$controlId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$controlId.'Info" name="updateInfo">'.$control['info'].'</textarea>&nbsp;';
		echo      '<input type="hidden" name="controlId" value="'.$controlId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
  		echo        '<button class="updateButton" type="submit" name="controlButton" value="update">Uppdatera</button>';
		echo   '</form>';
		infoButton('control');
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera kontrollen $controlId? Referenser till kontrollen hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="controlId" value="'.$controlId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo     "<button class='deleteButton1' type='submit' name='controlButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
		echo         '<form class="addForm" method="post">';
		echo         '<select class="headSelect" name="toMapId">';
		selectOptions('maps', false);
		echo         '</select>';
		echo '<input type="hidden" name="controlId" value="'.$controlId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo           '<button type="submit" name="controlButton" value="add">Lägg till i karta</button>';
		echo         '</form>';
		$mapParents=findParents('map', 'control', $controlId);
		if (!empty($mapParents))
		{
			echo '<form class="addForm" method="post">';
			echo   '<select class="headSelect" name="fromMapId">';
			printSelectOptions($mapParents);
			echo   '</select>';
			echo   '<input type="hidden" name="controlId" value="'.$controlId.'">';
			echo $hiddenInputs;
			echo   '<button type="submit" name="controlButton" value="remove">Ta bort från karta</button>';
			echo '</form>';
		}
	}

	//  Om sidfot vald

	elseif (isset($footerId) && in_array($footerId, array_column($footers, 'footer_id')))
	{
		$footer=array_column_search($footerId, 'footer_id', $footers);
		echo '<div style="display:flex;">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$footerId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$footerId.'Id" name="updateId">'.$footerId.'</textarea>&nbsp;';

		echo      '<label for="'.$footerId.'Img">Logotyp:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$footerId.'Img" name="updateImg">'.$footer['img'].'</textarea>&nbsp;';
		echo      '<label for="'.$footerId.'Url">Url:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$footerId.'Url" name="updateUrl">'.$footer['url'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$footerId.'Text">Text:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$footerId.'Text" name="updateText">'.$footer['text'].'</textarea>&nbsp;';
		echo      '<label for="'.$footerId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$footerId.'Info" name="updateInfo">'.$footer['info'].'</textarea>&nbsp;';
		echo      '<input type="hidden" name="footerId" value="'.$footerId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="footerButton" value="update">Uppdatera</button>';
		infoButton('footer');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera sidfoten $footerId? Referenser till sidfoten hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="footerId" value="'.$footerId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo     "<button class='deleteButton2' type='submit' name='footerButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
	}

	//  Om lager vald

	elseif (isset($layerId) && in_array($layerId, array_column($layers, 'layer_id')))
	{
		$layer=array_column_search($layerId, 'layer_id', $layers);
		echo '<div style="display:flex;">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$layerId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Id" name="updateId">'.$layerId.'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Title">Titel:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Title" name="updateTitle">'.$layer['title'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Source">Källa:</label>';
		echo      '<input type="text" list="sourcelist" class="bodySelect" id="'.$layerId.'Source" name="updateSource" value="'.$layer['source'].'" onfocus="this.value='."''".'" />';
		echo      '<datalist id="sourcelist">';
		printSelectOptions(array_merge(array(""), array_column($sources, 'source_id')), $layer['source']);
		echo      '</datalist>&nbsp;';
		echo      '<label for="'.$layerId.'Type">Typ:</label>';
		echo         '<select class="miniSelect" id="'.$layerId.'Type" name="updateType">';
		printSelectOptions(array("WMS", "WFS", "OSM", "GEOJSON", "GROUP", "WMTS"), $layer['type']);
		echo         '</select>&nbsp;';
		if ($layer['type'] == 'WFS')
		{
			echo '<label for="'.$layerId.'Editable">Redigerbar:</label>';
			echo '<select class="miniSelect" id="'.$layerId.'Editable" name="updateEditable">';
			printSelectOptions(array("", "f", "t"), $layer['editable']);
			echo '</select>&nbsp;';
		}
		elseif ($layer['type'] == 'WMS')
		{
			echo '<label for="'.$layerId.'Tiled">Tiled:</label>';
			echo '<select class="miniSelect" id="'.$layerId.'Tiled" name="updateTiled">';
			printSelectOptions(array("", "f", "t"), $layer['tiled']);
			echo '</select>&nbsp;';
		}
		echo      '<label for="'.$layerId.'Queryable">Klickbar:</label>';
		echo         '<select class="miniSelect" id="'.$layerId.'Queryable" name="updateQueryable">';
		printSelectOptions(array("", "f", "t"), $layer['queryable']);
		echo         '</select>&nbsp;';
		echo      '<label for="'.$layerId.'Visible">Synlig:</label>';
		echo         '<select class="miniSelect" id="'.$layerId.'Visible" name="updateVisible">';
		printSelectOptions(array("", "f", "t"), $layer['visible']);
		echo         '</select>&nbsp;';
		echo      '<label for="'.$layerId.'Opacity">Opacitet:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$layerId.'Opacity" name="updateOpacity">'.$layer['opacity'].'</textarea>&nbsp;';
		echo      '<br>';
		$layerService=array_column_search($layer['source'], 'source_id', $sources)['service'];
		if ($layerService == 'restricted')
		{
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'>&nbsp;";
			echo '<label for="'.$layerId.'Adusers">Användare:</label>';
			echo '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Adusers" name="updateAdusers">'.trim($layer['adusers'], '{}').'</textarea>&nbsp;';
			echo '<label for="'.$layerId.'Adgroups">Grupper:</label>';
			echo '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Adgroups" name="updateAdgroups">'.trim($layer['adgroups'], '{}').'</textarea>&nbsp;';
			echo "<img src='../img/png/lock_yellow.png' alt='Skyddat lager' title='Skyddat lager'>";
			echo '<br>';
		}
		echo      '<label for="'.$layerId.'Icon">Ikon:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Icon" name="updateIcon">'.$layer['icon'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Icon_extended">Utfälld ikon:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Icon_extended" name="updateIcon_extended">'.$layer['icon_extended'].'</textarea>&nbsp;';
		echo      '<br>';
		if ($layer['type'] == 'WMS')
		{
			echo '<label for="'.$layerId.'Format">Format:</label>';
			echo '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Format" name="updateFormat">'.$layer['format'].'</textarea>&nbsp;';
			echo '<label for="'.$layerId.'Featureinfolayer">FeatureInfo-lager:</label>';
			echo '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Featureinfolayer" name="updateFeatureinfolayer">'.$layer['featureinfolayer'].'</textarea>&nbsp;';
		}
		echo      '<label for="'.$layerId.'Stylefilter">Stilfilter:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Stylefilter" name="updateStylefilter">'.$layer['style_filter'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Style_config">Stilkonfiguration:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Style_config" name="updateStyle_config">'.$layer['style_config'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Attributes">Attribut:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Attributes" name="updateAttributes">'.$layer['attributes'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Abstract" name="updateAbstract">'.$layer['abstract'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Info" name="updateInfo">'.$layer['info'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Categories">Kategorier:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Categories" name="updateCategories">'.trim($layer['categories'], '{}').'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Attribution">Tillskrivning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Attribution" name="updateAttribution">'.$layer['attribution'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Maxscale">Maxskala:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$layerId.'Maxscale" name="updateMaxscale">'.$layer['maxscale'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Minscale">Minskala:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$layerId.'Minscale" name="updateMinscale">'.$layer['minscale'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Swiper">Swiper-lager:</label>';
		echo         '<select class="miniSelect" id="'.$layerId.'Swiper" name="updateSwiper">';
		printSelectOptions(array("", "f", "t", "under"), $layer['swiper']);
		echo         '</select>&nbsp;';
		if ($layer['type'] == 'GROUP')
		{
			echo '<label for="'.$layerId.'Layers">Lager:</label>';
			echo '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Layers" name="updateLayers">'.trim($layer['layers'], '{}').'</textarea>&nbsp;';
		}
		if ($layer['type'] == 'WFS')
		{
			echo '<label for="'.$layerId.'Layertype">WFS-typ:</label>';
			echo '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Layertype" name="updateLayertype">'.$layer['layertype'].'</textarea>&nbsp;';
			if ($layer['layertype'] == 'cluster')
			{
				echo '<label for="'.$layerId.'Clusterstyle">Klusterstil:</label>';
				echo '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Clusterstyle" name="updateClusterstyle">'.$layer['clusterstyle'].'</textarea>&nbsp;';
				echo '<br>';
				echo '<label for="'.$layerId.'Clusteroptions">Klusteralternativ:</label>';
				echo '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Clusteroptions" name="updateClusteroptions">'.$layer['clusteroptions'].'</textarea>&nbsp;';
			}
		}

		echo      '<input type="hidden" name="layerId" value="'.$layerId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="layerButton" value="update">Uppdatera</button>';
		infoButton('layer');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera lagret $layerId? Referenser till lagret hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="layerId" value="'.$layerId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		echo     "<button class='deleteButton6' type='submit' name='layerButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
		echo   '<form class="addForm" method="post">';
		echo     '<select class="headSelect" name="toMapId">';
		printSelectOptions(array_column($maps, 'map_id'));
		echo     '</select>';
		echo $hiddenInputs;
		echo         '<button type="submit" name="layerButton" value="add">Lägg till i karta</button>';
		echo       '</form>';
		echo       '<form class="addForm" method="post">';
		echo         '<select class="headSelect" name="toGroupId">';
		printSelectOptions(array_column($groups, 'group_id'));
		echo         '</select>';
		echo           '<input type="hidden" name="layerId" value="'.$layerId.'">';
		echo $hiddenInputs;
		echo         '<button type="submit" name="layerButton" value="add">Lägg till i grupp</button>';
		echo       '</form>';
		$mapParents=findParents('map', 'layer', $layerId);
		if (!empty($mapParents))
		{
			echo '<form class="addForm" method="post">';
			echo   '<select class="headSelect" name="fromMapId">';
			printSelectOptions($mapParents);
			echo   '</select>';
			echo   '<input type="hidden" name="layerId" value="'.$layerId.'">';
			echo $hiddenInputs;
			echo   '<button type="submit" name="layerButton" value="remove">Ta bort från karta</button>';
			echo '</form>';
		}
		$groupParents=findParents('group', 'layer', $layerId);
		if (!empty($groupParents))
		{
			echo '<form class="addForm" method="post">';
			echo   '<select class="headSelect" name="fromGroupId">';
			printSelectOptions($groupParents);
			echo   '</select>';
			echo   '<input type="hidden" name="layerId" value="'.$layerId.'">';
			echo $hiddenInputs;
			echo   '<button type="submit" name="layerButton" value="remove">Ta bort från grupp</button>';
			echo '</form>';
		}
		echo       '<table>';
		echo         '<tr>';
		echo           '<th>';
		echo             '<h3>Redigera källa</h3>';
		echo             '<div style="display:flex;">';
		echo               '<form class="headForm" method="post">';
		echo                 '<select onchange="this.form.submit()" class="headSelect" name="sourceId">';
		echo                   '<option value="'.$layer['source'].'">'.$layer['source'].'</option>';
		echo                 '</select>';
		echo                 '<input type="hidden" name="layerId" value="'.$layerId.'">';
		if (isset($mapId))
		{
			echo         '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo         '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		echo                 '<button type="submit" class="headButton" name="sourceButton" value="get">Hämta</button>';
		echo               '</form>';
		echo             '</div>';
		echo           '</th>';
		echo         '</tr>';
		echo       '</table>';
		echo       '<hr>';
	}

	//  Om källa vald

	if (isset($sourceId) && in_array($sourceId, array_column($sources, 'source_id')))
	{
		$source=array_column_search($sourceId, 'source_id', $sources);
		echo '<div style="display:flex;">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$sourceId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$sourceId.'Id" name="updateId">'.$sourceId.'</textarea>&nbsp;';
		echo      '<label for="'.$sourceId.'Service">Tjänst:</label>';
		echo         '<select class="bodySelect" id="'.$sourceId.'Service" name="updateService">';
		printSelectOptions(array_merge(array(""), array_column($services, 'service_id')), $source['service']);
		echo         '</select>&nbsp;';
		echo      '<label for="'.$sourceId.'Withgeometry">With_geometry:</label>';
		echo         '<select class="miniSelect" id="'.$sourceId.'Withgeometry" name="updateWithgeometry">';
		printSelectOptions(array("", "f", "t"), $source['with_geometry']);
		echo         '</select>&nbsp;';
		echo      '<label for="'.$sourceId.'Fipointtolerance">Fi_point_tolerance:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$sourceId.'Fipointtolerance" name="updateFipointtolerance">'.$source['fi_point_tolerance'].'</textarea>&nbsp;';
		echo      '<label for="'.$sourceId.'Ttl">Ttl:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$sourceId.'Ttl" name="updateTtl">'.$source['ttl'].'</textarea>&nbsp;';

		echo      '<label for="'.$sourceId.'Tilegrid">Tilegrid:</label>';
		echo         '<select class="bodySelect" id="'.$sourceId.'Tilegrid" name="updateTilegrid">';
		printSelectOptions(array_merge(array(""), array_column($tilegrids, 'tilegrid_id')), $source['tilegrid']);
		echo         '</select>&nbsp;';

		echo      '</br>';
		echo      '<label for="'.$sourceId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$sourceId.'Info" name="updateInfo">'.$source['info'].'</textarea>&nbsp;';
		echo      '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		if (isset($layerId))
		{
			echo '<input type="hidden" name="layerId" value="'.$layerId.'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="sourceButton" value="update">Uppdatera</button>';
		infoButton('source');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera källan $sourceId? Referenser till källan hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		if (isset($layerId))
		{
			echo '<input type="hidden" name="layerId" value="'.$layerId.'">';
		}
		echo     "<button class='deleteButton2' type='submit' name='sourceButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
		echo '<table>';
		echo   '<tr>';
		echo     '<th>';
		echo       '<h3>Redigera tjänst</h3>';
		echo       '<div style="display:flex;">';
		echo         '<form class="headForm" method="post">';
		echo           '<select onchange="this.form.submit()" class="headSelect" name="serviceId">';
		echo             '<option value="'.$source['service'].'">'.$source['service'].'</option>';
		echo           '</select>';
		echo           '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
		if (isset($layerId))
		{
			echo   '<input type="hidden" name="layerId" value="'.$layerId.'">';
		}
		if (isset($mapId))
		{
			echo   '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		echo           '<button type="submit" class="headButton" name="serviceButton" value="get" style="">Hämta</button>';
		echo         '</form>';
		echo       '</div>';
		echo     '</th>';

		if (!empty($source['tilegrid']))
		{
			echo     '<th>';
			echo       '<h3>Redigera tilegrid</h3>';
			echo       '<div style="display:flex;">';
			echo         '<form class="headForm" method="post">';
			echo           '<select onchange="this.form.submit()" class="headSelect" name="tilegridId">';
			echo             '<option value="'.$source['tilegrid'].'">'.$source['tilegrid'].'</option>';
			echo           '</select>';
			echo           '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
			if (isset($layerId))
			{
				echo   '<input type="hidden" name="layerId" value="'.$layerId.'">';
			}
			if (isset($mapId))
			{
				echo   '<input type="hidden" name="mapId" value="'.$mapId.'">';
			}
			if (!empty($tmpGroupIds))
			{
				echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
			}
			echo           '<button type="submit" class="headButton" name="tilegridButton" value="get" style="">Hämta</button>';
			echo         '</form>';
			echo       '</div>';
			echo     '</th>';
		}

		echo   '</tr>';
		echo '</table>';
		echo '<hr>';
	}

	//  Om tjänst vald

	if (isset($serviceId) && in_array($serviceId, array_column($services, 'service_id')))
	{
		$service=array_column_search($serviceId, 'service_id', $services);
		echo '<div style="display:flex;">';
		echo   '<form method="post">';
		echo      '<label for="'.$serviceId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$serviceId.'Id" name="updateId">'.$serviceId.'</textarea>&nbsp;';

		echo      '<label for="'.$serviceId.'Baseurl">Huvudurl:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$serviceId.'Baseurl" name="updateBaseurl">'.$service['base_url'].'</textarea>&nbsp;';
		echo      '</br>';
		echo      '<label for="'.$serviceId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$serviceId.'Info" name="updateInfo">'.$service['info'].'</textarea>&nbsp;';
		echo      '<input type="hidden" name="serviceId" value="'.$serviceId.'">';
		if (isset($sourceId))
		{
			echo      '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
		}
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		if (isset($layerId))
		{
			echo '<input type="hidden" name="layerId" value="'.$layerId.'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="serviceButton" value="update">Uppdatera</button>';
		infoButton('service');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera tjänsten $serviceId? Referenser till tjänsten hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="serviceId" value="'.$serviceId.'">';
		if (isset($sourceId))
		{
			echo      '<input type="hidden" name="sourceId" value="'.$sourceId.'">';
		}
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		if (!empty($tmpGroupIds))
		{
			echo   '<input type="hidden" name="groupIds" value="'.implode(',', $tmpGroupIds).'">';
		}
		if (isset($layerId))
		{
			echo '<input type="hidden" name="layerId" value="'.$layerId.'">';
		}
		echo     "<button class='deleteButton2' type='submit' name='serviceButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
	}

	//  Om tilegrid vald

	if (isset($tilegridId) && in_array($tilegridId, array_column($tilegrids, 'tilegrid_id')))
	{
		$tilegrid=array_column_search($tilegridId, 'tilegrid_id', $tilegrids);
		echo '<div style="display:flex;">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$tilegridId.'Id">Id:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$tilegridId.'Id" name="updateId">'.$tilegridId.'</textarea>&nbsp;';

		echo      '<label for="'.$tilegridId.'Tilesize">Tile-storlek:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$tilegridId.'Tilesize" name="updateTilesize">'.$tilegrid['tilesize'].'</textarea>&nbsp;';

		echo      '<label for="'.$tilegridId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$tilegridId.'Info" name="updateInfo">'.$tilegrid['info'].'</textarea>&nbsp;';
		echo      '<input type="hidden" name="tilegridId" value="'.$tilegridId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="tilegridButton" value="update">Uppdatera</button>';
		infoButton('tilegrid');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera tilegriden $tilegridId? Referenser till tilegriden hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="tilegridId" value="'.$tilegridId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo     "<button class='deleteButton2' type='submit' name='tilegridButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
	}

	//  Om proj4def vald

	if (isset($proj4defId) && in_array($proj4defId, array_column($proj4defs, 'code')))
	{
		$proj4def=array_column_search($proj4defId, 'code', $proj4defs);
		echo '<div style="display:flex;">';
		echo   '<form method="post" style="line-height:2">';
		echo      '<label for="'.$proj4defId.'Code">Kod:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$proj4defId.'Code" name="updateCode">'.$proj4defId.'</textarea>&nbsp;';

		echo      '<label for="'.$proj4defId.'Projection">Projektion:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$proj4defId.'Projection" name="updateProjection">'.$proj4def['projection'].'</textarea>&nbsp;';

		echo      '<label for="'.$proj4defId.'Alias">Alias:</label>';
		echo      '<textarea rows="1" class="textareaMedium" id="'.$proj4defId.'Alias" name="updateAlias">'.$proj4def['alias'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$proj4defId.'Info">Info:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$proj4defId.'Info" name="updateInfo">'.$proj4def['info'].'</textarea>&nbsp;';

		echo      '<input type="hidden" name="proj4defId" value="'.$proj4defId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo      '<div class="buttonDiv">';
  		echo        '<button class="updateButton" type="submit" name="proj4defButton" value="update">Uppdatera</button>';
		infoButton('proj4def');
		echo      '</div>';
		echo   '</form>';
		echo   "<form method='post' onsubmit='confirmStr=\"Är du säker att du vill radera proj4def $proj4defId? Referenser till aktuell proj4def hanteras separat.\"; return confirm(confirmStr);' style='line-height:2'>";
		echo      '<input type="hidden" name="proj4defId" value="'.$proj4defId.'">';
		if (isset($mapId))
		{
			echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
		}
		echo     "<button class='deleteButton2' type='submit' name='proj4defButton' value='delete'>Radera</button>";
		echo   '</form>';
		echo '</div>';
	}

?>
<script>
	updateSelect("layerCategories", categories);
	<?php
		if (isset($_POST['layerCategories']))
		{
			echo 'document.getElementById("layerCategories").value="'.$_POST['layerCategories'].'";';
			echo 'updateSelect("layerSelect", '.$_POST['layerCategories'].');';
			echo 'document.getElementById("layerSelect").value="'.$layerId.'";';

		}
	?>
</script>
</body>
</html>
