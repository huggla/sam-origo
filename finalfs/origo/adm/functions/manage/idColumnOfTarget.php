<?php
	function idColumnOfTarget($target)
	{
		if ($target == 'proj4def')
		{
			return 'code';
		}
		else
		{
			return rtrim($target, 's').'_id';
		}
	}
?>
