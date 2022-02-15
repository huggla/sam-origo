<?php
	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/pgArrayToPhp.php");
	include_once("./functions/array_column_search.php");
	$functionFiles = array_diff(scandir('./functions/writeConfig'), array('.', '..'));
	foreach ($functionFiles as $functionFile)
	{
		include_once("./functions/writeConfig/$functionFile");
	}
	$mapId = $_GET['map'];
	$mapIdArray = explode('#', $mapId, 2);
	$mapName = trim($mapIdArray[0]);
	if (!empty($mapIdArray[1]))
	{
		$mapNumber = trim($mapIdArray[1]);
	}
	else
	{
		$mapNumber = '';
	}
	$configfile = '/origo/'.$mapName.$mapNumber.'.json';
	ignore_user_abort(true);
	$dbh=dbh(CONNECTION_STRING);
	$conftables = array(
		"maps",
		"controls",
		"footers",
		"proj4defs",
		"sources",
		"services",
		"groups",
		"layers"
	);
	foreach ($conftables as $table)
	{
		eval("\$$table=get_conftable(\$dbh, '$table');"); // Skapar variabel med samma namn som tabellen ($maps, $controls, osv).
	}
	$map = array_column_search($mapId, 'map_id', $maps);
	$json = '{ ';
	addControlsToJson();
	$json = $json.', ';

	// PageSettings <start>
	$json = $json.'"pageSettings": {';
	if (!empty($map['footer']))
	{
		$footer = array_column_search($map['footer'], 'footer_id', $footers);
		$json = $json.'"footer": { "img": "'.$footer['img'].'", "url" : "'.$footer['url'].'", "text": "'.$footer['text'].'" },';
	}
	$json = $json.'"mapGrid": { "visible": '.pgBoolToText($map['mapgrid']).' } },';
	// PageSettings </end>
	$json = $json.'"projectionCode": "'.$map['projectioncode'].'", ';
	$json = $json.'"projectionExtent": ['.pgBoxToText($map['projectionextent']).'], ';
	$json = $json.'"featureinfoOptions": '.$map['featureinfooptions'].', ';
	// Proj4Defs <start>
	$mapProj4defs = pgArrayToPhp($map['proj4defs']);
	$json = $json.'"proj4Defs": [';
	$firstProj4def = true;
	foreach ($mapProj4defs as $proj4def)
	{
		if ($firstProj4def)
		{
			$firstProj4def = false;
		}
		else
		{
			$json = $json.', ';
		}
		$proj4def = array_column_search($proj4def, 'code', $proj4defs);
		$json = $json.'{ "code": "'.$proj4def['code'].'", "projection": "'.$proj4def['projection'].'" }';
	}
	$json = $json.'], ';
	// Proj4Defs </end>
	$json = $json.'"extent": ['.pgBoxToText($map['extent']).'], ';
	$json = $json.'"center": ['.pgCoordsToText($map['center']).'], ';
	$json = $json.'"zoom": '.$map['zoom'].', ';
	$json = $json.'"enableRotation": '.pgBoolToText($map['enablerotation']).', ';
	$json = $json.'"constrainResolution": '.pgBoolToText($map['constrainresolution']).', ';
	$json = $json.'"resolutions": [ '.pgArrayToText($map['resolutions']).' ]';
	$mapLayers = array();
	addGroupsToJson($map['groups']);
	$json = $json.', ';
	addLayersToJson();
	$json = $json.' }';
	file_put_contents($configfile, json_format($json));
?>
