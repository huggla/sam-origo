<?php

	function selectOptions($tablename, $setSelected=false)
	{
		GLOBAL $maps, $controls, $groups, $layers, $sources, $services, $footers, $tilegrids, $proj4defs, $mapId, $controlId, $groupId, $layerId, $sourceId, $serviceId, $footerId, $tilegridId, $proj4defId;
		eval("\$table=\$$tablename;");

		$type=rtrim($tablename, 's');
		if ($tablename == 'proj4defs')
		{
			$column='code';
		}
		else
		{
			$column=$type."_id";
		}
		if ($setSelected !== false)
		{
			if ($setSelected !== true)
			{
				$id=$setSelected;
			}
			else
			{
				eval("\$id=\$$type".'Id;');
			}
			printSelectOptions(array_merge(array(""),array_column($table, $column)), $id);
		}
		else
		{
			printSelectOptions(array_merge(array(""),array_column($table, $column)));
		}
	}

?>
