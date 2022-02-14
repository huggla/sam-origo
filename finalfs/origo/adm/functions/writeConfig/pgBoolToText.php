<?php

function pgBoolToText($pgBool)
{
	if ($pgBool == 't')
	{
		return "true";
	}
	elseif ($pgBool == 'f')
	{
		return "false";
	}
	else
	{
		return $pgBool;
	}
}

?>
