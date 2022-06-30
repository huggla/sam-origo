<!DOCTYPE html>
<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");

if (empty($_POST['json']))
{
$importid=uniqid();
			echo   '<form method="post" style="line-height:2">';
			echo      '<label for="json">Json:</label>';
			echo      '<textarea rows="1" id="json" name="json"></textarea>&nbsp;<br>';
			echo      '<label for="importid">Unikt import-id:</label>';
			echo      '<textarea rows="1" id="importid" name="importid">'.$importid.'</textarea>&nbsp;<br>';
			echo      '<label for="layers">Lager:</label>';
			echo      '<input type="checkbox" id="layers" name="layers" value="yes" checked>&nbsp;<br>';
			echo      '<label for="groups">Grupper:</label>';
			echo      '<input type="checkbox" id="groups" name="groups" value="yes" checked>&nbsp;<br>';
			echo      '<label for="map">Karta:</label>';
			echo      '<input type="checkbox" id="map" name="map" value="yes" checked>&nbsp;';
			echo      '<label for="mapid">Namn:</label>';
			echo      '<textarea rows="1" id="mapid" name="mapid">map#'.$importid.'</textarea>&nbsp;<br>';
			echo      '<label for="controls">Kontroller:</label>';
			echo      '<input type="checkbox" id="controls" name="controls" value="yes" checked>&nbsp;<br>';
			echo      '<label for="footers">Sidfötter:</label>';
			echo      '<input type="checkbox" id="footers" name="footers" value="yes" checked>&nbsp;<br>';
			echo      '<label for="proj4defs">proj4defs:</label>';
			echo      '<input type="checkbox" id="proj4defs" name="proj4defs" value="yes" checked>&nbsp;<br>';
			echo      '<label for="sources">Källor:</label>';
			echo      '<input type="checkbox" id="sources" name="sources" value="yes" checked>&nbsp;<br>';
			echo      '<label for="tilegrids">Tilegrids:</label>';
			echo      '<input type="checkbox" id="tilegrids" name="tilegrids" value="yes" checked>&nbsp;<br>';
			echo      '<label for="styles">Stilar:</label>';
			echo      '<input type="checkbox" id="styles" name="styles" value="yes" checked>&nbsp;<br>';

			echo      '<label for="services">Tjänster:</label>';
			echo      '<input type="checkbox" id="services" name="services" value="yes" checked>&nbsp;<br>';

			echo     "<button type=\"submit\" name=\"submit\" value=\"submit\">";
			echo       'Importera';
			echo     '</button>';
			echo   '</form>';
			exit;
}


	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/pgArrayToPhp.php");
	include_once("./functions/array_column_search.php");
	include_once("./functions/all_from_table.php");
	include_once("./functions/findParents.php");
	$functionFiles = array_diff(scandir('./functions/manage'), array('.', '..'));
	foreach ($functionFiles as $functionFile)
	{
		include_once("./functions/manage/$functionFile");
	}
	$dbh=dbh(CONNECTION_STRING);

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

$importId=$_POST['importid'];
$json=$_POST['json'];

//$json=file_get_contents('../../index.json');
//var_dump($controls);
//var_dump($json);
$json_arr=json_decode($json,true,512,JSON_BIGINT_AS_STRING);
$jsonControls=$json_arr['controls'];
$jsonLayers=$json_arr['layers'];
$jsonStyles=$json_arr['styles'];
$jsonPageSettings=$json_arr['pageSettings'];
$jsonMapGrid=$jsonPageSettings['mapGrid'];
$jsonFooter=$jsonPageSettings['footer'];
$jsonProjectionCode=$json_arr['projectionCode'];
$jsonProjectionExtent=$json_arr['projectionExtent'];
$jsonFeatureinfoOptions=$json_arr['featureinfoOptions'];
$jsonProj4Defs=$json_arr['proj4Defs'];
$jsonExtent=$json_arr['extent'];
$jsonCenter=$json_arr['center'];
$jsonZoom=$json_arr['zoom'];
$jsonGroups=array();
foreach ($json_arr['groups'] as $group)
{
	if (stripos($group['name'], 'background') === 0)
	{
		$backgroundGroup=$group;
	}
	else
	{
		$jsonGroups[]=$group;
	}
}
if (!empty($backgroundGroup))
{
	$jsonGroups[]=$backgroundGroup;
}
preg_match('/(.*)("source": *{.*)/s', $json, $matches);
$jsonPreSource=$matches[1].'}';


