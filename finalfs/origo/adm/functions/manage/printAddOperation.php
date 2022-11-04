<?php
	function printAddOperation($tablename, $targetId, $buttontext)
	{
			GLOBAL $target;
			$str=rtrim(ucfirst($tablename), 's');
			echo '<form class="addForm" method="post">';
			echo '<select class="headSelect" name="to'.$str.'Id">';
			selectOptions($tablename, false);
			echo '</select>';
			echo '<input type="hidden" name="'.$target.'Id" value="'.$targetId.'">';
			echo $hiddenInputs;
			echo '<button type="submit" name="'.$target.'Button" value="operation">'.$buttontext.'</button>';
			echo '</form>';
	}
?>
