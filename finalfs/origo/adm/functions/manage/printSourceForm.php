<?php
	function printSourceForm($targetId)
	{
		GLOBAL $hiddenInputs, $target;
		echo <<<HERE
		<div>
			<div style="float:left;">
				<form method="post" style="line-height:2">
		HERE;
		printTextarea($targetId, 'id', 'textareaMedium');
		printUpdateSelect($targetId, 'service', 'bodySelect', 'Tjänst:');
		printUpdateSelect($targetId, 'with_geometry', 'miniSelect', 'With_geometry:', array("", "f", "t"));
		printTextarea($targetId, 'fi_point_tolerance', 'textareaSmall', 'Fi_point_tolerance:');
		printTextarea($targetId, 'ttl', 'textareaSmall');
		printUpdateSelect($targetId, 'tilegrid', 'bodySelect', 'Tilegrid:');
		echo '</br>';
		printTextarea($targetId, 'info', 'textareaLarge');
		echo $hiddenInputs;
		echo 			'<div class="buttonDiv">';
		printUpdateButton();
                printInfoButton($targetId);
		echo 			'</div>';
		echo 		'</form>';
		echo 	'</div>';
		$deleteConfirmStr="Är du säker att du vill radera källan $targetId? Referenser till källan hanteras separat.";
		printDeleteButton($targetId, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
