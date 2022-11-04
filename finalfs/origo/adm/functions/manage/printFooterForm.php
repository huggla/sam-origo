<?php
	function printFooterForm($footerId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($footerId, 'id', 'textareaMedium');
		printTextarea($footerId, 'img', 'textareaLarge', 'Logotyp:');
		printTextarea($footerId, 'url', 'textareaLarge');
		echo 			'<br>';
		printTextarea($footerId, 'text', 'textareaMedium');
		printTextarea($footerId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($footerId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera sidfoten $footerId? Referenser till sidfoten hanteras separat.";
		printDeleteButton($footerId, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
