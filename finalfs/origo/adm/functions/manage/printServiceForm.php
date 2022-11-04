<?php
	function printServiceForm($targetId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($targetId, 'id', 'textareaMedium');
		printTextarea($targetId, 'base_url', 'textareaLarge', 'Huvudurl:');
		echo '</br>';
		printTextarea($targetId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($targetId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera tjänsten $targetId? Referenser till tjänsten hanteras separat.";
		printDeleteButton($targetId, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
