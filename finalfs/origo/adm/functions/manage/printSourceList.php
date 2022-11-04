<?php
	function printSourceList($targetId, $column, $class, $label=null)
	{
		GLOBAL $sources;
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, $target.'_id', $targetTableArr);
		$ucColumn=ucfirst($column);
		if ($ucColumn != 'Id')
		{ 
			$dbSchema=$GLOBALS['configSchema'];
			$columnDataType=dataTypeOfTableColumn($dbSchema, $targetTable, $column);
			if ($columnDataType == 'ARRAY')
			{
				$columnValue=trim($targetArr[$column], '{}');
			}
			else
			{
				$columnValue=$targetArr[$column];
			}
		}
		else
		{
			$columnValue=$targetId;
		}
		if (!isset($label))
		{
			$label=$ucColumn.':';
		}
		echo <<<HERE
			<label for="{$targetId}Source">Källa:</label>
			<input type="text" list="sourcelist" class="bodySelect" id="{$targetId}Source" name="updateSource" value="{$targetArr['source']}" onfocus="this.value='';" />
			<datalist id="sourcelist">
		HERE;
		printSelectOptions(array_merge(array(""), array_column($sources, 'source_id')), $targetArr['source']);
		echo      '</datalist>&nbsp;';
	}
?>
