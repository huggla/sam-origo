<?php
	function printGroupForm($group, $operationTables, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($group, 'group_id', 'textareaMedium', 'Id:');
		printTextarea($group, 'layers', 'textareaLarge', 'Lager:');
		printTextarea($group, 'groups', 'textareaLarge', 'Grupper:');
		echo '<br>';
		printTextarea($group, 'title', 'textareaMedium', 'Titel:');
		printUpdateSelect($group, array('expanded'=>array("f", "t")), 'miniSelect', 'Expanderad:');
		printTextarea($group, 'abstract', 'textareaMedium', 'Sammanfattning:');
		printTextarea($group, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('group');
		$group['group']=$group['group']['group_id'];
		printInfoButton($group);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker på att du vill radera gruppen ".$group['group']."? Ingående lager påverkas ej och referenser till gruppen hanteras separat.";
		printDeleteButton($group, $deleteConfirmStr, 'deleteButton2');
		echo '</div><div style="clear:both">';
		printAddOperation($group, array('maps'=>array_column($operationTables['maps'], 'map_id')), 'Lägg till i karta', $inheritPosts);
		printRemoveOperation($group, array('maps'=>$operationTables['maps']), 'Ta bort från karta', $inheritPosts);
		printAddOperation($group, array('groups'=>array_column($operationTables['groups'], 'group_id')), 'Lägg till i grupp', $inheritPosts);
		printRemoveOperation($group, array('groups'=>$operationTables['groups']),'Ta bort från grupp', $inheritPosts);
		echo '</div>';
	}
?>
