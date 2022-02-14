<?php

	function dbh($connection_string)
	{
		$dbh = pg_connect($connection_string);
		if (!$dbh)
		{
			echo '{"save_status":"Error in connection"}';
			die();
		}
		else
		{
			return $dbh;
		}
	}

?>
