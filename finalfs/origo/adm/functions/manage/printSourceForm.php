<?php
	function printSourceForm($source, $selectables, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($source, 'source_id', 'textareaMedium', 'Id:');
		printUpdateSelect($source, array('service'=>$selectables['services']), 'bodySelect', 'Tjänst:');
		printUpdateSelect($source, array('with_geometry'=>array("f", "t")), 'miniSelect', 'With_geometry:');
		printTextarea($source, 'fi_point_tolerance', 'textareaSmall', 'Fi_point_tolerance:');
		printTextarea($source, 'ttl', 'textareaSmall', 'Ttl:');
		printUpdateSelect($source, array('tilegrid'=>$selectables['tilegrids']), 'bodySelect', 'Tilegrid:');
		echo '</br>';
		printTextarea($source, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('source');
		$source['source']=$source['source']['source_id'];
		printInfoButton($source);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera källan ".$source['source']."? Referenser till källan hanteras separat.";
		printDeleteButton($source, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
