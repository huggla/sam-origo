<?php

	function addSourcesToJson()
	{
		GLOBAL $json, $mapSources, $sources, $services;
		$json = $json.'"source": { ';
		if (!is_array($mapSources))
		{
			$mapSources = pgArrayToPhp($mapSources);
		}
		$mapSources = array_unique($mapSources);
		$firstSource = true;
		foreach ($mapSources as $sourceId)
		{
			$source = array_column_search(trim(explode('@', $sourceId, 2)[0]), 'source_id', $sources);
			if (!empty($source))
			{
				if ($firstSource)
				{
					$firstSource = false;
				}
				else
				{
					$json = $json.', ';
				}
				$url = array_column_search($source['service'], 'service_id', $services, 'base_url');
				$sourceProject = trim(explode('#', $source['source_id'], 2)[0]);
				if (strpos($sourceId, '@wfs') !== false)
				{
					$wfsSource = true;
				}
				else
				{
					$wfsSource = false;
				}
				$url = rtrim($url, '/').'/'.$sourceProject;
				$sourceColumns = array_keys($source);
				$queryColumns = array();
				if (!$wfsSource)
				{
					foreach ($sourceColumns as $column)
					{
						if ($column != 'source_id' && $column != 'abstract' && $column != 'base_url' && $column != 'service' && $column != 'project' && !empty($source[$column]))
						{
							$queryColumns[] = $column;
						}
					}
					foreach ($queryColumns as $query)
					{
						if (strpos($url, '?') === false)
						{
							$url = $url.'?';
						}
						else
						{
							$url = $url.'&';
						}
						$url = $url.$query.'='.pgBoolToText($source[$query]);
					}
				}
				$json = $json.'"'.$sourceId.'": { "url": "'.$url.'"';
				if ($wfsSource)
				{
					$json = $json.', "workspace": "qgs"';
				}
				$json = $json.'}';
			}
		}
		$json = $json.' }';
	}

?>
