<?php
	function printControlForm($controlId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($controlId, 'id', 'textareaMedium');
		printTextarea($controlId, 'options', 'textareaLarge', 'Inställningar:');
		echo 			'<br>';
		printTextarea($controlId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($controlId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera kontrollen $controlId? Referenser till kontrollen hanteras separat.";
		printDeleteButton($controlId, $deleteConfirmStr, 'deleteButton1');
		echo '</div>';
		echo '<div style="clear:both">';
		printAddOperation('maps', $controlId, 'Lägg till i karta');
		printRemoveOperation('maps', $controlId, 'Ta bort från karta');
		echo '</div>';
	}
?>
