<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");

	/* SETTINGS */
	/************/
	$dbhConnectionString='host=localhost port=5432 dbname=origo user=postgres password=postgres';
	/************/

	include_once("./functions/common/dbh.php");
	include_once("./functions/common/pgArrayToPhp.php");
	$functionFiles = array_diff(scandir('./functions/manage'), array('.', '..'));
	foreach ($functionFiles as $functionFile)
	{
		include_once("./functions/manage/$functionFile");
	}
	$dbh=dbh($dbhConnectionString);
	if (!empty($_POST['mapId']))
	{ 
		$mapId=$_POST['mapId'];
	}
	$controlId=$_POST['controlId'];
	$layerId=$_POST['layerId'];
	$sourceId=$_POST['sourceId'];
	$serviceId=$_POST['serviceId'];
	$footerId=$_POST['footerId'];
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

	$newmapId=$_POST['newmapId'];
	$newcontrolId=$_POST['newcontrolId'];
	$newlayerId=$_POST['newlayerId'];
	$newsourceId=$_POST['newsourceId'];
	$newserviceId=$_POST['newserviceId'];
	$newgroupId=$_POST['newgroupId'];
	$newfooterId=$_POST['newfooterId'];

	$mapButton=$_POST['mapButton'];
	$groupButton=$_POST['groupButton'];
	$layerButton=$_POST['layerButton'];
	$controlButton=$_POST['controlButton'];
	$sourceButton=$_POST['sourceButton'];
	$serviceButton=$_POST['serviceButton'];
	$footerButton=$_POST['footerButton'];

var_dump($_POST);

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

	$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class="h3Focus";
	if (!isset($mapId) && (isset($_POST['groupIds']) || isset($layerId) || isset($controlId) || isset($sourceId) || isset($serviceId) || isset($footerId)))
	{
		if (isset($_POST['groupIds']) || isset($layerId) || isset($sourceId) || isset($serviceId) || isset($footerId))
		{
			if ((empty($groupIds) && isset($layerId)) || isset($controlId) || (empty($groupIds) && isset($sourceId)) || (empty($groupIds) && isset($serviceId)) || isset($footerId))
			{
				if (isset($_POST['groupIds']) || isset($controlId) || (!isset($layerId) && isset($sourceId)) || isset($serviceId) || isset($footerId))
				{
					if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || (!isset($sourceId) && isset($serviceId)) || isset($footerId))
					{
						if (isset($_POST['groupIds']) || isset($layerId) || isset($sourceId) || isset($serviceId) || isset($controlId))
						{
							if (isset($_POST['groupIds']) || isset($controlId) || isset($layerId) || isset($sourceId) || isset($footerId))
							{
							}
							else
							{
								$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class="h3NoFocus";
							}
						}
						else
						{
							$maph3class=$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$serviceh3class="h3NoFocus";
						}
					}
					else
					{
						$maph3class=$controlh3class=$grouph3class=$layerh3class=$footerh3class=$serviceh3class="h3NoFocus";
					}
				}
				else
				{
					$maph3class=$controlh3class=$grouph3class=$sourceh3class=$footerh3class=$serviceh3class="h3NoFocus";
				}
			}
			else
			{
				$maph3class=$controlh3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class="h3NoFocus";
			}
		}
		else
		{
			$maph3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class="h3NoFocus";
		}
	}
	elseif (isset($mapId))
	{
		$controlh3class=$grouph3class=$layerh3class=$sourceh3class=$footerh3class=$serviceh3class="h3NoFocus";
	}

