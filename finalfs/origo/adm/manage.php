<!DOCTYPE html>
<?php
	header("Cache-Control: must-revalidate, max-age=0, s-maxage=0, no-cache, no-store");
	include_once("./constants/CONNECTION_STRING.php");
	include_once("./functions/dbh.php");
	include_once("./functions/pgArrayToPhp.php");
	include_once("./functions/array_column_search.php");
	include_once("./functions/all_from_table.php");
	include_once("./functions/findParents.php");
	include_once("./functions/layerCategories.php");
	include_once("./functions/pkColumnOfTable.php");
	include_once("./functions/includeDirectory.php");
	includeDirectory("./functions/manage");
	$post=$_POST;
	unset($_POST);
	if (isset($post['groupIds']))
	{
		$groupIdsArray=explode(',', $post['groupIds']);
		if (!isset($post['groupId']))
		{
			$post['groupId']=$groupIdsArray[0];
		}
	}
	elseif (isset($post['groupId']))
	{
		$post['groupIds']=$post['groupId'];
		$groupIdsArray=array($post['groupId']);
	}
	else
	{
		$groupIdsArray=array();
	}
	//var_dump($post);
	$idPosts=array_filter($post, function($key) {return (substr($key, -2) == 'Id');}, ARRAY_FILTER_USE_KEY);
	unset($idPosts['fromMapId'], $idPosts['toMapId']);
	$focusTable=focusTable($idPosts);
	$dbh=dbh(CONNECTION_STRING);
	$configSchema='map_configs';
	$configTables=configTables($dbh, $configSchema);
	$layerCategories=layerCategories($configTables['layers']);
	$pressedButton=array_keys(array_filter($post, function($key) {return (substr($key, -6) == 'Button');}, ARRAY_FILTER_USE_KEY));
	if (isset($pressedButton[0]))
	{
		$pressedButton=$pressedButton[0];
	}
	else
	{
		unset($pressedButton);
	}
	if (isset($pressedButton))
	{
		$command=$post[$pressedButton];
		$sql="";
		if ($command == 'create' && !empty($post[substr($pressedButton, 0, -6).'IdNew']))
		{
			$target=array(substr($pressedButton, 0, -6) => $post[substr($pressedButton, 0, -6).'IdNew']);
			$targetTable=key($target).'s';
			$targetPkColumn=pkColumnOfTable($targetTable);
			if (!in_array(current($target), array_column($configTables[$targetTable], $targetPkColumn)))
			{
				$sql="INSERT INTO $configSchema.$targetTable($targetPkColumn) VALUES ('".current($target)."')";
			}
			unset($targetTable, $targetPkColumn);
		}
		elseif ($command == 'delete' && !empty($post[substr($pressedButton, 0, -6).'IdDel']))
		{
			$target=array(substr($pressedButton, 0, -6) => $post[substr($pressedButton, 0, -6).'IdDel']);
			$targetTable=key($target).'s';
			$targetPkColumn=pkColumnOfTable($targetTable);
			if (in_array(current($target), array_column($configTables[$targetTable], $targetPkColumn)))
			{
				$sql="DELETE FROM $configSchema.$targetTable WHERE $targetPkColumn = '".current($target)."'";
			}
			unset($targetTable, $targetPkColumn);
		}
		elseif (isset($post[substr($pressedButton, 0, -6).'Id']))
		{
			$target=array(substr($pressedButton, 0, -6) => $post[substr($pressedButton, 0, -6).'Id']);
			if ($command == 'update')
			{
				$updatePosts=array_filter($post, function($key) {return (substr($key, 0, 6) == 'update');}, ARRAY_FILTER_USE_KEY);
				$sql=sqlForUpdate($target, $configSchema, $updatePosts);
				unset($updatePosts);
			}
			elseif ($command == 'operation')
			{
				if (!empty($post['toMapId']) || !empty($post['fromMapId']))
				{
					$parentKey='map';
				}
				elseif (!empty($post['toGroupId']) || !empty($post['fromGroupId']))
				{
					$parentKey='group';
				}
				if (isset($parentKey))
				{
					if (!empty($post['toMapId']) || !empty($post['toGroupId']))
					{
						$operation='add';
						$parentPkColumnValue=$post['to'.ucfirst($parentKey).'Id'];
					}
					elseif (!empty($post['fromMapId']) || !empty($post['fromGroupId']))
					{
						$operation='remove';
						$parentPkColumnValue=$post['from'.ucfirst($parentKey).'Id'];
					}
					if (isset($operation))
					{
						$parentPkColumnKey=pkColumnOfTable($parentKey.'s');
						$parentOperationColumnKey=key($target).'s';
						$parentOperationColumnValue=array_column_search($parentPkColumnValue, $parentPkColumnKey, $configTables[$parentKey.'s'])[$parentOperationColumnKey];
						$operationParent=array($parentKey.'s'=>array($parentPkColumnKey=>$parentPkColumnValue, $parentOperationColumnKey=>$parentOperationColumnValue));
						$sql=sqlForOperation($operation, $target, $operationParent, $configSchema);
						unset($operation, $parentPkColumnValue, $parentPkColumnKey, $parentOperationColumnKey, $parentOperationColumnValue, $operationParent);
					}
					unset($parentKey);
				}
			}
		}
		if (!empty($sql))
		{
			$result=pg_query($dbh, $sql);
			if (!$result)
			{
				die("Error in SQL query: " . pg_last_error());
			}
			unset($result);
			$configTables=configTables($dbh, $configSchema);
			if ($command != 'operation' && key($target) == 'layer')
			{
				$layerCategories=layerCategories($configTables['layers']);
			}
		}
		unset($target, $command, $sql);
	}
	$inheritPosts=$idPosts;
	if (isset($post['groupIds']))
	{
		$inheritPosts['groupIds']=$post['groupIds'];
	}
	if (isset($post['layerCategory']))
	{
		$inheritPosts['layerCategory']=$post['layerCategory'];
	}
