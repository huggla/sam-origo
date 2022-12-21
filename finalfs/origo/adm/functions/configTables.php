<?php
	function configTables($dbh, $configSchema)
	{
		$configTables=array_flip(array_unique(array_merge(array('maps','groups','layers','sources'), tableNamesFromSchema($dbh, $configSchema))));
		foreach ($configTables as $table => $content)
		{
			$configTables[$table]=all_from_table($dbh, $configSchema, $table);
		}
		return $configTables;
	}
?>
