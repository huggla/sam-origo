<?php
	function printChildSelect($targetId, $column, $header, $selectedValue=null)
	{

		GLOBAL $mapId, $groupId, $groupIds, $groupLevels, $thClass;
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, idColumnOfTarget($target), $targetTableArr);
		$selected=$targetArr[$column];
		$type=rtrim($column, 's');
		if ($column == 'groups')
		{
		$columnId=$GLOBALS['groupIds'][$GLOBALS['level']];
		}
		else
		{
		$columnId=$GLOBALS[$type.'Id'];
		}
		if (!empty(trim($targetArr[$column], '{}')))
		{
			if (!isset($columnId))
			{
				$edith3Class='h3Lightgray';
			}
			else
			{
				$edith3Class='h3Black';
			}
			echo <<<HERE
				<th class="{$thClass}">
					<h3 class="{$edith3Class}">{$header}</h3>
					<div style="display:flex">
						<form class="headForm" method="post">
			HERE;
			printColumnSelect($targetId, $column, $selectedValue);
			if (isset($mapId))
			{
				echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
			}
			if (isset($groupId))
			{
				echo '<input type="hidden" name="groupId" value="'.$groupId.'">';
			}

			if ($column != 'groups' && !empty($groupIds))
			{
				echo '<input type="hidden" name="groupIds" value="'.implode(',', $GLOBALS['groupIds']).'">';
				echo '<input type="hidden" name="groupLevels" value="'.$groupLevels.'">';
			}

			echo <<<HERE
							<button type="submit" class="headButton" name="{$type}Button" value="get">
								Hämta
							</button>
						</form>
					</div>
				</th>
			HERE;
			$thClass='thNext';
		}
	}
?>
