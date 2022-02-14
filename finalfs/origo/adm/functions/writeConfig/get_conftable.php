<?php

	function get_conftable($dbh, $table)
	{
		$sqlResult = pg_query($dbh, "SELECT * FROM map_configs.$table");
		if (!$sqlResult)
		{
			die('Error in SQL query: '.pg_last_error());
		}
		else
		{
			return pg_fetch_all($sqlResult);
		}
	}

?>
