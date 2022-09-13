<?php

function recursiveGroups($groupsArr)
{
	GLOBAL $dbh, $groups, $importId, $groupsLayers;
	$parentGroups=array();
	foreach ($groupsArr as $group)
	{

		$parentGroups[]=$group['name']."#$importId";
		if (!empty($group['groups']))
		{
			$childGroups=recursiveGroups($group['groups']);
		}
		else
		{
			$childGroups=array();
		}
		if (!empty($group['expanded']))
		{
			$groupExpanded=var_export($group['expanded'], true);
		}
		else
		{
			$groupExpanded='false';
		}
		$sql="INSERT INTO map_configs.groups(group_id, title, expanded, abstract, groups, layers) VALUES ('".$group['name']."#$importId', '".$group['title']."', '$groupExpanded', ".pg_escape_literal(str_replace(array('"'), '\"', str_replace(array("\r\n", "\r", "\n"), "<br />", $group['abstract']))).", '{".implode(',', $childGroups)."}', '{".implode(',', (array) $groupsLayers[$group['name']])."}')";
		$result=pg_query($dbh, $sql);
		if (!$result)
		{
			var_dump($sql);
			die("Error in SQL query: $sql" . pg_last_error());
		}
	}
	return $parentGroups;
}

?>
