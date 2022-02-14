<?php

	function pgArrayToText($pgArray)
	{
		return str_replace('{', '', str_replace('}', '', $pgArray));
	}

?>
