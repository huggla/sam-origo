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
	$configSchema='map_configs';
	extract($_POST);
	if (isset($groupIds))
	{
		$groupIds=explode(',', $groupIds);
	}
	else
	{
		$groupIds=array();
	}
	if (isset($groupId) && empty($groupIds))
	{
		$groupIds[]=$groupId;
	}
	elseif (!isset($mapId) && !empty($groupIds) && !isset($groupId))
	{
		$groupId=$_POST['groupId']=$groupIds[0];
	}
	$idVarsNames=array_keys(array_filter($_POST, function($key) {return (substr($key, -2) == 'Id');}, ARRAY_FILTER_USE_KEY));
	$hiddenInputs="";
	foreach ($idVarsNames as $idVarName)
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="'.$idVarName.'" value="'.$GLOBALS[$idVarName].'">';
	}
	if (!empty($GLOBALS['groupIds']))
	{
		$hiddenInputs=$hiddenInputs.'<input type="hidden" name="groupIds" value="'.implode(',', $GLOBALS['groupIds']).'">';
	}
	$configTables=array_unique(array_merge(array('maps','groups','layers','sources'), configTablesNamesFromSchema($configSchema)));
	$focusSet=false;
	foreach ($configTables as $table)
	{
		if ($table == 'layers')
		{
			setLayers();
		}
		else
		{
			$tableWithSchema=$configSchema.'.'.$table;
			eval("\$$table=all_from_table('$tableWithSchema');");
		}
		$table=rtrim($table, 's');
		$classVar=$table.'h3class';
		if (!$focusSet && in_array($table.'Id', $idVarsNames))
		{
			$focusSet=true;
			eval("\$$classVar='h3Focus';");
		}
		else
		{
			eval("\$$classVar='h3NoFocus';");
		}
	}

