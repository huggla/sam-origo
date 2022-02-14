<?php

	function headForm($type)
	{
		GLOBAL $hiddenInputs;
		if ($type == 'group')
		{
			$name='groupIds';
		}
		else
		{
			$name=$type.'Id';
		}
		echo '<form class="headForm" method="post">';
		echo   "<select onchange=\"this.form.submit()\" class=\"headSelect\" name=\"$name\">";
		selectOptions($type.'s', true);
		echo   '</select>';
		echo   '<button type="submit" class="headButton" name="'.$type.'Button" value="get">';
		echo     'Hämta';
		echo   '</button>';
		echo '</form>';
		echo '<form class="headForm" method="post">';
		echo   '<input class="headInput" type="text" name="new'.$type.'Id">';
		echo   $hiddenInputs;
		echo   '<button type="submit" class="headButton" name="'.$type.'Button" value="create">';
		echo     'Skapa';
		echo   '</button>';
		echo '</form>';
	}

?>
