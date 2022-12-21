<?php
	function printHiddenInputs($inheritPosts)
	{
		$hiddenInputs="";
		foreach ($inheritPosts as $idKey => $idValue)
		{
			if ($idKey != 'layerCategory')
			{
				$hiddenInputs=$hiddenInputs.'<input type="hidden" name="'.$idKey.'" value="'.$idValue.'">';
			}
		}
		if (!empty($hiddenInputs))
		{
			echo $hiddenInputs;
		}
	}
?>
