<!DOCTYPE html>
<html style="width:100%;height:100%">
<head>
	<style>
		<?php include("./styles/info.css"); ?>
	</style>
</head>
<body>
<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");

	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/pgArrayToPhp.php");
	include_once("./functions/array_column_search.php");
	include_once("./functions/all_from_table.php");
	include_once("./functions/findParents.php");
	include_once("./functions/pkColumnOfTable.php");
	include_once("./functions/includeDirectory.php");
	includeDirectory("./functions/info");
	
	$dbh=dbh(CONNECTION_STRING);
	$configSchema='map_configs';
	$childType=$_GET['type'];
	$childId=$_GET['id'];
	if     ($childType == 'map') { $childTypeSv='karta'; }
	elseif ($childType == 'control') { $childTypeSv='kontroll'; }
	elseif ($childType == 'group') { $childTypeSv='grupp'; }
	elseif ($childType == 'layer') { $childTypeSv='lager'; }
	elseif ($childType == 'footer') { $childTypeSv='sidfot'; }
	elseif ($childType == 'source') { $childTypeSv='källa'; }
	elseif ($childType == 'service') { $childTypeSv='tjänst'; }
	else { $childTypeSv=$childType; }

	if (!empty($childId))
	{
		echo "<div style='float:left'>";
		echo "<h2>$childId</h2> ($childTypeSv)</br>";
		$allOfChildType=all_from_table($dbh, $configSchema, $childType.'s');
		$child=array($childType=>$childId);
		$info=array_column_search($childId, pkColumnOfTable($childType.'s'), $allOfChildType)['info'];
		if (!empty($info))
		{
			echo "$info</br>";
		}
		if ($childType != 'map')
		{
 			echo "<h3>Används av</h3></br>";
			echo "&nbsp;&nbsp;&nbsp;";
			if ($childType == 'group' || $childType == 'layer')
			{
				printParents(array('maps'=>all_from_table($dbh, $configSchema, 'maps')), $child);
				echo "&nbsp;&nbsp;&nbsp;";
				printParents(array('groups'=>all_from_table($dbh, $configSchema, 'groups')), $child);
			}
			elseif ($childType == 'control' || $childType == 'footer' || $childType == 'proj4def')
			{
				printParents(array('maps'=>all_from_table($dbh, $configSchema, 'maps')), $child);
			}
			elseif ($childType == 'source')
			{
				printParents(array('layers'=>all_from_table($dbh, $configSchema, 'layers')), $child);
			}
			elseif ($childType == 'service')
			{
				printParents(array('sources'=>all_from_table($dbh, $configSchema, 'sources')), $child);
			}
			elseif ($childType == 'tilegrid')
			{
				printParents(array('sources'=>all_from_table($dbh, $configSchema, 'sources')), $child);
			}
		}
		echo '</div>';
		if (strpos($_SERVER['HTTP_REFERER'], 'manage') === false)
		{
			echo '<button onclick="history.back()">Tillbaks</button>';
		}
	}
?>
</body>
</html>
