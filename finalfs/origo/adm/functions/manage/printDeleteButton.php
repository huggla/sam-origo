<?php
	function printDeleteButton($targetId, $deleteConfirmStr, $deleteButtonClass)
	{
		$target=$GLOBALS['target'];
		echo <<<HERE
			<div class="deleteButtonDiv">
				<form method='post' onsubmit='confirmStr="{$deleteConfirmStr}"; return confirm(confirmStr);' style='line-height:2'>
					<input type="hidden" name="{$target}IdDel" value="{$targetId}">
					<button class='{$deleteButtonClass}' type='submit' name='{$target}Button' value='delete'>Radera</button>
				</form>
			</div>
		HERE;
	}
?>
