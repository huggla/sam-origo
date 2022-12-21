<?php
	function appendUpdatedColumnsToSql($dbColumns, $sql)
	{
		$first=true;
		foreach ($dbColumns as $column => $value)
		{
			if (empty($value) && $value !== '0')
			{
				$value="null";
			}
			else
			{
				$value=pg_escape_literal($value);
			}
			if ($first)
			{
				$first=false;
			}
			else
			{
				$sql=$sql.',';
			}
			$sql=$sql." $column = $value";
		}
		return $sql;
	}
?>
