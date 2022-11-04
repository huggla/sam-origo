<?php
	function printUpdateSelect($targetId, $column, $class, $label=null, $options=null)
	{
		$target=$GLOBALS['target'];
		$targetTable=rtrim($target, 's').'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, idColumnOfTarget($target), $targetTableArr);
		$ucColumn=ucfirst($column);
		$selectName='update'.$ucColumn;
		$selected=$targetArr[rtrim($column, 's')];
		if (!isset($label))
		{
			$label=$ucColumn.':';
		}
		echo "<label for='{$targetId}{$ucColumn}'>{$label}</label>";
		echo "<select class='{$class}' id='{$targetId}{$ucColumn}' name='{$selectName}'>";
		if (!isset($options))
		{
				$columnArr=$GLOBALS[rtrim($column, 's').'s'];
				$columnArr=array_column($columnArr, idColumnOfTarget($column));
				$options=array_merge(array(""), $columnArr);
		}
		printSelectOptions($options, $selected);
		echo "</select>&nbsp;";
	}
?>