$jsonSource_str='{'.$matches[2];
$jsonSource=$json_arr['source'];
$jsonServices=array();
$jsonTilegrids=array();
$serviceCount=1;
$tilegridCount=1;
foreach ($jsonSource as $sourceId => $source)
{
	if ($_POST['tilegrids'] == 'yes' && !empty($source['tileGrid']))
	{
		$pregStr='/(.*)("'.$sourceId.'": *{.*)/s';
		preg_match($pregStr, $jsonSource_str, $matches);
		$jsonSource_str=$matches[2];
		if (preg_match('/"resolutions": *\[([^\]]*)\]/', $jsonSource_str, $matches))
		{
			$source['tileGrid']['resolutions']='{'.$matches[1].'}';
		}
		if (!in_array($source['tileGrid'], $jsonTilegrids, true))
		{
			$tilegridId="tilegrid$tilegridCount#$importId";
			$jsonTilegrids[$tilegridId]=$source['tileGrid'];
			$sql="INSERT INTO map_configs.tilegrids(tilegrid_id, tilesize, resolutions) VALUES ('$tilegridId', '".$source['tileGrid']['tileSize']."', '".$source['tileGrid']['resolutions']."')";
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			$tilegridCount++;

		}
	}

	if (strpos($source['url'], "?") !== false)
	{
		parse_str(substr($source['url'], strpos($source['url'], "?") + 1), $urlQuery);
		$source['url']=substr($source['url'], 0, strpos($source['url'], "?"));
	}
	if ($_POST['services'] == 'yes' && !in_array($source['url'], $jsonServices))
	{
		$jsonServices["service$serviceCount"]=$source['url'];
		$source['service']=array_search($source['url'], $jsonServices)."#$importId";
		$sql="INSERT INTO map_configs.services(service_id, base_url) VALUES ('".$source['service']."', '".$source['url']."')";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		$serviceCount++;
	}
	if ($_POST['sources'] == 'yes')
	{
		$sourceColumns='source_id, service, tilegrid';
		$sourceValues="'".$sourceId."#$importId', '".$source['service']."', '$tilegridId'";
		if (!empty($urlQuery['with_geometry']))
		{
			$sourceColumns=$sourceColumns.',with_geometry';
			$sourceValues=$sourceValues.",'".$urlQuery['with_geometry']."'";
		}
		if (!empty($urlQuery['fi_point_tolerance']))
		{
			$sourceColumns=$sourceColumns.',fi_point_tolerance';
			$sourceValues=$sourceValues.",'".$urlQuery['fi_point_tolerance']."'";
		}
		$sql="INSERT INTO map_configs.sources($sourceColumns) VALUES ($sourceValues)";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
	}

}

if (preg_match('/"resolutions": *\[([^\]]*)\]/', $jsonPreSource, $matches))
{
	$jsonResolutions='{'.$matches[1].'}';
}

$mapControls=array();
if ($_POST['controls'] == 'yes')
{
	foreach ($jsonControls as $control)
	{
		$mapControls[]=$control['name']."#$importId";
		$sql="INSERT INTO map_configs.controls(control_id, options) VALUES ('".$control['name']."#$importId', ".pg_escape_literal(json_encode($control['options'], JSON_PRETTY_PRINT)).")";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
	}
}

function renamedup($name, $count=0)
{
	GLOBAL $uniqueLayers;
	if ($count > 0)
	{
		$newname="$name$count";
	}
	else
	{
		$newname=$name;
	}
	if (in_array($newname, $uniqueLayers))
	{
		$count++;
		return renamedup($name, $count);
	}
	else
	{
		$uniqueLayers[]=$newname;
		return $newname;
	}
}

$uniqueLayers=array();
$mapLayers=array();
$groupsLayers=array();
if ($_POST['layers'] == 'yes')
{
	foreach ($jsonLayers as $layer)
	{
		$layer['name']=renamedup($layer['name']);
		if ($_POST['styles'] == 'yes')
		{
			$layerStyle=$jsonStyles[$layer['style']];

			if (count($layerStyle[0]) > 1)
			{
				$layerStyleConfig=json_encode($layerStyle, JSON_PRETTY_PRINT);
				$layerIcon='';
				$layerStyleFilter='';
			}
			else
			{
				if (!empty($layerStyle[0][0]['icon']['src']))
				{
					$layerStyleConfig='[]';
					$layerIcon=$layerStyle[0][0]['icon']['src'];
					$layerStyleFilter=$layerStyle[0][0]['filter'];
				}
				elseif (!empty($layerStyle[0][0]['image']['src']))
				{
					$layerStyleConfig='[]';
					$layerIcon=$layerStyle[0][0]['image']['src'];
					$layerStyleFilter='';
				}
				else
				{
					$layerStyleConfig=json_encode($layerStyle, JSON_PRETTY_PRINT);
					$layerIcon='';
					$layerStyleFilter='';
				}

			}

		}
		if (empty($layer['group']))
		{
			$mapLayers[]=$layer['name']."#$importId";
		}
		else
		{
			if (is_array($groupsLayers[$layer['group']]))
			{
				$groupsLayers[$layer['group']][]=$layer['name']."#$importId";
			}
			else
			{
				$groupsLayers[$layer['group']]=array($layer['name']."#$importId");
			}
		}
		if (!empty($layer['queryable']))
		{
			$layerQueryable=var_export($layer['queryable'], true);
		}
		else
		{
			$layerQueryable='true';
		}
		if (empty($layer['opacity']))
		{
			$layer['opacity']=1;
		}
		if (!empty($layer['visible']))
		{
			$layerVisible=var_export($layer['visible'], true);
		}
		else
		{
			$layerVisible='false';
		}
		$sql="INSERT INTO map_configs.layers(layer_id, title, format, type, attributes, abstract, queryable, featureinfolayer, opacity, visible, source, style_config, icon, style_filter) VALUES ('".$layer['name']."#$importId', '".$layer['title']."', '".$layer['format']."', '".$layer['type']."', ".pg_escape_literal(json_encode($layer['attributes'], JSON_PRETTY_PRINT)).", ".pg_escape_literal(str_replace(array('"'), '\"', str_replace(array("\r\n", "\r", "\n"), "<br />", $layer['abstract']))).", '$layerQueryable', '".$layer['featureinfolayer']."', '".$layer['opacity']."', '$layerVisible', '".$layer['source']."#$importId', ".pg_escape_literal($layerStyleConfig).", '$layerIcon', ".pg_escape_literal($layerStyleFilter).")";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			var_dump($sql);
			die("Error in SQL query: " . pg_last_error());
		}
	}
}