/*
 *********************************
 *  DATABAS-OPERATIONER <start>  *
 *********************************
*/
	$pressedButton=array_keys(array_filter($_POST, function($key) {return (substr($key, -6) == 'Button');}, ARRAY_FILTER_USE_KEY))[0];
	if (isset($pressedButton))
	{
		$pressedButtonCommand=$$pressedButton;
		if ($pressedButtonCommand != 'get')
		{
			$pressedButtonCommandTarget=substr($pressedButton, 0, -6);
			$pressedButtonCommandTargetTable=$pressedButtonCommandTarget.'s';
			$pressedButtonCommandTargetTableArray=$$pressedButtonCommandTargetTable;
			if ($pressedButtonCommandTarget == 'proj4def')
			{
				$pressedButtonCommandTargetTableKey='code';
			}
			else
			{
				$pressedButtonCommandTargetTableKey=$pressedButtonCommandTarget.'_id';
			}
			if ($pressedButtonCommand == 'create')
			{
				$pressedButtonCommandTargetTableValue=${$pressedButtonCommandTarget.'IdNew'};
				if (!empty($pressedButtonCommandTargetTableValue) && !in_array($pressedButtonCommandTargetTableValue, array_column($pressedButtonCommandTargetTableArray, $pressedButtonCommandTargetTableKey)))
				{
					$sql="INSERT INTO $configSchema.$pressedButtonCommandTargetTable($pressedButtonCommandTargetTableKey) VALUES ('$pressedButtonCommandTargetTableValue')";
				}
			}
			elseif ($pressedButtonCommand == 'delete')
			{
				$pressedButtonCommandTargetTableValue=${$pressedButtonCommandTarget.'IdDel'};
				if (!empty($pressedButtonCommandTargetTableValue) && in_array($pressedButtonCommandTargetTableValue, array_column($pressedButtonCommandTargetTableArray, $pressedButtonCommandTargetTableKey)))
				{
					$sql="DELETE FROM $configSchema.$pressedButtonCommandTargetTable WHERE $pressedButtonCommandTargetTableKey = '$pressedButtonCommandTargetTableValue'";
				}
			}
			else
			{
				$pressedButtonCommandTargetTableValue=${$pressedButtonCommandTarget.'Id'};
				if ($pressedButtonCommand == 'update')
				{
					$sql="UPDATE $configSchema.$pressedButtonCommandTargetTable SET";
					$sql=$sql." $pressedButtonCommandTargetTableKey = ".pg_escape_literal($updateId);
					$sql=$sql.", info = ".pg_escape_literal($updateInfo);
					if ($pressedButtonCommandTarget == 'map')
					{
						$dbColumns=array(
							'featureinfooptions'	=> $updateFeatureinfooptions,
							'center'		=> $updateCenter,
							'zoom'			=> $updateZoom,
							'footer'		=> $updateFooter,
							'tilegrid'		=> $updateTilegrid,
							'layers'		=> '{'.$updateLayers.'}',
							'groups'		=> '{'.$updateGroups.'}',
							'controls'		=> '{'.$updateControls.'}',
							'proj4defs'		=> '{'.$updateProj4defs.'}'
						);
					}
					elseif ($pressedButtonCommandTarget == 'group')
					{
						$dbColumns=array(
							'title'			=> $updateTitle,
							'abstract'		=> $updateAbstract,
							'expanded'		=> $updateExpanded,
							'layers'		=> '{'.$updateLayers.'}',
							'groups'		=> '{'.$updateGroups.'}'
						);
					}
					elseif ($pressedButtonCommandTarget == 'control')
					{
						$dbColumns=array(
							'options'		=> $updateOptions
						);
					}
					elseif ($pressedButtonCommandTarget == 'footer')
					{
						$dbColumns=array(
							'img'			=> $updateImg,
							'url'			=> $updateUrl,
							'text'			=> $updateText
						);
					}
					elseif ($pressedButtonCommandTarget == 'tilegrid')
					{
						$dbColumns=array(
							'tilesize'		=> $updateTilesize
						);
					}
					elseif ($pressedButtonCommandTarget == 'proj4def')
					{
						$dbColumns=array(
							'projection'		=> $updateProjection,
							'alias'			=> $updateAlias
						);
					}
					elseif ($pressedButtonCommandTarget == 'service')
					{
						$dbColumns=array(
							'base_url'		=> $updateBaseurl
						);
					}
					elseif ($pressedButtonCommandTarget == 'source')
					{
						$dbColumns=array(
							'service'		=> $updateService,
							'with_geometry'		=> $updateWithgeometry,
							'fi_point_tolerance'	=> $updateFipointtolerance,
							'ttl'			=> $updateTtl,
							'tilegrid'		=> $updateTilegrid
						);
					}
					elseif ($pressedButtonCommandTarget == 'layer')
					{
						$dbColumns=array(
							'attributes'		=> $updateAttributes,
							'editable'		=> $updateEditable,
							'allowededitoperations'	=> $updateAllowededitoperations,
							'tiled'			=> $updateTiled,
							'style_config'		=> $updateStyle_config,
							'maxscale'		=> $updateMaxscale,
							'minscale'		=> $updateMinscale,
							'clusterstyle'		=> $updateClusterstyle,
							'clusteroptions'	=> $updateClusteroptions,
							'title'			=> $updateTitle,
							'abstract'		=> $updateAbstract,
							'source'		=> $updateSource,
							'type'			=> $updateType,
							'queryable'		=> $updateQueryable,
							'visible'		=> $updateVisible,
							'icon'			=> $updateIcon,
							'icon_extended'		=> $updateIcon_extended,
							'style_filter'		=> $updateStylefilter,
							'opacity'		=> $updateOpacity,
							'featureinfolayer'	=> $updateFeatureinfolayer,
							'format'		=> $updateFormat,
							'attribution'		=> $updateAttribution,
							'layertype'		=> $updateLayertype,
							'swiper'		=> $updateSwiper,
							'categories'		=> '{'.$updateCategories.'}',
							'layers'		=> '{'.$updateLayers.'}',
							'adusers'		=> '{'.$updateAdusers.'}',
							'adgroups'		=> '{'.$updateAdgroups.'}'
						);
					}
					$sql=appendDbColumnsToSql($dbColumns, $sql);
					$sql=$sql." WHERE $pressedButtonCommandTargetTableKey = '$pressedButtonCommandTargetTableValue'";
				}
				elseif ($pressedButtonCommand == 'operation')
				{
					if (isset($toMapId) || isset($toGroupId))
					{
						$operation='add';
						if (isset($toMapId))
						{
							$target='map';
							$targetId=$toMapId;
						}
						elseif (isset($toGroupId))
						{
							$target='group';
							$targetId=$toGroupId;
						}
					}
					elseif (isset($fromMapId) || isset($fromGroupId))
					{
						$operation='remove';
						if (isset($fromMapId))
						{
							$target='map';
							$targetId=$fromMapId;
						}
						elseif (isset($fromGroupId))
						{
							$target='group';
							$targetId=$fromGroupId;
						}
					}
					$targetIdColumn=$target.'_id';
					$targetTable=$target.'s';
					$targetTableArr=$$targetTable;
					$targetArr=array_column_search($targetId, $targetIdColumn, $targetTableArr);
					$targetColumn=$pressedButtonCommandTarget.'s';
					$targetColumnArr=pgArrayToPhp($targetArr[$targetColumn]);
					if (empty($targetColumnArr[0]))
					{
						$targetColumnArr=array();
					}
					if ($operation == 'add')
					{
						if (!in_array($pressedButtonCommandTargetTableValue, $targetColumnArr))
						{
							$targetColumnArr[]=$pressedButtonCommandTargetTableValue;
						}
					}
					elseif ($operation == 'remove')
					{
						if (($targetKey = array_search($pressedButtonCommandTargetTableValue, $targetColumnArr)) !== false)
						{

							unset($targetColumnArr[$targetKey]);
						}
					}
					$sql="UPDATE $configSchema.$targetTable SET $targetColumn = '{".implode(',', $targetColumnArr)."}' WHERE $targetIdColumn = '$targetId'";
				}
				if (!empty($sql))
				{
					$result=pg_query($dbh, $sql);
					if (!$result)
					{
						die("Error in SQL query: " . pg_last_error());
					}
					unset($sql);
					if ($pressedButtonCommandTargetTable == 'layers')
					{
						setLayers();
					}
					else
					{
						$tableWithSchema=$configSchema.'.'.$pressedButtonCommandTargetTable;
						eval("\$$pressedButtonCommandTargetTable=all_from_table('$tableWithSchema');");
					}
				}
			}
		}
	}
