<?php
	function printParents($potentialParents, $child)
	{
		$parents=findParents($potentialParents, $child);
		$parentType=rtrim(key($potentialParents), 's');
		if (!empty($parents))
		{
			if     ($parentType == 'map') { $parentsTypeSv='kartor'; }
			elseif ($parentType == 'group') { $parentsTypeSv='grupper'; }
			elseif ($parentType == 'layer') { $parentsTypeSv='lager'; }
			elseif ($parentType == 'source') { $parentsTypeSv='källor'; }
			else { $parentsTypeSv=$parentType; }
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
				echo '<a href="info.php?type='.$parentType.'&id='.urlencode($parent).'">'.$parent.'</a>';
			}
			echo "</br>";
		}
		else
		{
			return false;
		}
	}
?>
