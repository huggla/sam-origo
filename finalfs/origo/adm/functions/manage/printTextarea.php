<?php
	function printTextarea($target, $column, $class, $label)
	{
		$targetId=current($target)[pkColumnOfTable(key($target).'s')];
		$columnValue=current($target)[$column];
		if (preg_match('/^\{([^"\{\[]*("[^:])?)*\}$/', $columnValue))
		{ 
			$columnValue=trim($columnValue, '{}');
		}
		$ucColumn=ucfirst($column);
		echo <<<HERE
			<label for="{$targetId}{$ucColumn}">{$label}</label>
			<textarea rows="1" class="{$class}" id="{$targetId}{$ucColumn}" name="update{$ucColumn}">{$columnValue}</textarea>&nbsp;
		HERE;
	}
?>
