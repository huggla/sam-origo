<?php

	function findParents($parentType, $childType, $child)
	{
		$parents=array();
		if     ($parentType == 'map') { $parentsTypeSv='kartor'; }
		elseif ($parentType == 'group') { $parentsTypeSv='grupper'; }
		elseif ($parentType == 'layer') { $parentsTypeSv='lager'; }
		elseif ($parentType == 'source') { $parentsTypeSv='källor'; }
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
			elseif ($childType == 'footer' || $childType == 'source' || $childType == 'service')
			{
				if ($child == $potentialParent[$childType])
				{
					$parents[]=$potentialParent[$parentType.'_id'];
				}
			}
		}
		if (!empty($parents))
		{
			echo "<b>$parentsTypeSv: </b>";
			$first=true;
			foreach ($parents as $parent)
			{
				if (!$first)
				{
					echo ', ';
				}
				else
				{
					$first=false;
				}
				echo '<a href="/origo/php/adm/info.php?type='.$parentType.'&id='.urlencode($parent).'">'.$parent.'</a>';
			}
			echo "</br>";
		}
		else
		{
			return false;
		}
	}

?>
