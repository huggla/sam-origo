<?php
	function dataTypeOfTableColumn($schema, $table, $column)
	{
		GLOBAL $dbh;
		$result=pg_query($dbh, "SELECT data_type FROM information_schema.columns WHERE table_schema='$schema' AND table_name='$table' AND column_name='$column'");
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		return pg_fetch_all_columns($result)[0];
	}
?>
