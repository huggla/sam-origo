<?php
	function printProj4defForm($targetId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($targetId, 'code', 'textareaMedium', 'Kod:');
		printTextarea($targetId, 'projection', 'textareaLarge', 'Projektion:');
		printTextarea($targetId, 'alias', 'textareaMedium');
		echo '</br>';
		printTextarea($targetId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($targetId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera proj4def $targetId? Referenser till aktuell proj4def hanteras separat.";
		printDeleteButton($targetId, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
