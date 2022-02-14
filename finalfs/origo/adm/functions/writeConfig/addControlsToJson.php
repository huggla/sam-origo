<?php

function addControlsToJson()
{
	GLOBAL $json, $map, $controls;
	$mapControls = pgArrayToPhp($map['controls']);
	$json = $json.'"controls": [';
	$firstControl = true;
	foreach ($mapControls as $control)
	{
		if ($firstControl)
		{
			$firstControl = false;
		}
		else
		{
			$json = $json.', ';
		}
		$control = array_column_search($control, 'control_id', $controls);
		$controlName = trim(explode('#', $control['control_id'], 2)[0]);
		$json = $json.'{ "name": "'.$controlName.'"';
		if (!empty($control['options']))
		{
			$json = $json.', "options": '.$control['options'];
		}
		$json = $json.' }';
	}
	$json = $json.']';
}

?>
