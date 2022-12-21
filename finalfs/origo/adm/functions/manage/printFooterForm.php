<?php
	function printFooterForm($footer, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($footer, 'footer_id', 'textareaMedium', 'Id:');
		printTextarea($footer, 'img', 'textareaLarge', 'Logotyp:');
		printTextarea($footer, 'url', 'textareaLarge', 'Url:');
		echo '<br>';
		printTextarea($footer, 'text', 'textareaMedium', 'Text:');
		printTextarea($footer, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('footer');
		$footer['footer']=$footer['footer']['footer_id'];
		printInfoButton($footer);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera sidfoten ".$footer['footer']."? Referenser till sidfoten hanteras separat.";
		printDeleteButton($footer, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