/*
 *********************************
 *  DATABAS-OPERATIONER </end>  *
 *********************************
*/
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
	<div style="width:calc( 100vw - 15px ); overflow-x:auto; margin-bottom: 5px">
		<table style="border-bottom:dashed 1px lightgray; margin-bottom: 2px; border-top:dashed 1px lightgray;">
			<tr>

<!--  REDIGERA KARTA  -->
				<th class="thLeft">
					<h3 class="<?php echo $maph3class; ?>">
						Redigera karta
					</h3>
					<?php headForm2('maps'); ?>
				</th>

<!--  REDIGERA KONTROLLER  -->
				<th class="thMiddle">
					<h3 class="<?php echo $controlh3class; ?>">
						Redigera kontroll
					</h3>
					<?php headForm2('controls'); ?>
					<?php multiselectButton('controls'); ?>
				</th>

<!--  REDIGERA GRUPP  -->
				<th class="thMiddle">
					<h3 class="<?php echo $grouph3class; ?>">
						Redigera grupp
					</h3>
					<?php headForm2('groups'); ?>
					<?php multiselectButton('groups'); ?>
				</th>

<!--  REDIGERA LAGER  -->
				<th class="thMiddle">
					<h3 class="<?php echo $layerh3class; ?>">
						Redigera lager
					</h3>
					<?php headForm2('layers'); ?>
					<?php multiselectButton('layers'); ?>
				</th>

