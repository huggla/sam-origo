<?php
	function printTilegridForm($tilegrid, $inheritPosts)
	{
		echo '<div><div style="float:left;"><form method="post" style="line-height:2">';
		printTextarea($tilegrid, 'tilegrid_id', 'textareaMedium', 'Id:');
		printTextarea($tilegrid, 'tilesize', 'textareaSmall', 'Tile-storlek:');
		echo '</br>';
		printTextarea($tilegrid, 'info', 'textareaLarge', 'Info:');
		printHiddenInputs($inheritPosts);
		echo '<div class="buttonDiv">';
		printUpdateButton('tilegrid');
		$tilegrid['tilegrid']=$tilegrid['tilegrid']['tilegrid_id'];
		printInfoButton($tilegrid);
		echo '</div></form></div>';
		$deleteConfirmStr="Är du säker att du vill radera tilegriden ".$tilegrid['tilegrid']."? Referenser till tilegriden hanteras separat.";
		printDeleteButton($tilegrid, $deleteConfirmStr, 'deleteButton2');
		echo '</div>';
	}
?>
