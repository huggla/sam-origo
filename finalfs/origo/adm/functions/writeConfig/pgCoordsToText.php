<?php

	function pgCoordsToText($pgCoords)
	{
		return str_replace('(', '', str_replace(')', '', $pgCoords));
	}

?>
