<?php
	function printRemoveOperation($targetToRemove, $tableToRemoveFrom, $buttontext, $inheritPosts)
	{
			$parents=findParents($tableToRemoveFrom, $targetToRemove);
			if (!empty($parents))
			{
				echo '<form class="addForm" method="post">';
				echo '<select class="headSelect" name="from'.ucfirst(rtrim(key($tableToRemoveFrom), 's')).'Id">';
				printSelectOptions(array_merge(array(""), $parents));
				echo '</select>';
				printHiddenInputs($inheritPosts);
				echo '<button type="submit" name="'.key($targetToRemove).'Button" value="operation">'.$buttontext.'</button>';
				echo '</form>';
			}
	}
?>
