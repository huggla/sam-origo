<?php

	function findParents($parentType, $childType, $child)
	{
		$parents=array();
		$allPotentialParents=all_from_table('map_configs.'.$parentType.'s');
		foreach ($allPotentialParents as $potentialParent)
		{
			if (($childType == 'control' || $childType == 'group' || $childType == 'layer') && ($parentType == 'map' || $parentType == 'group'))
			{
				if (in_array($child, pgArrayToPhp($potentialParent[$childType.'s'])))
				{
					$parents[]=$potentialParent[$parentType.'_id'];
				}
			}
			elseif ($childType == 'footer' || $childType == 'source' || $childType == 'service' || $childType == 'tilegrid')
			{
				if ($child == $potentialParent[$childType])
				{
					$parents[]=$potentialParent[$parentType.'_id'];
				}
			}
		}
		return $parents;
	}

?>
