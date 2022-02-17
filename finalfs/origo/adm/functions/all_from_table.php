<?php
	function all_from_table($table)
	{
		GLOBAL $dbh;
		$result=pg_query($dbh, "SELECT * FROM $table ORDER BY 1");
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		return pg_fetch_all($result);
	}
?>
