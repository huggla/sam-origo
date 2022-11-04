<?php
	function printColumnSelect($targetId, $column, $selectedValue=null)
	{
		$target=$GLOBALS['target'];
		$targetTable=rtrim($target, 's').'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, idColumnOfTarget($target), $targetTableArr);
		$ucColumn=ucfirst($column);
		$columnArr=pgArrayToPhp('{'.trim($targetArr[$column], '{}').'}');
		if ($column == 'groups')
		{
			$selectName='groupIds';
			$groupLevels=$GLOBALS['groupLevels'];
			$level=$GLOBALS['level'];
			$groupLevelsValue=$level+1;
			echo "<input type='hidden' name='groupLevels' value='".$groupLevelsValue."'>";
			$groupIds=$GLOBALS['groupIds'];
			if ($groupLevels > 1)
			{
				$groupIds=array_slice($groupIds, 0, $level);
			}
			if (!empty($groupIds))
			{
				$columnArr=array_map(function($val) use ($groupIds) { return implode(',', $groupIds).','.$val; } , $columnArr);

			}
		}
		else
		{
			$selectName=rtrim($column, 's').'Id';
		}
		$options=array_merge(array(""), $columnArr);	
		echo "<select onchange='this.form.submit();' class='headSelect' id='{$targetId}{$ucColumn}' name='{$selectName}'>";
		printSelectOptions($options, $selectedValue);
		echo "</select>&nbsp;";
	}
?>
