<?php
	function printColumnSelect2($targetId, $column, $selectedValue=null)
	{
		$target=$GLOBALS['target'];
		$targetTable=rtrim($target, 's').'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, idColumnOfTarget($target), $targetTableArr);
		$ucColumn=ucfirst($column);
		$columnArr=pgArrayToPhp('{'.trim($targetArr[$column], '{}').'}');
		$selectName=rtrim($column, 's').'Id';
		$options=array_merge(array(""), $columnArr);	
		echo "<select onchange='this.form.submit();' class='headSelect' id='{$targetId}{$ucColumn}' name='{$selectName}'>";
		printSelectOptions($options, $selectedValue);
		echo "</select>&nbsp;";
	}
?>
