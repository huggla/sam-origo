<!DOCTYPE html>
<html style="width:100%;height:100%">
<head>
	<style>
		<?php include("./styles/info"); ?>
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
	$functionFiles = array_diff(scandir('./functions/info'), array('.', '..'));
	foreach ($functionFiles as $functionFile)
	{
		include_once("./functions/info/$functionFile");
	}

	$dbh=dbh(CONNECTION_STRING);
	$childType=$_GET['type'];
	$child=$_GET['id'];
	if     ($childType == 'map') { $childTypeSv='karta'; }
	elseif ($childType == 'control') { $childTypeSv='kontroll'; }
	elseif ($childType == 'group') { $childTypeSv='grupp'; }
	elseif ($childType == 'layer') { $childTypeSv='lager'; }
	elseif ($childType == 'footer') { $childTypeSv='sidfot'; }
	elseif ($childType == 'source') { $childTypeSv='källa'; }
	elseif ($childType == 'service') { $childTypeSv='tjänst'; }

	if (!empty($child))
	{
		echo "<div style='float:left'>";
		echo "<h2>$child</h2> ($childTypeSv)</br>";
		$allOfChildType=all_from_table('map_configs.'.$childType.'s');
		$abstract=array_column_search($child, $childType.'_id', $allOfChildType)['abstract'];
		if (!empty($abstract))
		{
			echo "$abstract</br>";
		}
		if ($childType != 'map')
		{
 			echo "<h3>Används av</h3></br>";
			echo "&nbsp;&nbsp;&nbsp;";
			if ($childType == 'group' || $childType == 'layer')
			{
				echo findParents('map', $childType, $child);
				echo "&nbsp;&nbsp;&nbsp;";
				echo findParents('group', $childType, $child);
			}
			elseif ($childType == 'control' || $childType == 'footer')
			{
				echo findParents('map', $childType, $child);
			}
			elseif ($childType == 'source')
			{
				echo findParents('layer', $childType, $child);
			}
			elseif ($childType == 'service')
			{
				echo findParents('source', $childType, $child);
			}
		}
		echo '</div>';
		if (strpos($_SERVER['HTTP_REFERER'], 'manage') === false)
		{
			//echo '</br>';
			echo '<button onclick="history.back()">Tillbaks</button>';
		}
	}
?>
</body>
</html>
