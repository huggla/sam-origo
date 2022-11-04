<?php
	function isChosen($id)
	{
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		return (isset($id) && in_array($id, array_column($targetTableArr, idColumnOfTarget($target))));
	}
?>
