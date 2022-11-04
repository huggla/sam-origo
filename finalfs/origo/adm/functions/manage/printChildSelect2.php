<?php
	function printChildSelect2($targetId, $column, $header, $selectedValue=null)
	{

		GLOBAL $mapId, $groupId, $thClass;
		$target=$GLOBALS['target'];
		$targetTable=$target.'s';
		$targetTableArr=$GLOBALS[$targetTable];
		$targetArr=array_column_search($targetId, idColumnOfTarget($target), $targetTableArr);
		$selected=$targetArr[$column];
		$type=rtrim($column, 's');
		$columnId=$GLOBALS[$type.'Id'];

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
			printColumnSelect2($targetId, $column, $selectedValue);
			if (isset($mapId))
			{
				echo '<input type="hidden" name="mapId" value="'.$mapId.'">';
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
