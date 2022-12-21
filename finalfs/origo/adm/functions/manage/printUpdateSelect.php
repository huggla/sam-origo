<?php
	function printUpdateSelect($target, $column, $class, $label, $options=null)
	{
		$targetId=current($target)[pkColumnOfTable(key($target).'s')];
		$ucColumn=ucfirst(key($column));
		$sName='update'.$ucColumn;
		$selected=current($target)[rtrim(key($column), 's')];
		echo "<label for='{$targetId}{$ucColumn}'>{$label}</label>";
		echo "<select class='{$class}' id='{$targetId}{$ucColumn}' name='{$sName}'>";
		if (!isset($options))
		{
				$options=array_merge(array(""), current($column));
		}
		printSelectOptions($options, $selected);
		echo "</select>&nbsp;";
	}
?>
