<?php

	function headForm2($table)
	{
		GLOBAL $hiddenInputs, $formCount, $groupIds, $mapId, $groupId;
		$type=rtrim($table, 's');
		echo '<form class="headForm" method="post">';
		if ($table == 'maps')
		{
			echo '<input type="hidden" name="groupLevels" value="1">';
		}
		if ($table == 'groups')
		{
			echo '<input type="hidden" name="groupLevels" value="1">';
			if (!isset($mapId) && !isset($groupId))
			{
				$name='groupId';
			}
			else
			{
				$name='groupIds';
			}
		}
		else
		{
			$name=$type.'Id';
		}
		if (isset($_POST['layerCategories']))
		{
			echo '<input type="hidden" name="layerCategories" value="'.$_POST['layerCategories'].'">';
		}
		if ($table == 'layers')
		{
			echo "<select class=\"headSelect\" id=\"layerCategories\" name=\"layerCategories\" onchange='updateSelect(\"layerSelect\", window[this.value]);'></select>";
			$selectId='id="'.$type.'Select"';
		}
		else
		{
			$selectId='';
		}
		echo   "<select $selectId onchange=\"this.form.submit()\" class=\"headSelect\" name=\"$name\">";
		if ($table == 'groups')
		{
			if (isset($mapId))
			{
				selectOptions($table);
			}
			elseif (isset($groupIds[0]))
			{
				selectOptions($table, $groupIds[0]);
			}
			else
			{
				selectOptions($table, true);
			}
		}
		else
		{
			selectOptions($table, true);
		}
		echo   '</select>';
		echo   '<button type="submit" class="headButton" name="'.$type.'Button" value="get">';
		echo     'Hämta';
		echo   '</button>';
		echo '</form>';
		echo '<form class="headForm" method="post">';
		echo   '<input class="headInput" type="text" name="'.$type.'IdNew">';
		echo   $hiddenInputs;
		echo   '<button type="submit" class="headButton" name="'.$type.'Button" value="create">';
		echo     'Skapa';
		echo   '</button>';
		echo '</form>';
	}

?>
