<?php
	function printTextarea($targetId, $column, $class, $label=null)
	{
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		if ($target == 'proj4def')
		{
			$idColumn='code';
		}
		else
		{
			$idColumn=$target.'_id';
		}
		$targetArr=array_column_search($targetId, $idColumn, $targetTableArr);
		$ucColumn=ucfirst($column);
		if ($ucColumn != 'Id' && $ucColumn != 'Code')
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
			<label for="{$targetId}{$ucColumn}">{$label}</label>
			<textarea rows="1" class="{$class}" id="{$targetId}{$ucColumn}" name="update{$ucColumn}">{$columnValue}</textarea>&nbsp;
		HERE;
	}
?>
