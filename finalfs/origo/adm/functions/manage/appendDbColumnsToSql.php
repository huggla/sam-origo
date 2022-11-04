<?php
	function appendDbColumnsToSql($dbColumns, $sql)
	{
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
			$sql=$sql.", $column = $value";
		}
		return $sql;
	}
?>
