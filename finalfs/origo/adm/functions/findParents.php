<?php
	function findParents($potentialParents, $child)
	{
		$parents=array();
		foreach (current($potentialParents) as $potentialParent)
		{
			if ((key($child) == 'control' || key($child) == 'group' || key($child) == 'layer' || key($child) == 'proj4def') && (key($potentialParents) == 'maps' || key($potentialParents) == 'groups'))
			{
				if (in_array(current($child), pgArrayToPhp($potentialParent[key($child).'s'])))
				{
					$parents[]=$potentialParent[pkColumnOfTable(key($potentialParents))];
				}
			}
			elseif (key($child) == 'footer' || key($child) == 'source' || key($child) == 'service' || key($child) == 'tilegrid')
			{
				if (current($child) == $potentialParent[key($child)])
				{
					$parents[]=$potentialParent[pkColumnOfTable(key($potentialParents))];
				}
			}
		}
		return $parents;
	}
?>
