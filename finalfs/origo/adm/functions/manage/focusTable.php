<?php
	function focusTable($idPosts)
	{
		if (isset($idPosts['mapId']))
		{
			$focusTable='maps';
		}
		elseif (isset($idPosts['groupId']))
		{
			$focusTable='groups';
		}
		else
		{
			$firstKey=key($idPosts);
			if (isset($firstKey))
			{
				$focusTable=substr($firstKey, 0, -2).'s';
			}
			else
			{
				$focusTable="";
			}
		}
		return $focusTable;
	}
?>
