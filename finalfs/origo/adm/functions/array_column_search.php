<?php
	function array_column_search($search, $column, $array, $return=false)
	{
		$columnValues = array_column($array, $column);
		$key = array_search($search, $columnValues);
		if ( $key !== false )
		{
			if (!$return)
			{
				return $array[$key];
			}
			else
			{
				return $array[$key][$return];
			}
		}
		else
		{
			return array();
		}
	}
?>
