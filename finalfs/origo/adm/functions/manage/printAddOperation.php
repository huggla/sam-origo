<?php
	function printAddOperation($target, $addToTable, $buttontext, $inheritPosts)
	{
			$str=rtrim(ucfirst(key($addToTable)), 's');
			echo '<form class="addForm" method="post">';
			echo '<select class="headSelect" name="to'.$str.'Id">';
			printSelectOptions(array_merge(array(""),current($addToTable)));
			echo '</select>';
			printHiddenInputs($inheritPosts);
			echo '<button type="submit" name="'.key($target).'Button" value="operation">'.$buttontext.'</button>';
			echo '</form>';
	}
?>
