<?php
	function printControlForm($control, $maps, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($control, 'control_id', 'textareaMedium', 'Id:');
		printTextarea($control, 'options', 'textareaLarge', 'Inställningar:');
		echo '<br>';
		printTextarea($control, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('control');
		$control['control']=$control['control']['control_id'];
		printInfoButton($control);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera kontrollen ".$control['control']."? Referenser till kontrollen hanteras separat.";
		printDeleteButton($control, $deleteConfirmStr, 'deleteButton1');
		echo '</div><div style="clear:both">';
		printAddOperation($control, array('maps'=>array_column($maps['maps'], 'map_id')), 'Lägg till i karta', $inheritPosts);
		printRemoveOperation($control, $maps, 'Ta bort från karta', $inheritPosts);
		echo '</div>';
	}
?>