/*
 *************************
 *  DATABAS-OPERATIONER  *
 *************************
*/
	$maps=all_from_table('map_configs.maps');
	$groups=all_from_table('map_configs.groups');
	$layers=all_from_table('map_configs.layers');
	$controls=all_from_table('map_configs.controls');
	$sources=all_from_table('map_configs.sources');
	$services=all_from_table('map_configs.services');
	$footers=all_from_table('map_configs.footers');

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
			$sql="UPDATE map_configs.maps SET layers = '{".$_POST['updateLayers']."}', groups = '{".$_POST['updateGroups']."}', controls = '{".$_POST['updateControls']."}', featureinfooptions = '".$_POST['updateFeatureinfooptions']."', center = '".$_POST['updateCenter']."', zoom = '".$_POST['updateZoom']."', footer = '".$_POST['updateFooter']."', abstract = '".$_POST['updateAbstract']."', map_id = '".$_POST['updateId']."' WHERE map_id = '$mapId'";
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
			$sql="UPDATE map_configs.controls SET options = '".$_POST['updateOptions']."', abstract = '".$_POST['updateAbstract']."', control_id = '".$_POST['updateId']."' WHERE control_id = '$controlId'";
		}
		elseif ($controlButton == 'add')
		{
			$toMap=array_column_search($toMapId, 'map_id', $maps);
			$toMapControls=explode(',', trim($toMap['controls'], '{}'));
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
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if ($controlButton == 'add')
			{
				$maps=all_from_table('map_configs.maps');
			}
			else
			{
				$controls=all_from_table('map_configs.controls');
			}
		}
	}

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
			$sql="UPDATE map_configs.footers SET img = '".$_POST['updateImg']."', url = '".$_POST['updateUrl']."', text = '".$_POST['updateText']."', abstract = '".$_POST['updateAbstract']."', footer_id = '".$_POST['updateId']."' WHERE footer_id = '$footerId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$footers=all_from_table('map_configs.footers');
	}

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
			if (!empty($_POST['updateDpi']))
			{
				$dpi="'".$_POST['updateDpi']."'";
			}
			else
			{
				$dpi="null";
			}
			$sql="UPDATE map_configs.sources SET service = '".$_POST['updateService']."', with_geometry = $withgeometry, fi_point_tolerance = $fipointtolerance, ttl = $ttl, dpi = $dpi, abstract = '".$_POST['updateAbstract']."', source_id = '".$_POST['updateId']."' WHERE source_id = '$sourceId'";
		}
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$sources=all_from_table('map_configs.sources');
	}

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
			$sql="UPDATE map_configs.services SET base_url = '".$_POST['updateBaseurl']."', abstract = '".$_POST['updateAbstract']."', service_id = '".$_POST['updateId']."' WHERE service_id = '$serviceId'";
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
			$sql="UPDATE map_configs.groups SET layers = '{".$_POST['updateLayers']."}', groups = '{".$_POST['updateGroups']."}', title = '".$_POST['updateTitle']."', abstract = '".$_POST['updateAbstract']."', expanded = '".$_POST['updateExpanded']."', group_id = '".$_POST['updateId']."' WHERE group_id = '".end($groupIds)."'";
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
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if ($groupButton == 'add' && isset($toMapId))
			{
				$maps=all_from_table('map_configs.maps');
			}
			else
			{
				$groups=all_from_table('map_configs.groups');
			}
		}
	}
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
				$attributesStr=", attributes = '".$_POST['updateAttributes']."'";
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
			if (!empty($_POST['updateFeatureinfolayer']))
			{
				$featureinfolayerStr=", featureinfolayer = '".$_POST['updateFeatureinfolayer']."'";
			}
			else
			{
				$featureinfolayerStr="";
			}
			$sql="UPDATE map_configs.layers SET title = '".$_POST['updateTitle']."', abstract = '".$_POST['updateAbstract']."', source = '".$_POST['updateSource']."', type = '".$_POST['updateType']."', queryable ='".$_POST['updateQueryable']."', visible = '".$_POST['updateVisible']."', icon = '".$_POST['updateIcon']."', icon_extended = '".$_POST['updateIcon_extended']."', style_filter = '".$_POST['updateStylefilter']."', layer_id = '".$_POST['updateId']."' $editableStr $tiledStr $attributesStr $featureinfolayerStr WHERE layer_id = '$layerId'";
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
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			if ($layerButton == 'add')
			{
				if (isset($toGroupId))
				{
					$groups=all_from_table('map_configs.groups');
				}
				elseif (isset($toMapId))
				{
					$maps=all_from_table('map_configs.maps');
				}
			}
			else
			{
				$layers=all_from_table('map_configs.layers');
			}
		}
	}

?>

<!DOCTYPE html>
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
	</script>
	<style>
		<?php include("styles/manage"); ?>
	</style>
</head>
<body>
	<iframe id="topFrame" name="topFrame" style="display:none"></iframe>
	<iframe id="hiddenFrame" name="hiddenFrame" style="display:none"></iframe>