?>
<html style="width:100%;height:100%;font-size:0.9vw;line-height:2">
<head>
	<meta charset="utf-8"/>
	<script>
		var topFrame="";
		<?php
			includeDirectory("./js-functions/manage");
			echo "var categories = ".json_encode(array_keys($layerCategories)).";\n";
			foreach ($layerCategories as $category => $catLayers)
			{
				$catLayers=array_merge(array(""), $catLayers);
				echo "var $category = ".json_encode($catLayers).";\n";
				unset($category, $catLayers);
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
	<div style="width:calc( 100vw - 15px ); overflow-x:auto; margin-bottom: 5px">
		<table style="border-bottom:dashed 1px lightgray; margin-bottom: 2px; border-top:dashed 1px lightgray;">
			<tr>

<!--  REDIGERA KARTA  -->
				<th class="thLeft">
					<h3 class="<?php if ($focusTable == 'maps') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera karta
					</h3>
					<?php headForm(array('maps'=>$configTables['maps']), $inheritPosts); ?>
				</th>
<!--  REDIGERA KONTROLLER  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'controls') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera kontroll
					</h3>
					<?php headForm(array('controls'=>$configTables['controls']), $inheritPosts); ?>
					<?php printMultiselectButton('controls'); ?>
				</th>

<!--  REDIGERA GRUPP  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'groups') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera grupp
					</h3>
					<?php headForm(array('groups'=>$configTables['groups']), $inheritPosts); ?>
					<?php printMultiselectButton('groups'); ?>
				</th>

<!--  REDIGERA LAGER  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'layers') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera lager
					</h3>
					<?php headForm(array('layers'=>$configTables['layers']), $inheritPosts); ?>
					<?php printMultiselectButton('layers'); ?>
				</th>

<!--  REDIGERA KÄLLOR  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'sources') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera källa
					</h3>
					<?php headForm(array('sources'=>$configTables['sources']), $inheritPosts); ?>
				</th>

<!--  REDIGERA SIDFÖTTER  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'footers') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera sidfot
					</h3>
					<?php headForm(array('footers'=>$configTables['footers']), $inheritPosts); ?>
				</th>

<!--  REDIGERA TJÄNSTER  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'services') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera tjänst
					</h3>
					<?php headForm(array('services'=>$configTables['services']), $inheritPosts); ?>
				</th>

<!--  REDIGERA TILEGRIDS  -->
				<th class="thMiddle">
					<h3 class="<?php if ($focusTable == 'tilegrids') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera tilegrid
					</h3>
					<?php headForm(array('tilegrids'=>$configTables['tilegrids']), $inheritPosts); ?>
				</th>

<!--  REDIGERA PROJ4DEFS  -->
				<th class="thRight">
					<h3 class="<?php if ($focusTable == 'proj4defs') {echo 'h3Focus';} else {echo 'h3NoFocus';} ?>">
						Redigera proj4defs
					</h3>
					<?php headForm(array('proj4defs'=>$configTables['proj4defs']), $inheritPosts); ?>
					<?php printMultiselectButton('proj4defs'); ?>
				</th>

			</tr>
		</table>
	</div>
	<script>
		updateSelect("layerCategories", categories);
		<?php
			if (isset($post['layerCategory']))
			{
				echo <<<HERE
					document.getElementById("layerCategories").value="{$post['layerCategory']}";
					updateSelect("layerSelect", {$post['layerCategory']});
					document.getElementById("layerSelect").value="{$post['layerId']}";
				HERE;
			}
		?>
	</script>