<!--  REDIGERA KÄLLOR  -->
				<th class="thMiddle">
					<h3 class="<?php echo $sourceh3class; ?>">
						Redigera källa
					</h3>
					<?php headForm2('sources'); ?>
				</th>

<!--  REDIGERA SIDFÖTTER  -->
				<th class="thMiddle">
					<h3 class="<?php echo $footerh3class; ?>">
						Redigera sidfot
					</h3>
					<?php headForm2('footers'); ?>
				</th>

<!--  REDIGERA TJÄNSTER  -->
				<th class="thMiddle">
					<h3 class="<?php echo $serviceh3class; ?>">
						Redigera tjänst
					</h3>
					<?php headForm2('services'); ?>
				</th>

<!--  REDIGERA TILEGRIDS  -->
				<th class="thMiddle">
					<h3 class="<?php echo $tilegridh3class; ?>">
						Redigera tilegrid
					</h3>
					<?php headForm2('tilegrids'); ?>
				</th>

<!--  REDIGERA PROJ4DEFS  -->
				<th class="thRight">
					<h3 class="<?php echo $proj4defh3class; ?>">
						Redigera proj4defs
					</h3>
					<?php headForm2('proj4defs'); ?>
					<?php multiselectButton('proj4defs'); ?>
				</th>
			</tr>
		</table>
	</div>
<?php
/*
 ********************************
 *  DYNAMISKT INNEHÅLL <start>  *
 ********************************
*/
	// Om karta vald
	$target='map';
	if (isChosen($mapId))
	{
		printMapForm($mapId);
		echo '<table><tr>';
		$thClass='thFirst';
		printChildSelect2($mapId, 'groups', 'Redigera grupp', $groupId);
		printChildSelect($mapId, 'layers', 'Redigera lager');
		printChildSelect($mapId, 'controls', 'Redigera kontroll', $controlId);
		echo '</tr></table><hr>';
	}

	//  Om kontroll vald
	$target='control';
	if (isChosen($controlId))
	{
		printControlForm($controlId);
	}

	//  Om grupp vald
	$target='group';
	$tmpGroupIds=$groupIds;
	$tmpGroupIds2=$groupIds;
	$parent=array_shift($tmpGroupIds2);
	$level=1;
	foreach ($tmpGroupIds as $key => $group_Id)
	{
		$selectedValue=array_shift($tmpGroupIds2);
		$parent="$parent,$selectedValue";
		$selectedValue=$parent;
		unset($style);
		if (isChosen($group_Id))
		{
			printGroupForm($group_Id);
			echo '<table><tr>';
			$thClass='thFirst';
			printChildSelect($group_Id, 'groups', 'Redigera grupp', $selectedValue);
			printChildSelect($group_Id, 'layers', 'Redigera lager', $layerId);
			echo '</tr></table><hr>';
			$level++;
		}
	}

	//  Om sidfot vald
	$target='footer';
	if (isChosen($footerId))
	{
		printFooterForm($footerId);
	}

	//  Om lager vald
	$target='layer';
	if (isChosen($layerId))
	{
		printLayerForm($layerId);
	}

	//  Om källa vald
	$target='source';
	if (isChosen($sourceId))
	{
		printSourceForm($sourceId);
	}

	//  Om tjänst vald
	$target='service';
	if (isChosen($serviceId))
	{
		printServiceForm($serviceId);
	}

	//  Om tilegrid vald
	$target='tilegrid';
	if (isChosen($tilegridId))
	{
		printTilegridForm($tilegridId);
	}

	//  Om proj4def vald
	$target='proj4def';
	if (isChosen($proj4defId))
	{
		printProj4defForm($proj4defId);
	}
/*
 *******************************
 *  DYNAMISKT INNEHÅLL </end>  *
 *******************************
*/
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