<!--  REDIGERA KARTA  -->
	<table>
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
			<th class="thRight">
				<h3 class="<?php echo $serviceh3class; ?>">
					Redigera tjänst
				</h3>
				<?php headForm('service'); ?>
			</th>
		</tr>
	</table>
	<hr>
<?php
/*
 ************************
 *  DYNAMISKT INNEHÅLL  *
 ************************
*/
	$level=0;
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
				echo '<label for="'.$groupId.'Featureinfooptions">FeatureInfoOptions:</label>';
				echo '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Featureinfooptions" name="updateFeatureinfooptions">'.$group['featureinfooptions'].'</textarea>&nbsp;';
				echo '<br>';
				echo '<label for="'.$groupId.'Center">Mittpunkt:</label>';
				echo '<textarea rows="1" class="textareaMedium" id="'.$groupId.'Center" name="updateCenter">'.$group['center'].'</textarea>&nbsp;';
				echo '<label for="'.$groupId.'Zoom">Zoom:</label>';
				echo '<textarea rows="1" class="textareaXSmall" id="'.$groupId.'Zoom" name="updateZoom">'.$group['zoom'].'</textarea>&nbsp;';
				$childType="map";
				$deleteButtonClass="deleteButton3";
			}
				echo '<label for="'.$groupId.'Abstract">Sammanfattning:</label>';
				echo '<textarea rows="1" class="textareaLarge" id="'.$groupId.'Abstract" name="updateAbstract">'.$group['abstract'].'</textarea>&nbsp;';

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

			}
			echo '<div style="white-space:nowrap">';
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
		echo      '<label for="'.$controlId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$controlId.'Abstract" name="updateAbstract">'.$control['abstract'].'</textarea>&nbsp;';
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
	}
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
		echo      '<label for="'.$footerId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$footerId.'Abstract" name="updateAbstract">'.$footer['abstract'].'</textarea>&nbsp;';
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
		echo         '<select class="bodySelect" id="'.$layerId.'Source" name="updateSource">';
		printSelectOptions(array_merge(array(""), array_column($sources, 'source_id')), $layer['source']);
		echo         '</select>&nbsp;';
		echo      '<label for="'.$layerId.'Type">Typ:</label>';
		echo         '<select class="miniSelect" id="'.$layerId.'Type" name="updateType">';
		printSelectOptions(array("WMS", "WFS"), $layer['type']);
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
		echo      '<br>';
		echo      '<label for="'.$layerId.'Icon">Ikon:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Icon" name="updateIcon">'.$layer['icon'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Icon_extended">Utfälld ikon:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Icon_extended" name="updateIcon_extended">'.$layer['icon_extended'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Stylefilter">Stilfilter:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Stylefilter" name="updateStylefilter">'.$layer['style_filter'].'</textarea>&nbsp;';
		echo      '<label for="'.$layerId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Abstract" name="updateAbstract">'.$layer['abstract'].'</textarea>&nbsp;';
		echo      '<br>';
		echo      '<label for="'.$layerId.'Attributes">Attribut:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$layerId.'Attributes" name="updateAttributes">'.$layer['attributes'].'</textarea>&nbsp;';
		if ($layer['type'] == 'WMS')
		{
			echo      '<label for="'.$layerId.'Featureinfolayer">Info-lager:</label>';
			echo      '<textarea rows="1" class="textareaMedium" id="'.$layerId.'Featureinfolayer" name="updateFeatureinfolayer">'.$layer['featureinfolayer'].'</textarea>&nbsp;';
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
		echo     "<button class='deleteButton4' type='submit' name='layerButton' value='delete'>Radera</button>";
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
		echo      '<label for="'.$sourceId.'Dpi">Dpi:</label>';
		echo      '<textarea rows="1" class="textareaSmall" id="'.$sourceId.'Dpi" name="updateDpi">'.$source['dpi'].'</textarea>&nbsp;';
		echo      '</br>';
		echo      '<label for="'.$sourceId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$sourceId.'Abstract" name="updateAbstract">'.$source['abstract'].'</textarea>&nbsp;';
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
		echo   '</tr>';
		echo '</table>';
		echo '<hr>';
	}
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
		echo      '<label for="'.$serviceId.'Abstract">Sammanfattning:</label>';
		echo      '<textarea rows="1" class="textareaLarge" id="'.$serviceId.'Abstract" name="updateAbstract">'.$service['abstract'].'</textarea>&nbsp;';
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

?>
</body>
</html>