<?php
/*
 ************************
 *  DYNAMISKT INNEHÅLL  *
 ************************
*/
	// Om karta vald
	if (isset($post['mapId']))
	{
		$map=array('map'=>array_column_search($post['mapId'], 'map_id', $configTables['maps']));
		if (!empty(current($map)))
		{
			$selectables=array('footers'=>array_column($configTables['footers'], 'footer_id'), 'tilegrids'=>array_column($configTables['tilegrids'], 'tilegrid_id'));
			printMapForm($map, $selectables, $inheritPosts);
			echo '<table><tr>';
			$thClass='thFirst';
			printChildSelect($map, 'groups', $thClass, 'Redigera grupp', $inheritPosts);
			printChildSelect($map, 'layers', $thClass, 'Redigera lager', $inheritPosts);
			printChildSelect($map, 'controls', $thClass, 'Redigera kontroll', $inheritPosts);
			echo '</tr></table><hr>';
			unset($selectables, $thClass);
		}
		unset($map);
	}

	//  Om kontroll vald
	if (isset($post['controlId']))
	{
		$control=array('control'=>array_column_search($post['controlId'], 'control_id', $configTables['controls']));
		if (!empty(current($control)))
		{
			printControlForm($control, array('maps'=>$configTables['maps']), $inheritPosts);
		}
		unset($control);
	}

	//  Om grupp vald
	$tmpGroupIds=$groupIdsArray;
	$parent=array_shift($tmpGroupIds);
	$groupLevel=1;
	foreach ($groupIdsArray as $groupId)
	{
		$group=array('group'=>array_column_search($groupId, 'group_id', $configTables['groups']));
		if (!empty(current($group)))
		{
			if (count($tmpGroupIds) > 0)
			{
				$parent="$parent,".array_shift($tmpGroupIds);
			}
			printGroupForm($group, array('maps'=>$configTables['maps'], 'groups'=>$configTables['groups']), $inheritPosts);
			echo '<table><tr>';
			$thClass='thFirst';
			printChildSelect($group, 'groups', $thClass, 'Redigera grupp', $inheritPosts, $groupLevel, $parent);
			printChildSelect($group, 'layers', $thClass, 'Redigera lager', $inheritPosts, $groupLevel);
			echo '</tr></table><hr>';
			$groupLevel++;
		}
		unset($group);
	}
	unset($tmpGroupIds, $parent, $groupLevel, $groupId, $thClass);

	//  Om sidfot vald
	if (isset($post['footerId']))
	{
		$footer=array('footer'=>array_column_search($post['footerId'], 'footer_id', $configTables['footers']));
		if (!empty(current($footer)))
		{
			printFooterForm($footer, $inheritPosts);
		}
		unset($footer);
	}

	//  Om lager vald
	if (isset($post['layerId']))
	{
		$layer=array('layer'=>array_column_search($post['layerId'], 'layer_id', $configTables['layers']));
		if (!empty(current($layer)))
		{
			if (isset($layer['layer']['source']))
			{
				$layerSource=array_column_search($layer['layer']['source'], 'source_id', $configTables['sources']);
				if (!empty($layerSource) && isset($layerSource['service']))
				{
					$layer['layer']['service']=$layerSource['service'];
				}
				unset($layerSource);
			}
			printLayerForm($layer, array('maps'=>$configTables['maps'], 'groups'=>$configTables['groups']), array_column($configTables['sources'], 'source_id'), $inheritPosts);
		}
		unset($layer);
	}

	//  Om källa vald
	if (isset($post['sourceId']))
	{
		$source=array('source'=>array_column_search($post['sourceId'], 'source_id', $configTables['sources']));
		if (!empty(current($source)))
		{
			$selectables=array('services'=>array_column($configTables['services'], 'service_id'), 'tilegrids'=>array_column($configTables['tilegrids'], 'tilegrid_id'));
			printSourceForm($source, $selectables, $inheritPosts);
			unset($selectables);
		}
		unset($source);
	}

	//  Om tjänst vald
	if (isset($post['serviceId']))
	{
		$service=array('service'=>array_column_search($post['serviceId'], 'service_id', $configTables['services']));
		if (!empty(current($service)))
		{
			printServiceForm($service, $inheritPosts);
		}
		unset($service);
	}

	//  Om tilegrid vald
	if (isset($post['tilegridId']))
	{
		$tilegrid=array('tilegrid'=>array_column_search($post['tilegridId'], 'tilegrid_id', $configTables['tilegrids']));
		if (!empty(current($tilegrid)))
		{
			printTilegridForm($tilegrid, $inheritPosts);
		}
		unset($tilegrid);
	}

	//  Om proj4def vald
	if (isset($post['proj4defId']))
	{
		$proj4def=array('proj4def'=>array_column_search($post['proj4defId'], 'code', $configTables['proj4defs']));
		if (!empty(current($proj4def)))
		{
			printProj4defForm($proj4def, $inheritPosts);
		}
		unset($proj4def);
	}
?>
</body>
</html>
