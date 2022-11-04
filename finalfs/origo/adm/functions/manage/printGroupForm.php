<?php
	function printGroupForm($groupId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($groupId, 'id', 'textareaMedium', 'Id:');
		printTextarea($groupId, 'layers', 'textareaLarge', 'Lager:');
		printTextarea($groupId, 'groups', 'textareaLarge', 'Grupper:');
		echo 			'<br>';
		printTextarea($groupId, 'title', 'textareaMedium', 'Titel:');
		printUpdateSelect($groupId, 'expanded', 'miniSelect', 'Expanderad:', array("", "f", "t"));
		printTextarea($groupId, 'abstract', 'textareaMedium', 'Sammanfattning:');
		printTextarea($groupId, 'info', 'textareaLarge', 'Info:');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($groupId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker på att du vill radera gruppen $groupId? Ingående lager påverkas ej och referenser till gruppen hanteras separat.";
		printDeleteButton($groupId, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
		printAddOperation('maps', $groupId, 'Lägg till i karta');
		printRemoveOperation('maps', $groupId, 'Ta bort från karta');
		printAddOperation('groups', $groupId, 'Lägg till i grupp');
		printRemoveOperation('groups', $groupId, 'Ta bort från grupp');
	}
?>
