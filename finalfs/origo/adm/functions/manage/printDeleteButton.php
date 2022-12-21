<?php
	function printDeleteButton($target, $deleteConfirmStr, $deleteButtonClass)
	{
		$targetType=key($target);
		$targetId=current($target);
		echo <<<HERE
			<div class="deleteButtonDiv">
				<form method='post' onsubmit='confirmStr="{$deleteConfirmStr}"; return confirm(confirmStr);' style='line-height:2'>
					<input type="hidden" name="{$targetType}IdDel" value="{$targetId}">
					<button class='{$deleteButtonClass}' type='submit' name='{$targetType}Button' value='delete'>Radera</button>
				</form>
			</div>
		HERE;
	}
?>
