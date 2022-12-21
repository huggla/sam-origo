<?php
	function printServiceForm($service, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($service, 'service_id', 'textareaMedium', 'Id:');
		printTextarea($service, 'base_url', 'textareaLarge', 'Huvudurl:');
		echo '</br>';
		printTextarea($service, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('service');
		$service['service']=$service['service']['service_id'];
		printInfoButton($service);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera tjänsten ".$service['service']."? Referenser till tjänsten hanteras separat.";
		printDeleteButton($service, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
