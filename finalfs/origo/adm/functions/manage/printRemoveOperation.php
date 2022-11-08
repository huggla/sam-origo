<?php
	function printRemoveOperation($tablename, $targetId, $buttontext)
	{
			GLOBAL $target;
			$tabletype=rtrim($tablename, 's');
			$mapParents=findParents($tabletype, $target, $targetId);
			if (!empty($mapParents))
			{
				$str=ucfirst($tabletype);
				echo '<form class="addForm" method="post">';
				echo '<select class="headSelect" name="from'.$str.'Id">';
				printSelectOptions(array_merge(array(""),$mapParents));
				echo '</select>';
				echo '<input type="hidden" name="'.$target.'Id" value="'.$targetId.'">';
				echo $hiddenInputs;
				echo '<button type="submit" name="'.$target.'Button" value="operation">'.$buttontext.'</button>';
				echo '</form>';
			}
	}
?>
