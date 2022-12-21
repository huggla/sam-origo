<?php
	function includeDirectory($directory)
	{
		$files = array_diff(scandir($directory), array('.', '..'));
		foreach ($files as $file)
		{
			include_once("$directory/$file");
		}
	}
?>
