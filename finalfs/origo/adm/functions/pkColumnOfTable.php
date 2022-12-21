<?php
	function pkColumnOfTable($table)
	{
		if ($table == 'proj4defs')
		{
			return 'code';
		}
		else
		{
			return rtrim($table, 's').'_id';
		}
	}
?>
