<?php

	function addGroupsToJson($mapGroups)
	{
		GLOBAL $groups, $json, $mapLayers;
		if (!empty($mapGroups))
		{
			$mapGroups = pgArrayToPhp($mapGroups);
			$json = $json. ', "groups": [ ';
			$firstGroup = true;
			foreach ($mapGroups as $group)
			{
				$group = array_column_search($group, 'group_id', $groups);
				$groupName = trim(explode('#', $group['group_id'], 2)[0]);
				$mapLayers = array_merge($mapLayers, array($groupName => pgArrayToPhp($group['layers'])));
				if ($groupName !== 'none')
				{
					if ($firstGroup)
					{
						$firstGroup = false;
					}
					else
					{
						$json = $json.', ';
					}
					$json = $json.'{ "name": "'.$groupName.'", "title": "'.$group['title'].'"';
					if ($group['expanded'] == 't')
					{
						$json = $json.', "expanded": true';
					}
					if (!empty($group['abstract']))
					{
						$json = $json.', "abstract": "'.$group['abstract'].'"';
					}
					if (!empty(trim($group['groups'], '{}')))
					{
						addGroupsToJson($group['groups']);
					}
					$json = $json.' }';
				}
			}
			$json = $json.' ]';
		}
	}

?>
