<?php

function pgBoxToText($pgBox)
{
	$arr = explode('),(', trim($pgBox, '()'));
	$c1 = explode(',', $arr[0]);
	$c2 = explode(',', $arr[1]);
	if ($c1[0] > $c2[0])
	{
		$arr = array($arr[1], $arr[0]);
	}
	return implode(',', $arr);
}

?>
