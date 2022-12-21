<?php
	function printProj4defForm($proj4def, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($proj4def, 'code', 'textareaMedium', 'Kod:');
		printTextarea($proj4def, 'projection', 'textareaLarge', 'Projektion:');
		printTextarea($proj4def, 'alias', 'textareaMedium', 'Alias:');
		echo '</br>';
		printTextarea($proj4def, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('proj4def');
		$proj4def['proj4def']=$proj4def['proj4def']['code'];
		printInfoButton($proj4def);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera proj4def ".$proj4def['proj4def']."? Referenser till aktuell proj4def hanteras separat.";
		printDeleteButton($proj4def, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
