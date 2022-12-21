<?php
	function tableNamesFromSchema($dbh, $schema)
	{
		$result=pg_query($dbh, "SELECT table_name FROM information_schema.tables WHERE table_schema='$schema' AND table_type='BASE TABLE'");
		if (!$result)
		{
			die("Error in SQL query: " . pg_last_error());
		}
		return pg_fetch_all_columns($result);
	}
?>