function recursiveGroups($groupsArr)
{
	GLOBAL $dbh, $groups, $importId, $groupsLayers;
	$parentGroups=array();
	foreach ($groupsArr as $group)
	{
		$parentGroups[]=$group['name']."#$importId";
		if (!empty($group['groups']))
		{
			$childGroups=recursiveGroups($group['groups']);
		}
		else
		{
			$childGroups=array();
		}
		if (!empty($group['expanded']))
		{
			$groupExpanded=var_export($group['expanded'], true);
		}
		else
		{
			$groupExpanded='false';
		}
		$sql="INSERT INTO map_configs.groups(group_id, title, expanded, abstract, groups, layers) VALUES ('".$group['name']."#$importId', '".$group['title']."', '$groupExpanded', ".pg_escape_literal(str_replace(array('"'), '\"', str_replace(array("\r\n", "\r", "\n"), "<br />", $group['abstract']))).", '{".implode(',', $childGroups)."}', '{".implode(',', $groupsLayers[$group['name']])."}')";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			var_dump($sql);
			die("Error in SQL query: $sql" . pg_last_error());
		}
	}
	return $parentGroups;
}

if ($_POST['groups'] == 'yes')
{
	$mapGroups=recursiveGroups($jsonGroups);
}

$mapProj4Defs=array();
foreach ($jsonProj4Defs as $def)
{
	$mapProj4Defs[]=$def['code'];
	if ($_POST['proj4defs'] == 'yes')
	{
		if (!in_array($def['code'], array_column($proj4defs, 'code')))
		{
			$sql="INSERT INTO map_configs.proj4defs(code, projection) VALUES ('".$def['code']."', '".$def['projection']."')";
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
		}
	}
}

if (!empty($jsonFooter))
{
	$mapFooter="footer#$importId";
	if ($_POST['footers'] == 'yes')
	{
		$sql="INSERT INTO map_configs.footers(footer_id, img, url, text) VALUES ('$mapFooter', '".$jsonFooter['img']."', '".$jsonFooter['url']."', '".$jsonFooter['text']."')";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
	}
}

if ($_POST['map'] == 'yes')
{
	if (!empty($json_arr['enableRotation']))
	{
		$jsonEnableRotation=var_export($json_arr['enableRotation'], true);
	}
	else
	{
		$jsonEnableRotation='false';
	}
	if (!empty($json_arr['constrainResolution']))
	{
		$jsonConstrainResolution=var_export($json_arr['constrainResolution'], true);
	}
	else
	{
		$jsonConstrainResolution='true';
	}
	$sql="INSERT INTO map_configs.maps(map_id, mapgrid, projectioncode, projectionextent, featureinfooptions, extent, center, zoom, enablerotation, constrainresolution, resolutions, controls, groups, layers, proj4defs, footer) VALUES ('".$_POST['mapid']."', '".var_export($jsonMapGrid['visible'], true)."', '$jsonProjectionCode', '(".$jsonProjectionExtent[0].",".$jsonProjectionExtent[1]."),(".$jsonProjectionExtent[2].",".$jsonProjectionExtent[3].")', '".json_encode($jsonFeatureinfoOptions, JSON_PRETTY_PRINT)."', '(".$jsonExtent[0].",".$jsonExtent[1]."),(".$jsonExtent[2].",".$jsonExtent[3].")', '(".$jsonCenter[0].",".$jsonCenter[1].")', '$jsonZoom', '$jsonEnableRotation', '$jsonConstrainResolution', '$jsonResolutions', '{".implode(',', $mapControls)."}', '{".implode(',', $mapGroups)."}', '{".implode(',', $mapLayers)."}', '{".implode(',', $mapProj4Defs)."}', '$mapFooter')";
	$result=pg_query($dbh, $sql);
	if (!$result)
	{
		die("Error in SQL query: " . pg_last_error());
	}
}

?>
