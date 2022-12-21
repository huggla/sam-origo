<?php
	function sqlForUpdate($target, $configSchema, $updatePosts)
	{
		$targetTable=key($target).'s';
		$targetPkColumn=pkColumnOfTable($targetTable);
		$updatedColumns=updatedColumns($targetTable, $updatePosts);
		$sql="UPDATE $configSchema.$targetTable SET";
		$sql=appendUpdatedColumnsToSql($updatedColumns, $sql);
		$sql=$sql." WHERE $targetPkColumn = '".current($target)."'";
		return $sql;
	}
?>
