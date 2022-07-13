<?php

function renamedup($name, $count=0)
{
	GLOBAL $uniqueLayers;
	if ($count > 0)
	{
		$newname="$name#$count";
	}
	else
	{
		$newname="$name#";
	}
	if (in_array($newname, $uniqueLayers))
	{
		$count++;
		return renamedup($name, $count);
	}
	else
	{
		$uniqueLayers[]=$newname;
		return $newname;
	}
}

?>
